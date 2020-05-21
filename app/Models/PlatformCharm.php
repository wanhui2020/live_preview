<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Facades\CommonFacade;

/**
 * 魅力管理
 * Class PlatformCharm
 * @package App\Models
 */
class PlatformCharm extends BaseModel
{
    //
    use  SoftDeletes;
    protected $table = 'platform_charm';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        /*
         * 创建开始
         * */
        static::creating(function ($model) {
            $model->name = 'M' . $model->grade;
        });
    }

}
