<?php

namespace App\Models;

use App\Facades\CommonFacade;
use App\Facades\GreenFacade;
use App\Facades\ImFacade;
use App\Facades\MemberFacade;
use App\Facades\PlatformFacade;
use App\Jobs\ScanJob;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
 * 资料审核
 * */

class MemberVerification extends BaseModel
{
    use  SoftDeletes;
    protected $table = 'member_verifications';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {

        });
        static::created(function ($model) {
            //系统审核
            if ($model->status == 9 && PlatformFacade::config('platform_text_audit') == 1) {
                MemberFacade::VerificationAudit($model->id);
                //生成检查队列
//                ScanJob::dispatch('MemberVerification', $model->id);
            }
        });


        static::updated(function ($model) {
            //系统审核
            if ($model->status == 9 && PlatformFacade::config('platform_text_audit') == 1) {
                MemberFacade::VerificationAudit($model->id);
                //生成检查队列
//                ScanJob::dispatch('MemberVerification', $model->id);
            }

        });
        static::saving(function ($model) {
            if ($model->isDirty('status')) {
                if ($model->status != 9) {
                    $model->audit_time = Carbon::now()->toDateTimeString();
                }
            }
        });
        static::saved(function ($model) {
            if ($model->isDirty('status')) {
                if ($model->status == 0) {
                    //昵称
                    if ($model->info_type == 0) {
                        $model->member()->update(['nick_name' => $model->new_data]);
                        ImFacade::userSetInfo($model->member->no, 'Tag_Profile_IM_Nick', $model->new_data);
                    }
                    //签名
                    if ($model->info_type == 1) {
                        $model->member()->update(['signature' => $model->new_data]);
                    }
                    //格言
                    if ($model->info_type == 2) {
                        $model->member()->update(['aphorism' => $model->new_data]);
                    }
                }
            }
        });
    }

    /**
     * 所属用户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo(MemberUser::class, 'member_id', 'id');
    }

    /**
     * 所属用户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function audit()
    {
        return $this->belongsTo(SystemUser::class, 'audit_uid', 'id');
    }
}
