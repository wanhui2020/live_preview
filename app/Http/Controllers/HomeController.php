<?php

namespace App\Http\Controllers;

use App\Facades\GreenFacade;
use App\Facades\OssFacade;
use App\Jobs\GenerateShareImageJob;
use App\Models\MemberUser;
use App\Models\PlatformEdition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Image;
use QrCode;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $edition = PlatformEdition::where('type', 'android')
            ->where('status', 0)
            ->first();

        return view('home', ['androidUrl' => $edition ? $edition->url : '/']);
    }

    public function admin()
    {
        return response()->redirectTo('/system');
    }

    public function share(Request $request, $no = null)
    {
        $app = app('wechat.official_account');

        $oauth = $app->oauth;
        // 未登录
        if (!Cache::has('wechat_user')) {
            return $oauth->redirect(url('callback/wechat/oauth?url='
                .$request->fullUrl()));
        }
        $user = Cache::pull('wechat_user');
        // \Log::info($_SERVER['HTTP_USER_AGENT']);

        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'android')
            !== false
        ) {
            $PlatformEdition = PlatformEdition::where('type', 'android')
                ->where('status', 0)->first();
        } else {
            $PlatformEdition = PlatformEdition::where('type', 'ios')
                ->where('status', 0)->first();
        }
        //        if (strpos($_SERVER['HTTP_USER_AGENT'], 'iphone') || strpos($_SERVER['HTTP_USER_AGENT'], 'ipad')) {
        //            $PlatformEdition = PlatformEdition::where('type', 'ios')->where('status', 0)->first();
        //        } else if (strpos($_SERVER['HTTP_USER_AGENT'], 'android')) {
        //            $PlatformEdition = PlatformEdition::where('type', 'android')->where('status', 0)->first();
        //        } else {
        //            $PlatformEdition = PlatformEdition::where('type', 'android')->where('status', 0)->first();
        //        }
        if (MemberUser::where('unionid', $user['unionid'])->exists()) {
            if (isset($PlatformEdition)) {
                return redirect($PlatformEdition->url);
            }

            //            abort(200, '下载地址请联系客服！');
            return redirect(config('app.url'));
        }
        $member = MemberUser::firstOrNew(['unionid' => $user['unionid']]);
        if ($no) {
            $parent = MemberUser::where('no', $no)->first();
            if (isset($parent)) {
                $member->parent_id = $parent->id;
            }
        }
        $member->weixin_openid = $user['openid'];
        $member->nick_name     = $user['nickname'];
        $member->head_pic      = $user['headimgurl'];
        $result                = $member->save();

        if (isset($PlatformEdition)) {
            return redirect($PlatformEdition->url);
        }

        return view('share')->with('parent', $parent)
            ->with('app', $PlatformEdition);
    }

    public function test(Request $request)
    {

    }
}
