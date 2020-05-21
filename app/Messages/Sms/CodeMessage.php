<?php

namespace App\Messages\Sms;

use App\Models\PlatformTemplate;
use Overtrue\EasySms\Message;
use Overtrue\EasySms\Contracts\GatewayInterface;
use Overtrue\EasySms\Strategies\OrderStrategy;

//短息验证码
class CodeMessage extends Message
{
    public $code = '';
    protected $strategy = OrderStrategy::class;
    protected $gateways = ['entinfo', 'qcloud', 'aliyun'];


    public function __construct()
    {
        parent::__construct();
        $this->code = rand('100000', '999999');

    }

    // 定义直接使用内容发送平台的内容
    public function getContent(GatewayInterface $gateway = null)
    {
        if ($gateway->getName() == 'aliyun') {
            return sprintf('验证码%s，您正在进行身份验证，打死不要告诉别人哦！', $this->code);
        }
        if ($gateway->getName() == 'qcloud') {
            return sprintf('尊敬的用户，您的验证码为%s，有效期为一分钟，如非本人操作，请忽略本条消息。', $this->code);
        }
        if ($gateway->getName() == 'entinfo') {
            return sprintf('尊敬的用户，您的验证码为%s，有效期为一分钟，如非本人操作，请忽略本条消息。', $this->code);
        }

        return sprintf('尊敬的用户，您的验证码为%s，有效期为一分钟，如非本人操作，请忽略本条消息。', $this->code);
    }

    // 定义使用模板发送方式平台所需要的模板 ID
    public function getTemplate(GatewayInterface $gateway = null)
    {

        if ($gateway->getName() == 'aliyun') {
            return 'SMS_173185687';
        }
        if ($gateway->getName() == 'qcloud') {
            return '417547';
        }


        return 'SMS_173185687';
    }

    // 模板参数
    public function getData(GatewayInterface $gateway = null)
    {
        if ($gateway->getName() == 'aliyun') {
            return [
                'code' => $this->code,
            ];
        }
        if ($gateway->getName() == 'qcloud') {
            return [
                '1' => $this->code
            ];
        }
        return [
            'code' => $this->code,
        ];
    }
}