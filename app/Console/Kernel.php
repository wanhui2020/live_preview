<?php

namespace App\Console;

use App\Console\Commands\ClearDatabaseCommand;
use App\Console\Commands\ClearTestDataCommand;
use App\Console\Commands\FixUserShareImageCommand;
use App\Console\Commands\PermissionGenerateCommand;
use App\Console\Commands\ProjectInitCommand;
use App\Facades\ImFacade;
use App\Models\MemberUser;
use App\Traits\ResultTrait;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    use ResultTrait;

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands
        = [
            ClearDatabaseCommand::class,
            ClearTestDataCommand::class,
            ProjectInitCommand::class,
            PermissionGenerateCommand::class,
            FixUserShareImageCommand::class
        ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //每分钟进行扣费处理
        $schedule->command('deal talkDeduct')->everyMinute()->before(function (
        ) {
            // 任务即将开始...
        })->after(function () {
            //            $this->logs('每分钟进行扣费处理');
        });

        //每分钟进行直接未接听通话
        $schedule->command('deal talkTimeout')->everyMinute()->before(function (
        ) {
            // 任务即将开始...
        })->after(function () {
            //            $this->logs('每分钟进行直接未接听通话');
        });

        //        //每分钟进行IM状态同步
        //        $schedule->command('member imSync')->everyMinute()->before(function () {
        //            // 任务即将开始...
        //        })->after(function () {
        ////            $this->logs('每分钟进行会员状态同步');
        //        });

        //每分钟进行IM状态同步
        $schedule->command('member imOnlineSync')->everyFiveMinutes()
            ->before(function () {
                // 任务即将开始...
            })->after(function () {
                //            $this->logs('每分钟进行会员状态同步');
            });
        //每五分钟切换一次陪聊状态
        $schedule->command('member onlineRebot')->everyFiveMinutes()
            ->before(function () {
                // 任务即将开始...
            })->after(function () {
                //            $this->logs('每分钟进行会员状态同步');
            });


        //每天晚上1点更新vip等级
        $schedule->command('member vipIntegralSync')->dailyAt('01:00')
            ->before(function () {
                // 任务即将开始...
            })->after(function () {
                //            $this->logs('每分钟进行会员状态同步');
            });

        $schedule->command('fix:share-image')->dailyAt('01:30');

        //每天晚上1点更新vip等级
        $schedule->command('member charmIntegralSync')->dailyAt('01:00')
            ->before(function () {
                // 任务即将开始...
            })->after(function () {
                //            $this->logs('每分钟进行会员状态同步');
            });

        //每小时检查一次  3天以前的用户给推送
        $schedule->command('member pushMessage')->hourly()
            ->before(function () {
                // 任务即将开始...
            })->after(function () {
                //            $this->logs('每分钟进行会员状态同步');
            });
        $schedule->call(function () {
            set_time_limit(0);
            $a = MemberUser::all();
            foreach ($a as $v) {
                $data = [];
                array_push($data, [
                    "Tag"   => 'Tag_Profile_Custom_Type',
                    "Value" => (string)$v['type'],
                ]);
                array_push($data, [
                    "Tag"   => 'Tag_Profile_Custom_Uid',
                    "Value" => (string)$v['id'],
                ]);
                array_push($data,
                    ["Tag" => 'Tag_Profile_Custom_No', "Value" => $v['no']]);
                $q = ImFacade::userUpdate($v['no'], $data);
                ImFacade::userSetInfo($v['no'], 'Tag_Profile_IM_Nick',
                    $v['nick_name']);
            }
        })->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
