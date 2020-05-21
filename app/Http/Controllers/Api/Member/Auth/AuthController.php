<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2019/4/16
 * Time: 10:22
 */

namespace App\Http\Controllers\Api\Member\Auth;


use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\UserRequest;
use App\Http\Resources\MemberUserMyResource;
use App\Repositories\CustomerUserRepository;
use App\Repositories\MemberUserRepository;
use App\Utils\Helper;

class AuthController extends ApiController
{

    public function __construct(MemberUserRepository $repository)
    {
        $this->repository = $repository;
    }


    public function loginWeixin(UserRequest $request)
    {
        try {
            if (!$request->filled('openid')) {
                return $this->failure(1, 'OpenID异常', $request->all());
            }
            if (!$request->filled('access_token')) {
                return $this->failure(1, 'access_token异常', $request->all());
            }
            $data = $request->only(['openid', 'access_token']);

            return $this->repository->loginWeixin($data);
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }


    public function loginMobile(UserRequest $request)
    {
        try {
            if (!$request->filled('mobile')) {
                return $this->validation('手机号不能为空');
            }

            if (!$request->filled('code')) {
                return $this->validation('验证码不能为空');
            }
            $data = $request->only(['mobile', 'code']);

            return $this->repository->loginMobile($data);
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }

    public function logout(UserRequest $request)
    {
        try {
            $member = $request->user('ApiMember');
            $member->online_status = 1;
//            $member->im_status = 1;
            $member->live_status = 0;
            $member->api_token = Helper::rand_str(64);;
            $member->save();
            return $this->succeed(new MemberUserMyResource($member));
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }


    public function register(UserRequest $request)
    {
        try {
            $data = $request->only(['phone', 'password', 'code', 'invite_code']);
            if (!$data['invite_code']) {
                return $this->validation('请输入邀请码');
            }


            return $this->repository->register($data);
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }


    public function forget(UserRequest $request)
    {
        try {
            $data = $request->only(['phone', 'password', 'code']);
            return $this->repository->forget($data);
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }
}
