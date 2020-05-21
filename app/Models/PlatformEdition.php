<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class PlatformEdition extends BaseModel
{

    use SoftDeletes;

    const OPEN = 0;

    const CLOSE = 1;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        /*
         * 比例表创建开始
         * */
        static::creating(function ($model) {
        });
    }

    /**
     * 安装量
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function members()
    {
        return $this->hasMany(MemberUser::class, 'app_version', 'version');
    }
}
