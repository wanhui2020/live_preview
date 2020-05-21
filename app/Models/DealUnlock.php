<?php

namespace App\Models;

use App\Facades\CommonFacade;
use App\Facades\PlatformFacade;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
 * 聊天解锁
 * */

class DealUnlock extends BaseModel
{
    use  SoftDeletes;
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->no)) {
                $model->no = CommonFacade::number('DealUnlock', 10000000,'DC-');
            }

//            $model->gold = PlatformFacade::config('chat_unlock');
//            $model->rate = PlatformFacade::config('chat_rate');
            $model->end_time = Carbon::now()->addDays(PlatformFacade::config('chat_unlock_duration'));
//            $model->commission = $model->gold * $model->rate;
            $model->received = $model->gold - $model->commission;
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
