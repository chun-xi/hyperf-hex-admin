<?php

namespace App\Service;


interface SystemConfigServiceInterface
{

    /**
     * 通过ID获取配置
     * @param int $id
     * @return mixed
     */
    public function findById(int $id);

    /**
     * 获取配置项
     * @param string $key
     * @param bool $public
     * @return mixed
     */
    public function getConfig(string $key, bool $public = false);
}