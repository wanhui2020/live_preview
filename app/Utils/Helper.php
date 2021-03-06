<?php

namespace App\Utils;

use Exception;
use File;
use Illuminate\Support\Facades\Log;

/**
 * Created by PhpStorm.
 * User: wuhong
 * Date: 16/10/29
 * Time: 下午2:45
 */
class Helper
{

    public static function getNo($prefix = '')
    {
        return $prefix.time().str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }

    public static function getNowTime($format = 'y-m-d H:i:s')
    {
        return date($format);
    }

    /**
     * 方法二：获取随机字符串
     *
     * @param  int  $randLength  长度
     * @param  int  $addtime  是否加入当前时间戳
     * @param  int  $includenumber  是否包含数字
     *
     * @return string
     */
    public static function rand_str(
        $randLength = 6,
        $addtime = 1,
        $includenumber = 0
    ) {
        if ($includenumber) {
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNPQEST123456789';
        } else {
            $chars = 'abcdefghijklmnopqrstuvwxyz';
        }
        $len     = strlen($chars);
        $randStr = '';
        for ($i = 0; $i < $randLength; $i++) {
            $randStr .= $chars[mt_rand(0, $len - 1)];
        }
        $tokenvalue = $randStr;
        if ($addtime) {
            $tokenvalue = $randStr.time();
        }

        return $tokenvalue;
    }

    /*
 * 生成随机字符串
 * @param int $length 生成随机字符串的长度
 * @param string $char 组成随机字符串的字符串
 * @return string $string 生成的随机字符串
 */

    /**
     * 去除所有空格
     *
     * @param $str
     *
     * @return mixed
     */
    public static function trimall($str)
    {
        $qian = [" ", "　", "\t", "\n", "\r"];

        return str_replace($qian, '', $str);
    }

    /**
     * 字符串处理
     *
     * @param $str
     *
     * @return mixed
     */
    public static function strSub($str, $len = 12, $sub = '...')
    {
        if (mb_strlen($str, 'utf8') <= $len) {
            return $str;
        }

        if (!File::isDirectory(storage_path('logs/test/'))) {
            File::makeDirectory(storage_path('logs/test'));
        }

        $path    = storage_path('logs/test/log.log');
        $content = strlen($sub).'@'.mb_substr($str, 0, $len - strlen($sub)).'@'
            .$str;

        File::append($path, $content."\r\n");

        return mb_substr($str, 0, $len - strlen($sub)).$sub;
    }

    /**
     * URL base64解码
     */

    public static function urlsafe_b64decode($string)
    {
        $data = str_replace(['-', '_'], ['+', '/'], $string);

        $mod4 = strlen($data) % 4;

        if ($mod4) {
            $data .= substr('====', $mod4);
        }

        return base64_decode($data);
    }

    /**
     * URL base64编码
     */

    public static function urlsafe_b64encode($string)
    {
        $data = base64_encode($string);

        $data = str_replace(['+', '/', '='], ['-', '_', ''], $data);

        return $data;
    }

    /**
     * 二维数据根据某字段分组
     *
     * @param $arr      二维数组
     * @param $key      需要分组的键名
     *
     * @return array    新数组
     */
    public static function group_same_key($arr, $key)
    {
        $new_arr = [];
        foreach ($arr as $k => $v) {
            $new_arr[$v[$key]][] = $v;
        }

        return $new_arr;
    }

    public static function ObjectToArray($array)
    {
        if (is_object($array)) {
            $array = (array)$array;
        }
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $array[$key] = self::object_array($value);
            }
        }

        return $array;
    }

    public static function XmlToArray($value)
    {
        try {
            if (is_string($value)) {
                //禁止引用外部xml实体
                libxml_disable_entity_loader(true);
                //先把xml转换为simplexml对象，再把simplexml对象转换成 json，再将 json 转换成数组。
                $value_array
                    = json_decode(json_encode(simplexml_load_string($value,
                    'SimpleXMLElement', LIBXML_NOCDATA)), true);

                return $value_array;
            }

            return false;
        } catch (Exception $ex) {
            return false;
        }
    }

    public static function isMobile()
    {
        $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP'])
            ? $_SERVER['ALL_HTTP'] : '';
        $mobile_browser      = '0';
        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i',
            strtolower($_SERVER['HTTP_USER_AGENT']))
        ) {
            $mobile_browser++;
        }
        if ((isset($_SERVER['HTTP_ACCEPT']))
            and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),
                    'application/vnd.wap.xhtml+xml') !== false)
        ) {
            $mobile_browser++;
        }
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
            $mobile_browser++;
        }
        if (isset($_SERVER['HTTP_PROFILE'])) {
            $mobile_browser++;
        }
        $mobile_ua     = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
        $mobile_agents = [
            'w3c ',
            'acs-',
            'alav',
            'alca',
            'amoi',
            'audi',
            'avan',
            'benq',
            'bird',
            'blac',
            'blaz',
            'brew',
            'cell',
            'cldc',
            'cmd-',
            'dang',
            'doco',
            'eric',
            'hipt',
            'inno',
            'ipaq',
            'java',
            'jigs',
            'kddi',
            'keji',
            'leno',
            'lg-c',
            'lg-d',
            'lg-g',
            'lge-',
            'maui',
            'maxo',
            'midp',
            'mits',
            'mmef',
            'mobi',
            'mot-',
            'moto',
            'mwbp',
            'nec-',
            'newt',
            'noki',
            'oper',
            'palm',
            'pana',
            'pant',
            'phil',
            'play',
            'port',
            'prox',
            'qwap',
            'sage',
            'sams',
            'sany',
            'sch-',
            'sec-',
            'send',
            'seri',
            'sgh-',
            'shar',
            'sie-',
            'siem',
            'smal',
            'smar',
            'sony',
            'sph-',
            'symb',
            't-mo',
            'teli',
            'tim-',
            'tosh',
            'tsm-',
            'upg1',
            'upsi',
            'vk-v',
            'voda',
            'wap-',
            'wapa',
            'wapi',
            'wapp',
            'wapr',
            'webc',
            'winw',
            'winw',
            'xda',
            'xda-',
        ];
        if (in_array($mobile_ua, $mobile_agents)) {
            $mobile_browser++;
        }
        if (strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false) {
            $mobile_browser++;
        }
        // Pre-final check to reset everything if the user is on Windows
        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows')
            !== false
        ) {
            $mobile_browser = 0;
        }
        // But WP7 is also Windows, with a slightly different characteristic
        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone')
            !== false
        ) {
            $mobile_browser++;
        }
        if ($mobile_browser > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 判断字符串是否为 Json 格式
     *
     * @param  string  $data  Json 字符串
     * @param  bool  $assoc  是否返回关联数组。默认返回对象
     *
     * @return bool|array 成功返回转换后的对象或数组，失败返回 false
     */
    public static function jsonDecode($data = '', $assoc = false)
    {
        try {
            if (is_string($data)) {
                $data = json_decode($data, $assoc);

                return $data;
            }
            if (is_object($data) || is_array($data)) {
                return json_decode(json_encode($data), $assoc);
            }
            if (is_array($data)) {
                return $data;
            }
            Log::error('jsonDecode格式错误', [$data]);

            return false;
        } catch (Exception $ex) {
            Log::error('jsonDecode格式错误', [$ex->getMessage()]);

            return false;
        }
    }

    /**
     * 获取当前IP
     *
     * @return string
     */
    public static function getIP()
    {
        try {
            global $ip;
            if (getenv("HTTP_CLIENT_IP")) {
                $ip = getenv("HTTP_CLIENT_IP");
            } else {
                if (getenv("HTTP_X_FORWARDED_FOR")) {
                    $ip = getenv("HTTP_X_FORWARDED_FOR");
                } else {
                    if (getenv("REMOTE_ADDR")) {
                        $ip = getenv("REMOTE_ADDR");
                    } else {
                        $ip = false;
                    }
                }
            }

            return $ip;
        } catch (Exception $ex) {
            return false;
        }
    }

    function str_rand(
        $length = 32,
        $char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ) {
        if (!is_int($length) || $length < 0) {
            return false;
        }

        $string = '';
        for ($i = $length; $i > 0; $i--) {
            $string .= $char[mt_rand(0, strlen($char) - 1)];
        }

        return $string;
    }

    /**
     * 获取系统信息
     *
     * @return bool|string
     */
    public function get_os()
    {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $os    = false;

        if (preg_match('/win/i', $agent) && strpos($agent, '95')) {
            $os = 'Windows 95';
        } else {
            if (preg_match('/win 9x/i', $agent) && strpos($agent, '4.90')) {
                $os = 'Windows ME';
            } else {
                if (preg_match('/win/i', $agent)
                    && preg_match('/98/i', $agent)
                ) {
                    $os = 'Windows 98';
                } else {
                    if (preg_match('/win/i', $agent)
                        && preg_match('/nt 6.0/i', $agent)
                    ) {
                        $os = 'Windows Vista';
                    } else {
                        if (preg_match('/win/i', $agent)
                            && preg_match('/nt 6.1/i', $agent)
                        ) {
                            $os = 'Windows 7';
                        } else {
                            if (preg_match('/win/i', $agent)
                                && preg_match('/nt 6.2/i', $agent)
                            ) {
                                $os = 'Windows 8';
                            } else {
                                if (preg_match('/win/i', $agent)
                                    && preg_match('/nt 10.0/i', $agent)
                                ) {
                                    $os = 'Windows 10';#添加win10判断
                                } else {
                                    if (preg_match('/win/i', $agent)
                                        && preg_match('/nt 5.1/i', $agent)
                                    ) {
                                        $os = 'Windows XP';
                                    } else {
                                        if (preg_match('/win/i', $agent)
                                            && preg_match('/nt 5/i', $agent)
                                        ) {
                                            $os = 'Windows 2000';
                                        } else {
                                            if (preg_match('/win/i', $agent)
                                                && preg_match('/nt/i', $agent)
                                            ) {
                                                $os = 'Windows NT';
                                            } else {
                                                if (preg_match('/win/i', $agent)
                                                    && preg_match('/32/i',
                                                        $agent)
                                                ) {
                                                    $os = 'Windows 32';
                                                } else {
                                                    if (preg_match('/linux/i',
                                                        $agent)
                                                    ) {
                                                        $os = 'Linux';
                                                    } else {
                                                        if (preg_match('/unix/i',
                                                            $agent)
                                                        ) {
                                                            $os = 'Unix';
                                                        } else {
                                                            if (preg_match('/sun/i',
                                                                    $agent)
                                                                && preg_match('/os/i',
                                                                    $agent)
                                                            ) {
                                                                $os = 'SunOS';
                                                            } else {
                                                                if (preg_match('/ibm/i',
                                                                        $agent)
                                                                    && preg_match('/os/i',
                                                                        $agent)
                                                                ) {
                                                                    $os
                                                                        = 'IBM OS/2';
                                                                } else {
                                                                    if (preg_match('/Mac/i',
                                                                            $agent)
                                                                        && preg_match('/PC/i',
                                                                            $agent)
                                                                    ) {
                                                                        $os
                                                                            = 'Macintosh';
                                                                    } else {
                                                                        if (preg_match('/PowerPC/i',
                                                                            $agent)
                                                                        ) {
                                                                            $os
                                                                                = 'PowerPC';
                                                                        } else {
                                                                            if (preg_match('/AIX/i',
                                                                                $agent)
                                                                            ) {
                                                                                $os
                                                                                    = 'AIX';
                                                                            } else {
                                                                                if (preg_match('/HPUX/i',
                                                                                    $agent)
                                                                                ) {
                                                                                    $os
                                                                                        = 'HPUX';
                                                                                } else {
                                                                                    if (preg_match('/NetBSD/i',
                                                                                        $agent)
                                                                                    ) {
                                                                                        $os
                                                                                            = 'NetBSD';
                                                                                    } else {
                                                                                        if (preg_match('/BSD/i',
                                                                                            $agent)
                                                                                        ) {
                                                                                            $os
                                                                                                = 'BSD';
                                                                                        } else {
                                                                                            if (preg_match('/OSF1/i',
                                                                                                $agent)
                                                                                            ) {
                                                                                                $os
                                                                                                    = 'OSF1';
                                                                                            } else {
                                                                                                if (preg_match('/IRIX/i',
                                                                                                    $agent)
                                                                                                ) {
                                                                                                    $os
                                                                                                        = 'IRIX';
                                                                                                } else {
                                                                                                    if (preg_match('/FreeBSD/i',
                                                                                                        $agent)
                                                                                                    ) {
                                                                                                        $os
                                                                                                            = 'FreeBSD';
                                                                                                    } else {
                                                                                                        if (preg_match('/teleport/i',
                                                                                                            $agent)
                                                                                                        ) {
                                                                                                            $os
                                                                                                                = 'teleport';
                                                                                                        } else {
                                                                                                            if (preg_match('/flashget/i',
                                                                                                                $agent)
                                                                                                            ) {
                                                                                                                $os
                                                                                                                    = 'flashget';
                                                                                                            } else {
                                                                                                                if (preg_match('/webzip/i',
                                                                                                                    $agent)
                                                                                                                ) {
                                                                                                                    $os
                                                                                                                        = 'webzip';
                                                                                                                } else {
                                                                                                                    if (preg_match('/offline/i',
                                                                                                                        $agent)
                                                                                                                    ) {
                                                                                                                        $os
                                                                                                                            = 'offline';
                                                                                                                    } else {
                                                                                                                        $os
                                                                                                                            = '未知操作系统';
                                                                                                                    }
                                                                                                                }
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $os;
    }

    /**
     * 获取浏览器信息
     *
     * @return string
     */
    public function get_broswer()
    {
        $sys = $_SERVER['HTTP_USER_AGENT'];  //获取用户代理字符串
        if (stripos($sys, "Firefox/") > 0) {
            preg_match("/Firefox\/([^;)]+)+/i", $sys, $b);
            $exp[0] = "Firefox";
            //$exp[1] = $b[1];  	//获取火狐浏览器的版本号
        } elseif (stripos($sys, "Maxthon") > 0) {
            preg_match("/Maxthon\/([\d\.]+)/", $sys, $aoyou);
            $exp[0] = "傲游";
            //$exp[1] = $aoyou[1];
        } elseif (stripos($sys, "MSIE") > 0) {
            preg_match("/MSIE\s+([^;)]+)+/i", $sys, $ie);
            $exp[0] = "IE";
            //$exp[1] = $ie[1];  //获取IE的版本号
        } elseif (stripos($sys, "OPR") > 0) {
            preg_match("/OPR\/([\d\.]+)/", $sys, $opera);
            $exp[0] = "Opera";
            //$exp[1] = $opera[1];
        } elseif (stripos($sys, "Edge") > 0) {
            //win10 Edge浏览器 添加了chrome内核标记 在判断Chrome之前匹配
            preg_match("/Edge\/([\d\.]+)/", $sys, $Edge);
            $exp[0] = "Edge";
            //$exp[1] = $Edge[1];
        } elseif (stripos($sys, "Chrome") > 0) {
            preg_match("/Chrome\/([\d\.]+)/", $sys, $google);
            $exp[0] = "Chrome";
            //$exp[1] = $google[1];  //获取google chrome的版本号
        } elseif (stripos($sys, 'rv:') > 0 && stripos($sys, 'Gecko') > 0) {
            preg_match("/rv:([\d\.]+)/", $sys, $IE);
            $exp[0] = "IE";
            //$exp[1] = $IE[1];
        } else {
            $exp[0] = "未知浏览器";
            //$exp[1] = "";
        }

        return $exp[0];
        //return $exp[0].'('.$exp[1].')';
    }

    /**
     * 监测移动端
     *
     * @return bool
     */
    public function check_wap()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset($_SERVER['HTTP_VIA'])) {
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高。其中'MicroMessenger'是电脑微信
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = [
                'nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile',
                'MicroMessenger',
            ];
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(".implode('|', $clientkeywords).")/i",
                strtolower($_SERVER['HTTP_USER_AGENT']))
            ) {
                return true;
            }
        }
        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false)
                && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false
                    || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml')
                        < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))
            ) {
                return true;
            }
        }

        return false;
    }


    /**
     * 发送post请求
     * @param string $url 请求地址
     * @param array $post_data post键值对数据
     * @return string
     */
    public static function sendPost($url, $post_data) {
        $postdata = http_build_query($post_data);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $postdata,
                'timeout' => 15 * 60 // 超时时间（单位:s）
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
    }
}
