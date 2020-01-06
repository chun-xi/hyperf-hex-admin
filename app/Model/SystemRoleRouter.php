<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property int $router_id
 * @property int $role_id
 */
class SystemRoleRouter extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_role_router';
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
    protected $casts = ['id' => 'integer', 'router_id' => 'integer', 'role_id' => 'integer'];

    public $timestamps = false;
}