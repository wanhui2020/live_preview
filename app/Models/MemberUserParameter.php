<?php

namespace App\Models;

use App\Facades\CommonFacade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
 * 会员参数
 * */

class MemberUserParameter extends BaseModel
{
    use  SoftDeletes;
    protected $table = 'member_user_parameter';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {

        });
    }

    /**
     * 所属会员
     * @return mixed
     */
    public function member()
    {
        return $this->belongsTo(MemberUser::class,'member_id','id');
    }
}
