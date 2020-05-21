<?php

namespace App\Providers;

use App\Service\OssService;
use App\Services\RiskService;
use Illuminate\Support\ServiceProvider;

class OssServiceProvider extends ServiceProvider
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
        $this->app->bind('OssFacade', function () {
            return new OssService();
        });
    }
}
