<?php

namespace App\Services;


use App\Models\MemberUser;
use App\Models\MemberWalletCash;
use App\Models\MemberWalletGold;
use App\Models\MemberWalletRecharge;
use App\Models\MemberWalletRecord;
use App\Models\MemberWalletWithdraw;
use App\Traits\ResultTrait;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * 微信服务
 * @package App\Http\Service
 */
class WechatService
{
    use ResultTrait;
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }


    /**
     * 根据token和openid获取用户信息
     * @param $openid
     * @param $access_token
     * @return array
     */
    public function getUserInfo($openid, $access_token)
    {
        try {
            $url = 'https://api.weixin.qq.com/sns/userinfo';
            $response = $this->client->post($url, ['form_params' => [
                'access_token' => $access_token,
                'openid' => $openid
            ]]);
            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);
            if (isset($data['errcode'])) {
                return $this->failure(1, '获取微信信息失败', $data);

            }
            return $this->succeed($data, '获取成功');
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

}
