<?php

namespace App\Listeners;

use App\Models\SystemLogin;
use App\Traits\ResultTrait;
use App\Utils\Helper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;

class UserEventSubscriber
{
    use ResultTrait;

    /**
     * 处理用户登录事件.
     * @translator laravelacademy.org
     */
    public function onUserLogin($event)
    {
//        $this->logs('onUserLogin', $event);

        $mac = Helper::rand_str(64);

        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '未知';
        $event->user->logins()->create([
            'mac' => request()->cookie($event->guard . ':' . $event->user->id)
            , 'address' => $ip
            , 'device' => $event->user->id
            , 'browser' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '未知'
            , 'referer' => request()->fullUrl()
            , 'login_time' => Carbon::now()->toDateTimeString()

        ]);


//        Cookie::queue('mac', $mac, 60*24);
//        $value = request()->cookie('name');
//        response('mac')->cookie($event->guard . ':' . $event->user->id, $mac);
    }

    /**
     * 处理用户退出事件.
     */
    public function onUserLogout($event)
    {
        $login = $event->user->logins()->orderBy('login_time', 'desc')->first();
        if (isset($login)) {
            $login->logout_time = Carbon::now()->toDateTimeString();
            $login->save();
        }
    }

    /**
     * 为订阅者注册监听器.
     *
     * @param Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Login',
            'App\Listeners\UserEventSubscriber@onUserLogin'
        );

        $events->listen(
            'Illuminate\Auth\Events\Logout',
            'App\Listeners\UserEventSubscriber@onUserLogout'
        );
    }

}