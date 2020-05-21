<?php

namespace App\Http\Controllers\System\Member\User;

use App\Facades\PushFacade;
use App\Http\Controllers\Controller;
use App\Models\MemberUser;
use App\Models\MemberUserRealname;
use App\Models\MemberUserSelfie;
use App\Repositories\MemberUserRealnameRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 *
 * 实名认证
 * Class RealnameController
 * @package App\Http\Controllers\System\Member
 */
class RealnameController extends Controller
{
    public function __construct(MemberUserRealnameRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 页面首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('system.member.user.realname.index');
    }

    /**
     * 数据列表
     * @return array
     */
    public function lists(Request $request)
    {
        try {
            if ($request->way == 1) { //排除已经实名认证的人
                $list = $this->repository->with(['member:id,nick_name,no,mobile' => function ($query) {
                    $query->where('is_real', 1);
                }, 'audit:id,name'])->lists();
                return $this->paginate($list);
            } else {
                $list = $this->repository->with(['member:id,nick_name,no,mobile', 'audit:id,name'])->lists();
                return $this->paginate($list);
            }
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
        return view('system.member.user.realname.create');
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
            unset($data['file']);
            if (!isset($data['member_id'])) {
                return $this->validation('请选择所属会员!');
            }
            $memberuser = MemberUser::find($data['member_id']);
            if (!isset($memberuser)) {
                return $this->validation('未知会员!');
            }
            $memberuser->is_real = 1;
            $memberuser->save();


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
            if ($request->filled('memberId')) {
                $data = MemberUserRealname::firstOrCreate(['member_id' => $request->memberId]);
            } else {
                $data = MemberUserRealname::find($request->id);
            }
            if (!isset($data)) {
                return $this->validation('实名信息不存在');
            }
            return view('system.member.user.realname.edit', compact('data'));
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
            $realname = $this->repository->find($request->id);
            $realname->status=$data['status'];
            $realname->save();
            $user = MemberUser::where(['id' => $realname['member_id']])->first();
            switch ($realname['status']) {
                case 0:
                    $status = '实名认证审核通过';
                    break;
                case 1:
                    $status = '实名认证审核拒绝';
                    break;
                default :
                    $status = '实名认证待审核';
                    break;
            }
            if ($user->push_token) {
                PushFacade::pushToken($user->push_token, $user->app_platform, $user->nick_name, $status, $type = 'NOTICE', ['type' => 'member', 'id' => $user->id, 'no' => $user->no, 'nickname' => $user->nick_name]);
            }

            return $this->succeed($realname);
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

    /**
     * 审核
     * @param Request $request
     * @return array
     */
    public function audit(Request $request)
    {
        $data = $request->all();
        return $this->repository->audit($data, $request);
    }
}

