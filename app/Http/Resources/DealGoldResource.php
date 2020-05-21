<?php

namespace App\Http\Resources;


use App\Facades\HcFacade;
use App\Facades\HyFacade;
use App\Facades\PayFacade;
use App\Models\MemberWalletRecharge;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

/**
 * 能量充值
 * Class MemberUserResource
 * @package App\Http\Resources
 */
class DealGoldResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id, //ID
            'member_id' => $this->member_id, //所属会员ID
            'price_id' => $this->price_id, //价格编号
            'name' => $this->name, //价格名称
            'money' => $this->money,//产品金额
            'gold' => $this->gold,//充值能量
            'give' => $this->give,//赠送能量
            'received' => $this->received,//实到能量
            'status' => $this->status,//支付状态
//            'recharge' => new WalletRechargeResource($this->recharge),//充值记录
        ];
        if (isset($this->recharge)) {
            $recharge = $this->recharge;
            $data['recharge_no'] = $recharge->no;
            if (isset($recharge->payment)) {
                $payment = $recharge->payment;
                $data['type'] = $payment->type;
                if ($payment->type == 'h5') {
                    $data['params'] = URL::route('pay', ['no' => $recharge->no]);
                    //URL::temporarySignedRoute('pay', now()->addMicros(5), ['no' => $recharge->no]);
                }
                if ($payment->type == 'app') {
                    $data['params'] = PayFacade::pay($recharge->no);

                }

                if (isset($payment->channel)) {
                    $channel = $payment->channel;
                    $data['code'] = $channel->code;
                    if ($channel->code == 'huichao'){ //汇潮支付  一麻袋支付
                        $data['parameter'] = 'http://' . $_SERVER['HTTP_HOST'] . '/common/hcpay?no=' . $recharge->no.'&payment_id='.$payment->id;
                    }
                    if ($channel->code == 'hengyun'){ //恒云支付宝H5支付
                        $params['out_trade_no'] = $recharge->no; //订单号
                        $params['amount'] = $this->money; //交易金额
                        $notify_url = 'https://' . $_SERVER['HTTP_HOST'] . '/callback/hypayback';
                        $params['notify_url'] = $notify_url; //恒云支付1
                        $params['mch_id'] = $payment->account; //商户号
                        $par = $payment->parameter;
                        $arr1 = [];
                        parse_str($par,$arr1);
                        $params['key'] = $arr1['key'];
                        $params['wgurl'] = $arr1['wgurl'];
                        $params['pay_type'] = $arr1['pay_type'];
                        $jhpay = HyFacade::hyPay($params);
                        $data['parameter'] = $jhpay;
                    }
                }
            }
        }

        return $data;
    }

}
