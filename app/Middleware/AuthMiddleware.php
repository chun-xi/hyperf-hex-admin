<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Exception\HexException;
use App\Service\SystemUserServiceInterface;
use Firebase\JWT\JWT;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Context;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * 权限验证中间件
 * Class AuthMiddleware
 * @package App\Middleware
 */
class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @Inject()
     * @var SystemUserServiceInterface
     */
    protected SystemUserServiceInterface $systemUserService;


    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $router = trim($request->getUri()->getPath(), "/");

        $token = $request->getHeader("token");

        if (!$token) {
            throw new HexException("用户未登录", 1001);
        }

        $token = $token[0];

        try {
            $user = JWT::decode($token, env("JWT_KEY"), ['HS256']);
        } catch (\Exception $e) {
            throw new HexException("登录已过期", 1001);
        }

        $userId = $user->data->userId;

        if (!$userId) {
            throw new HexException("用户校验失败", 1001);
        }

        $userCache = $this->systemUserService->findByUserCache($userId);

        if ($userCache['login_date'] != $user->data->loginDate) {
            throw new HexException("登录已过期", 1001);
        }

        if (!array_key_exists($router, $userCache['routers'])) {
            throw new HexException("您还没有权限访问或该功能已停用");
        }

        //将用户ID保存到请求上下文中
        $request->systemUserId = $userId;
        $request->systemUserInfo = $userCache;

        Context::set(ServerRequestInterface::class, $request);

        return $handler->handle($request);
    }
}