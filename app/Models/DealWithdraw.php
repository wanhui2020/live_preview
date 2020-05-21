<?php

namespace App\Models;

use App\Facades\CommonFacade;
use App\Facades\DealFacade;
use App\Facades\PlatformFacade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
 * 会员提现申请
 * */

class DealWithdraw extends BaseModel
{
    use  SoftDeletes;
    protected $guarded = [];
    protected $table = 'deal_withdraws';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->no)) {
                $model->no = CommonFacade::number('DealWithdraw', 10000000, 'DW-');
            }
            $model->total = $model->money;
            $model->platform_rate = PlatformFacade::config('withdraw_rate');

            if ($model->platform_rate < 1) {
                $model->platform_commission = $model->total * $model->platform_rate;
            } else {
                $model->platform_commission = $model->platform_rate;
            }
            $model->received = $model->money - $model->platform_commission;

        });
        static::created(function ($model) {
            //生成支付
            DealFacade::withdrawPay($model->id);
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
     * 提现记录
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function withdraw()
    {
        return $this->morphOne(MemberWalletWithdraw::class, 'relevance');
    }

}
