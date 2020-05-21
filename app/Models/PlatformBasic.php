<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Facades\CommonFacade;
use Illuminate\Support\Facades\Cache;

//平台基础数据
class PlatformBasic extends BaseModel
{
    //
    use  SoftDeletes;
    protected $table = 'platform_basic';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        /*
         * 创建开始
         * */
        static::creating(function ($model) {

        });

        static::saved(function ($model) {
            Cache::forget('PlatformBasic-' . $model->type);
        });
    }

}
