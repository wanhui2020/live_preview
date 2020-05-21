<?php

namespace App\Jobs;

use App\Facades\DealFacade;
use App\Facades\ImFacade;
use App\Facades\StockFacade;
use App\Facades\TradeFacade;
use App\Traits\ResultTrait;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImJob implements ShouldQueue
{
    use ResultTrait;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

//    public $tries = 3;
//    public $timeout = 600;
    protected $type;
    protected $params;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($type = '', $params = [])
    {
        $this->type = $type;
        $this->params = $params;
    }


    /**
     * @return void
     */
    public function handle()
    {
        try {
            switch ($this->type) {
                //扣费信息发送
                case 'send.talk.deduction':
                    ImFacade::sendTalkDeduction($this->params['form_id'], $this->params['to_id'], $this->params['room_id'], $this->params['usable']);
                    break;
            }


        } catch (Exception $ex) {
            $this->exception($ex);
        }

    }

    /**
     * 5分钟内
     */
    public function retryUntil()
    {
        return now()->addSeconds(1);
    }

    /**
     * 要处理的失败任务。
     *
     * @param Exception $exception
     * @return void
     */
    public function failed(Exception $ex)
    {
        $this->exception($ex, '队列失败');
    }
}
