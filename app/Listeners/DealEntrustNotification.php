<?php


namespace App\Listeners;


use App\Events\DealEntrustEvent;
use App\Facades\DealFacade;
use Illuminate\Contracts\Queue\ShouldQueue;

class DealEntrustNotification implements ShouldQueue
{
    /**
     * 任务将被推送到的连接名称.
     *
     * @var string|null
     */
    //  public $connection = 'redis';

    /**
     * 任务将被推送到的连接名称.
     *
     * @var string|null
     */
//    public $queue = 'listeners';

    /**
     * 任务被处理之前的延迟时间（秒）
     *
     * @var int
     */
    // public $delay = 60;


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
     * @param DealEntrustEvent $event
     * @return void
     */
    public function handle(DealEntrustEvent $event)
    {
        $entrust = $event->entrust;

        $resp = DealFacade::entrustUpdate($entrust->id, $entrust->success_num, $entrust->success_price, $entrust->cancel_num);

    }
}