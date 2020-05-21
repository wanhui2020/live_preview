<?php

namespace App\Providers;

use App\Services\BaseService;
use App\Services\PayService;
use App\Services\PushService;
use Illuminate\Support\ServiceProvider;

class PayServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //使用bind绑定实例到接口以便依赖注入
        $this->app->bind('PayFacade', function () {
            return new PayService();
        });
    }
}
