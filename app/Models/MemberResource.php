<?php

namespace App\Models;

use App\Facades\CommonFacade;
use App\Facades\GreenFacade;
use App\Facades\MemberFacade;
use App\Facades\PlatformFacade;
use App\Jobs\ScanJob;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use function Composer\Autoload\includeFile;

/*
 * 会员资源
 * */

class MemberResource extends BaseModel
{
    use  SoftDeletes;
    protected $table = 'member_resources';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();


        static::creating(function ($model) {
            if (empty($model->status) && PlatformFacade::config('picture_audit') == 1) {
                $model->status = 0;
            }
            $model->status = 9;
        });
        static::created(function ($model) {

        });
        static::saving(function ($model) {
            if ($model->isDirty('status') && $model->status != 9) {
                $model->audit_time = Carbon::now()->toDateTimeString();
            }

        });
        static::saved(function ($model) {
            if ($model->isDirty('status') && $model->status == 0) {
                $member = $model->member;
                if (isset($resource->file) && !strstr($model->file['url'],'outin-46d0eabc635811eaa4b500163e1c60dc.oss-cn-shanghai.aliyuncs.com')) {
                    if (empty($member->cover)) {
                        $member->cover = $model->file->url;
                        $member->save();
                    }
                }
            }


        });
    }

    //关联会员
    public function member()
    {
        return $this->belongsTo(MemberUser::class, 'member_id', 'id');
    }

    //关注度
    public function views()
    {
        return $this->hasMany(DealView::class, 'resource_id', 'id');
    }


    /**
     * 资源文件
     * @return \Illuminate\Database\Eloquent\Relations\morphOne
     */
    public function file()
    {
        return $this->morphOne(PlatformFile::class, 'relevance');
    }


    //关联系统用户
    public function audit()
    {
        return $this->belongsTo(SystemUser::class, 'audit_uid', 'id');
    }
}
