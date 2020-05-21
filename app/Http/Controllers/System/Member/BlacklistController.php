<?php

namespace App\Http\Controllers\System\Member;

use App\Http\Controllers\Controller;
use App\Repositories\MemberBlacklistRepository;
use Illuminate\Http\Request;

/**
 * 会员拉黑
 * Class LoginsController
 * @package App\Http\Controllers\System\Member
 */
class BlacklistController extends Controller
{
    public function __construct(MemberBlacklistRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 页面首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('system.member.blacklist.index');
    }

    /**
     * 数据列表
     * @return array
     */
    public function lists()
    {
        try {
            $list = $this->repository->with(['member:id,nick_name,no,mobile', 'tomember:id,nick_name,no'])->lists();
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
        return view('system.member.blacklist.create');
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
     * @param Request $request
     * @return array|mixed
     */
    public function destroy(Request $request)
    {
        try {
            $result = $this->repository->forceDelete($request->ids);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }
}

