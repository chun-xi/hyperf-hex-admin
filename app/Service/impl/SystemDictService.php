<?php


namespace App\Service\impl;


use App\Model\SystemDict;
use App\Service\SystemDictServiceInterface;
use App\Utils\CategoryUtil;
use Hyperf\Database\Model\Relations\Relation;
use Hyperf\DbConnection\Db;

class SystemDictService implements SystemDictServiceInterface
{

    /**
     * 获取字典列表
     * @param string $dictName
     * @return mixed
     */
    public function getDict(string $dictName)
    {
        $dict = explode(",", $dictName);

        $dictLength = count($dict);

        if ($dictLength == 1) {
            //普通字典查询
            $list = SystemDict::with(['dicts' => function (Relation $relation) {
                $relation->where("status", 1)->orderBy("rank", "asc")->select(['val as id', 'dict_id', 'name']);
            }])->where("code", $dict[0])->first();

            return $list->dicts->toArray();

        } elseif ($dictLength >= 3) {
            //远程表字典查询
            $prefix = env('DB_PREFIX');
            try {
                $field = "{$dict[1]} as id,{$dict[2]} as name" . (array_key_exists(3, $dict) ? ",{$dict[3]} as pid" : '');

                $select = Db::select("select {$field} from {$prefix}{$dict[0]} order by id desc");

                if (array_key_exists(3, $dict)) {
                    $select = CategoryUtil::generateTree($select, 'id', 'pid', 'children');
                }

            } catch (\Exception $e) {
                return null;
            }
            return $select;
        }

        return null;
    }
}