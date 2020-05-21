<?php

namespace App\Models;

use App\Facades\CommonFacade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlatformQuestion extends BaseModel
{
    use  SoftDeletes;
    protected $fillable = ['no', 'relevance_type', 'relevance_id', 'parent_id','order_id', 'title', 'content', 'atlas', 'grade', 'finish_status', 'finish_time', 'status', 'sort', 'remark'];
    protected $touches = ['parent'];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->no = CommonFacade::number('PlatformQuestion',100000,'PQ-');
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

    /**
     * 获取服务商的下一级
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childrens()
    {
        return $this->hasMany(PlatformQuestion::class, 'parent_id', 'id');
    }

    /**
     *此用户所属服务商
     */
    public function parent()
    {
        return $this->belongsTo(PlatformQuestion::class, 'parent_id', 'id');
    }
    /**
     *关联订单
     */
    public function order()
    {
        return $this->belongsTo(DealOrder::class, 'order_id', 'id');
    }

}
