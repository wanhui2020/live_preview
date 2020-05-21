<?php

namespace App\Models;

use App\Facades\CommonFacade;
use App\Facades\MemberFacade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PlatformConfig extends BaseModel
{
    use SoftDeletes;
    protected $table = 'platform_config';

    protected $guarded = [];


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
        /**
         * 保存
         */
        static::saved(function ($model) {
            Cache::forever('PlatformConfig', $model);
            if ($model->isDirty('online_robot')) {
                MemberFacade::onlineRebot();
            }
        });


    }

}
