<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Facades\CommonFacade;
use Illuminate\Support\Facades\Cache;

//管理
class PlatformSendMessage extends BaseModel
{
    //
    use  SoftDeletes;
    protected $table = 'platform_send_messages';
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
            Cache::forget('PlatformSendMessage');
        });

        static::deleted(function ($model) {
            Cache::forget('PlatformSendMessage');
        });
    }

}
