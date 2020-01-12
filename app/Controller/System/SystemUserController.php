<?php
declare(strict_types=1);

namespace App\Controller\System;

use App\Controller\HexBaseController;
use App\Entity\CreateObjectEntity;
use App\Entity\DeleteBatchEntity;
use App\Entity\QueryTemplateEntity;
use App\Exception\HexException;
use App\Middleware\AuthMiddleware;
use App\Model\SystemUser;
use App\Model\SystemUserRole;
use App\Quickly\QueryServiceQuickly;
use App\Service\SystemUserServiceInterface;
use App\Utils\CategoryUtil;
use App\Utils\StringUtil;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use Psr\Http\Message\ResponseInterface;


/**
 * Class SystemUserController
 * @package App\Controller\System
 * @Controller(prefix="/system/user")
 * @Middleware(AuthMiddleware::class)
 */
class SystemUserController extends HexBaseController
{
    use QueryServiceQuickly;

    /**
     * @Inject()
     * @var SystemUserServiceInterface
     */
    protected SystemUserServiceInterface $systemUserService;

    /**
     * @PostMapping(path="getMeInfo")
     * @return ResponseInterface
     */
    public function getMeInfo(): ResponseInterface
    {
        $systemUserInfo = $this->systemUserService->findByUserId($this->getSystemUserId(), ['id', 'face', 'login_date', 'login_ip', 'user', 'nickname', 'phone']);
        return $this->response->json($this->getJson(200, '', $systemUserInfo));
    }

    /**
     * @PostMapping(path="saveMeInfo")
     * @return ResponseInterface
     */
    public function saveMeInfo(): ResponseInterface
    {
        $systemUserInfo = $this->systemUserService->findByUserId($this->getSystemUserId());

        $map = $this->request->post();

        if ($map['new_password'] !== '') {
            $this->validator($map, ['new_password' => 'between:6,20'], ['new_password.between' => '密码不能低于6位和不能大于20位']);
            if (StringUtil::generatePassword($map['password'], $systemUserInfo->salt) != $systemUserInfo->pass) {
                throw new HexException("原密码输入错误");
            }
            $systemUserInfo->salt = StringUtil::generateRandStr(32);
            $systemUserInfo->pass = StringUtil::generatePassword($map['new_password'], $systemUserInfo->salt);
        }

        $systemUserInfo->nickname = $map['nickname'];
        $systemUserInfo->phone = $map['phone'];
        $systemUserInfo->face = $map['face'];

        $systemUserInfo->save();

        return $this->response->json($this->getJson(200, '修改成功'));
    }

    /**
     * @PostMapping(path="logout")
     * @return ResponseInterface
     */
    public function logout(): ResponseInterface
    {
        $this->systemUserService->logout($this->getSystemUserId());
        return $this->response->json($this->getJson(200, '安全注销成功'));
    }

    /**
     * @return ResponseInterface
     * @PostMapping(path="getMenu")
     */
    public function getMenu(): ResponseInterface
    {
        $menus = CategoryUtil::generateTree($this->getSystemUserInfo()['menus'], 'id', 'pid', 'list');
        return $this->response->json($this->getJson(200, 'success', $menus));
    }

    /**
     * @PostMapping(path="getUsers")
     * @return ResponseInterface
     */
    public function getUsers(): ResponseInterface
    {
        $queryTemplateEntity = new QueryTemplateEntity();
        $queryTemplateEntity->setModel(SystemUser::class);
        $queryTemplateEntity->setLimit((int)$this->request->post('limit'));
        $queryTemplateEntity->setPage((int)$this->request->post('page'));
        $queryTemplateEntity->setPaginate(true);
        $queryTemplateEntity->setWith(['roles']);
        $queryTemplateEntity->setWhere($this->request->post());
        $queryTemplateEntity->setField(['id', 'user', 'face', 'login_date', 'create_date', 'status']);
        $data = $this->findTemplateAll($queryTemplateEntity)->toArray();
        $json = $this->getJson(200, null, $data['data']);
        $json['count'] = $data['total'];
        return $this->response->json($json);
    }

    /**
     * @PostMapping(path="saveUser")
     * @return ResponseInterface
     */
    public function saveUser(): ResponseInterface
    {
        $map = $this->request->post();
        if (!empty($map['pass'])) {
            $map['salt'] = StringUtil::generateRandStr(32);
            $map['pass'] = StringUtil::generatePassword($map['pass'], $map['salt']);
        }
        $createObjectEntity = new CreateObjectEntity();
        $createObjectEntity->setModel(SystemUser::class);
        $createObjectEntity->setMap($map);
        $createObjectEntity->setCreateDate('create_date');
        $createObjectEntity->setMiddle('role', SystemUserRole::class, 'role_id', 'user_id');
        $roleId = $this->createOrUpdateTemplate($createObjectEntity);
        if (!$roleId) {
            throw new HexException("本次操作没有任何更改");
        }
        $this->systemUserService->logout((int)$map['id']);
        return $this->response->json($this->getJson(200, '成功啦!'));
    }

    /**
     * @PostMapping(path="delUser")
     * @return ResponseInterface
     */
    public function delUser(): ResponseInterface
    {
        $list = $this->request->post('list');
        $deleteBatchEntity = new DeleteBatchEntity();
        $deleteBatchEntity->setModel(SystemUser::class);
        $deleteBatchEntity->setList($list);
        $count = $this->deleteTemplate($deleteBatchEntity);
        if ($count == 0) {
            throw new HexException("一个也没有删除成功~");
        }
        foreach ($list as $id) {
            $this->systemUserService->logout((int)$id);
        }
        return $this->response->json($this->getJson(200, '删除成功!'));
    }

}
