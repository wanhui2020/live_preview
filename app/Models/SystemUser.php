<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class SystemUser extends Authenticatable
{

    use SoftDeletes, Notifiable,HasRoles;

    protected $guarded = [];

    protected $hidden = [
        'password', 'remember_token',
    ];
    protected static function boot()
    {
        parent::boot();

        /**
         * 创建开始
         */
        static::creating(function ($model) {


        });
        /**
         * 创建成功后
         */
        static::created(function ($model) {

        });

        /**
         * 更新成功
         */
        static::updating(function ($model) {

        });
        /**
         * 删除成功
         */
        static::deleted(function ($model) {

        });

        /**
         * 创建开始
         */
        static::saving(function ($model) {

        });


    }

    /**
     * 登录日志
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function logins()
    {
        return $this->morphMany(SystemLogin::class, 'relevance');
    }
}
