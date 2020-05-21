<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Facades\CommonFacade;
use Illuminate\Support\Facades\Cache;

//礼物管理
class PlatformGift extends BaseModel
{
    //
    use  SoftDeletes;
    protected $table = 'platform_gifts';
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
            Cache::forget('PlatformGift');
        });
    }
    /**
     * 发送记录
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function gifts()
    {
        return $this->hasMany(DealGift::class, 'gift_id', 'id');
    }
}
