<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property string $name
 * @property int $dict_id
 * @property string $val
 * @property int $status
 * @property int $rank
 * @property string $create_date
 */
class SystemDictList extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_dict_list';
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
    protected $casts = ['id' => 'integer', 'dict_id' => 'integer', 'status' => 'integer', 'rank' => 'integer'];

    public $timestamps = false;
}