<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberUserType extends Model
{
    //
    use  SoftDeletes;
    protected $table = 'member_user_type';

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


}
