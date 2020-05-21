<?php

namespace App\Providers;

use App\Services\DealService;
use App\Services\WalletService;
use Illuminate\Support\ServiceProvider;

class WalletServiceProvider extends ServiceProvider
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
        $this->app->bind('WalletFacade', function () {
            return new WalletService();
        });
    }
}
