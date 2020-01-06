<?php

declare(strict_types=1);

namespace App\Controller\System;

use App\Controller\HexBaseController;
use App\Entity\CreateObjectEntity;
use App\Entity\DeleteBatchEntity;
use App\Exception\HexException;
use App\Model\SystemRouter;
use App\Quickly\QueryServiceQuickly;
use App\Service\SystemMenuServiceInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use App\Middleware\AuthMiddleware;
use Psr\Http\Message\ResponseInterface;


/**
 * Class SystemMenuController
 * @package App\Controller
 * @Controller(prefix="/system/menu")
 * @Middleware(AuthMiddleware::class)
 */
class SystemMenuController extends HexBaseController
{

    use QueryServiceQuickly;

    /**
     * @Inject()
     * @var SystemMenuServiceInterface
     */
    protected SystemMenuServiceInterface $systemMenuService;


    /**
     * @PostMapping(path="getMenus")
     * @return ResponseInterface
     */
    public function getMenus(): ResponseInterface
    {
        $all = $this->systemMenuService->findAll()->toArray();
        $json = $this->getJson(200, 'success', $all);
        $json['count'] = count($all);
        return $this->response->json($json);
    }


    /**
     * @PostMapping(path="saveMenu")
     * @return ResponseInterface
     */
    public function saveMenu(): ResponseInterface
    {
        $createObjectEntity = new CreateObjectEntity();
        $createObjectEntity->setModel(SystemRouter::class);
        $createObjectEntity->setMap($this->request->post());
        $save = $this->createOrUpdateTemplate($createObjectEntity);
        if (!$save) {
            throw new HexException("本次操作没有任何更改");
        }
        return $this->response->json($this->getJson(200, '成功啦!'));
    }


    /**
     * @PostMapping(path="delMenu")
     * @return ResponseInterface
     */
    public function delMenu(): ResponseInterface
    {
        $deleteBatchEntity = new DeleteBatchEntity();
        $deleteBatchEntity->setModel(SystemRouter::class);
        $deleteBatchEntity->setList($this->request->post('list'));
        $count = $this->deleteTemplate($deleteBatchEntity);
        if ($count == 0) {
            throw new HexException("一个也没有删除成功~");
        }
        return $this->response->json($this->getJson(200, '删除成功!'));
    }
}
