<?php

namespace App\Models;

use App\Facades\CommonFacade;
use App\Facades\PlatformFacade;
use App\Facades\PushFacade;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
 * 聊天记录
 * */

class DealChat extends BaseModel
{
    use  SoftDeletes;
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->no)) {
                $model->no = CommonFacade::number('DealChat', 10000000, 'DC-');
            }

            $model->platform_commission = $model->total * $model->platform_rate;
            $model->received = $model->total - $model->platform_commission;
            if ($model->platform_way == 1) {
                $model->received = $model->received / env('PLATFORM_EXCHANGE_RATE', 100);
            }
        });
        static::created(function ($model) {
            //未在线推送信息
            $member = $model->member;
            $tomember = $model->tomember;
            if ($tomember->online_status != 0) {
                if ($tomember->push_token) {
                    PushFacade::pushToken($tomember->push_token, $tomember->app_platform, $member->nick_name, '你有新信息',$type='NOTICE', ['type' => 'message', 'id' => $member->id, 'no' => $member->no, 'nickname' => $member->nick_name]);
                }
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

    /**
     * 发送记录
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function formMessages()
    {
        return $this->hasMany(DealMessage::class, 'member_id', 'member_id');
    }

    /**
     * 接收记录
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function toMessages()
    {
        return $this->hasMany(DealMessage::class, 'to_member_id', 'to_member_id');
    }
}
