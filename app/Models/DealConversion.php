<?php

namespace App\Models;

use App\Facades\CommonFacade;
use App\Facades\DealFacade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
 * 会员提现申请
 * */

class DealConversion extends BaseModel
{
    use  SoftDeletes;
    protected $guarded = [];
    protected $table = 'deal_conversions';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->no)) {
                $model->no = CommonFacade::number('DealConversion', 10000000,'DC-');
            }

        });
        static::created(function ($model) {

        });
    }

    /**
     * 会员
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo(MemberUser::class, 'member_id', 'id');
    }

    /**
     * 价格
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function price()
    {
        return $this->belongsTo(PlatformPrice::class, 'price_id', 'id');
    }

    /**
     * 审核
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function audit()
    {
        return $this->belongsTo(SystemUser::class, 'audit_uid', 'id');
    }


    /**
     * 充值记录
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function recharge()
    {
        return $this->morphOne(MemberWalletRecharge::class, 'relevance');
    }
}
