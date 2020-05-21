<?php

namespace App\Models;

use App\Facades\CommonFacade;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
 * 访问记录
 * */

class MemberVisitor extends BaseModel
{
    use  SoftDeletes;
    protected $table = 'member_visitors';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {

        });
        static::updating(function ($model) {
            $model->last_time = Carbon::now()->toDateTimeString();
        });
        static::saving(function ($model) {
        });
    }

    //访问人
    public function member()
    {
        return $this->belongsTo(MemberUser::class, 'member_id', 'id')->withDefault();
    }

    //被访问人
    public function tomember()
    {
        return $this->belongsTo(MemberUser::class, 'to_member_id', 'id')->withDefault();
    }
}
