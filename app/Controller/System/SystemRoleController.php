<?php

declare(strict_types=1);

namespace App\Controller\System;

use App\Controller\HexBaseController;
use App\Entity\CreateObjectEntity;
use App\Entity\DeleteBatchEntity;
use App\Entity\QueryTemplateEntity;
use App\Exception\HexException;
use App\Model\SystemRole;
use App\Model\SystemRoleRouter;
use App\Quickly\QueryServiceQuickly;
use Hyperf\Database\Model\Relations\Relation;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use App\Middleware\AuthMiddleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use Psr\Http\Message\ResponseInterface;

/**
 * Class SystemMenuController
 * @package App\Controller
 * @Controller(prefix="/system/role")
 * @Middleware(AuthMiddleware::class)
 */
class SystemRoleController extends HexBaseController
{

    use QueryServiceQuickly;

    /**
     * @PostMapping(path="getRoles")
     * @return ResponseInterface
     */
    public function getRoles(): ResponseInterface
    {
        $queryTemplateEntity = new QueryTemplateEntity();
        $queryTemplateEntity->setModel(SystemRole::class);
        $queryTemplateEntity->setLimit((int)$this->request->post('limit'));
        $queryTemplateEntity->setPage((int)$this->request->post('page'));
        $queryTemplateEntity->setPaginate(true);
        $queryTemplateEntity->setWith(['routers']);
        $data = $this->findTemplateAll($queryTemplateEntity)->toArray();
        $json = $this->getJson(200, null, $data['data']);
        $json['count'] = $data['total'];
        return $this->response->json($json);
    }


    /**
     * @PostMapping(path="saveRole")
     * @return ResponseInterface
     */
    public function saveRole(): ResponseInterface
    {
        $createObjectEntity = new CreateObjectEntity();
        $createObjectEntity->setModel(SystemRole::class);
        $createObjectEntity->setMap($this->request->post());
        $createObjectEntity->setMiddle('auth', SystemRoleRouter::class, 'router_id', 'role_id');
        $roleId = $this->createOrUpdateTemplate($createObjectEntity);
        if (!$roleId) {
            throw new HexException("本次操作没有任何更改");
        }
        return $this->response->json($this->getJson(200, '成功啦!'));
    }

    /**
     * @PostMapping(path="delRole")
     * @return ResponseInterface
     */
    public function delRole(): ResponseInterface
    {
        $deleteBatchEntity = new DeleteBatchEntity();
        $deleteBatchEntity->setModel(SystemRole::class);
        $deleteBatchEntity->setList($this->request->post('list'));
        $count = $this->deleteTemplate($deleteBatchEntity);
        if ($count == 0) {
            throw new HexException("一个也没有删除成功~");
        }
        return $this->response->json($this->getJson(200, '删除成功!'));
    }

}
