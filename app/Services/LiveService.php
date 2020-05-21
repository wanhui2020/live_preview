<?php

namespace App\Services;

use App\Traits\ResultTrait;
use App\Utils\TLSSigAPIv2;

//音视频服务
class LiveService
{
    use ResultTrait;
    private $config;
    private $client;

    public function __construct()
    {
        $this->config = [
            'base_url' => 'https://trtc.tencentcloudapi.com/',
            'SdkAppId' => '1400292701',
            'SecretId' => '1400292701',
            'SecretKey' => '1400292701',
        ];

    }

    /**
     * 解散房间
     */
    public function DissolveRoom($roomId)
    {
        try {
            $param["Nonce"] = rand();
            $param["Timestamp"] = time();
            $param["Region"] = "ap-guangzhou";
            $param["SecretId"] = $this->config['SecretId'];
            $param["Action"] = "DissolveRoom";
            $param["Version"] = "2019-07-22";
            $param["SdkAppId"] = $this->config['SdkAppId'];
            $param["RoomId"] = $roomId;
            ksort($param);
            $signStr = "GETtrtc.tencentcloudapi.com/?";
            foreach ($param as $key => $value) {
                $signStr = $signStr . $key . "=" . $value . "&";
            }
            $signStr = substr($signStr, 0, -1);

            $signature = base64_encode(hash_hmac("sha1", $signStr, $this->config['SecretKey'], true));

            $param['Signature'] = $signature;
            $params = http_build_query($param);
            $url = 'https://trtc.tencentcloudapi.com' . '?' . $params;
            return $this->requestPost($url);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }

    }

    //HTTP请求（支持HTTP/HTTPS，支持GET/POST）
    function requestPost($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)) {
            if (is_array($data)) {
                $data = json_encode($data);
            }
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($curl);
        curl_close($curl);
        $output = json_decode($output, true);

        return $output;
    }

}
