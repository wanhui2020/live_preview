<?php

namespace App\Models;

use App\Utils\SelectList;
use Illuminate\Database\Eloquent\SoftDeletes;
/*
 * 会员评论
 * */
class DealComment extends BaseModel
{
    use  SoftDeletes;
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
        return $this->belongsTo(MemberUser::class,'member_id','id')->withDefault();
    }

    protected $appends = ['status_cn', 'type_cn'];

    public function getStatusCnAttribute()
    {
        if (isset($this->status)) {
            return SelectList::statusList()[$this->status];
        }
        return '';
    }

    public function getTypeCnAttribute()
    {
        if (isset($this->type)) {
            return SelectList::levelType()[$this->type];
        }
        return '';
    }
}
