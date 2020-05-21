<?php

namespace App\Models;

use App\Utils\SelectList;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/*
 * 会员自拍认证
 * */

class MemberUserSelfie extends BaseModel
{
    use  SoftDeletes;
    protected $table = 'member_user_selfie';

    protected $guarded = [];
    protected $fillable = [];
    protected $appends = ['fill_picture', 'fill_video', 'fill_undertaking'];

    protected static function boot()
    {
        parent::boot();
        /*
         * 创建开始
         * */
        static::creating(function ($model) {

        });
        static::saving(function ($model) {
            if (empty($model->picture)) {
                unset($model->picture);
            }
            if (empty($model->video)) {
                unset($model->video);
            }
            if (empty($model->undertaking)) {
                unset($model->undertaking);
            }

            if ($model->isDirty('status') && $model->status != 9) {
                $model->audit_time = Carbon::now()->toDateTimeString();
                $systemUser = Auth::guard('SystemUser')->user();
                if (isset($systemUser)) {
                    $model->audit_uid = $systemUser->id;
                }
            }
            if ($model->isDirty('picture') || $model->isDirty('video') || $model->isDirty('undertaking')) {
                $model->status = 8;
            }
        });
        static::saved(function ($model) {
            if ($model->isDirty('status')) {
                $model->member->is_selfie = $model->status;
                $model->member->push();
            }
        });
    }

    /**
     * 自拍照
     * @return string
     */
    public function getFillPictureAttribute()
    {
        if (strpos($this->picture, 'http') === 0) {
            return url($this->picture);
        }
        return $this->picture;
    }

    /**
     * 自拍视频
     * @return string
     */
    public function getFillVideoAttribute()
    {
        if (strpos($this->video, 'http') === 0) {
            return url($this->video);
        }
        return $this->video;
    }

    /**
     * 承诺条款
     * @return string
     */
    public function getFillUndertakingAttribute()
    {
        if (strpos($this->undertaking, 'http') === 0) {
            return url($this->undertaking);
        }
        return $this->undertaking;
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
