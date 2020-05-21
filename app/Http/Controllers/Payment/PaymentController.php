<?php

namespace App\Http\Controllers\Payment;

use App\Facades\DealFacade;
use App\Facades\PayFacade;
use App\Http\Controllers\Controller;
use App\Models\DealOrder;
use App\Models\MemberWalletRecharge;
use App\Models\MerchantUser;
use App\Models\PlatformCurrency;
use App\Models\PlatformLegal;
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

//        $view = Helper::isMobile() ? 'pay.mobile' : 'pay.pc';
//        return view($view, compact('recharge'));


    }
    public function hcpay(Request $request)
    {
        $recharge = MemberWalletRecharge::where('no', $request->no)->first();
        if (!isset($recharge)) {
            die('订单不存在');
        }
        return PayFacade::pay($request->no);
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
