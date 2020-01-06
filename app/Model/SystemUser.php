<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id
 * @property string $user
 * @property string $pass
 * @property string $salt
 * @property string $login_date
 * @property string $create_date
 * @property string $login_ip
 * @property int $status
 * @property string $nickname
 * @property string $face
 * @property string $phone
 */
class SystemUser extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_user';
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

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * 获取用户下的角色
     */
    public function roles()
    {
        return $this->belongsToMany(SystemRole::class, 'system_user_role', 'user_id', 'role_id');
    }

}