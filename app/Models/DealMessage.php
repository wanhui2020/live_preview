<?php

namespace App\Models;

use App\Facades\CommonFacade;
use App\Facades\PlatformFacade;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
 * 聊天收费
 * */

class DealMessage extends BaseModel
{
    use  SoftDeletes;
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->no)) {
                $model->no = CommonFacade::number('DealMessage', 10000000,'DM-');
            }

            $model->total = $model->price  ;
            $model->platform_commission = $model->total * $model->platform_rate;
            $received = $model->total - $model->platform_commission;
            $model->received = $received > 0 ? $received : 0;

            if ($model->platform_way == 1) {
                $model->received = $model->received / env('PLATFORM_EXCHANGE_RATE', 100);
            }
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
     * 解锁会员
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tomember()
    {
        return $this->belongsTo(MemberUser::class, 'to_member_id', 'id');
    }
}
