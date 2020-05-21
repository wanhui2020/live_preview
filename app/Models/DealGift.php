<?php

namespace App\Models;

use App\Facades\CommonFacade;
use App\Facades\PlatformFacade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

/*
 * 礼物赠送
 * */

class DealGift extends BaseModel
{
    use  SoftDeletes;
    protected $table = 'deal_gifts';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        Relation::morphMap([
            'MemberUser' => MemberUser::class,
            'DealTalk' => DealTalk::class,
            'DealSocial' => DealSocial::class,
        ]);
        static::creating(function ($model) {
            if (empty($model->no)) {
                $model->no = CommonFacade::number('DealGift', 10000000, 'DG-');
            }

            if (empty($model->relevance_type)) {
                $model->relevance_type = 'MemberUser';
                $model->relevance_id = $model->to_member_id;
            }

            $model->total = $model->price * $model->quantity;
            $model->platform_commission = $model->total * $model->platform_rate;
            $model->received = $model->total - $model->platform_commission;
            if ($model->platform_way == 1) {
                $model->received = $model->received / env('PLATFORM_EXCHANGE_RATE', 100);
            }
        });

        static::saved(function ($model) {
            Cache::forever('PlatformCharm-'.$model->grade, $model);
        });

    }

    /**
     * 平台礼物
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gift()
    {
        return $this->belongsTo(PlatformGift::class, 'gift_id', 'id');
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


}
