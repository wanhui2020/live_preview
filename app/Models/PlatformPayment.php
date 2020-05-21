<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Facades\CommonFacade;

/**
 * 支付通道管理
 * Class PlatformPayment
 * @package App\Models
 */
class PlatformPayment extends BaseModel
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

    public function channel()
    {
        return $this->belongsTo(PlatformPaymentChannel::class, 'channel_id', 'id');
    }
}
