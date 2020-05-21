<?php

namespace App\Http\Controllers\System\Report;

use App\Http\Controllers\Controller;
use App\Repositories\DealEntrustRepository;
use App\Services\ReportService;
use Illuminate\Http\Request;

class EntrustController extends Controller
{
    /**
     *  成交报表首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('system.report.order.index');
    }

    /**
     *  成交报表列表
     * @param Request $request
     * @param DealEntrustRepository $repository
     * @return array
     */
    public function lists(Request $request, DealEntrustRepository $repository)
    {
        try {
            $lists = $repository->where(function ($query) {
                $query->where('entrust_status', 0)
                    ->where('success_num', '>', 0);
            })
                ->selectRaw(
                    'id,customer_id,agent_id,position_id,no,
                    entrust_status,success_total,trade_type,charge_deal,
                    charge_service,created_at,stock_code,stock_name,
                    charge_stamps,charge_transfer,charge_min,charge_trade'
                )
                ->with([
                    'customer:id,no,realname,phone',
                    'agent:id,no,name',
                    'position:id,no,charge_money,platform_commission'
                ])
                ->lists();
            return $this->paginate($lists);
        } catch (\Exception $e) {
            $this->exception($e);
            return $this->validation('成交报表异常，请联系管理员');
        }
    }

    /**
     *  报表页面表头统计汇总
     * @param ReportService $report
     * @return array|bool
     */
    public function getCollect(Request $request, ReportService $report)
    {
        try {
            $data = $report->getEntrustCount($request);
            return $this->succeed($data);
        } catch (\Exception $e) {
            $this->exception($e);
            return false;
        }
    }

    /**
     *  成交报表导出
     * @param ReportService $report
     * @return array
     */
    public function csvDownload(ReportService $report,Request $request)
    {
        try {
            $report->getEntrustExport($request);
            die();
        } catch (\Exception $e) {
            $this->exception($e);
            return $this->validation('成交报表导出异常');
        }
    }
}
