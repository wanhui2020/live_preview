<?php

namespace App\Models;

use App\Facades\CommonFacade;
use App\Facades\DealFacade;
use App\Facades\PlatformFacade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
 * 主播打赏
 * */

class DealGive extends BaseModel
{
    use  SoftDeletes;
    protected $table = 'deal_gives';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->no)) {
                $model->no = CommonFacade::number('DealGive', 10000000,'DG-');
            }
            $model->rate = PlatformFacade::config('give_rate');
            $model->commission = $model->money * $model->rate;
            $model->received = $model->money - $model->commission;

        });
        static::created(function ($model) {
            //执行打赏支付
            DealFacade::givePay($model->id);
        });
    }

    /**
     * 赠送人
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo(MemberUser::class, 'member_id', 'id');
    }

    /**
     * 接收人
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tomember()
    {
        return $this->belongsTo(MemberUser::class, 'to_member_id', 'id');
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
