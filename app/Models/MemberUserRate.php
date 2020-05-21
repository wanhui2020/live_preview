<?php

namespace App\Models;

use App\Facades\CommonFacade;
use App\Facades\PlatformFacade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
 * 会员费率
 * */

class MemberUserRate extends BaseModel
{
    use  SoftDeletes;
    protected $table = 'member_user_rate';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::retrieved(function ($model) {
        });
        static::creating(function ($model) {
            $model->text_fee = PlatformFacade::charm('text_fee');
            $model->voice_fee = PlatformFacade::charm('voice_fee');
            $model->video_fee = PlatformFacade::charm('video_fee');
            $model->view_picture_fee = PlatformFacade::charm('view_picture_fee');
            $model->view_video_fee = PlatformFacade::charm('view_video_fee');

            $model->gift_rate = PlatformFacade::charm('gift_rate');
            $model->chat_rate = PlatformFacade::charm('chat_rate');
            $model->text_rate = PlatformFacade::charm('text_rate');
            $model->voice_rate = PlatformFacade::charm('voice_rate');
            $model->video_rate = PlatformFacade::charm('video_rate');
            $model->view_picture_rate = PlatformFacade::charm('view_picture_rate');
            $model->view_video_rate = PlatformFacade::charm('view_video_rate');

            $model->recommender_income_rate = (float)PlatformFacade::config('recommender_income_rate');
            $model->recommender_recharge_rate = (float)PlatformFacade::config('recommender_recharge_rate');
            $model->superior_revenue_time = (int)PlatformFacade::config('superior_revenue_time');
            $model->wechat_pay_money = PlatformFacade::config('wechat_pay_money');
            $model->wechat_platform_share = PlatformFacade::config('wechat_platform_share');

        });
    }

    public function member()
    {
        return $this->belongsTo(MemberUser::class, 'member_id', 'id');
    }
}
