<?php

namespace App\Models;

use App\Facades\CommonFacade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
 * 会员现金钱包提现
 * */

class MemberWalletWithdraw extends BaseModel
{
    use  SoftDeletes;
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        Relation::morphMap([
            'DealWithdraw' => DealWithdraw::class,
        ]);
        static::creating(function ($model) {
            if (empty($model->no)) {
                $model->no = CommonFacade::number('MemberWalletWithdraw', 10000000,'MWW-');
            }

        });
    }

    public function member()
    {
        return $this->belongsTo(MemberUser::class, 'member_id', 'id');
    }

    public function cash()
    {
        return $this->belongsTo(MemberWalletCash::class, 'member_id', 'member_id');
    }

    /**
     * 所属业务
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function relevance()
    {
        return $this->morphTo('relevance', 'relevance_type', 'relevance_id');
    }
}
