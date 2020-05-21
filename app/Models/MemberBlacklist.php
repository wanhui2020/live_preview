<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
 * 会员拉黑
 * */
class MemberBlacklist extends BaseModel
{
    //
    use  SoftDeletes;
    protected $table = 'member_blacklists';
    protected $guarded = [];
    protected  static function boot()
    {
        parent::boot();
        /*
         * 创建开始
         * */
        static::creating(function($model){

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
