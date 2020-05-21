<?php

namespace App\Messages\Sms;

use App\Models\DealOrder;
use Overtrue\EasySms\Message;
use Overtrue\EasySms\Contracts\GatewayInterface;
use Overtrue\EasySms\Strategies\OrderStrategy;

//订单确认
class OrderMessage extends Message
{
    public $order;
    protected $strategy = OrderStrategy::class;
    protected $gateways = ['entinfo','qcloud', 'aliyun'];

    public function __construct(DealOrder $order)
    {
        parent::__construct();
        $this->order = $order;
    }

    // 定义直接使用内容发送平台的内容
    public function getContent(GatewayInterface $gateway = null )
    {

        if ($gateway->getName() == 'entinfo') {
            switch ($this->order->order_status) {
                case 0://已完成
                    return sprintf('您的订单已完成，金额%s元，订单号：%s，请查询！', $this->order->total, $this->order->no);
                case 2://支付中
                    return sprintf('订单待支付提醒，金额%s元，订单：%s,支付后请确认已支付， 10分钟后超时自动取消！', $this->order->total, $this->order->no);
                case 3://已支付
                    return sprintf('新任务，订单已支付，金额%s元，订单号：%s，请查账后放行，10分钟后超时自动挂起！', $this->order->total, $this->order->no);
                case 4://未到账
                    return sprintf('申诉提醒，金额%s元，订单编号：%s，交易有争议请举证！', $this->order->total, $this->order->no);
                case 9://待接单
                    return sprintf('新任务，订单待接收，金额%s元，订单号：%s，10分钟后超时自动取消！', $this->order->total, $this->order->no);
            }
        }

        if ($gateway->getName() == 'qcloud') {
            switch ($this->order->order_status) {
                case 0://已完成
                    return sprintf('您的订单已完成，金额%s元，订单号：%s，请查询！', $this->order->total, $this->order->no);
                case 2://支付中
                    return sprintf('订单待支付提醒，金额%s元，订单：%s,支付后请确认已支付， 10分钟后超时自动取消！', $this->order->total, $this->order->no);
                case 3://已支付
                    return sprintf('新任务，订单已支付，金额%s元，订单号：%s，请查账后放行，10分钟后超时自动挂起！', $this->order->total, $this->order->no);
                case 4://申诉中
                    return sprintf('申诉提醒，金额%s元，订单编号：%s，交易有争议请举证！', $this->order->total, $this->order->no);
                case 9://待接单
                    return sprintf('新任务，订单待接收，金额%s元，订单号：%s，10分钟后超时自动取消！', $this->order->total, $this->order->no);
            }
        }

        return sprintf('订单状态变化，金额%s元，订单号：%s，请关注！', $this->order->total, $this->order->no);
    }

    // 定义使用模板发送方式平台所需要的模板 ID
    public function getTemplate(GatewayInterface $gateway = null)
    {
        if ($gateway->getName() == 'qcloud') {
            switch ($this->order->order_status) {
                case 0://已完成
                    return '417540';
                case 2://支付中
                    return '417538';
                case 3://已支付
                    return '417539';
                case 4://申诉中
                    return '417542';
                case 9://待接单
                    return '417537';
            }
        }

        return '417545';
    }

    // 模板参数
    public function getData(GatewayInterface $gateway = null)
    {
        if ($gateway->getName() == 'qcloud') {
            return [
                '1' => $this->order->total,
                '2' => $this->order->no,
            ];
        }
        return [
            'money' => $this->order->total,
            'order' => $this->order->no,
        ];
    }
}