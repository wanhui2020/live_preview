<?php

namespace App\Models;

use App\Facades\CommonFacade;
use App\Utils\SelectList;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
 * 会员金币钱包
 * */

class MemberWalletCash extends BaseModel
{
    use  SoftDeletes;
    protected $table = 'member_wallet_cashs';
    protected $fillable = ['member_id', 'usable', 'lock', 'freeze', 'platform'];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
//            if ($model->lock > $model->usable) {
//                $model->lock = $model->usable;
//            }

            $model->balance = $model->usable + $model->freeze + $model->platform;
        });
    }


    protected $appends = ['drawing'];

    public function getDrawingAttribute()
    {
        return $this->usable - $this->lock;
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
