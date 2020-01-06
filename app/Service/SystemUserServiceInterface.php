<?php


namespace App\Service;


interface SystemUserServiceInterface
{
    /**
     * 通过用户名获取用户信息
     * @param string $user
     * @return mixed
     */
    public function findByUserName(string $user);

    /**
     * 获取用户的权限列表
     * @param int $id
     * @return array|null
     */
    public function findByUserRouters(int $id);


    /**
     * 更新用户登录时间
     * @param object $systemUser
     * @param string $address
     * @param string $loginDate
     * @return mixed
     */
    public function updateUserLoginInfo(object $systemUser, string $address, string $loginDate);


    /**
     * 用户登录，成功返回用户信息和token，记得抛异常哦
     * @param array $data
     * @param string $address
     * @return string
     */
    public function login(array $data, string $address): string;


    /**
     * 退出登录
     * @param int $id
     */
    public function logout(int $id): void;


    /**
     * 获取缓存中的用户信息
     * @param int $id
     * @return mixed
     */
    public function findByUserCache(int $id);


    /**
     * 获取用户信息
     * @param int $id
     * @param array $fields
     * @return mixed
     */
    public function findByUserId(int $id, array $fields = ['*']);

}