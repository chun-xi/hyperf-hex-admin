<?php

declare(strict_types=1);

namespace App\Controller\System;

use App\Controller\HexBaseController;
use App\Entity\CreateObjectEntity;
use App\Entity\DeleteBatchEntity;
use App\Entity\QueryTemplateEntity;
use App\Exception\HexException;
use App\Model\SystemDict;
use App\Quickly\QueryServiceQuickly;
use App\Service\SystemDictServiceInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Psr\Http\Message\ResponseInterface;
use App\Middleware\AuthMiddleware;

/**
 * Class SystemMenuController
 * @package App\Controller
 * @Controller(prefix="/system/dict")
 * @Middleware(AuthMiddleware::class)
 */
class SystemDictController extends HexBaseController
{
    use QueryServiceQuickly;

    /**
     * @Inject()
     * @var SystemDictServiceInterface
     */
    protected SystemDictServiceInterface $systemDictService;


    /**
     * @RequestMapping(path="getDict",methods={"get","post"})
     * @return ResponseInterface
     */
    public function getDict(): ResponseInterface
    {
        $this->validator($this->request->all(), ['dict' => 'required'], ['dict.required' => '字典数据不能为空']);

        $dict = $this->systemDictService->getDict($this->request->input('dict'));

        if ($dict == null) {
            throw new HexException("字段数据不支持该格式");
        }

        return $this->response->json($this->getJson(200, null, $dict));
    }

    /**
     * @PostMapping(path="getDicts")
     * @return ResponseInterface
     */
    public function getDicts(): ResponseInterface
    {
        $queryTemplateEntity = new QueryTemplateEntity();
        $queryTemplateEntity->setModel(SystemDict::class);
        $queryTemplateEntity->setLimit((int)$this->request->post('limit'));
        $queryTemplateEntity->setPage((int)$this->request->post('page'));
        $queryTemplateEntity->setPaginate(true);
        $data = $this->findTemplateAll($queryTemplateEntity)->toArray();
        $json = $this->getJson(200, null, $data['data']);
        $json['count'] = $data['total'];
        return $this->response->json($json);
    }

    /**
     * @PostMapping(path="saveDict")
     * @return ResponseInterface
     */
    public function saveDict(): ResponseInterface
    {
        $createObjectEntity = new CreateObjectEntity();
        $createObjectEntity->setModel(SystemDict::class);
        $createObjectEntity->setMap($this->request->post());
        $roleId = $this->createOrUpdateTemplate($createObjectEntity);
        if (!$roleId) {
            throw new HexException("本次操作没有任何更改");
        }
        return $this->response->json($this->getJson(200, '成功啦!'));
    }

    /**
     * @PostMapping(path="delDict")
     * @return ResponseInterface
     */
    public function delDict(): ResponseInterface
    {
        $deleteBatchEntity = new DeleteBatchEntity();
        $deleteBatchEntity->setModel(SystemDict::class);
        $deleteBatchEntity->setList($this->request->post('list'));
        $count = $this->deleteTemplate($deleteBatchEntity);
        if ($count == 0) {
            throw new HexException("一个也没有删除成功~");
        }
        return $this->response->json($this->getJson(200, '删除成功!'));
    }
}
