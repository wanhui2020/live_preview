<?php

namespace App\Services;

use App\Traits\ResultTrait;
//汇潮支付
class HcPayService
{
    use ResultTrait;
    private $website;//授权域名
    private static $accountNumber = '50340';//商户号 50340  微游50207
//    private $wgurl = 'https://gwapi.yemadai.com/pay/sslpayment'; //网关url
    // 下发秘钥 商户私钥  并且不能出现空格等多余字符【别用成交易秘钥】
    /**
     * @var string
     * MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAJqr2Eg8QObWk3mxUWW+r3T+WxVWjKOwuAtjkG4tvQDnLn/WL5jdJdzUHpB5xVtMgK9GvWoZ2XBkML0C4NV2v9HTQyAOTbG8S+6YA13htc6xfbhtxEpjNN6hfzEqRErRVyVLO3WBU9J5DSzLC4X1RnvU9nv+GD/hFaP4Zl8FPgetAgMBAAECgYBD9dXmh3MM+qN6CQt25T467bgfvBO3qOJ1Pp4rizVMvEeWLApl5GXKjfmQCbFJ2GeCnFaF1C3SUHOJ2UXpBXBqkpKjdov9DWR89VU2KpUVP4bQD/l2EYJT+RHCeaTNdbno0turrNa+CIq5QnpWhinZav7q1zPeU2DS0wAyeiwHkQJBAPes8ZRHRPpVL7BRF/RvzUP1BD9b5g/WmaSOCTRaBDF5ZSQS2GRnx8TDkB7mDWIBneBv2t1lVnDwVfskFEdq+iMCQQCf3qvaOJqwhogN+cotm+vWVIpNQsAwBIBqPVSqWwK3jhukrsvn83LL0mWva+hNSFsMqyI6okqhsGXQ/q2mVQvvAkEA1bslogq6qLhYdfQVEvhkD2/iIXcBmstbPRjhMo83rSYQNMgaetLgHpmQxklKZTf18Nc17PZlWQLdf+MLqPHVNwJBAJ3iV9AcxNB/HFDJFx1x9jhmp2tj98+0Mmo5har0VLuYcb5zDldVe1LFx7y2EeZ5Bcp+HEDR1GjYf8UmL7KsHSsCQB0NW7Si5VNsEUjpRdoLhZMcP16O+Os1wyMY7vZsU8LYAu3T2EOOd0kv1aziC51tsQeHYMGsNGmanCz/keQaCY4=
     */
    private static $prikey = 'MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAJqr2Eg8QObWk3mxUWW+r3T+WxVWjKOwuAtjkG4tvQDnLn/WL5jdJdzUHpB5xVtMgK9GvWoZ2XBkML0C4NV2v9HTQyAOTbG8S+6YA13htc6xfbhtxEpjNN6hfzEqRErRVyVLO3WBU9J5DSzLC4X1RnvU9nv+GD/hFaP4Zl8FPgetAgMBAAECgYBD9dXmh3MM+qN6CQt25T467bgfvBO3qOJ1Pp4rizVMvEeWLApl5GXKjfmQCbFJ2GeCnFaF1C3SUHOJ2UXpBXBqkpKjdov9DWR89VU2KpUVP4bQD/l2EYJT+RHCeaTNdbno0turrNa+CIq5QnpWhinZav7q1zPeU2DS0wAyeiwHkQJBAPes8ZRHRPpVL7BRF/RvzUP1BD9b5g/WmaSOCTRaBDF5ZSQS2GRnx8TDkB7mDWIBneBv2t1lVnDwVfskFEdq+iMCQQCf3qvaOJqwhogN+cotm+vWVIpNQsAwBIBqPVSqWwK3jhukrsvn83LL0mWva+hNSFsMqyI6okqhsGXQ/q2mVQvvAkEA1bslogq6qLhYdfQVEvhkD2/iIXcBmstbPRjhMo83rSYQNMgaetLgHpmQxklKZTf18Nc17PZlWQLdf+MLqPHVNwJBAJ3iV9AcxNB/HFDJFx1x9jhmp2tj98+0Mmo5har0VLuYcb5zDldVe1LFx7y2EeZ5Bcp+HEDR1GjYf8UmL7KsHSsCQB0NW7Si5VNsEUjpRdoLhZMcP16O+Os1wyMY7vZsU8LYAu3T2EOOd0kv1aziC51tsQeHYMGsNGmanCz/keQaCY4=';
    // 下发秘钥 一麻袋公钥   并且不能出现空格等多余字符
    //汇潮支付
    /**
     * @var string
     * MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCPg1Y/qAWizgyCveDMWx1pBM8NmDM9364Qho4fCzhBuC0ramCEjG/AlAmvPKGGpxi2JTs9jRiqv3N6vW9A83KKDrjM/avLrfeCTR0jvqNPt2D5tXjXvX5s2eozSDeVsXT+SBTFmy5M31liblPHrRkuAbcY8AqPz2GTgJmLJ1+AzQIDAQAB
     */
    private static $pubkey = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCPg1Y/qAWizgyCveDMWx1pBM8NmDM9364Qho4fCzhBuC0ramCEjG/AlAmvPKGGpxi2JTs9jRiqv3N6vW9A83KKDrjM/avLrfeCTR0jvqNPt2D5tXjXvX5s2eozSDeVsXT+SBTFmy5M31liblPHrRkuAbcY8AqPz2GTgJmLJ1+AzQIDAQAB';
    public function hcPay($params)
    {
        try {
            if ($params['orderNo'] == '') {
                return $this->failure(1, '没有订单号,请稍后重试！', $params);
            }
            if ($params['amount'] == '') {
                return $this->failure(1, '没有支付金额,请稍后重试！', $params);
            }
            $parameters = $this->payParameters($params);  //获取支付参数
            $url = $this->wgurl; //网关地址
            $formString = '';
            foreach($parameters as $key=>$value){
                $formString .= '<input  type="hidden" name="'.$key.'" value="'.$value.'" />';
            }
            $data = [
//                'action'=>'https://gwapi.yemadai.com/pay/sslpayment',
                "MerNo" => $params['accountNumber'],
                "BillNo" => $parameters['BillNo'],
                "Amount" => $parameters['Amount'],
                "ReturnURL" => $parameters['ReturnURL'],
                "AdviceURL" => $parameters['AdviceURL'],
                "OrderTime" => $parameters['OrderTime'],
                "payType" => $parameters['payType'],
                "Remark" => $parameters['Remark'],
                "products" => $parameters['products'],
                "SignInfo" => $parameters['SignInfo'],
            ];
            return $data;
//            echo  '
//            <form id="buy_form" action="https://gwapi.yemadai.com/pay/sslpayment" method="post">
//                '.$formString.'
//                <input type="submit" name="submit" value="支付" />
//            </form>
//            ';
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }
    /**
     *  提取支付参数
     * @return array
     */
    public function payParameters($params)
    {
        try {
            $BillNo = $params['orderNo'];
            $Amount = $params['amount'];
            if ($params['notifyUrl']) {
                $ReturnURL = 'http://' . $_SERVER['HTTP_HOST'] . '/common/runturnback';    //	通知地址
                $AdviceURL = $params['notifyUrl'];    //	通知地址
            } else {
                $ReturnURL = 'http://' . $_SERVER['HTTP_HOST'] . '/common/runturnback';   //	通知地址
                $AdviceURL = "http://120.24.250.152/common/hcpayback";    //	通知地址
            }
            $OrderTime = date('YmdHis');  //订单时间
            $payType = 'noCard';
            $defaultBankNumber = 'ICBC';//银行编码,ICBC,
            $Remark = '备注';
            $products = $params['productName'];
            $signSource = "MerNo=".$params['accountNumber']."&BillNo=".$BillNo."&Amount=".$Amount."&OrderTime=".$OrderTime."&ReturnURL=".$ReturnURL."&AdviceURL=".$AdviceURL;
            $sign = $this->_reaEncode($signSource);
            $native = array(
                "MerNo" => $params['accountNumber'],
                "BillNo" => $BillNo,
                "Amount" => $Amount,
                "ReturnURL" => $ReturnURL,
                "AdviceURL" => $AdviceURL,
                "OrderTime" => $OrderTime,
                "payType" => $payType,
                "Remark" => $Remark,
                "products" => $products,
                "SignInfo" => $sign,
            );
            return $native;
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }
    // RSA加密
    private function _reaEncode($str) {
        $prikey = self::_redPrikey();
        return openssl_sign($str, $sign, $prikey, OPENSSL_ALGO_SHA1) ? base64_encode($sign) : false;
    }
    private function _redPrikey() {
        $pem = "-----BEGIN RSA PRIVATE KEY-----\n" . chunk_split(self::$prikey, 64, "\n") . "-----END RSA PRIVATE KEY-----\n";
        return openssl_pkey_get_private($pem);
    }
}
