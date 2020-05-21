<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Facades\CommonFacade;

/**
 * 支付通道管理
 * Class PlatformPaymentChannel
 * @package App\Models
 */
class PlatformPaymentChannel extends BaseModel
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
    }

    /**
     * 通道账号
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(PlatformPayment::class, 'channel_id', 'id');
    }

}
