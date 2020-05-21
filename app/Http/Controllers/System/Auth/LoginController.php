<?php

namespace App\Http\Controllers\System\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    protected $redirectTo = '/system';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * 登录界面
     */
    public function showLoginForm(Request $request)
    {
        if(Auth::guard('SystemUser')->check()){
            return redirect('/system');
        }
        return view('system.auth.login');
    }

    /**
     * 中间件
     * @return mixed
     */
    protected function guard()
    {
        return Auth::guard('SystemUser');
    }

    /**
     * 登录
     * @param Request $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {

        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    /**
     * 登出
     *
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();
        return $this->succeed('退出成功');
    }
}
