<?php
declare(strict_types=1);

namespace App\Controller\System;


use App\Controller\HexBaseController;
use App\Service\SystemUserServiceInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\PostMapping;
use Psr\Http\Message\ResponseInterface;

/**
 * Class SystemUserController
 * @package App\Controller\System
 * @Controller(prefix="/system/user")
 */
class SystemLoginController extends HexBaseController
{
    /**
     * @Inject()
     * @var SystemUserServiceInterface
     */
    protected SystemUserServiceInterface $systemUserService;

    /**
     * @return ResponseInterface
     * @PostMapping(path="login")
     */
    public function login(): ResponseInterface
    {
        $this->validator($this->request->post(), [
            'user' => 'required',
            'pass' => 'required|between:6,20'
        ], [
            'user.required' => '用户名不能为空',
            'pass.required' => '密码不能为空',
            'pass.between' => '密码错误'
        ]);
        $jwt = $this->systemUserService->login($this->request->post(), $this->getAddress());
        return $this->response->json($this->getJson(200, 'success', ['token' => $jwt]));
    }
}