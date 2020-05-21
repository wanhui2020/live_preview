<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;

class SystemTicket extends BaseModel
{
    protected $fillable = [
        'type', 'prefix'
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
            if (isset($model->versions)) {
                $model->versions++;
            }
        });

        /**
         * 创建开始
         */
        static::saved(function ($model) {
        });


    }

}
