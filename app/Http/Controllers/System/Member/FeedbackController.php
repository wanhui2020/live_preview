<?php

namespace App\Http\Controllers\System\Member;

use App\Http\Controllers\Controller;
use App\Repositories\MemberFeedbackRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * 意见反馈
 * Class LoginsController
 * @package App\Http\Controllers\System\Member
 */
class FeedbackController extends Controller
{
    public function __construct(MemberFeedbackRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 页面首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('system.member.feedback.index');
    }

    /**
     * 数据列表
     * @return array
     */
    public function lists()
    {
        try {
            $list = $this->repository->with(['member:id,nick_name,no,mobile', 'audit:id,name'])->lists();
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
        return view('system.member.feedback.create');
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
     * 回复
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        try {
            $feedback = $this->repository->find($request->id);
            return view('system.member.feedback.edit')->with('feedback', $feedback);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }
    //回复
    public function update(Request $request)
    {
        try {
            $data = $request->all();
            $data['replay_status'] = 1;
            $systemuser = $request->user('SystemUser');
            if (!isset($systemuser)){
                return $this->validation('请进行后台登录');
            }
            $data['replay_uid'] = $systemuser->id;
            $data['replay_time'] = Carbon::now()->toDateTimeString();
            $result = $this->repository->update($data);
            return $result;
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

