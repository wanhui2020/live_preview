<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class RiskFacade extends Facade
{
    /**
     * 获取组件注册名称
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'RiskFacade';
    }
}