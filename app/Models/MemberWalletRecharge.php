<?php

namespace App\Models;

use App\Facades\CommonFacade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
 * 会员现金钱包充值
 * */

class MemberWalletRecharge extends BaseModel
{
    use  SoftDeletes;
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        Relation::morphMap([
            'DealVip' => DealVip::class,
            'DealCash' => DealCash::class,
            'DealGold' => DealGold::class,
            'DealGive' => DealGive::class,
        ]);
        static::creating(function ($model) {
            if (empty($model->no)) {
                $model->no = CommonFacade::number('MemberWalletRecharge', 10000000,'MWR-');
            }

        });
    }

    public function member()
    {
        return $this->belongsTo(MemberUser::class, 'member_id', 'id');
    }

    public function payment()
    {
        return $this->belongsTo(PlatformPayment::class, 'payment_id', 'id');
    }

    public function price()
    {
        return $this->belongsTo(PlatformPrice::class, 'price_id', 'id');
    }

    /**
     * 审核人
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function audit()
    {
        return $this->belongsTo(SystemUser::class, 'audit_uid', 'id');
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
