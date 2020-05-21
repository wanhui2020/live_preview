<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 点赞
 * */
class DealLike extends BaseModel
{
    use  SoftDeletes;
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        Relation::morphMap([
            'MemberUser' => MemberUser::class,
            'DealSocial' => DealSocial::class,
            'MemberDynamic' => MemberDynamic::class,
        ]);
        /*
         * 创建开始
         * */
        static::creating(function ($model) {

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


    /**
     * 所属业务
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function relevance()
    {
        return $this->morphTo('relevance', 'relevance_type', 'relevance_id');
    }
}
