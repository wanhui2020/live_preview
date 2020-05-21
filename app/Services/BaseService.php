<?php

namespace App\Services;

use App\Models\PlatformConfig;
use App\Models\SystemConfig;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;


/**
 * 基础服务
 * @package App\Http\Service
 */
class BaseService
{

    public function __construct()
    {

    }


    /**
     *获取用户信息
     * @param $key
     * @return mixed
     */
    public function user($key = null)
    {
        if (Auth::guard('SystemUser')->guest()) {
            return null;
        }
        $user = Auth::guard('SystemUser')->user();

        if ($key) {
            return $user->$key;
        }
        return $user;
    }

    /**
     * 获取系统参数配置
     * @param $key
     * @return mixed
     */
    public function config($key = null)
    {
        try {
            $config = Cache::rememberForever('SystemConfig', function () {
                return SystemConfig::first();
            });

            if ($key) {
                if (isset($config->$key)){
                    return $config->$key;
                }
                return false;
            }
            return $config;
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * 获取平台参数
     * @param $key
     * @return mixed
     */
    public function platform($key = null)
    {
        try {
            $config = Cache::rememberForever('PlatformConfig', function () {
                return PlatformConfig::first();
            });
            if ($key) {
                if (isset($config->$key)){
                    return $config->$key;
                }
                return false;
            }
            return $config;
        } catch (\Exception $ex) {
            return false;
        }
    }


    /**
     * 获得日期前缀的固定长度15位系统编号生成
     * @param $type
     * @param int $newNo
     * @return string
     */
    public function getFixedDateNumber($type, $newNo = 1)
    {
        $num = $this->getNumber($type, $newNo);
        return date('Ymd') . str_pad($num, 7, 0, STR_PAD_LEFT);
    }

    /**
     * 隐藏数字（手机号，身份证，银行卡）
     * @param $str
     * @return mixed
     */
    public function hideNumber($str, $start = 3, $end = 4)
    {
        if ($str) {
            $len = strlen($str) - $start - $end;
            $stars = '';
            for ($i = 0; $i < $len; $i++) {
                $stars .= '*';
            }
            return substr_replace($str, $stars, $start, $end);
        }
        return $str;
    }

    /**
     *  记录业务日志
     * @param $type 0系统日志1策略日志2委托日志3券商日志4充值日志5提现日志6注册日志
     * @param $title    标题
     * @param $content  日志内容
     * @param int $customerId 用户ID
     */
    public function setLogs($type, $title, $content, $customerId = 0)
    {
        try {

            $systemId = 0;
            if (Auth::guard('SystemUser')->check()) {
                $user = Auth::guard('SystemUser')->user();
                $systemId = $user->id;
            }

            SystemLog::firstOrCreate(['type' => $type, 'name' => $title, 'content' => $content, 'customer_id' => $customerId, 'system_id' => $systemId,]);
        } catch (\Exception $ex) {
            $this->exception($ex);
        }
    }

    /**
     * 服务器数据交换
     * @param string $url 请求地址
     * @param string $method GET|POST
     * @param array $data 请求内容
     */
    public function curl($url, $method, $data = array(), $setcooke = false, $cookie_file = null)
    {
        $ch = curl_init();     //1.初始化
        curl_setopt($ch, CURLOPT_URL, $url); //2.请求地址
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);//3.请求方式
        //4.参数如下    禁止服务器端的验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        //伪装请求来源，绕过防盗
        //curl_setopt($ch,CURLOPT_REFERER,"http://wthrcdn.etouch.cn/");
        //配置curl解压缩方式（默认的压缩方式）
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Encoding:gzip'));
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");

        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0'); //指明以哪种方式进行访问
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        if ($method == "POST") {//5.post方式的时候添加数据
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        if ($setcooke == true) {
            //如果设置要请求的cookie，那么把cookie值保存在指定的文件中
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
        } else {
            //就从文件中读取cookie的信息
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $tmpInfo = curl_exec($ch);

        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $tmpInfo;
    }
}
