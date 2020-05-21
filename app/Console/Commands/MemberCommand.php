<?php

namespace App\Console\Commands;

use App\Facades\DealFacade;
use App\Facades\MemberFacade;
use App\Models\MemberUser;
use App\Models\PlatformConfig;
use App\Models\SystemConfig;
use App\Models\SystemUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class MemberCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'member {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '业务命令';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $type = $this->argument('type');
        switch ($type) {
            case 'imSync'://定期IM账户同步
                MemberFacade::imSync();
                break;
            case 'imOnlineSync'://会员在线状态检查
                MemberFacade::imOnlineSync();
                break;
            case 'onlineRebot'://陪聊动态切换
                MemberFacade::onlineRebot();
                break;
            case 'vipIntegralSync'://VIP积分计算
                MemberFacade::vipIntegralSync();
                break;
            case 'charmIntegralSync'://魅力积分计算
                MemberFacade::charmIntegralSync();
                break;
            case 'pushMessage':
                MemberFacade::pushMessage();
                break;

        }

    }
}
