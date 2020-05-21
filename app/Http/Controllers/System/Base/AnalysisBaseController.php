<?php

namespace App\Http\Controllers\System\Base;

use App\Facades\FinanceFacade;
use App\Http\Controllers\Controller;

//平台统计表
class AnalysisBaseController extends Controller
{


    public function __construct(AnalysisBaseRepository $repository)
    {
        $this->repository = $repository;
    }

    //平台统计表
    public function index()
    {

        //客户总充值
        FinanceFacade::total('recharge');
        //客户总提现
        FinanceFacade::total('withdraw');
        //客户总提现手续费 实收手续费
        FinanceFacade::total('service');
        //客户持仓中的总策略金
        FinanceFacade::total('contract');
        //总冻结资金
        FinanceFacade::total('assure');
        //总递延费
        FinanceFacade::total('deferred');
        //总成交金额
        FinanceFacade::total('succeed');
        //客户资金金币
        FinanceFacade::total('balance');
        // 总策略盈亏
        FinanceFacade::total('contracts');
        //客户总资产
        FinanceFacade::total('asset');
        //总持仓市值
        FinanceFacade::total('market');
        return $this->succeed(1,'所有统计完成');
    }
}
