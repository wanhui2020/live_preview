<?php

namespace App\Http\Controllers\System\Report;

use App\Http\Controllers\Controller;
use App\Http\Repositories\DealDeferredRepository;
use App\Repositories\FinanceRechargeRepository;
use App\Repositories\FinanceWithdrawRepository;
use App\Services\ReportService;
use Illuminate\Http\Request;

class FinaceController extends Controller
{
    /**
     *  充值报表页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function charge()
    {
        return view('system.report.finace.charge');
    }

    /**
     *  充值报表列表
     * @param Request $request
     * @param FinanceRechargeRepository $repository
     * @return array
     */
    public function chargeList(Request $request, FinanceRechargeRepository $repository)
    {
        try {
            $lists = $repository->where(function ($query) {
                $query->where('pay_status', 0);
            })
                ->with([
                    'customer:id,phone,realname,no',
                    'agent:id,name,no'
                ])
                ->lists();
            return $this->paginate($lists);
        } catch (\Exception $e) {
            $this->exception($e);
            return $this->validation('充值报表异常，请联系管理员');
        }
    }

    /**
     *  提现报表页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function withdraw()
    {
        return view('system.report.finace.withdraw');
    }

    /**
     *  提现报表列表
     * @param Request $request
     * @param FinanceWithdrawRepository $repository
     * @return array
     */
    public function withdrawList(Request $request, FinanceWithdrawRepository $repository)
    {
        try {
            $lists = $repository->where(function ($query) use ($request) {
                $query->where('pay_status', 0);
            })
                ->with([
                    'customer:id,phone,realname,no',
                    'agent:id,name,no'
                ])
                ->lists();
            return $this->paginate($lists);
        } catch (\Exception $e) {
            $this->exception($e);
            return $this->validation('提现报表异常，请联系管理员');
        }
    }

    /**
     *  递延费报表页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function deferred()
    {
        return view('system.report.finace.deferred');
    }

    public function deferredList(Request $request, DealDeferredRepository $repository)
    {
        try {
            $lists = $repository->where(function ($query){
                $query->where('deduct_status', 0);
            })
                ->with([
                    'customer:id,phone,realname,no',
                    'agent:id,name,no',
                    'contract:id,rate,no,deposite_money,borrow_money'
                ])
                ->lists();
            return $this->paginate($lists);
        } catch (\Exception $e) {
            $this->exception($e);
            return $this->validation('递延费报表异常，请联系管理员');
        }
    }

    /**
     *  报表导出
     * @param Request $request
     * @param ReportService $report
     * @return array
     */
    public function csvDownload(Request $request,ReportService $report)
    {
        try{
            switch ($request->type){
                case 1: // 充值
                    $report->chargeDownload($request);
                    die();
                    break;
                case 2: // 提现
                    $report->withdrawDownload($request);
                    die();
                    break;
                case 3: // 递延费
                    $report->deferredDownload($request);
                    die();
            }
        }catch (\Exception $e){
            $this->exception($e);
            return $this->validation('报表导出异常');
        }
    }

    /**
     *  报表页面表头统计汇总
     * @param ReportService $report
     * @return array|bool
     */
    public function getCollect(Request $request,ReportService $report)
    {
        try{
            $data = $report->getDataCount($request);
            return $this->succeed($data);
        }catch (\Exception $e){
            $this->exception($e);
            return false;
        }
    }
}
