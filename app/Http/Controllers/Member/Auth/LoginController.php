<?php

namespace App\Http\Controllers\Member\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    protected $redirectTo = '/member';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * 登录界面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('member.auth.login');
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
            'captcha' => 'required|captcha'

        ]);

    }


    /**
     * Log the user out of the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();
        return $this->loggedOut($request) ?: redirect('/otc');
    }

    public function loggedOut(Request $request)
    {
        return response()->redirectTo('/otc');
    }

    /**
     * 中间件
     * @return mixed
     */
    protected function guard()
    {
        return Auth::guard('MemberUser');
    }

    /**
     * 登录
     * @param Request $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        /*if (md5($request->safety) != env('SAFETY')) {
            return false;
        }*/
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    public function username()
    {
        return 'mobile';
    }
}
