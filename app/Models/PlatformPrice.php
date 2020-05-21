<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Facades\CommonFacade;
use Illuminate\Support\Facades\Cache;

//平台充值价格维护
class PlatformPrice extends BaseModel
{
    //
    use  SoftDeletes;
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
            Cache::forget('PlatformPrice');
        });
    }

}
