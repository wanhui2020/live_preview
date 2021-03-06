<?php


namespace App\Listeners;


use App\Events\DealEntrustEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class StockMarketNotification implements ShouldQueue
{
    /**
     * 任务将被推送到的连接名称.
     *
     * @var string|null
     */
    public $connection = 'redis';

    /**
     * 任务将被推送到的连接名称.
     *
     * @var string|null
     */
    public $queue = 'listeners';

    /**
     * 任务被处理之前的延迟时间（秒）
     *
     * @var int
     */
    //public $delay = 60;


    /**
     * 创建事件监听器.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 处理事件.
     *
     * @param StockMarketEvent $event
     * @return void
     */
    public function handle(StockMarketEvent $event)
    {

        // 使用 $event->order 发访问订单...
    }
}