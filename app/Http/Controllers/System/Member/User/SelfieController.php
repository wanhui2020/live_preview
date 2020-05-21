<?php

namespace App\Http\Controllers\System\Member\User;

use App\Facades\PushFacade;
use App\Http\Controllers\Controller;
use App\Models\MemberUser;
use App\Models\MemberUserParameter;
use App\Models\MemberUserSelfie;
use App\Repositories\MemberSelfieRepository;
use App\Repositories\MemberUserSelfieRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 *
 * 自拍认证
 * Class RealnameController
 * @package App\Http\Controllers\System\Member
 */
class SelfieController extends Controller
{
    public function __construct(MemberUserSelfieRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 页面首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('system.member.user.selfie.index');
    }

    /**
     * 数据列表
     * @return array
     */
    public function lists()
    {
        try {
            $list = $this->repository->with(['member:id,nick_name,no', 'audit:id,name'])->lists();
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
        return view('system.member.user.selfie.create');
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
                $data = MemberUserSelfie::firstOrCreate(['member_id' => $request->memberId]);
            } else {
                $data = MemberUserSelfie::find($request->id);
            }
            if (!isset($data)) {
                return $this->validation('认证信息不存在');
            }
            return view('system.member.user.selfie.edit', compact('data'));
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
            $selfie = $this->repository->find($request->id);
            $selfie->status=$data['status'];
            $selfie->save();
            $user = MemberUser::where(['id' => $selfie['member_id']])->first();
            switch ($selfie['status']) {
                case 0:
                    $status = '自拍认证通过';
                    break;
                case 1:
                    $status = '自拍认证拒绝';
                    break;
                case 8:
                    $status = '自拍认证审核中';
                    break;
                default :
                    $status = '自拍认证待审核';
                    break;
            }
            if ($user->push_token) {
                PushFacade::pushToken($user->push_token, $user->app_platform, $user->nick_name, $status, $type = 'NOTICE', ['type' => 'member', 'id' => $user->id, 'no' => $user->no, 'nickname' => $user->nick_name]);
            }
            return $this->succeed($selfie);
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
     * 自拍审核
     * @param Request $request
     * @return array
     */
    public function audit(Request $request)
    {
        $data = $request->all();
        unset($data['file']);
        return $this->repository->audit($data);
    }
}

