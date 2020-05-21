<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Facades\CommonFacade;
//banner 图
class PlatformNotice extends BaseModel
{
    //
    use  SoftDeletes;
    protected $table = 'platform_notices';
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


    /**
     * 用户是否已读
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     */
    public function detail()
    {
        return $this->morphMany(PlatformNoticeDetail::class, 'relevance');
    }
}
