<?php

declare(strict_types=1);

namespace App\Controller\System;

use App\Constant\Cache;
use App\Controller\HexBaseController;
use App\Entity\CreateObjectEntity;
use App\Entity\DeleteBatchEntity;
use App\Entity\QueryTemplateEntity;
use App\Exception\HexException;
use App\Middleware\AuthMiddleware;
use App\Model\SystemConfig;
use App\Quickly\QueryServiceQuickly;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use Psr\Http\Message\ResponseInterface;

/**
 * Class SystemConfigController
 * @package App\Controller\System
 * @Controller(prefix="/system/config")
 * @Middleware(AuthMiddleware::class)
 */
class SystemConfigController extends HexBaseController
{
    use QueryServiceQuickly;

    /**
     * @PostMapping(path="getConfigs")
     * @return ResponseInterface
     */
    public function getConfigs(): ResponseInterface
    {
        $queryTemplateEntity = new QueryTemplateEntity();
        $queryTemplateEntity->setModel(SystemConfig::class);
        $queryTemplateEntity->setLimit((int)$this->request->post('limit'));
        $queryTemplateEntity->setPage((int)$this->request->post('page'));
        $queryTemplateEntity->setPaginate(true);
        $data = $this->findTemplateAll($queryTemplateEntity)->toArray();
        $json = $this->getJson(200, null, $data['data']);
        $json['count'] = $data['total'];
        return $this->response->json($json);
    }

    /**
     * @PostMapping(path="saveConfig")
     * @return ResponseInterface
     */
    public function saveConfig(): ResponseInterface
    {
        $map = $this->request->post();
        $createObjectEntity = new CreateObjectEntity();
        $createObjectEntity->setModel(SystemConfig::class);
        $createObjectEntity->setMap($map);
        $configId = $this->createOrUpdateTemplate($createObjectEntity);
        if (!$configId) {
            throw new HexException("本次操作没有任何更改");
        }
        //清空缓存
        $cache = $this->getRedis()->del(sprintf(Cache::SYSTEM_CONFIG, $map['key']));
        return $this->response->json($this->getJson(200, '已更新,缓存更新状态:' . $cache));
    }

    /**
     * @PostMapping(path="delConfig")
     * @return ResponseInterface
     */
    public function delConfig(): ResponseInterface
    {
        $list = $this->request->post('list');
        $deleteBatchEntity = new DeleteBatchEntity();
        $deleteBatchEntity->setModel(SystemConfig::class);
        $deleteBatchEntity->setList($list);
        $count = $this->deleteTemplate($deleteBatchEntity);
        if ($count == 0) {
            throw new HexException("一个也没有删除成功~");
        }
        return $this->response->json($this->getJson(200, '删除成功!'));
    }
}
