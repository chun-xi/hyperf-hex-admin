<?php


namespace App\Service\impl;


use App\Constant\Cache;
use App\Exception\HexException;
use App\Model\SystemUser;
use App\Service\SystemUserServiceInterface;
use App\Utils\DateUtil;
use App\Utils\StringUtil;
use Firebase\JWT\JWT;
use Hyperf\Database\Model\Relations\Relation;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;

class SystemUserService implements SystemUserServiceInterface
{
    /**
     * @Inject()
     * @var \Redis
     */
    protected Redis $redis;

    /**
     * 通过用户名获取用户信息
     * @param string $user
     * @return mixed
     */
    public function findByUserName(string $user)
    {
        return SystemUser::query()->where("user", $user)->first();
    }

    /**
     * 获取用户的权限列表
     * @param int $id
     * @return array|null
     */
    public function findByUserRouters(int $id)
    {
        $findByUserRouters = SystemUser::with(['roles' => function (Relation $relation) {
            $relation->with(['routers' => function (Relation $relation) {
                $relation->where("status", 1)->orderBy("rank", "asc");
            }])->where("status", 1);
        }])->find($id);

        if (!$findByUserRouters) {
            return null;
        };

        $routers = [];
        $menus = [];

        foreach ($findByUserRouters->roles as $role) {
            foreach ($role->routers as $router) {
                if ($router->type == 1) {
                    $routers[trim($router->path, '/')] = $router->id;
                } else {
                    $menus[] = [
                        'id' => $router->id,
                        'title' => $router->name,
                        'name' => $router->path,
                        'icon' => $router->face,
                        'pid' => $router->pid
                    ];
                }
            }
        }

        return ['routers' => $routers, 'menus' => $menus];
    }


    /**
     * 更新用户登录时间
     * @param object $systemUser
     * @param string $address
     * @param string $loginDate
     * @return mixed
     */
    public function updateUserLoginInfo(object $systemUser, string $address, string $loginDate): void
    {
        $systemUser->login_date = $loginDate;
        $systemUser->login_ip = $address;
        $systemUser->save();
    }

    /**
     * 用户登录，成功返回token
     * @param array $data
     * @param string $address
     * @return string
     */
    public function login(array $data, string $address): string
    {
        $systemUser = $this->findByUserName($data['user']);


        if (!$systemUser) {
            throw new HexException("用户名不存在");
        }

        $password = StringUtil::generatePassword($data['pass'], $systemUser->salt);

        if ($systemUser->pass != $password) {
            throw new HexException("密码错误");
        }

        if ($systemUser->status != 1) {
            throw new HexException("您的账号已被停用");
        }

        $expire = (int)env('JWT_EXPIRE');

        $loginDate = DateUtil::current();

        $payload = array(
            "exp" => time() + $expire,
            "data" => [
                "userId" => $systemUser->id,
                'loginDate' => $loginDate
            ]
        );

        $userRouter = $this->findByUserRouters($systemUser->id);

        if (empty($userRouter['routers'])) {
            throw new HexException("没有任何权限,您暂时无法登录");
        }

        $jwt = JWT::encode($payload, env("JWT_KEY"));

        $this->updateUserLoginInfo($systemUser, $address, $loginDate);

        $systemUser->routers = $userRouter['routers'];
        $systemUser->menus = $userRouter['menus'];
        $systemUser->login_date = $loginDate;

        $this->redis->set(sprintf(Cache::SYSTEM_USER, $systemUser->id), $systemUser->toArray(), (int)env('JWT_EXPIRE'));

        return $jwt;
    }

    /**
     * 获取缓存中的用户信息
     * @param int $id
     * @return mixed
     */
    public function findByUserCache(int $id)
    {
        $user = $this->redis->get(sprintf(Cache::SYSTEM_USER, $id));
        if (empty($user)) {
            throw new HexException("用户不在线", 1001);
        }
        return $user;
    }

    /**
     * 获取用户信息
     * @param int $id
     * @param array $fields
     * @return mixed
     */
    public function findByUserId(int $id, array $fields = ['*'])
    {
        $systemUser = SystemUser::query()->with(['roles'])->find($id, $fields);
        return $systemUser;
    }

    /**
     * 退出登录
     * @param int $id
     */
    public function logout(int $id): void
    {
        $this->redis->del(sprintf(Cache::SYSTEM_USER, $id));
    }
}