<?php

namespace App\Http\Controllers\Callback;

use App\Facades\JhPayFacade;
use App\Facades\MemberFacade;
use App\Facades\PayFacade;
use App\Facades\RechargeFacade;
use App\Facades\WalletFacade;
use App\Http\Controllers\Controller;
use App\Models\MemberRecharge;
use App\Models\MemberWalletRecharge;
use App\Repositories\MemberPlanOrderRepository;
use App\Repositories\MemberRechargeRepository;
use App\Repositories\MemberWalletRechargeRepository;
use App\Utils\Helper;
use App\Utils\WechatAppPay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayController extends Controller
{
    /**
     * 汇潮支付回调
     */
    public function hcpayback(Request $request)
    {
        try {
            $BillNo = $request->get('BillNo');
            $recharge = MemberWalletRecharge::where('no',$BillNo)->first();
            if (isset($recharge)) {
                WalletFacade::rechargeAudit($recharge->id, 0);
                echo 'ok';
            }
            echo 'fail';
        } catch (\Exception $exception) {
            $this->logs('接收汇潮支付通知充值处理异常', $exception->getMessage());
            echo 'fail';
        }
    }
    /**
     * 恒云支付回调
     */
    public function hypayback(Request $request)
    {
        try {
            $data = $request->all();
            $this->logs('恒云支付回调返回成功',$data);
            $out_trade_no = $request->get('out_trade_no');
            $recharge = MemberWalletRecharge::where('no',$out_trade_no)->first();
            if (isset($recharge)) {
                WalletFacade::rechargeAudit($recharge->id, 0);
                echo 'ok';
            }
            echo 'fail';
        } catch (\Exception $exception) {
            $this->logs('接收恒云支付通知充值处理异常', $exception->getMessage());
            echo 'fail';
        }
    }
    /**
     * 支付宝回调
     * @param Request $request
     */
    public function alipay(Request $request, MemberWalletRechargeRepository $repository)
    {
        if ($request->isMethod('GET')) {
            return view('callback.pay.success');
        }
        try {

            $this->logs('支付宝回调', $request->getContent());
            $resp = PayFacade::alipayNotify($request);
            if ($resp['status']) {
                //付款成功
                $this->logs('支付宝付款成功回调处理', $resp['data']);

                echo 'SUCCESS';
            } else {
                echo 'Fail';
            }
        } catch (\Exception $exception) {
            echo 'Fail';
        }

    }

    /**
     * 微信回调
     * @param Request $request
     */
    public function wechat(Request $request)
    {
        //支付
        $resp = PayFacade::alipay(rand(), 1);
        //查询
//        $resp = PayFacade::alipayQuery('2129209909');
        if ($resp['status']) {
            return $resp['data'];
        }
        echo '支付失败';
    }

    /*
     * 三方支付平台回调(充值)
     * */
    public function typay(Request $request, MemberRechargeRepository $rechargeRepository)
    {
        try {
            $data = $request->all();
            $this->logs('三方支付充值付款成功回调处理', $data);
            //查询订单
            $order = $rechargeRepository->findBy('order_no', $data['no']);
            if (isset($order)) {
                if ($order->status == 0) {
                    //处理
                    $order['status'] = 1;
                    $order['pay_time'] = Helper::getNowTime();
                    $ret = RechargeFacade::rechargeDeal($order);
                    if (!$ret['status']) {
                        echo '{"status":"1","msg":"失败"}';
                    }
                }
            }
            echo '{"status":"0","msg":"成功"}';
        } catch (\Exception $exception) {
            $this->logs('接收三方通知充值处理异常', $exception->getMessage());
            echo '{"status":"1","msg":"失败"}';
        }
    }

    /*
     * 三方支付平台回调(商务处理)
     * */
    public function typayBusiness(Request $request, MemberPlanOrderRepository $memberPlanOrderRepository)
    {
        try {
            $data = $request->all();
            $this->logs('三方支付商务预约付款成功回调处理', $data);
            //查询订单
            $order = $memberPlanOrderRepository->findBy('order_no', $data['no']);
            if (isset($order)) {
                if ($order->status == 0) {
                    //处理
                    $ret = MemberFacade::dealPlanOrder($order, 1);
                    if (!$ret['status']) {
                        echo '{"status":"1","msg":"失败"}';
                    }
                }
            }
            echo '{"status":"0","msg":"成功"}';
        } catch (\Exception $exception) {
            $this->logs('接收三方通知商务预约处理异常', $exception->getMessage());
            echo '{"status":"1","msg":"失败"}';
        }
    }

    /*
    * 拼多支付平台回调(充值)
    * */
    public function pddPayRecharge(Request $request, MemberRechargeRepository $rechargeRepository)
    {
        try {

            $data = $request->all();
            $this->logs('拼多多支付充值付款成功回调处理', $data);
            if ($data['callbacks'] == 'CODE_SUCCESS') {
                //查询订单
                $order = $rechargeRepository->findBy('order_no', $data['api_order_sn']);
                if (isset($order)) {
                    if ($order->status == 0) {
                        //处理
                        $order['status'] = 1;
                        $order['pay_time'] = Helper::getNowTime();
                        $ret = RechargeFacade::rechargeDeal($order);
                        if (!$ret['status']) {
                            exit('fail');
                        }
                    }
                }
                exit('success');
            }
            exit('fail');
        } catch (\Exception $exception) {
            $this->logs('接收拼多多支付通知充值处理异常', $exception->getMessage());
            exit('fail');
        }
    }

    /*
     * 三方支付平台回调(商务处理)
     * */
    public function pddPayBusiness(Request $request, MemberPlanOrderRepository $memberPlanOrderRepository)
    {
        try {
            $data = $request->all();
            $this->logs('拼多多支付商务预约付款成功回调处理', $data);
            if ($data['callbacks'] == 'CODE_SUCCESS') {
                //查询订单
                $order = $memberPlanOrderRepository->findBy('order_no', $data['no']);
                if (isset($order)) {
                    if ($order->status == 0) {
                        //处理
                        $ret = MemberFacade::dealPlanOrder($order, 1);
                        if (!$ret['status']) {
                            exit('fail');
                        }
                    }
                }
                exit('success');
            }
            exit('fail');
        } catch (\Exception $exception) {
            $this->logs('拼多多支付通知商务预约处理异常', $exception->getMessage());
            exit('fail');
        }
    }


    /**
     * APP微信支付回调(充值)
     * @param Request $request
     */
    public function appWechatRecharge(Request $request, MemberRechargeRepository $rechargeRepository)
    {
        $wechatPay = new WechatAppPay();
        try {
            $data = $wechatPay->getNotifyData();
            $this->logs('微信APP充值付款成功回调处理', $data);

            if (!$data) {
                $wechatPay->replyNotify('FAIL', '接收回调异常');
            } else {
                //支付成功
                $out_trade_no = $data['out_trade_no'];
                //查询订单
                $order = $rechargeRepository->findBy('order_no', $out_trade_no);
                if (isset($order)) {
                    if ($order->status == 0) {
                        //处理
                        $order['status'] = 1;
                        $order['pay_time'] = Helper::getNowTime();
                        $ret = RechargeFacade::rechargeDeal($order);
                        if (!$ret['status']) {
                            $wechatPay->replyNotify('FAIL', '业务处理失败');
                        }
                    }
                }
                $wechatPay->replyNotify(); //成功结束
            }
        } catch (\Exception $exception) {
            $this->logs('接收微信APP支付通知充值处理异常', $exception->getMessage());
            $wechatPay->replyNotify('FAIL', '业务处理失败');
        }
    }

    /**
     * APP微信支付回调（商务）
     * @param Request $request
     */
    public function appWechatBusiness(Request $request, MemberPlanOrderRepository $memberPlanOrderRepository)
    {
        $wechatPay = new WechatAppPay();
        try {
            $data = $wechatPay->getNotifyData();
            $this->logs('微信APP商务付款成功回调处理', $data);

            if (!$data) {
                $wechatPay->replyNotify('FAIL', '接收回调异常');
            } else {
                //支付成功
                $out_trade_no = $data['out_trade_no'];
                //查询订单
                $order = $memberPlanOrderRepository->findBy('order_no', $out_trade_no);
                if (isset($order)) {
                    if ($order->status == 0) {
                        //处理
                        $ret = MemberFacade::dealPlanOrder($order, 1);
                        if (!$ret['status']) {
                            $wechatPay->replyNotify('FAIL', '业务处理失败');
                        }
                    }
                }
                $wechatPay->replyNotify(); //成功结束
            }
        } catch (\Exception $exception) {
            $this->logs('接收微信APP支付通知充值处理异常', $exception->getMessage());
            $wechatPay->replyNotify('FAIL', '业务处理失败');
        }
    }

}
