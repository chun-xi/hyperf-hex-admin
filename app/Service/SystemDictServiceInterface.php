<?php


namespace App\Service;


interface SystemDictServiceInterface
{
    /**
     * 获取字典列表
     * @param string $dictName
     * @return mixed
     */
    public function getDict(string $dictName);
}