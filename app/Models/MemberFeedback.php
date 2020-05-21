<?php

namespace App\Models;

use App\Facades\PlatformFacade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
 * 意见反馈
 * */

class MemberFeedback extends BaseModel
{
    //
    use  SoftDeletes;
    protected $table = 'member_feedbacks';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        /*
         * 创建开始
         * */
        static::creating(function ($model) {

        });
        static::saving(function ($model) {
            if ($model->isDirty('content')) {
                if (!$model->content = PlatformFacade::keyword($model->content)) {
                    return false;
                }
            }
        });
    }

    //关联会员用户
    public function member()
    {
        return $this->belongsTo(MemberUser::class, 'member_id', 'id');
    }

    //关联系统用户
    public function audit()
    {
        return $this->belongsTo(SystemUser::class, 'replay_uid', 'id');
    }
}
