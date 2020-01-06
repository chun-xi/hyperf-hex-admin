<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property string $name
 * @property int $status
 */
class SystemRole extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_role';
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
    protected $casts = ['id' => 'integer', 'status' => 'integer'];

    public $timestamps = false;

    /**
     * 获取角色下的权限
     */
    public function routers()
    {
        return $this->belongsToMany(SystemRouter::class, 'system_role_router', 'role_id', 'router_id');
    }
}