<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public $merchant;

    public function __construct()
    {
    }

    /**
     * 签名生成
     * @param Request $request
     */
    public function sign(Request $request)
    {
        try {
            //参数中含有sign
            $params = $request->all();
            if (isset($params['_url'])) {
                unset($params['_url']);
            }
            if (isset($params['sign'])) {
                unset($params['sign']);
            }
            if (!isset($params['timestamp'])||empty($params['timestamp'])){
                $this->validation('timestamp参数不能为空');
            }
            if (!isset($params['secret_key'])||empty($params['secret_key'])){
                $this->validation('secret_key参数不能为空');
            }
            $secret_key = $params['secret_key'];
            if ($secret_key) {
                unset($params['secret_key']);
            }
            if (isset($params['api_token'])||empty($params['api_token'])) {
                unset($params['api_token']);
            }

            if (count($params) >= 1) {
                //参数字典排序
                ksort($params);
                $str='';
                foreach ($params as $k=>$v){
                    if ( strlen($v) > 0) {
                        $str .= $k . '=' . $v . '&';
                    }
                }
                $str=substr($str,0,strlen($str)-1);
                $params['sign'] = md5($str . $secret_key);
            }

            return $this->succeed($params);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 签名验证
     * @param Request $request
     */
    public function verify(Request $request)
    {
        $params = $request->all();
        $user = $request->user();

        $sign = $params['sign'];

        if (isset($params['sign'])) {
            unset($params['sign']);
        } else {
            return ['status' => 1, 'msg' => '未找到签名信息'];
        }
        $timestamp = $params['timestamp'];
        if ($timestamp > time()) {
            return ['status' => 1, 'msg' => '请求时间异常'];


        }
        if ($timestamp + 60 < time()) {
            return ['status' => 1, 'msg' => '请求超时'];

        }
        if (isset($params['api_token'])) {
            unset($params['api_token']);
        }

        if (count($params) >= 1) {
            //参数字典排序
            ksort($params);
            $str='';
            foreach ($params as $k=>$v){
                if ( strlen($v) > 0) {
                    $str .= $k . '=' . $v . '&';
                }
            }
            $str=substr($str,0,strlen($str)-1);
            if ($sign == md5($str . $user->secret_key)) {
                return ['status' => 0, 'msg' => '签名效验成功'];

            }
        }
        return ['status' => 1, 'msg' => '签名效验失败'];
    }

}
