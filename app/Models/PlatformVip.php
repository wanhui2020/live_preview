<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Facades\CommonFacade;
use Illuminate\Support\Facades\Cache;

/**
 * VIP管理
 * Class PlatformVip
 * @package App\Models
 */
class PlatformVip extends BaseModel
{
    //
    use  SoftDeletes;
    protected $table = 'platform_vip';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        /*
         * 创建开始
         * */
        static::creating(function ($model) {
            $model->name = 'V' . $model->grade;
        });
        static::saved(function ($model) {
            Cache::forget('PlatformVip');
        });
    }

}
