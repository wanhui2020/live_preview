<?php

namespace App\Providers;

use App\Service\GreenService;
use App\Services\RiskService;
use Illuminate\Support\ServiceProvider;

class GreenServiceProvider extends ServiceProvider
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
        $this->app->bind('GreenFacade', function () {
            return new GreenService();
        });
    }
}