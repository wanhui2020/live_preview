<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
 * 会员标签
 * */

class MemberTag extends BaseModel
{
    //
    use  SoftDeletes;
    protected $table = 'member_tags';
    protected $fillable = ['member_id', 'tag_id', 'sort'];
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        /*
         * 创建开始
         * */
        static::creating(function ($model) {

        });
    }

    //所属会员
    public function member()
    {
        return $this->belongsTo(MemberUser::class, 'member_id', 'id')->withDefault();
    }

    //所属分类标签
    public function tag()
    {
        return $this->belongsTo(SystemTag::class, 'tag_id', 'id')->withDefault();
    }


}
