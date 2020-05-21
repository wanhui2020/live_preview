<?php

namespace App\Http\Controllers\System\Report;

use App\Http\Controllers\Controller;
use App\Models\DealOrder;
use App\Repositories\DealEntrustRepository;
use App\Repositories\DealTalkRepository;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct(DealTalkRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     *  平台报表
     * @param Request $request
     * @return array
     */
    public function platform(Request $request)
    {
        try {
            if ($request->isMethod('GET')) {
                return view('system.report.order.platform');
            }
            $lists = DealOrder::where(function ($query) use ($request) {
                if (isset($request->merchant_id)) {
                    $query->where('merchant_id', $request->merchant_id);
                }
                if (isset($request->member_id)) {
                    $query->where('member_id', $request->member_id);
                }
                if (isset($request->currency_id)) {
                    $query->where('currency_id', $request->currency_id);
                }
                if (isset($request->legal_id)) {
                    $query->where('legal_id', $request->legal_id);
                }
                if (isset($request->type)) {
                    $query->where('type', $request->type);
                }
                if (isset($request->order_status)) {
                    $query->where('order_status', $request->order_status);
                } else {
                    $query->where('order_status', 0) ;
                }
                if (isset($request->start_time)) {
                    $query->whereDate('created_at', '>=', $request->start_time);
                }
                if (isset($request->end_time)) {
                    $query->whereDate('created_at', '<=', $request->end_time);
                }

            })->groupBy('date')
                ->orderBy('date', 'DESC')
                ->get([
                    DB::raw('Date(created_at) as date'),
                    DB::raw('count(*) as count'),
                    DB::raw('sum(quantity) as quantity'),
                    DB::raw('sum(received_quantity) as received_quantity'),
                    DB::raw('sum(platform_commission) as platform_commission'),
                    DB::raw('sum(otc_commission) as otc_commission'),
                    DB::raw('sum(buy_rebate_commission) as buy_rebate_commission'),
                    DB::raw('sum(sell_rebate_commission) as sell_rebate_commission'),
                    DB::raw('sum(platform_profit) as platform_profit'),
                ]);

//                ->selectRaw(
//                    'id,customer_id,agent_id,position_id,no,
//                    entrust_status,success_total,trade_type,charge_deal,
//                    charge_service,created_at,stock_code,stock_name,
//                    charge_stamps,charge_transfer,charge_min,charge_trade'
//                )
//                ->with([
//                    'customer:id,no,realname,phone',
//                    'agent:id,no,name',
//                    'position:id,no,charge_money,platform_commission'
//                ])

            return $this->paginate($lists);
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }

}
