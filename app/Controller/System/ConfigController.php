<?php
declare(strict_types=1);

namespace App\Controller\System;


use App\Controller\HexBaseController;
use App\Exception\HexException;
use App\Service\SystemConfigServiceInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ConfigController
 * @package App\Controller\System
 * @Controller(prefix="/system/config")
 */
class ConfigController extends HexBaseController
{

    /**
     * @Inject()
     * @var SystemConfigServiceInterface
     */
    protected SystemConfigServiceInterface $systemConfigService;

    /**
     * 获取配置
     * @return ResponseInterface
     * @RequestMapping(path="getConfig",methods={"get","post"})
     */
    public function getConfig(): ResponseInterface
    {
        $this->validator($this->request->all(), ['key' => 'required'], ['key.required' => 'configuration name cannot be empty']);
        $config = $this->systemConfigService->getConfig($this->request->input('key'), true);
        if (!$config) {
            throw new HexException("configuration not found");
        }
        return $this->response->json($this->getJson(200, null, $config));
    }
}