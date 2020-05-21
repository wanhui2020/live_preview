<?php

namespace App\Http\Controllers\System\Member\User;

use App\Http\Controllers\Controller;
use App\Models\MemberUserRate;
use App\Models\MemberUser;
use App\Models\MemberUserSelfie;
use App\Models\PlatformConfig;
use App\Repositories\MemberUserRateRepository;
use Illuminate\Http\Request;

/**
 * 会员费率
 * Class LoginsController
 * @package App\Http\Controllers\System\Member
 */
class RateController extends Controller
{
    public function __construct(MemberUserRateRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 页面首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('system.member.user.rate.index');
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
     * 创建
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('system.member.user.rate.create');
    }

    /**
     * 新增
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
     * 编辑
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        try {
            $data = MemberUserRate::firstOrNew(['member_id' => $request->id]);
            return view('system.member.user.rate.edit')->with('data', $data);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 修改
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


    /**
     * 删除
     * @param Request $request
     * @return array|mixed
     */
    public function destroy(Request $request)
    {
        try {
            $result = $this->repository->destroy($request->ids);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

}

