<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
 * 会员举报
 * */

class MemberReport extends BaseModel
{
    //
    use  SoftDeletes;
    protected $table = 'member_reports';
    protected $guarded = [];
    protected $fillable = [];

    protected static function boot()
    {
        parent::boot();
        /*
         * 创建开始
         * */
        static::creating(function ($model) {

        });
    }

    //所属举报会员
    public function member()
    {
        return $this->belongsTo(MemberUser::class, 'member_id', 'id')->withDefault();
    }

    //被举报会员
    public function tomember()
    {
        return $this->belongsTo(MemberUser::class, 'to_member_id', 'id')->withDefault();
    }

    //基础数据id
    public function report()
    {
        return $this->belongsTo(PlatformBasic::class, 'report_id', 'id')->withDefault();
    }
    //关联系统用户
    public function audit()
    {
        return $this->belongsTo(SystemUser::class, 'audit_uid', 'id');
    }
}
