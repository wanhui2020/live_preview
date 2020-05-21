<?php

namespace App\Models;

use App\Repositories\SystemUserRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemLog extends BaseModel
{
    //
    use  SoftDeletes;
    protected $guarded = [];



    /**
     * 系统用户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function system()
    {
        return $this->belongsTo(SystemUser ::class, 'system_id', 'id');
    }
}
