<?php

namespace App\Http\Controllers\System\Member;

use App\Http\Controllers\Controller;
use App\Repositories\MemberVisitorRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 访问记录
 * Class LoginsController
 * @package App\Http\Controllers\System\Member
 */
class VisitorController extends Controller
{
    public function __construct(MemberVisitorRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * 页面首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('system.member.visitor.index');
    }

    /**
     * 数据列表
     * @return array
     */
    public function lists()
    {
        try {
            $list = $this->repository->with(['member:id,nick_name,no','tomember:id,nick_name,no'])->lists();
            return $this->paginate($list);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 禁用和启用
     * @param Request $request
     * @return array|mixed
     */
    public function status(Request $request)
    {
        try {
            $list = $this->repository->find($request->id);
            $status = $list['status'] == 1 ? 0 : 1;
            $result = $this->repository->update(['id' => $request->id, 'status' => $status]);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 创建系统用户
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('system.member.visitor.create');
    }

    /**
     * 新增系统用户
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
     * 修改系统用户
     * @param Request $request
     * @return array|mixed
     */
    public function info(Request $request)
    {
        $visitor = Auth::guard('SystemUser')->user();
        return view('system.member.visitor.info')->with('visitor', $visitor);
    }

    /**
     * 编辑系统用户
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        try {
            $visitor = $this->repository->find($request->id);
            return view('system.member.visitor.edit')->with('visitor', $visitor);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
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

    /**
     * 删除系统用户
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

