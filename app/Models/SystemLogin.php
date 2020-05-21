<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemLogin extends BaseModel
{
    use  SoftDeletes;
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        Relation::morphMap([
            'SystemUser' => SystemUser::class,
            'MemberUser' => MemberUser::class,
        ]);

        /**
         * 创建开始
         */
        static::creating(function ($model) {

        });
        /**
         * 创建成功后
         */
        static::created(function ($model) {

        });

        /**
         * 更新成功
         */
        static::updating(function ($model) {

        });
        /**
         * 删除成功
         */
        static::deleted(function ($model) {

        });

        /**
         * 创建开始
         */
        static::saving(function ($model) {

        });
        /**
         * 创建开始
         */
        static::saved(function ($model) {
        });


    }

    /**
     * 所属商户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function relevance()
    {
        return $this->morphTo('relevance', 'relevance_type', 'relevance_id');
    }
}
