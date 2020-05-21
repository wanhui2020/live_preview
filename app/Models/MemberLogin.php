<?php

namespace App\Models;

use App\Utils\SelectList;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
 * 会员登录
 * */

class MemberLogin extends BaseModel
{
    //
    use  SoftDeletes;
    protected $guarded = [];
    protected $fillable = [];

    protected static function boot()
    {
        parent::boot();
        /*
         * 创建开始
         * */
        static::creating(function ($model) {
            $model->login_time = Carbon::now()->toDateTimeString();
        });
        static::saving(function ($model) {
            if ($model->isDirty('logout_time')) {
                $model->duration = Carbon::parse($model->logout_time)->diffInSeconds(Carbon::parse($model->login_time),true);
            }

        });
    }

    //所属会员
    public function member()
    {
        return $this->belongsTo(MemberUser::class, 'member_id', 'id')->withDefault();
    }

}
