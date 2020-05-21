<?php

namespace App\Console\Commands;

use App\Models\MemberUser;
use App\Models\PlatformCharm;
use App\Models\PlatformConfig;
use App\Models\PlatformPayment;
use App\Models\PlatformPaymentChannel;
use App\Models\PlatformPrice;
use App\Models\PlatformVip;
use App\Models\SystemConfig;
use App\Models\SystemUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '系统安装';

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


        if ($this->confirm('确认进行系统初始化安装? [y|N]')) {


            if (env('APP_ENV') == 'production') {
                $this->info("非local环境不可以安装！");
            } else {

                DB::beginTransaction();
                try {
                    DB::statement('SET FOREIGN_KEY_CHECKS = 0');
//                    Artisan::call('migrate');

                    $this->call(':fresh');
                    SystemConfig::truncate();
                    SystemUser::truncate();
//                    PlatformConfig::truncate();
//                    AgentUser::truncate();
//                    MemberUser::truncate();
//
//                    //服务商
//                    AgentUser:: create([
//                        'name' => '测试总代',
//                        'password' => bcrypt('20080808'),
//                        'email' => 'agent@yeah.net',
//                        'mobile' => '13888888888',
//                    ]);
//                    //会员信息
//                    MemberUser:: create([
//                        'name' => '测试承兑商',
//                        'password' => bcrypt('20080808'),
//                        'email' => 'otc@yeah.net',
//                        'mobile' => '13888888888',
//                    ]);
//
                    //系统参数

                    SystemConfig::create([
                        'name' => '测试系统',
                        'logo' => '/images/logo.png',
                        'domain' => url('/'),
                        'tel' => '023-88888888',
                    ]);

                    //系统用户
                    SystemUser:: create([
                        'name' => '超级管理员',
                        'password' => bcrypt('20080808'),
                        'email' => 'admin@yeah.net',

                        'mobile' => '13888888888',
                        'type' => '0',
                    ]);
                    //平台参数
                    PlatformConfig:: create([
                    ]);

                    //魅力等级
                    PlatformCharm:: create([
                        'name' => 'M0',
                        'grade' => 0,
                    ]);
                    //VIP等级
                    PlatformVip:: create([
                        'name' => 'V0',
                        'grade' => 0,
                    ]);
                    //支付通道
                    PlatformPaymentChannel:: create([
                        'name' => '支付宝',
                        'code' => 'alipay',
                        'icon' => '/images/default/pay/alipay.png',
                    ]);
                    PlatformPaymentChannel:: create([
                        'name' => '微信',
                        'code' => 'weixin',
                        'icon' => '/images/default/pay/weixin.png',
                    ]);
                    PlatformPayment:: create([
                        'name' => '支付宝测试',
                        'account' => '测试账户',
                        'channel_id' => 1,
                    ]);

                    PlatformPrice:: create([
                        'name' => '测试充值',
                        'money' => 100,
                        'rate' => 0.8,
                    ]);

                    DB::statement('SET FOREIGN_KEY_CHECKS = 1');//启用外键约束
                    DB::commit();
                    $this->info("系统初始化已成功！");
                } catch (\Exception $ex) {
                    $this->info($ex->getMessage());
                }
            }

        }

    }
}
