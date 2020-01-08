<?php

namespace App\Service;


interface SystemConfigServiceInterface
{
    /**
     * 获取配置项
     * @param string $key
     * @return mixed
     */
    public function getConfig(string $key);
}