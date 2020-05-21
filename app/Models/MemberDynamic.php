<?php

namespace App\Models;

use App\Facades\CommonFacade;
use App\Facades\MemberFacade;
use App\Facades\PlatformFacade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

/*
 * 动态
 * */

class MemberDynamic extends BaseModel
{
    use  SoftDeletes;
    protected $table = 'member_dynamics';
    protected $guarded = [];
    protected $appends = ['consumable'];

    protected static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            //系统审核
            if ($model->status == 9 && PlatformFacade::config('platform_text_audit') == 1) {
                MemberFacade::DynamicVerificationAudit($model->id);
            }
        });


        static::updated(function ($model) {
            //系统审核
            if ($model->status == 9 && PlatformFacade::config('platform_text_audit') == 1) {
                MemberFacade::DynamicVerificationAudit($model->id);
            }

        });
        static::saving(function ($model) {
//            $model->balance = $model->usable + $model->platform;
        });
    }

    /**
     * 可消费金额
     * @return mixed
     */
    public function getConsumableAttribute()
    {
        return $this->usable - $this->freeze;
    }

    public function member()
    {
        return $this->belongsTo(MemberUser::class, 'member_id', 'id');
    }


    /**
     * 文件图片
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function file()
    {
        return $this->morphMany(PlatformFile::class, 'relevance');
    }


    /**
     * 点赞
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function like()
    {
        return $this->morphMany(DealLike::class, 'relevance');
    }


    /**
     * 评论
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function comment()
    {
        return $this->morphMany(DealComment::class, 'relevance');
    }

    //关联系统用户
    public function audit()
    {
        return $this->belongsTo(SystemUser::class, 'audit_uid', 'id');
    }
}
