<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Facades\CommonFacade;

//平台文件
class PlatformFile extends BaseModel
{
    //
    use  SoftDeletes;
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        Relation::morphMap([
            'MemberUser' => MemberUser::class,
            'MemberResource' => MemberResource::class,
            'MemberSocial' => DealSocial::class,
            'MemberDynamic' => MemberDynamic::class,
        ]);
        static::retrieved(function ($model) {
            if (empty($model->thumb)) {
                $model->thumb = $model->url;
                if (in_array($model->extension, ['mp4'])) {
                    $model->thumb = $model->thumb . '?x-oss-process=video/snapshot,t_1000,f_jpg,w_0,h_0,m_fast';
                }

            }
        });

        /*
         * 创建开始
         * */
        static::creating(function ($model) {
            if (empty($model->extension)){
                $resp=$model->url.'?x-oss-process=image/info';

            }
            if (in_array($model->extension, ['mp4'])) {
                $model->thumb = $model->url . '?x-oss-process=video/snapshot,t_1000,f_jpg,w_0,h_0,m_fast';
            } else {
                $model->thumb = $model->url;
            }

        });
    }

    /**
     * 所属业务
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function relevance()
    {
        return $this->morphTo('relevance', 'relevance_type', 'relevance_id');
    }
}
