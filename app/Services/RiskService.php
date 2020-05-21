<?php

namespace App\Services;

use App\Traits\ResultTrait;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Log;
use Yuanyou\Risk\Risk;

/**
 * 风按平台
 * Class RiskService
 *
 * @package App\Services
 */
class RiskService
{

    use ResultTrait;

    private $risk;

    public function __construct()
    {
        $this->risk = new Risk();
    }

    /**
     * 发送短信
     */
    public function send($mobiles, $text)
    {
        try {
            return $this->failure(1, '发送失败', '手机号为空');
        } catch (\Exception $ex) {
            return $this->exception(ex, '发送失败');
        }
    }


    /**
     * 发送验证码
     *
     * @param $phone
     * @param  int  $seconds
     *
     * @return array
     */
    public function sendCode($phone, $seconds = null)
    {
        try {
            if (Cache::has($phone)) {
                return $this->failure(1, '失败,请3分钟后再试', '请3分钟后再试');
            }
            $code   = rand(100000, 999999);

            $result = $this->risk->MessageSend($phone,
                config('sms.verify_code_template_id'),
                ['code' => $code]);

            Cache::put($phone, $code, Carbon::now()->addSeconds(
                $seconds ?? config('sms.verify_code_expire_seconds'))
            );

            return $this->succeed(Carbon::now()->toDateTimeString(), '发送成功');
        } catch (\Exception  $ex) {
            return $this->exception($ex, '发送失败');
        }
    }

    /**
     * 效验验证码
     *
     * @param $phone
     * @param $code
     *
     * @return bool
     */
    public function verifyCode($phone, $code)
    {
        if (empty($phone) || empty($code)) {
            return false;
        }

        if (Cache::has($phone)) {

            if (Cache::get($phone) === $code) {
                Cache::forget($phone);

                return true;
            }
        }

        return false;
    }

}
