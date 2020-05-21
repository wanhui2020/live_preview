<?php

namespace App\Messages\Sms;

use Overtrue\EasySms\Message;
use Overtrue\EasySms\Contracts\GatewayInterface;
use Overtrue\EasySms\Strategies\OrderStrategy;

//订单通过放行
class OrderPassMessage extends Message
{
    public $money, $order;
    public $msg_content='新任务提醒，当前有一笔%s元待付款需完成，订单号%s，超时10分钟订单自动取消！';
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
        return sprintf($this->msg_content, $this->code);
//        return '新任务提醒，当前有一笔${money}元待付款需完成，订单号${order}，超时10分钟订单自动取消！';
//        if ($gateway->getName() == 'aliyun') {
//            return sprintf('收款提醒，当前有一笔%s元收款需确认，订单编号：%s！', $this->money, $this->order);
//        }
//        return sprintf('收款提醒，当前有一笔%s元收款需确认，订单编号：%s！', $this->money, $this->order);
    }

    // 定义使用模板发送方式平台所需要的模板 ID
    public function getTemplate(GatewayInterface $gateway = null)
    {
        if ($gateway->getName() == 'aliyun') {
            return 'SMS_173341189';
        }

        return 'SMS_173341189';
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