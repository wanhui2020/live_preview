<?php

namespace App\Http\Controllers\System\Base;

use App\Http\Controllers\Controller;
use App\Models\SystemUser;
use App\Repositories\SystemUserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function __construct(SystemUserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 页面首页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('system.base.user.index');
    }

    /**
     * 数据列表
     *
     * @return array
     */
    public function lists()
    {
        try {
            $list = $this->repository->lists();

            return $this->paginate($list);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 禁用和启用
     *
     * @param  Request  $request
     *
     * @return array|mixed
     */
    public function status(Request $request)
    {
        try {
            $list   = $this->repository->find($request->id);
            $status = $list['status'] == 1 ? 0 : 1;
            $result = $this->repository->update([
                'id'     => $request->id,
                'status' => $status,
            ]);

            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 创建系统用户
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('system.base.user.create');
    }

    /**
     * 新增系统用户
     *
     * @param  Request  $request
     *
     * @return array
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            if ($data['type'] == null) {
                $data['type'] = 0;
            }
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
     *
     * @param  Request  $request
     *
     * @return array|mixed
     */
    public function info(Request $request)
    {
        $user = Auth::guard('SystemUser')->user();

        return view('system.base.user.info')->with('user', $user);
    }

    /**
     * 编辑系统用户
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        try {
            $user = $this->repository->find($request->id);

            return view('system.base.user.edit')->with('user', $user);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 修改系统用户
     *
     * @param  Request  $request
     *
     * @return array|mixed
     */
    public function update(Request $request)
    {
        try {
            $data   = $request->all();
            $result = $this->repository->update($data);

            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 删除系统用户
     *
     * @param  Request  $request
     *
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
     * token单点登录
     */
    public function login(Request $request)
    {
        try {
            $user = SystemUser::find($request->id);
            if (!isset($user)) {
                return $this->validation('未找到相关用户');
            }
            Auth::guard('SystemUser')->login($user);

            return response()->redirectTo('/system');
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }


    public function showAssignRoleForm($id)
    {
        $roles = Role::orderBy('name')->get();
        $user  = SystemUser::find($id);

        return view('system.base.user.assign_role',
            compact('user', 'roles')
        );
    }

    public function assignRole(Request $request)
    {
        $roles = [];
        foreach ($request->all() as $name => $value) {
            if (substr($name, 0, 6) === 'roles[') {
                $roles[] = $value;
            }
        }

        $user = SystemUser::find($request->get('id'));
        $user->syncRoles($roles);

        return $this->succeed();
    }
}

