<?php

namespace App\Http\Controllers\System\Member;

use App\Http\Controllers\Controller;
use App\Repositories\MemberLoginRepository;
use Illuminate\Http\Request;

/**
 * 用户登录日志
 * Class LoginsController
 * @package App\Http\Controllers\System\Member
 */
class LoginController extends Controller
{
    public function __construct(MemberLoginRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 页面首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('system.member.login.index');
    }

    /**
     * 数据列表
     * @return array
     */
    public function lists()
    {
        try {
            $list = $this->repository->with(['member'])->lists();
            return $this->paginate($list);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('system.member.login.create');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $result = $this->repository->store($data);
            if ($result['status']) {
                return $this->succeed($result);
            }
            return $this->failure(1, $result['msg']);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        try {
            $login = $this->repository->find($request->id);
            return view('system.member.login.edit')->with('login', $login);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
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

