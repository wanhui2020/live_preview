<?php

namespace App\Http\Controllers\System\Member;

use App\Http\Controllers\Controller;
use App\Repositories\MemberReportRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 会员举报
 * Class LoginsController
 * @package App\Http\Controllers\System\Member
 */
class ReportController extends Controller
{
    public function __construct(MemberReportRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 页面首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('system.member.report.index');
    }

    /**
     * 数据列表
     * @return array
     */
    public function lists()
    {
        try {
            $list = $this->repository->with(['member:id,nick_name,no,mobile', 'tomember:id,nick_name,no',  'audit:id,name','report:id,value'])->lists();
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
        return view('system.member.report.create');
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
     * 修改
     * @param Request $request
     * @return array|mixed
     */
    public function info(Request $request)
    {
        $report = Auth::guard('SystemUser')->user();
        return view('system.member.report.info')->with('report', $report);
    }

    /**
     * 编辑
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        try {
            $report = $this->repository->find($request->id);
            return view('system.member.report.edit')->with('report', $report);
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
            $systemuser = $request->user('SystemUser');
            if (!isset($systemuser)) {
                return $this->validation('请进行后台登录');
            }
            $data['status'] = 1;
            $data['audit_uid'] = $systemuser->id;
            $data['audit_time'] = Carbon::now()->toDateTimeString();
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
            $result = $this->repository->forceDelete($request->ids);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

}

