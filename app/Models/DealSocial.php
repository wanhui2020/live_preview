<?php

namespace App\Models;

use App\Facades\CommonFacade;
use App\Facades\PlatformFacade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
 * 社交动态
 * */

class DealSocial extends BaseModel
{
    use  SoftDeletes;
    protected $table = 'deal_socials';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {

        });
        static::saving(function ($model) {
            if ($model->isDirty('name')) {
                if (!$model->name = PlatformFacade::keyword($model->name)) {
                    return false;
                }
            }
            if ($model->isDirty('content')) {
                if (!$model->content = PlatformFacade::keyword($model->content)) {
                    return false;
                }
            }
        });
    }

    //关联所属会员
    public function member()
    {
        return $this->belongsTo(MemberUser::class, 'member_id', 'id');
    }

    //关联系统用户
    public function audit()
    {
        return $this->belongsTo(SystemUser::class, 'audit_uid', 'id');
    }

    /**
     * 点赞记录
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function likes()
    {
        return $this->morphMany(DealLike::class, 'relevance');
    }

    /**
     * 评论记录
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function comments()
    {
        return $this->morphMany(DealComment::class, 'relevance');
    }

}
