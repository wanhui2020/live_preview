<?php

namespace App\Models;

use App\Facades\CommonFacade;
use App\Facades\DealFacade;
use App\Facades\PlatformFacade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
 * 余额充值记录
 * */

class DealCash extends BaseModel
{
    use  SoftDeletes;
    protected $table = 'deal_cashs';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->no)) {
                $model->no = CommonFacade::number('DealCash', 10000000, 'DC-');
            }

            $model->commission = $model->money * PlatformFacade::config('recharge_rate');
            $model->received = $model->money - $model->commission;
        });
        static::created(function ($model) {
            //申请支付
            DealFacade::cashPay($model->id);
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
