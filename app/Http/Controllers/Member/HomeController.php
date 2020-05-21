<?php

namespace App\Http\Controllers\Member;

use App\Facades\SmsFacade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;

class HomeController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 页面首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = $request->user('MemberUser');
        return view('member.index',compact('user'));
    }

    /**
     * 发送短信验证码
     */
    public function code(Request $request)
    {
        $user = $request->user('MemberUser');
        $resp = SmsFacade::sendCode($user->mobile);
        return $resp;

    }

    /**
     * 有效验证
     */
    public function verify(Request $request)
    {

        $user = Auth::guard('MemberUser')->user();
        if ($request->isMethod('GET')) {
            return view('member.common.verify', compact('user'));
        }
        $mobile = $user->mobile;
        $code = $request->code;
        if (SmsFacade::verifyCode($mobile, $code)) {
            Cache::put('member-' . $user->id, $user->no, Carbon::now()->addSeconds(60 * 60 * 2));
            Cookie::queue('member-mac', $user->no, 60 * 24);
            return $this->succeed($user, '验证成功');
        }
        return $this->validation('验证失败');
    }
    /**
     * 工作台
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home(Request $request)
    {
        return view('member.home');
    }


    public function info()
    {
        return view('member.info');
    }


}
