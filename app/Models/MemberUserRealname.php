<?php

namespace App\Models;

use App\Utils\SelectList;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/*
 * 会员实名认证
 * */

class MemberUserRealname extends BaseModel
{
    use  SoftDeletes;
    protected $table = 'member_user_realname';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::retrieved(function ($model) {

        });
        /*
         * 创建开始
         * */
        static::creating(function ($model) {

        });

        /*
         * 保存前
         * */
        static::saving(function ($model) {
            if ($model->isDirty('status') && $model->status != 9) {
                $model->audit_time = Carbon::now()->toDateTimeString();
                $systemUser = Auth::guard('SystemUser')->user();
                if (isset($systemUser)) {
                    $model->audit_uid = $systemUser->id;
                }
            }
        });

        static::saved(function ($model) {
            if ($model->isDirty('status')) {
                $model->member->is_real = $model->status;
                $model->member->push();
            }
        });

    }

    //所属会员
    public function member()
    {
        return $this->belongsTo(MemberUser::class, 'member_id', 'id');
    }

    //关联系统用户
    public function audit()
    {
        return $this->belongsTo(SystemUser::class, 'audit_uid', 'id');
    }
}
