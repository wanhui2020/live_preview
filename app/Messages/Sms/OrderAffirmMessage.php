<?php

namespace App\Messages\Sms;

use Overtrue\EasySms\Message;
use Overtrue\EasySms\Contracts\GatewayInterface;
use Overtrue\EasySms\Strategies\OrderStrategy;

//订单确认
class OrderAffirmMessage extends Message
{
    public $money, $order;
    protected $strategy = OrderStrategy::class;
    protected $gateways = ['entinfo','aliyun'];

    public function __construct($money, $order)
    {
        parent::__construct();
        $this->money = $money;
        $this->order = $order;
    }

    // 定义直接使用内容发送平台的内容
    public function getContent(GatewayInterface $gateway = null)
    {
        return '收款提醒，当前有一笔${money}元收款需确认，订单编号：${order}！';
        if ($gateway->getName() == 'aliyun') {
            return sprintf('收款提醒，当前有一笔%s元收款需确认，订单编号：%s！', $this->money, $this->order);
        }
        return sprintf('收款提醒，当前有一笔%s元收款需确认，订单编号：%s！', $this->money, $this->order);
    }

    // 定义使用模板发送方式平台所需要的模板 ID
    public function getTemplate(GatewayInterface $gateway = null)
    {
        if ($gateway->getName() == 'aliyun') {
            return 'SMS_173341193';
        }

        return 'SMS_173341193';
    }

    // 模板参数
    public function getData(GatewayInterface $gateway = null)
    {
        if ($gateway->getName() == 'aliyun') {
            return [
                'money' => $this->money,
                'order' => $this->order,
            ];
        }
        return [
            'money' => $this->money,
            'order' => $this->order,
        ];
    }
}