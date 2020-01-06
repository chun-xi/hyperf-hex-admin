<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property string $path
 * @property int $pid
 * @property string $name
 * @property int $status
 * @property string $face
 * @property int $type
 * @property int $rank
 */
class SystemRouter extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_router';
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
    protected $casts = ['id' => 'integer', 'pid' => 'integer', 'status' => 'integer', 'type' => 'integer', 'rank' => 'integer'];

    public $timestamps = false;
}