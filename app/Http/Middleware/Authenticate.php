<?php

namespace App\Http\Middleware;

use App\Traits\ResultTrait;
use Carbon\Carbon;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class Authenticate extends Middleware
{

    use ResultTrait;

    /**
     * @param  Request  $request
     * @param  Closure  $next
     * @param  array  ...$guards
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|mixed
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if (in_array('SystemUser', $guards)) {
            if (!Auth::guard('SystemUser')->check()) {
                return redirect()->guest('/system/login');
            }
            $user = $request->user('SystemUser');
            if ($user->status != 0) {
                return redirect()->guest('/system/login');
            }
        }

        if (in_array('MemberUser', $guards)) {
            if (!Auth::guard('MemberUser')->check()) {
                return redirect()->guest('/otc/login');
            }
            $user = $request->user('MemberUser');
            if ($user->status != 0) {
                return redirect()->guest('/otc/login')->with(['msg' => '用户禁用']);
            }
            if (!$request->user('SystemUser') && env('APP_ENV') == 'production'
                && !request()->cookie('member-mac')
                && in_array($_SERVER['REQUEST_URI'], ['/otc/base/user/safety'])
            ) {
                return redirect()->to('/otc/verify');
            }
            if ($user->online_status != 0) {
                $user->online_status = 0;
                $user->save();
            }
        }


        if (in_array('ApiMember', $guards)) {
            $url = ['/api/member/platform/config', '/api/member/platform/text/helpCenter'];
            $path = \Request::getRequestUri();

            if (!in_array($path, $url)) {
                if (!Auth::guard('ApiMember')->getTokenForRequest()) {
                    return response()->json(
                        $this->failure(
                            1,
                            'api_token不能为空',
                            $request->all()
                        )
                    )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
                }



                if (Auth::guard('ApiMember')->guest()) {
                    return response()->json($this->failure(9, 'ApiToken错误',
                        $request->all()))
                        ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
                }


                $member = $request->user('ApiMember');
                $member->last_time = Carbon::now()->toDateTimeString();
                $member->save();

                //更新在线状态
                Cache::put('user-is-online-' . $member->id, true,
                    Carbon::now()->addMinutes(2));
            }
        }

        $this->authenticate($request, $guards);

        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are not
     * authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return string
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }

    /**
     * 签名验证
     *
     * @param  Request  $request
     */
    private function verify(Request $request)
    {
        try {
            $params = $request->all();
            $user   = $request->user('ApiMerchant');
            if (isset($params['_url'])) {
                unset($params['_url']);
            }
            if (!isset($params['sign']) || empty($params['sign'])) {
                return $this->validation('未找到签名信息', $params);
            }

            if (!isset($params['timestamp']) || empty($params['timestamp'])) {
                return $this->validation('请求时间异常', $params);
            }
            $timestamp = $params['timestamp'];
            if ((int)$timestamp + 600 < time()) {
                return $this->validation('请求超时', $params);
            }

            $sign = $params['sign'];
            unset($params['sign']);


            if (isset($params['api_token'])) {
                unset($params['api_token']);
            }

            if (count($params) >= 1) {
                //参数字典排序
                ksort($params);
                $str = '';
                foreach ($params as $k => $v) {
                    if (strlen($v) > 0) {
                        $str .= $k.'='.$v.'&';
                    }
                }
                $str = substr($str, 0, strlen($str) - 1);
                if ($sign == md5($str.$user->secret_key)) {
                    return $this->succeed($sign, '签名效验成功');
                }

                return $this->failure(1, '签名效验失败',
                    "原始参数：".json_encode($params)."转换后参数：".$str."用户Key："
                    .$user->secret_key);
            }

            return $this->failure(1, '签名效验失败，参数异常', $params);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }
}
