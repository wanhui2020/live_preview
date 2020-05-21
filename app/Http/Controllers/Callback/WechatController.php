<?php

namespace App\Http\Controllers\Callback;

use App\Facades\MemberFacade;
use App\Http\Controllers\Controller;
use App\Models\MemberUser;
use App\Utils\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WechatController extends Controller
{

    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function index(Request $request)
    {
//        Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

        $app = app('wechat.official_account');
        $app->server->push(function($message){
            return "欢迎关注 overtrue！";
        });

        return $app->server->serve();
    }
    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function oauth(Request $request)
    {
        $app = app('wechat.official_account');
        $oauth = $app->oauth;
        $user = $oauth->user()->original;
        Cache::put('wechat_user',$user,10);
        $targetUrl =$request->url??'/';
        return redirect($targetUrl);


    }
}
