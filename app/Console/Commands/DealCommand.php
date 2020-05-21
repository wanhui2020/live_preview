<?php

namespace App\Console\Commands;

use App\Facades\DealFacade;
use App\Models\MemberUser;
use App\Models\PlatformConfig;
use App\Models\SystemConfig;
use App\Models\SystemUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class DealCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deal {type}';

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
            case 'talkDeduct'://定期扣费
                DealFacade::talkDeduct();
                break;
            case 'talkTimeout'://超时挂断
                DealFacade::talkTimeout();
                break;
        }

    }
}
