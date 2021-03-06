<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppErrorLog extends Model
{

    const ANDROID = 'android';

    const IOS = 'ios';

    public $timestamps = false;

    protected $guarded = [];

    protected $table = 'app_error_logs';

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        static::creating(function ($model) {
            $model->created_at = now()->toDateTimeString();
        });
    }

}
