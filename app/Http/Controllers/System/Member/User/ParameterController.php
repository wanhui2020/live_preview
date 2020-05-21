<?php

namespace App\Http\Controllers\System\Member\User;

use App\Http\Controllers\Controller;
use App\Models\MemberUserParameter;
use App\Models\MemberUser;
use App\Models\MemberUserRealname;
use App\Repositories\MemberUserParameterRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 会员参数
 * Class LoginsController
 * @package App\Http\Controllers\System\Member
 */
class ParameterController extends Controller
{
    public function __construct(MemberUserParameterRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 页面首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('system.member.user.parameter.index');
    }

    /**
     * 数据列表
     * @return array
     */
    public function lists()
    {
        try {
            $list = $this->repository->with(['member:id,nick_name,mobile,no'])->lists();
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
        return view('system.member.user.parameter.create');
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
     * 编辑
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        try {
            $data = MemberUserParameter::firstOrNew(['member_id' => $request->id]);
            return view('system.member.user.parameter.edit')->with('data', $data);
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
            unset($data['file']);
            $parameter = $this->repository->update($data);
            return $parameter;
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

