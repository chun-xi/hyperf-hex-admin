<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\HexException;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Redis\Redis;
use Hyperf\Utils\Context;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class HexBaseController
{
    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    /**
     * @Inject
     * @var ResponseInterface
     */
    protected $response;


    /**
     * @Inject()
     * @var ValidatorFactoryInterface
     */
    protected $validationFactory;


    /**
     * @param int $code
     * @param string|null $message
     * @param null $data
     * @return array
     */
    protected function getJson(int $code, string $message = null, $data = null): array
    {
        $content = ['code' => $code];
        $message ? $content['msg'] = $message : null;
        $data ? $content['data'] = $data : null;
        return $content;
    }

    /**
     * @param array $data 验证数据
     * @param array $rules 验证规则
     * @param array $message 错误消息
     * @throws HexException
     */
    protected function validator(array $data, array $rules, array $message): void
    {
        $validator = $this->validationFactory->make($data, $rules, $message);
        if ($validator->fails()) {
            throw new HexException($validator->errors()->first(), 0);
        }
    }

    /**
     * 获取客户端地址
     * @return array|string|null
     */
    protected function getAddress()
    {
        $ip = null;
        if ($this->request->server('x-real-ip') != '') {
            $ip = $this->request->server('x-real-ip');
        } elseif ($this->request->server('x-real-ip') != '') {
            $ip = $this->request->server('x-real-ip');
        } elseif ($this->request->server('remote-host') != '') {
            $ip = $this->request->server('remote-host');
        } else if ($this->request->server('remote_addr') != '') {
            $ip = $this->request->server('remote_addr');
        }
        return $ip;
    }


    /**
     * 获取redis客户端实例
     * @return \Redis
     */
    protected function getRedis(): Redis
    {
        return $this->container->get(\Redis::class);
    }

    /**
     * 获取系统账号ID
     * @return mixed
     */
    protected function getSystemUserId()
    {
        $serverRequest = Context::get(ServerRequestInterface::class);
        return $serverRequest->systemUserId;
    }

    /**
     * 获取系统账号信息
     * @return mixed
     */
    protected function getSystemUserInfo()
    {
        $serverRequest = Context::get(ServerRequestInterface::class);
        return $serverRequest->systemUserInfo;
    }
}
