<?php

namespace App\Models;

use App\Utils\SelectList;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
 * 会员扩展信息
 * */

class MemberUserExtend extends BaseModel
{
    //
    use  SoftDeletes;
    protected $table = 'member_user_extend';

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

    //所属会员
    public function member()
    {
        return $this->belongsTo(MemberUser::class, 'member_id', 'id');
    }

}
