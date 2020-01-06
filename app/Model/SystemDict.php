<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $remark
 */
class SystemDict extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_dict';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer'];

    public $timestamps = false;

    /**
     * 获取字典列表
     */
    public function dicts()
    {
        return $this->hasMany(SystemDictList::class, 'dict_id', 'id');
    }
}