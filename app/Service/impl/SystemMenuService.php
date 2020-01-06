<?php


namespace App\Service\impl;


use App\Entity\QueryTemplateEntity;
use App\Model\SystemRouter;
use App\Quickly\QueryServiceQuickly;
use App\Service\SystemMenuServiceInterface;

class SystemMenuService implements SystemMenuServiceInterface
{
    use QueryServiceQuickly;

    public function findAll()
    {
        $queryTemplateEntity = new QueryTemplateEntity();
        $queryTemplateEntity->setModel(SystemRouter::class);
        $data = $this->findTemplateAll($queryTemplateEntity);
        return $data;
    }
}