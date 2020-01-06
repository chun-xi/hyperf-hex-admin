<?php


namespace App\Service;


interface SystemMenuServiceInterface
{
    /**
     * 查询所有菜单
     * @return mixed
     */
    public function findAll();
}