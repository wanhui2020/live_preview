<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Facades\CommonFacade;
//
class PlatformType extends BaseModel
{
    //
    use  SoftDeletes;
    protected $table = 'platform_types';
    protected $guarded = [];
    protected  static function boot()
    {
        parent::boot();
        /*
         * 创建开始
         * */
        static::creating(function($model){

        });
    }

}
