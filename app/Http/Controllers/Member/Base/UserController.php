<?php

namespace App\Http\Controllers\Member\Base;

use App\Facades\SmsFacade;
use App\Http\Controllers\Member\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Criteria\RelevanceCriteria;
use App\Models\MemberUser;
use App\Repositories\MemberUserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserController extends BaseController
{
    public function __construct(MemberUserRepository $repository)
    { parent::__construct();
        $this->repository = $repository;

    }

    /**
     * 页面首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::guard('MemberUser')->user();
        return view('member.base.user.index', compact('user'));
    }

    /**
     *  用户详情
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function info(Request $request)
    {
        try {
            $user = $request->user('MemberUser');
            return view('member.base.user.info', compact('user'));
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }

    /**
     * 编辑页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit()
    {
        $user = Auth::guard('MemberUser')->user();
        return view('member.base.user.edit', compact('user'));
    }

    /**
     * 安全设置
     */
    public function safety(Request $request)
    {
        $user = Auth::guard('MemberUser')->user();

        if (!Cache::has('member-' . $user->id)&&Auth::guard('SystemUser')->guest()) {
            return redirect()->to('/otc/verify');
        }


        if ($request->isMethod('GET')) {
            return view('member.base.user.safety', compact('user'));
        }

        $data['id'] = $user->id;
        if (isset($request->money_password)){
            $data['money_password'] = md5($request->money_password);
        }
        $data['public_key'] = $request->public_key;
        $data['whitelist'] = $request->whitelist;


        $result = $this->repository->update($data);
        return $result;

    }


    /**
     * 修改系统用户
     * @param Request $request
     * @return array|mixed
     */
    public function update(Request $request)
    {
        try {
            $data = $request->all();

            $result = $this->repository->update($data);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }



}

