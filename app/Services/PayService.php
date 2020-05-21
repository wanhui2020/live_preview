<?php

namespace App\Services;

use AlipayFundTransAppPayRequest;
use AlipayFundTransUniTransferRequest;
use AopCertClient;
use App\Facades\WalletFacade;
use App\Models\MemberWalletRecharge;
use App\Traits\ResultTrait;
use Illuminate\Http\Request;
use Yansongda\Pay\Pay;

/**
 * 支付服务
 * Class PayService
 *
 * @package App\Services
 */
class PayService
{

    use ResultTrait;

    private $config;

    private $client;

    public function __construct()
    {
        $this->config = [
            'app_id'         => config('alipay.H5.APP_ID'),
            'notify_url'     => '',
            'return_url'     => url('pay/succeed'),
            'ali_public_key' => config('alipay.H5.PUBLIC_KEY'),
            'private_key'    => config('alipay.H5.PRIVATE_KEY'),
        ];
    }


    /**
     * 充值订单号
     *
     * @param $id
     */
    public function pay($no)
    {
        try {
            $recharge = MemberWalletRecharge::where('no', $no)->first();
            if (!isset($recharge)) {
                return $this->validation('充值订单不存在');
            }
            if ($recharge->pay_status != 9) {
                return $this->validation('订单状态已失效');
            }

            $payment = $recharge->payment;
            if (!isset($payment)) {
                return $this->validation('支付通道繁忙！');
            }
            $channel = $payment->channel;

            if (!empty($payment->parameter)) {
                $this->config = json_decode($payment->parameter, true);
            }
            $this->config['return_url'] = url('pay/succeed?no='.$recharge->no);
            $this->config['notify_url'] = url('callback/pay/alipay?no='
                .$recharge->no);

            switch ($channel->code) {
                case  'alipay':

                    if ($payment->type == 'h5') {
                        return $this->alipayH5([
                            'out_trade_no' => $recharge->no,
                            'total_amount' => $recharge->money,
                            'subject'      => '订单编号：'.$recharge->no,
                        ]);
                    }
                    if ($payment->type == 'app') {
                        $params = $this->alipayApp([
                            'out_trade_no' => $recharge->no,
                            'total_amount' => $recharge->money,
                            'subject'      => '订单编号：'.$recharge->no,
                        ]);

                        //                        $this->logs('params', $params);
                        return $params;
                    }
            }

            return $this->validation('支付失败，请联系客服！');
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 支付宝H5支付
     *
     * @param $data
     */
    public function alipayH5($order = [])
    {
        try {
            return Pay::alipay($this->config)->wap($order);
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }

    /**
     * 支付宝App支付
     *
     * @param $data
     */
    public function alipayApp($order = [])
    {
        try {
            return Pay::alipay($this->config)->app($order)->getContent();
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }

    /**
     * 支付宝回调
     *
     * @param $data
     */
    public function alipayNotify(Request $request)
    {
        try {
            $alipay = Pay::alipay($this->config);
            $find   = $alipay->find($request->out_trade_no);

            if (in_array($find->trade_status,
                ['TRADE_SUCCESS', 'TRADE_FINISHED'])
            ) {
                $recharge = MemberWalletRecharge::where('no',
                    $find->out_trade_no)->first();
                if (!isset($recharge)) {
                    return $this->failure(1, '充值订单不存在', $find);
                }
                if ($recharge->money != $find->total_amount) {
                    return $this->failure(1, '订单金额异常', $find);
                }

                return WalletFacade::rechargeAudit($recharge->id, 0);
            }

            return $this->failure(1, '回调失败', $find);
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }


    /**
     * 微信H5支付
     *
     * @param $data
     */
    public function wechantH5($data)
    {
        try {
            $order = [
                'out_trade_no' => time(),
                'total_amount' => '1',
                'subject'      => 'test subject - 测试',
            ];

            return Pay::wechat($this->config)->mp($order);
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }


    public function redPacket()
    {
        $aop = new AopCertClient ();
        $appCertPath
             = storage_path('cert/appCertPublicKey_2018021202185666.crt');// "应用证书路径（要确保证书文件可读），例如：/home/admin/cert/appCertPublicKey.crt";
        $alipayCertPath
             = storage_path('cert/alipayCertPublicKey_RSA2.crt');//"支付宝公钥证书路径（要确保证书文件可读），例如：/home/admin/cert/alipayCertPublicKey_RSA2.crt";
        $rootCertPath
             = storage_path('cert/alipayRootCert.crt');// "支付宝根证书路径（要确保证书文件可读），例如：/home/admin/cert/alipayRootCert.crt";


        $aop->gatewayUrl  = 'https://openapi.alipay.com/gateway.do';
        $aop->appId       = '2018021202185666';
        $aop->rsaPrivateKey
                          = 'MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCVi6Cx8MWjGeC3rGrz8mRg2iWvPT+p+q81xyvue+BjlAxbGg+GBBiECTZqPGquwJ5c5vs1P8bc766ov0uhLfZ/VFlwizL6MLBmjsDeCnpJMAm+H2nIDS1Z6PZTiDaq8t7JwN9yegGK8ou5m3Sz/Z8UNeV6lEuW8H7bmomB7JZcd7niB9CF7dPII4rto/IQH/MxFBzGm1hOltVXIC9gwXvUFvH3jsIMKZ0LxZhegx8MzjWV08EyYGgsAU7bkJJ46OKzMZK8boirmjTtVczMcFIvNWAeKUpHuOCe9GjY2pxxvmfiMrjc1HNeMXORsA35lYTN4PmP2/Jhai+hb18crAVLAgMBAAECggEBAIJVpQIwlC+oeB/cl4JxOWbn+XwXZ4sfvuCh6/n34QMonvQvbGQi4bMBzHkemuQfYGYbsckhApLAGntb7fBV/MXQn/rkdtNy5+sac6GRhC6RPYyEGE+QnHwF+9mll++5qM4x2Q0OpJWS+pATmszcOG8G4i/JW2/7vrRqpXmk1w9StyNfz2t0aOfO9dvI7y0WyWUyI9DIvaL1s07Nn6kMb4mwIPTZtvIalMDR9EzXs04wort36zS6RFKsnKIVkh870ekXy4Qby6olihjQx+pLpaszMzQ3UIUnFyb9S+IPj6P8ldhKrVoICBYpA6e40r3kl6i3/7BafmUiU0zkIESbiVECgYEA6dYQovXxfWW6w84/ickMCbgtXqPT0DHTirSEq5r/clCoXnCAHUmm7G4fIayX1nYuvf53Bnu+632JxPXqkpREAtqNs/PRxrQK+dS97sp5oTF73HpQ/9AL8Jybd1utS+08q2ehAbae4YNqq0KA9dqIEYW53Z6HXNEPHe5VFM1DjJ8CgYEAo7hIhZUPXb4nyDZ/YmWwjdEEv9NtsZARhvpXihbKipDskejgke+5/kpq0mIWKoq4GIdU9GJdEeGjPaNXcGU7P2W3HO5IHx7A07a9R31xfOzMA46282q+DFpIAbCK6rnAV0Nype0qb6ErZy6iRzt65H+9TCYiy54swnrtstpU29UCgYEAywibmWODMyZJbAqBENoIIchRXINTPsqEq0Lijz4d3tw51QZJtyRkU5WqYkrB6+zh2QZBwtdfhomSPfpomq0yAGhT8ZQa6TUa8qHN6LDVuyiEK4PpILU8KMdSFej8J5jtvMZV/m7atWSuEdXEs7PzwYMjR1KGpgi/33U5Wr5/kHECgYAKQd95Tw95ZhKD/rYYahqXqALOKebzev0+Ia0eX7Ms30uTEK4RNp3Gis8Kg060N6C1GLV/4jHGjwZ1NCikSBNgM3U3gM5P9NNL8GUHd5qGoyddCbH4qjVh/L4KLQ9nCWA9l2I1dxhzift4KXnNULKwYjhv7uu7KhvPy3fc/sTWSQKBgHzH+CMpniuZIPFUiu64FnGmu2SZcCoR/i9Y3Uw6Mr040YLGo0hW3WVyf25fB/cnFZG0qPIB81mI+hfIvZymQcPKD7j4Fpj8TU3pjdJrjW2QIADxXYwrlx6X2h1ivL4PUxuvTuTyV9L3vC5RWGpjG6B9job8MyvQ149ck41wVPsl';
        $aop->alipayrsaPublicKey
                          = $aop->getPublicKey($alipayCertPath);//调用getPublicKey从支付宝公钥证书中提取公钥
        $aop->apiVersion  = '1.0';
        $aop->signType    = 'RSA2';
        $aop->postCharset = 'utf-8';
        $aop->format      = 'json';
        $aop->isCheckAlipayPublicCert
                          = false;//是否校验自动下载的支付宝公钥证书，如果开启校验要保证支付宝根证书在有效期内
        $aop->appCertSN
                          = $aop->getCertSN($appCertPath);//调用getCertSN获取证书序列号
        $aop->alipayRootCertSN
                          = $aop->getRootCertSN($rootCertPath);//调用getRootCertSN获取支付宝根证书序列号


        $request        = new AlipayFundTransUniTransferRequest ();
        $requestConfigs = [
            'out_biz_no'      => time(),
            'trans_amount'    => 1, //单位 元
            'product_code'    => 'STD_RED_PACKET',
            'biz_scene'       => 'DIRECT_TRANSFER',
            'remark'          => '红包',
            'order_title'     => '这就是红包了',  //订单标题
            'payee_info'      => [
                'identity'      => '2088002063680308',     //接受红包的用户id
                'identity_type' => 'ALIPAY_USER_ID',     //参与方的标识类型
            ],
            'business_params' => [
                'sub_biz_scene' => 'REDPACKET',       //子场景
            ],
        ];
        $request->setBizContent(json_encode($requestConfigs));
        $result = $aop->pageExecute($request);

        return $result;
    }


    public function sendPacket()
    {
        $aop = new AopCertClient ();
        $appCertPath
             = storage_path('cert/appCertPublicKey_2018021202185666.crt');// "应用证书路径（要确保证书文件可读），例如：/home/admin/cert/appCertPublicKey.crt";
        $alipayCertPath
             = storage_path('cert/alipayCertPublicKey_RSA2.crt');//"支付宝公钥证书路径（要确保证书文件可读），例如：/home/admin/cert/alipayCertPublicKey_RSA2.crt";
        $rootCertPath
             = storage_path('cert/alipayRootCert.crt');// "支付宝根证书路径（要确保证书文件可读），例如：/home/admin/cert/alipayRootCert.crt";


        $aop->gatewayUrl  = 'https://openapi.alipay.com/gateway.do';
        $aop->appId       = '2018021202185666';
        $aop->rsaPrivateKey
                          = 'MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCVi6Cx8MWjGeC3rGrz8mRg2iWvPT+p+q81xyvue+BjlAxbGg+GBBiECTZqPGquwJ5c5vs1P8bc766ov0uhLfZ/VFlwizL6MLBmjsDeCnpJMAm+H2nIDS1Z6PZTiDaq8t7JwN9yegGK8ou5m3Sz/Z8UNeV6lEuW8H7bmomB7JZcd7niB9CF7dPII4rto/IQH/MxFBzGm1hOltVXIC9gwXvUFvH3jsIMKZ0LxZhegx8MzjWV08EyYGgsAU7bkJJ46OKzMZK8boirmjTtVczMcFIvNWAeKUpHuOCe9GjY2pxxvmfiMrjc1HNeMXORsA35lYTN4PmP2/Jhai+hb18crAVLAgMBAAECggEBAIJVpQIwlC+oeB/cl4JxOWbn+XwXZ4sfvuCh6/n34QMonvQvbGQi4bMBzHkemuQfYGYbsckhApLAGntb7fBV/MXQn/rkdtNy5+sac6GRhC6RPYyEGE+QnHwF+9mll++5qM4x2Q0OpJWS+pATmszcOG8G4i/JW2/7vrRqpXmk1w9StyNfz2t0aOfO9dvI7y0WyWUyI9DIvaL1s07Nn6kMb4mwIPTZtvIalMDR9EzXs04wort36zS6RFKsnKIVkh870ekXy4Qby6olihjQx+pLpaszMzQ3UIUnFyb9S+IPj6P8ldhKrVoICBYpA6e40r3kl6i3/7BafmUiU0zkIESbiVECgYEA6dYQovXxfWW6w84/ickMCbgtXqPT0DHTirSEq5r/clCoXnCAHUmm7G4fIayX1nYuvf53Bnu+632JxPXqkpREAtqNs/PRxrQK+dS97sp5oTF73HpQ/9AL8Jybd1utS+08q2ehAbae4YNqq0KA9dqIEYW53Z6HXNEPHe5VFM1DjJ8CgYEAo7hIhZUPXb4nyDZ/YmWwjdEEv9NtsZARhvpXihbKipDskejgke+5/kpq0mIWKoq4GIdU9GJdEeGjPaNXcGU7P2W3HO5IHx7A07a9R31xfOzMA46282q+DFpIAbCK6rnAV0Nype0qb6ErZy6iRzt65H+9TCYiy54swnrtstpU29UCgYEAywibmWODMyZJbAqBENoIIchRXINTPsqEq0Lijz4d3tw51QZJtyRkU5WqYkrB6+zh2QZBwtdfhomSPfpomq0yAGhT8ZQa6TUa8qHN6LDVuyiEK4PpILU8KMdSFej8J5jtvMZV/m7atWSuEdXEs7PzwYMjR1KGpgi/33U5Wr5/kHECgYAKQd95Tw95ZhKD/rYYahqXqALOKebzev0+Ia0eX7Ms30uTEK4RNp3Gis8Kg060N6C1GLV/4jHGjwZ1NCikSBNgM3U3gM5P9NNL8GUHd5qGoyddCbH4qjVh/L4KLQ9nCWA9l2I1dxhzift4KXnNULKwYjhv7uu7KhvPy3fc/sTWSQKBgHzH+CMpniuZIPFUiu64FnGmu2SZcCoR/i9Y3Uw6Mr040YLGo0hW3WVyf25fB/cnFZG0qPIB81mI+hfIvZymQcPKD7j4Fpj8TU3pjdJrjW2QIADxXYwrlx6X2h1ivL4PUxuvTuTyV9L3vC5RWGpjG6B9job8MyvQ149ck41wVPsl';
        $aop->alipayrsaPublicKey
                          = $aop->getPublicKey($alipayCertPath);//调用getPublicKey从支付宝公钥证书中提取公钥
        $aop->apiVersion  = '1.0';
        $aop->signType    = 'RSA2';
        $aop->postCharset = 'utf-8';
        $aop->format      = 'json';
        $aop->isCheckAlipayPublicCert
                          = false;//是否校验自动下载的支付宝公钥证书，如果开启校验要保证支付宝根证书在有效期内
        $aop->appCertSN
                          = $aop->getCertSN($appCertPath);//调用getCertSN获取证书序列号
        $aop->alipayRootCertSN
                          = $aop->getRootCertSN($rootCertPath);//调用getRootCertSN获取支付宝根证书序列号
        $request          = new AlipayFundTransAppPayRequest ();
        $request->setBizContent("{".
            "\"out_biz_no\":\"2018062800001\",".
            "\"trans_amount\":8.88,".
            "\"product_code\":\"STD_RED_PACKET\",".
            "\"biz_scene\":\"PERSONAL_PAY\",".
            "\"remark\":\"拼手气红包\",".
            "\"order_title\":\"钉钉拼手气红包\",".
            "\"time_expire\":\"2018-03-23 19:15\",".
            "\"refund_time_expire\":\"2018-11-08 10:00\",".
            "\"business_params\":\"{\\\"sub_biz_scene\\\":\\\"REDPACKET\\\",\\\"payer_binded_alipay_uid:\\\"2088302510459335\\\"}\""
            .
            "  }");
        $result = $aop->pageExecute($request);

        return $result;

        $responseNode = str_replace(".", "_", $request->getApiMethodName())
            ."_response";
        $resultCode   = $result->$responseNode->code;
        if (!empty($resultCode) && $resultCode == 10000) {
            echo "成功";
        } else {
            echo "失败";
        }

        return $result;
        $request        = new AlipayFundTransAppPayRequest  ();
        $requestConfigs = [
            'out_biz_no'         => time(),
            'trans_amount'       => 1, //单位 元
            'product_code'       => 'STD_RED_PACKET',
            'biz_scene'          => 'PERSONAL_PAY',
            'remark'             => '红包',//支付备注
            'order_title'        => '这就是红包了',  //支付订单的标题
            'time_expire'        => '2020-03-23 19:15',  //绝对超时时间
            'refund_time_expire' => '2019-03-23 19:15',  //退款超时时间
            'business_params'    => [
                [
                    'sub_biz_scene'           => 'REDPACKET',
                    //子场景
                    'payer_binded_alipay_uid' => '2088002063680308',
                    //支付宝userId
                ],
            ],
        ];
        $request->setBizContent(json_encode($requestConfigs));

        $result = $aop->pageExecute($request);

        return $result;
    }


}
