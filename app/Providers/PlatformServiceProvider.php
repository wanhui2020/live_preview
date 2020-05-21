<?php

namespace App\Providers;

use App\Services\AliApiService;
use App\Services\PlatformService;
use Illuminate\Support\ServiceProvider;

class PlatformServiceProvider extends ServiceProvider
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
        $this->app->bind('PlatformFacade', function () {
            return new PlatformService();
        });
    }
}
