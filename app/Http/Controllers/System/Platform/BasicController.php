<?php

namespace App\Http\Controllers\System\Platform;

use App\Http\Controllers\Controller;
use App\Http\Repositories\PlatformBasicRepository;
use App\Models\PlatformBasic;
use Illuminate\Http\Request;
//举报管理
class BasicController extends Controller
{
    public function __construct(PlatformBasicRepository $repository)
    {
        $this->repository = $repository;
    }

    /*
     * 站内通信显示列表
     * */
    public function index()
    {
        return view('system.platform.basic.index');
    }

    /*
    * 站内通信显示列表
    * */
    public function lists(Request $request)
    {
        try {
            if ($request->way == 1){
                $list = $this->repository->lists(function ($query){
                    $query->where('type',7);
                });
                return $this->paginate($list);
            }
            $list = $this->repository->lists();
            return $this->paginate($list);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /*
    * 站内通信改变状态
    * */
    public function status(Request $request)
    {
        try {
            $contract = PlatformBasic::findOrFail($request->id);
            if ($contract) {
                $contract->status = $contract->status == 1 ? 0 : 1;
                if ($contract->save()) {
                    return $this->succeed('操作成功');
                } else {
                    return $this->validation('操作失败');
                }
            } else {
                return $this->validation('用户不存在');
            }
        } catch (\Exception $ex) {
            return $this->validation('操作失败', $ex);
        }
    }

    /*
     * 添加站内通信渲染视图
     * */
    public function create()
    {
        return view('system.platform.basic.create');
    }

    /*
    * 添加站内通信到数据库
    * */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $data['status'] = 0;
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

    /*
     * 渲染站内通信修改界面
     * */
    public function edit(Request $request)
    {
        try {
            $cons = $this->repository->find($request->id);
            return view('system.platform.basic.edit')->with('cons', $cons);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /*
    * 修改站内通信数据到数据库
    * */
    public function update(Request $request)
    {
        try {
            $data = $request->all();
            unset($data['file']);
            $result = $this->repository->update($data);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /*
     * */
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
