<?php

namespace App\Models;

use App\Facades\CommonFacade;
use function GuzzleHttp\Psr7\str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

/*
 * 会员金币钱包
 * */

class MemberWalletGold extends BaseModel
{
    use  SoftDeletes;
    protected $table = 'member_wallet_golds';
    protected $guarded = [];
    protected $appends = ['consumable'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {

        });

        static::saving(function ($model) {
            $money = $model->usable + $model->platform;
            $model->balance = ($money>0)?$money:0;
        });
    }

    /**
     * 可消费金额
     * @return mixed
     */
    public function getConsumableAttribute()
    {
        return $this->usable - $this->freeze;
    }

    public function member()
    {
        return $this->belongsTo(MemberUser::class, 'member_id', 'id');
    }


    /**
     * 交易记录
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function records()
    {
        return $this->morphMany(MemberWalletRecord::class, 'relevance');
    }
}
