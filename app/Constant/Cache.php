<?php


namespace App\Constant;


interface Cache
{
    /**
     * 用户缓存键
     */
    const SYSTEM_USER = 'user:%s';

    /**
     * 系统配置键
     */
    const SYSTEM_CONFIG = 'config:%s';
}