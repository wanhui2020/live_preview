<?php

namespace App\Jobs;

use App\Facades\DealFacade;
use App\Facades\MemberFacade;
use App\Facades\StockFacade;
use App\Facades\TradeFacade;
use App\Models\MemberVerification;
use App\Traits\ResultTrait;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScanJob implements ShouldQueue
{
    use ResultTrait;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $type;
    protected $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($type, $id)
    {
        $this->logs('安全过虑队列',$id);
        $this->type = $type;
        $this->id = $id;
    }


    /**
     * @return void
     */
    public function handle()
    {
        try {
            switch ($this->type) {
                //资源检查
                case 'MemberResource':
                    MemberFacade::ResourceAudit($this->id);
                    break;
                //文本检查
                case 'MemberVerification':
                    MemberFacade::VerificationAudit($this->id);
                    break;
            }
        } catch (Exception $ex) {
            $this->exception($ex);
        }

    }


    /**
     * 要处理的失败任务。
     * @param Exception $exception
     * @return void
     */
    public function failed(Exception $ex)
    {
        $this->exception($ex, '队列失败');
    }
}
