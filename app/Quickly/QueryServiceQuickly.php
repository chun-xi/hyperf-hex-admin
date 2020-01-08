<?php


namespace App\Quickly;


use App\Entity\CreateObjectEntity;
use App\Entity\DeleteBatchEntity;
use App\Entity\QueryTemplateEntity;
use App\Exception\HexException;

trait QueryServiceQuickly
{
    /**
     * 智能查询模型
     * @param QueryTemplateEntity $queryTemplateEntity
     * @return mixed
     */
    public function findTemplateAll(QueryTemplateEntity $queryTemplateEntity)
    {
        $query = $queryTemplateEntity->getModel()::query();
        $with = $queryTemplateEntity->getWith();
        $where = $queryTemplateEntity->getWhere();  // equal_user = xxx

        //将where拆分
        foreach ($where as $key => $val) {
            $key = urldecode($key);
            $val = urldecode($val);
            $args = explode('-', $key);
            if ($val === '') {
                continue;
            }
            switch ($args[0]) {
                case "equal":
                    $query = $query->where($args[1], $val);
                    break;
                case "betweenStart":
                    $query = $query->where($args[1], ">=", $val);
                    break;
                case "betweenEnd":
                    $query = $query->where($args[1], "<=", $val);
                    break;
            }
        }

        foreach ($with as $w) {
            $query = $query->with($w);
        }

        if ($queryTemplateEntity->isPaginate()) {
            return $query->paginate($queryTemplateEntity->getLimit(), $queryTemplateEntity->getField(), '', $queryTemplateEntity->getPage());
        }

        return $query->get();
    }


    /**
     * 智能创建或修改模型
     * @param CreateObjectEntity $createObjectEntity
     * @return bool
     */
    public function createOrUpdateTemplate(CreateObjectEntity $createObjectEntity)
    {
        $object = $createObjectEntity->getModel();
        $query = $object::query();
        $map = $createObjectEntity->getMap();
        $model = $query->find((int)$map['id']);
        $createDate = $createObjectEntity->getCreateDate();
        $updateDate = $createObjectEntity->getUpdateDate();

        if (!$model) {
            $model = new $object;
        }

        $middles = [];

        foreach ($map as $key => $item) {
            $middle = $createObjectEntity->getMiddle($key);
            $item = urldecode($item);
            if ($middle) {
                $middles[] = ['middle' => $middle, 'data' => $item];
            } else {
                $model->$key = $item;
            }
        }

        $dateNow = date("Y-m-d H:i:s", time());

        if (!empty($createDate)) {
            $model->$createDate = $dateNow;
        }

        if (!empty($updateDate)) {
            $model->$updateDate = $dateNow;
        }

        try {
            $model->save();
            $id = $model->id;
            foreach ($middles as $m) {
                $middle = $m['middle'];
                $data = $m['data'];
                //删除中间表关系
                $middle['middle']::query()->where($middle['localKey'], $id)->delete();
                $localKey = $middle['localKey'];
                $foreignKey = $middle['foreignKey'];
                //重新建立模型关系
                foreach ($data as $datum) {
                    $middleObject = new $middle['middle'];
                    $middleObject->$localKey = $id;
                    $middleObject->$foreignKey = $datum;
                    $middleObject->save();
                }
            }
            return $id;
        } catch (\Exception $e) {
            return false;
        }
    }


    /**
     * 自动删除主键模型
     * @param DeleteBatchEntity $batchEntity
     * @return int
     */
    public function deleteTemplate(DeleteBatchEntity $batchEntity)
    {
        $list = $batchEntity->getList();
        if (!is_array($list) || count($list) == 0) {
            throw new HexException("你还没有选择数据呢(◡ᴗ◡✿)");
        }
        return $batchEntity->getModel()::destroy($list);
    }
}