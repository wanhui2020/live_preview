<?php

namespace App\Http\Controllers\Common;

use App\Facades\DealFacade;
use App\Facades\HcFacade;
use App\Facades\HyFacade;
use App\Facades\PayFacade;
use App\Http\Controllers\Controller;
use App\Models\DealOrder;
use App\Models\MemberWalletRecharge;
use App\Models\MerchantUser;
use App\Models\PlatformCurrency;
use App\Models\PlatformLegal;
use App\Models\PlatformPayment;
use App\Repositories\DealOrderRepository;
use App\Repositories\MemberWalletRechargeRepository;
use App\Traits\ResultTrait;
use App\Utils\Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * 支付主页
 * Class AlipayController
 * @package App\Http\Controllers
 */
class PaymentController extends Controller
{
    use ResultTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $repository;

    public function __construct(MemberWalletRechargeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 发起支付
     * @param $no
     * @param Request $request
     */
    public function index(Request $request)
    {
        $recharge = MemberWalletRecharge::where('no', $request->no)->first();
        if (!isset($recharge)) {
            die('订单不存在');
        }
        return PayFacade::pay($request->no);
//
//        $view = Helper::isMobile() ? 'pay.mobile' : 'pay.pc';
//        return view($view, compact('recharge'));

    }

    public function hcpay(Request $request)
    {
        $recharge = MemberWalletRecharge::where('no', $request->no)->first();
        if (!isset($recharge)) {
            die('订单不存在');
        }
        $payment_id = $request->payment_id; //支付通道的账号
        if ($request->filled($payment_id)) {
            return $this->validation('确认支付通道的账号！');
        }
        $payment = PlatformPayment::find($payment_id);
        if (!isset($payment)){
            return $this->validation('支付通道不存在！');
        }
        //汇潮支付  一麻袋支付
        $para['orderNo'] = $recharge->no; //订单号
        $para['amount'] = $recharge->money; //交易金额
        $para['accountNumber'] =$payment->account; //支付通道编号
        $para['notifyUrl'] = 'http://' . $_SERVER['HTTP_HOST'] . '/callback/hcpayback';
        $para['productName'] = '充值金币';//商品描述
        $jhpay = HcFacade::hcPay($para);
        $parameter = $jhpay; //汇潮支付参数
        return view('common.huichao', compact('parameter'));
    }

    /**
     * 恒云支付
     */
    public function hypay(Request $request)
    {
        $params['out_trade_no'] = $request->no; //订单号
        $params['amount'] = $request->money; //交易金额
        $notify_url = 'https://' . $_SERVER['HTTP_HOST'] . '/common/hypay';
        $params['notify_url'] = $notify_url; //恒云支付1
        $jhpay = HyFacade::hyPay($params);
        $data['parameter'] = $jhpay;
    }
    public function runturnback()
    {
        echo '支付成功了，请返回app查看';
    }
    /**
     * 支付成功提示
     * @param $no
     * @param Request $request
     */
    public function success(Request $request)
    {
        abort(200, '支付成功,请返回');

    }

}
