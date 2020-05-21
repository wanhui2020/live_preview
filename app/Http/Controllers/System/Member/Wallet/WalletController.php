<?php

namespace App\Http\Controllers\System\Member\Wallet;

use App\Http\Controllers\Controller;
use App\Repositories\MemberUserRepository;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function __construct(MemberUserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 平台用户管理首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('system.member.wallet.index');
    }

    /**
     * 平台所有用户列表
     * @param Request $request
     * @return array
     */
    public function lists(Request $request)
    {
        try {
            $lists = $this->repository->with(['cash','gold'])->lists();
            return $this->paginate($lists);
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }


    /**
     * 新增服务商页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('system.member.wallet.create');
    }


    /**
     *  后台为用户实名认证页面
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        try {
            $wallet = $this->repository->find($request->id);
            if (!$wallet) {
                return $this->failure(1, '用户不存在');
            }
            return view('system.member.wallet.edit')->with('wallet', $wallet);
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }


    /**
     *  编辑
     * @param Request $request
     * @return array
     */
    public function update(Request $request)
    {
        try {
            $data = $request->all();
            $result = $this->repository->update($data);
            if ($result['status']) {
                return $this->succeed($result);
            }
            return $this->failure(1, $result['msg']);
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }


    /**
     *  编辑
     * @param Request $request
     * @return array
     */
    public function audit(Request $request)
    {
        try {
            $data = $request->all();
            $result = $this->repository->audit($data);

            if ($result['status']) {
                return $this->succeed($result);
            }
            return $this->failure(1, $result['msg']);
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }

}
