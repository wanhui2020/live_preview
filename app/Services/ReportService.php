<?php

namespace App\Services;

use App\Facades\CommonFacade;
use App\Traits\ResultTrait;
use Illuminate\Http\Request;

//统计报表
class ReportService
{

    private $pageSize = 10;
    use ResultTrait;

    public function getEntrusts(Request $request)
    {
        $where = function ($query) use ($request) {
            if ($request->key != null) {
                $query->where('no', 'like', '%' . $request->key . '%');
            }
            if ($request->customer != null) {
                $query->whereHas('customer', function ($query) use ($request) {
                    $query->where('no', 'like', '%' . $request->customer . '%')
                        ->orwhere('realname', 'like', '%' . $request->customer . '%')
                        ->orwhere('phone', 'like', '%' . $request->customer . '%');
                });
            }
            if ($request->agent != null) {
                $query->whereHas('agent', function ($query) use ($request) {
                    $query->where('no', 'like', '%' . $request->agent . '%')
                        ->orWhere('name', 'like', '%' . $request->agent . '%')
                        ->orWhere('email', 'like', '%' . $request->agent . '%')
                        ->orWhere('phone', 'like', '%' . $request->agent . '%');
                });
            }
            if ($request->stock != null) {
                $query->where('stock_code', 'like', '%' . $request->stock . '%')
                    ->orWhere('stock_name', 'like', '%' . $request->stock . '%');
            }
            if ($request->start_time != null) {
                $query->where('created_at', '>', $request->start_time);
            }

            if ($request->end_time != null) {
                $query->where('created_at', '<', $request->end_time . '23:59:59');
            }
            if ($request->trade_type != null) {
                $query->where('trade_type', $request->trade_type);
            }
        };
        $entrusts = DealEntrust::where('entrust_status', 0)
            ->where('success_num', '>', 0)
            ->with([
                'customer:id,no,realname,phone',
                'agent:id,no,name',
                'position:id,no,charge_money,platform_commission'
            ])
            ->where($where)
            ->get(['id', 'customer_id', 'agent_id', 'position_id', 'no',
                'entrust_status', 'success_total', 'trade_type', 'charge_deal',
                'charge_service', 'created_at', 'stock_code', 'stock_name',
                'charge_stamps', 'charge_transfer']);
        return $entrusts;
    }

    /**
     *  导出
     * @return array
     */
    public function getEntrustExport(Request $request)
    {
        try {
            $entrusts = $this->getEntrusts($request);
            $arr = [];
            $count_arr = $this->getEntrustCount($request);
            foreach ($entrusts as $k => $v) {
                $arr[$k]['订单委托编号'] = $v['no'];
                $arr[$k]['客户编号'] = $v['customer']['realname'] . $v['customer']['no'];
                $arr[$k]['所属服务商'] = $v['agent']['name'] . $v['agent']['no'];
                $arr[$k]['股票信息'] = $v['stock_name'] . $v['stock_code'];
                $arr[$k]['买卖方向'] = $v['trade_type'] == 0 ? '买入' : '卖出';
                $arr[$k]['成交金额'] = round($v['success_total'], 2);
                $arr[$k]['策略建仓费'] = round(($v['trade_type'] == 1 ? 0 : $v['position']['charge_money']), 2); // 卖出没有建仓费
                $arr[$k]['交易手续费'] = round($v['charge_deal'], 2);
                $arr[$k]['印花税'] = round($v['charge_stamps'], 2);
                $arr[$k]['过户费'] = round($v['charge_transfer'], 2);
                $arr[$k]['券商手续费'] = round(($v['charge_trade']), 2); // 券商手续费
                $arr[$k]['服务费'] = round($v['charge_service'], 2);
                $arr[$k]['收益分成'] = round(($v['trade_type'] == 0 ? 0 : $v['position']['platform_commission']), 2); // 收益分成买入没有
                $arr[$k]['委托时间'] = $v['created_at'];
            }
            $count_export_arr = [
                "平台手续费:{$count_arr['total_charge']}元",
                "印花税:{$count_arr['total_charge_stamps']}元",
                "过户费:{$count_arr['total_charge_transfer']}元",
                "技术服务费:{$count_arr['total_charge_service']}元",
                "券商手续费:{$count_arr['total_trade_charge']}元",
                "总利润(不含服务费):{$count_arr['total_earn']}元",
            ];
            CommonFacade::csv($arr, $count_export_arr);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 成交报表统计
     * @param  $request
     * @param  $customer_no
     * @return array
     */
    public function getEntrustCount(Request $request)
    {
        try {
            // 建仓费
            $total_charge = 0;
            // 总利润 = 交易费 + 建仓费 + 收益分成-　券商费 -　过户费 - 印花税 - 技术
            $total_earn = 0;
            // 交易手续费
            $charge_deal = 0;
            // 过户费
            $charge_transfer = 0;
            // 印花税
            $charge_stamps = 0;
            // 收益分成
            $platform_commission = 0;
            // 券商手续费
            $charge_trade = 0;
            // 技术服务费
            $charge_service = 0;
            $entrusts = $this->getEntrusts($request);
            if (!$entrusts->isEmpty()) {
                foreach ($entrusts as $entrust) {
                    if ($entrust->trade_type == 0) { // 建仓费求和且买入才有
                        $total_charge += $entrust->position->charge_money;
                    } else { //卖出时统计收益分成
                        $platform_commission += $entrust->position->platform_commission;
                    }
                }
                $charge_deal = $entrusts->sum('charge_deal');
                $charge_transfer = $entrusts->sum('charge_transfer');
                $charge_stamps = $entrusts->sum('charge_stamps');
                $charge_service = $entrusts->sum('charge_service');
                $charge_trade = $entrusts->sum('charge_trade');
                // 总利润 = 交易费 + 建仓费 + 收益分成-　券商费 -　过户费 - 印花税 - 技术
                $total_earn = $charge_deal + $total_charge - $charge_transfer + $platform_commission - $charge_stamps - $charge_transfer - $charge_trade - $charge_service;
            }
            return [
                'total_charge' => round($charge_deal + $total_charge + $platform_commission, 2),     //平台收入 = 交易费 + 建仓费 + 收益分成
                'total_charge_service' => round($charge_service, 2),     //交易手续费
                'total_charge_stamps' => round($charge_stamps, 2),     //印花税
                'total_charge_transfer' => round($charge_transfer, 2),     //过户费
                'total_trade_charge' => round($charge_trade, 2),     //总成券商手续费
                'total_earn' => round($total_earn, 2),  //总利润(不含服务费)
            ];
        } catch (\Exception $ex) {
            return $this->exception($ex, 'obj');
        }
    }

    public function getDatas(Request $request)
    {
        $data = [];
        switch ($request->type) {
            case 1:
                $where = function ($query) use ($request) {
                    if ($request->key != null) {
                        $query->whereHas('customer', function ($query) use ($request) {
                            $query->where('no', 'like', '%' . $request->key . '%')
                                ->orwhere('realname', 'like', '%' . $request->key . '%')
                                ->orwhere('phone', 'like', '%' . $request->key . '%');
                        });
                    }
                    if ($request->agent != null) {
                        $query->whereHas('agent', function ($query) use ($request) {
                            $query->where('no', 'like', '%' . $request->agent . '%')
                                ->orWhere('name', 'like', '%' . $request->agent . '%')
                                ->orWhere('email', 'like', '%' . $request->agent . '%')
                                ->orWhere('phone', 'like', '%' . $request->agent . '%');
                        });
                    }
                    if ($request->start_time != null) {
                        $query->where('created_at', '>', $request->start_time);
                    }

                    if ($request->end_time != null) {
                        $query->where('created_at', '<', $request->end_time . '23:59:59');
                    }

                    if ($request->no != null) {
                        $query->where('no', 'like', '%' . $request->no . '%');
                    }

                    if ($request->money != null) {
                        $query->where('money', 'like', '%' . $request->money . '%');
                    }

                    if ($request->pay_type != null) {
                        $query->where('pay_type', $request->pay_type);
                    }
                };
                $data = FinanceRecharge::where('pay_status', 0)
                    ->with([
                        'customer:id,phone,realname,no',
                        'agent:id,name,no'
                    ])
                    ->where($where)
                    ->get();
                break;
            case 2:
                $where = function ($query) use ($request) {
                    if ($request->key != null) {
                        $query->whereHas('customer', function ($query) use ($request) {
                            $query->where('no', 'like', '%' . $request->key . '%')
                                ->orwhere('realname', 'like', '%' . $request->key . '%')
                                ->orwhere('phone', 'like', '%' . $request->key . '%');
                        });
                    }
                    if ($request->agent != null) {
                        $query->whereHas('agent', function ($query) use ($request) {
                            $query->where('no', 'like', '%' . $request->agent . '%')
                                ->orWhere('name', 'like', '%' . $request->agent . '%')
                                ->orWhere('email', 'like', '%' . $request->agent . '%')
                                ->orWhere('phone', 'like', '%' . $request->agent . '%');
                        });
                    }
                    if ($request->start_time != null) {
                        $query->where('created_at', '>', $request->start_time);
                    }

                    if ($request->end_time != null) {
                        $query->where('created_at', '<', $request->end_time . '23:59:59');
                    }

                    if ($request->no != null) {
                        $query->where('no', 'like', '%' . $request->no . '%');
                    }

                    if ($request->money != null) {
                        $query->where('money', 'like', '%' . $request->money . '%');
                    }
                };
                $data = FinanceWithdraw::where('pay_status', 0)
                    ->with([
                        'customer:id,phone,realname,no',
                        'agent:id,name,no'
                    ])
                    ->where($where)
                    ->get();
                break;
            case 3:
                $where = function ($query) use ($request) {
                    if ($request->customer != null) {
                        $query->whereHas('customer', function ($query) use ($request) {
                            $query->where('no', 'like', '%' . $request->customer . '%')
                                ->orwhere('realname', 'like', '%' . $request->customer . '%')
                                ->orwhere('phone', 'like', '%' . $request->customer . '%');
                        });
                    }
                    if ($request->agent != null) {
                        $query->whereHas('agent', function ($query) use ($request) {
                            $query->where('no', 'like', '%' . $request->agent . '%')
                                ->orWhere('name', 'like', '%' . $request->agent . '%')
                                ->orWhere('email', 'like', '%' . $request->agent . '%')
                                ->orWhere('phone', 'like', '%' . $request->agent . '%');
                        });
                    }
                    if ($request->start_time != null) {
                        $query->where('created_at', '>', $request->start_time);
                    }

                    if ($request->end_time != null) {
                        $query->where('created_at', '<', $request->end_time . '23:59:59');
                    }

                    if ($request->key != null) {
                        $query->where('no', 'like', '%' . $request->key . '%');
                    }

                    if ($request->money != null) {
                        $query->where('money', $request->money);
                    }
                };
                $data = DealDeferred::where('deduct_status', 0)
                    ->with([
                        'customer:id,phone,realname,no',
                        'agent:id,name,no',
                        'contract:id,rate,no,deposite_money,borrow_money'
                    ])
                    ->where($where)
                    ->get();
                break;
        }

        return $data;
    }

    /**
     * 充值数据下载
     * @param $request
     */
    public function chargeDownload(Request $request)
    {
        $charges = $this->getDatas($request);
        $dataCount = $this->getDataCount($request);
        // 总充值金额
        $total_charge = $dataCount['total_charge'];
        // 总实收手续费
        $total_poundage = $dataCount['total_poundage'];
        // 总充值成本
        $total_real_poundage = $dataCount['total_real_poundage'];
        $arr = [];
        foreach ($charges as $k => $charge) {
            if ($charge->customer->is_real == 0) {
                $arr[$k]['客户编号'] = $charge->customer->realname . $charge->customer->no;
            } else {
                $arr[$k]['客户编号'] = $charge->customer->name . $charge->customer->no;
            }
            $arr[$k]['客户电话'] = $charge->customer->phone;
            $arr[$k]['所属服务商'] = $charge->agent ? $charge->agent->name . $charge->agent->no : '--';
            $arr[$k]['订单编号'] = $charge->no;
            $arr[$k]['充值金额'] = round($charge->money, 2);
            $arr[$k]['实收手续费'] = round($charge->user_poundage, 2);
            $arr[$k]['充值成本'] = round($charge->platform_poundage, 2);
            $arr[$k]['应收手续费'] = round($charge->poundage, 2);
            $arr[$k]['支付方式'] = config('feildmap.pay_type')[$charge->pay_type];

            $arr[$k]['备注'] = $charge->remark;
            $arr[$k]['创建时间'] = $charge->created_at;
        }
        // 总利润
        $loss = $dataCount['loss'];
        $count_export_arr = [
            "总充值金额:{$total_charge}元",
            "总实收手续费:{$total_poundage}元",
            "总充值成本:{$total_real_poundage}元",
            "盈利:{$loss}元",
        ];
        exit(CommonFacade::csv($arr, $count_export_arr));
    }


    /**
     * 提现数据下载
     * @param $request
     */
    public function withdrawDownload(Request $request)
    {
        $withdraws = $this->getDatas($request);
        $dataCount = $this->getDataCount($request);
        // 总提现金额
        $total_charge = $dataCount['total_charge'];
        // 总实收手续费
        $total_poundage = $dataCount['total_poundage'];
        // 总提现成本
        $total_real_poundage = $dataCount['total_real_poundage'];
        $arr = [];
        foreach ($withdraws as $k => $withdraw) {
            if ($withdraw->customer->is_real == 0) {
                $arr[$k]['客户编号'] = $withdraw->customer->realname . $withdraw->customer->no;
            } else {
                $arr[$k]['客户编号'] = $withdraw->customer->name . $withdraw->customer->no;
            }
            $arr[$k]['客户电话'] = $withdraw->customer->phone;
            $arr[$k]['所属服务商'] = $withdraw->agent ? $withdraw->agent->name . $withdraw->agent->no : '--';
            $arr[$k]['订单编号'] = $withdraw->no;
            $arr[$k]['提现金额'] = round($withdraw->money, 2);
            $arr[$k]['实收手续费'] = round($withdraw->user_poundage, 2);
            $arr[$k]['应收手续费'] = round($withdraw->poundage, 2);
            $arr[$k]['提现成本'] = round($withdraw->platform_poundage, 2);
            $arr[$k]['创建时间'] = $withdraw->created_at;
        }
        // 总利润
        $loss = $dataCount['loss'];
        $count_export_arr = [
            "总提现金额:{$total_charge}元",
            "实收手续费:{$total_poundage}元",
            "提现成本:{$total_real_poundage}元",
            "利润:{$loss}元",
        ];
        exit(CommonFacade::csv($arr, $count_export_arr));
    }


    /**
     * 递延费数据下载
     * @param $request
     */
    public function deferredDownload(Request $request)
    {
        $deferreds = $this->getDatas($request);
        $dataCount = $this->getDataCount($request);
        $total_charge = $dataCount['total_charge'];
        $total_capital_cost = $dataCount['total_capital_cost'];
        $arr = [];
        foreach ($deferreds as $k => $deferred) {
            if ($deferred->customer->is_real == 0) {
                $arr[$k]['客户编号'] = $deferred->customer->realname . $deferred->customer->no;
            } else {
                $arr[$k]['客户编号'] = $deferred->customer->name . $deferred->customer->no;
            }
            $arr[$k]['客户电话'] = $deferred->customer->phone;
            $arr[$k]['所属服务商'] = $deferred->agent ? $deferred->agent->name . $deferred->agent->no : '--';
            $arr[$k]['订单编号'] = $deferred->no;
            $arr[$k]['策略编号'] = $deferred->contract->no;
            $arr[$k]['保证金'] = $deferred->contract->deposit_money;
            $arr[$k]['策略金'] = $deferred->contract->borrow_money;
            $arr[$k]['递延费费率'] = $deferred->contract->rate;
            $arr[$k]['扣费天数'] = 1 . '*' . 1 . '天';
            $arr[$k]['递延费'] = $deferred->money;
            $arr[$k]['资金成本'] = $deferred->capital_cost;
            $arr[$k]['资金成本利率'] = $deferred->capital_cost_rate;
            $arr[$k]['创建时间'] = $deferred->created_at;
        }
        // 总利润
        $loss = $dataCount['loss'];
        $count_export_arr = [
            "总递延费:{$total_charge}元",
            "总资金成本:{$total_capital_cost}元",
            "利润:{$loss}元",
        ];
        exit(CommonFacade::csv($arr, $count_export_arr));
    }

    public function getDataCount(Request $request)
    {
        $data = [];
        switch ($request->type) {
            case 1:
                $charges = $this->getDatas($request);
                $data['total_charge'] = round($charges->sum('money'), 2);
                $data['total_poundage'] = round($charges->sum('user_poundage'), 2);
                $data['total_real_poundage'] = round($charges->sum('platform_poundage'), 2);
                $data['loss'] = round($charges->sum('user_poundage') - $charges->sum('platform_poundage'), 2);
                break;
            case 2:
                $withdraws = $this->getDatas($request);
                $data['total_charge'] = round($withdraws->sum('money'), 2);
                $data['total_poundage'] = round($withdraws->sum('user_poundage'), 2);
                $data['total_real_poundage'] = round($withdraws->sum('platform_poundage'), 2);
                $data['loss'] = round($withdraws->sum('user_poundage') - $withdraws->sum('platform_poundage'), 2);
                break;
            case 3:
                $deferreds = $this->getDatas($request);
                $data['total_charge'] = round($deferreds->sum('money'), 2);
                $data['total_capital_cost'] = round($deferreds->sum('capital_cost'), 2);
                $data['loss'] = round($deferreds->sum('money') - $deferreds->sum('capital_cost'), 2);
                break;
        }
        return $data;
    }
}
