<?php

namespace App\Models;

use App\Facades\CommonFacade;

/*
 * 微信查看支付
 * */

class WechatPayment extends BaseModel
{
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->no)) {
                $model->no = CommonFacade::number('DealChat', 10000000, 'WP-');
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
     * 被看会员
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tomember()
    {
        return $this->belongsTo(MemberUser::class, 'to_member_id', 'id');
    }


}
