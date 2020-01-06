<?php

declare(strict_types=1);

namespace App\Controller\System;


use App\Controller\HexBaseController;
use App\Entity\CreateObjectEntity;
use App\Entity\DeleteBatchEntity;
use App\Entity\QueryTemplateEntity;
use App\Exception\HexException;
use App\Middleware\AuthMiddleware;
use App\Model\SystemDictList;
use App\Quickly\QueryServiceQuickly;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use Psr\Http\Message\ResponseInterface;

/**
 * Class SystemMenuController
 * @package App\Controller
 * @Controller(prefix="/system/dict/list")
 * @Middleware(AuthMiddleware::class)
 */
class SystemDictListController extends HexBaseController
{

    use QueryServiceQuickly;

    /**
     * @PostMapping(path="getDictLists")
     * @return ResponseInterface
     */
    public function getDictLists(): ResponseInterface
    {
        $queryTemplateEntity = new QueryTemplateEntity();
        $queryTemplateEntity->setModel(SystemDictList::class);
        $queryTemplateEntity->setLimit((int)$this->request->post('limit'));
        $queryTemplateEntity->setPage((int)$this->request->post('page'));
        $queryTemplateEntity->setPaginate(true);
        $queryTemplateEntity->setWhere(['equal-dict_id' => $this->request->input("id")]);
        $data = $this->findTemplateAll($queryTemplateEntity)->toArray();
        $json = $this->getJson(200, null, $data['data']);
        $json['count'] = $data['total'];
        return $this->response->json($json);
    }

    /**
     * @PostMapping(path="saveDictValue")
     * @return ResponseInterface
     */
    public function saveDictValue(): ResponseInterface
    {
        $createObjectEntity = new CreateObjectEntity();
        $createObjectEntity->setModel(SystemDictList::class);
        $createObjectEntity->setMap($this->request->post());
        $createObjectEntity->setCreateDate("create_date");
        $roleId = $this->createOrUpdateTemplate($createObjectEntity);
        if (!$roleId) {
            throw new HexException("本次操作没有任何更改");
        }
        return $this->response->json($this->getJson(200, '成功啦!'));
    }

    /**
     * @PostMapping(path="delDictValue")
     * @return ResponseInterface
     */
    public function delDictValue(): ResponseInterface
    {
        $deleteBatchEntity = new DeleteBatchEntity();
        $deleteBatchEntity->setModel(SystemDictList::class);
        $deleteBatchEntity->setList($this->request->post('list'));
        $count = $this->deleteTemplate($deleteBatchEntity);
        if ($count == 0) {
            throw new HexException("一个也没有删除成功~");
        }
        return $this->response->json($this->getJson(200, '删除成功!'));
    }
}
