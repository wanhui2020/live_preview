<?php

namespace App\Models;

use App\Facades\CommonFacade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/*
 * 资源查看
 * */

class DealView extends BaseModel
{
    use  SoftDeletes;
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->no)) {
                $model->no = CommonFacade::number('DealView', 10000000, 'DV-');
            }
            $model->platform_commission = $model->total * $model->platform_rate;
            if ($model->total > $model->platform_commission) {
                $model->received = $model->total - $model->platform_commission;
                if ($model->platform_way == 1) {
                    $model->received = $model->received / env('PLATFORM_EXCHANGE_RATE', 100);
                }
            }

        });
    }

    /**
     * 会员
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member()
    {
        return $this->belongsTo(MemberUser::class, 'member_id', 'id');
    }

    /**
     * 资源所属
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tomember()
    {
        return $this->belongsTo(MemberUser::class, 'to_member_id', 'id');
    }

    /**
     * 资源
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resource()
    {
        return $this->belongsTo(MemberResource::class, 'resource_id', 'id');
    }
}
