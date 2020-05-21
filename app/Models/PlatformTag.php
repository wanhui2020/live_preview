<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Facades\CommonFacade;
use Illuminate\Support\Facades\Cache;

//标签管理
class PlatformTag extends BaseModel
{
    //
    use  SoftDeletes;
    protected $table = 'platform_tags';
    protected $guarded = [];
    protected  static function boot()
    {
        parent::boot();
        /*
         * 创建开始
         * */
        static::creating(function($model){

        });

        static::saved(function ($model) {
            Cache::forget('PlatformTag');
        });

        static::deleted(function ($model) {
            Cache::forget('PlatformTag');
        });
    }

}
