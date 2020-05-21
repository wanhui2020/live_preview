<?php

namespace App\Jobs;

use App\Facades\DealFacade;
use App\Facades\StockFacade;
use App\Facades\TradeFacade;
use App\Traits\ResultTrait;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DealJob implements ShouldQueue
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
                //创建房间
                case 'deal.talk.create.room':
                    DealFacade::talkCreateRoom($this->params['room_id']);
                    break;
                //扣费通知
                case 'deal.talk.deduction':
                    DealFacade::talkCreateRoom($this->params['room_id']);
                    break;
            }


        } catch (Exception $ex) {
            $this->exception($ex);
        }

    }

    /**
     * 5分钟内
     * @return static
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
