<?php

namespace App\Models;

use App\Facades\PushFacade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
 * 会员关注/被关注
 * */

class MemberAttention extends BaseModel
{
    //
    use  SoftDeletes;
    protected $table = 'member_attentions';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        /*
         * 创建开始
         * */
        static::creating(function ($model) {

        });
        static::created(function ($model) {
            if (isset($model->tomember->push_token) && !empty($model->tomember->push_token)) {
                PushFacade::pushToken($model->tomember->push_token, $model->tomember->app_platform, $model->member->nick_name, '关注了您！', $type = 'NOTICE', ['type' => 'member', 'id' => $model->member->id, 'no' => $model->member->no, 'nickname' => $model->member->nick_name]);
            }
        });
        static::updating(function ($model) {
        });
    }

    //所属会员
    public function member()
    {
        return $this->belongsTo(MemberUser::class, 'member_id', 'id')->withDefault();
    }

    //对象会员
    public function tomember()
    {
        return $this->belongsTo(MemberUser::class, 'to_member_id', 'id')->withDefault();
    }
}
