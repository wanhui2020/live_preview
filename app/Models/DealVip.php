<?php

namespace App\Models;

use App\Facades\CommonFacade;
use App\Facades\DealFacade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
 * vip 购买记录
 * */

class DealVip extends BaseModel
{
    use  SoftDeletes;
    protected $table = 'deal_vips';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->no)) {
                $model->no = CommonFacade::number('DealVip', 10000000,'DV-');
            }
        });
        static::created(function ($model) {
            //申请支付
            DealFacade::vipPay($model->id);
        });
    }

    /**
     * 所属会员
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo(MemberUser::class, 'member_id', 'id');
    }

    /**
     *
     * 所属VIP产品
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vip()
    {
        return $this->belongsTo(PlatformVip::class, 'vip_id', 'id');
    }

    /**
     *
     * 关联系统用户
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
