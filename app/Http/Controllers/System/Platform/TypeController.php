<?php

namespace App\Http\Controllers\System\Platform;

use App\Http\Controllers\Controller;
use App\Models\PlatformCondition;
use App\Models\PlatformNotice;
use App\Models\PlatformType;
use App\Repositories\PlatformTypeRepository;
use Illuminate\Http\Request;

//首页分类
class TypeController extends Controller
{
    public function __construct(PlatformTypeRepository $repository)
    {
        $this->repository = $repository;
    }

    /*
     * 列表
     * */
    public function index()
    {
        return view('system.platform.type.index');
    }

    /*
    * 站内通信显示列表
    * */
    public function lists()
    {
        try {
            $list = $this->repository->lists();
            return $this->paginate($list);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /*
    * 改变状态
    * */
    public function status(Request $request)
    {
        try {
            $contract = PlatformType::findOrFail($request->id);
            if ($contract) {
                $contract->status = $contract->status == 1 ? 0 : 1;
                if ($contract->save()) {
                    return $this->succeed('操作成功');
                } else {
                    return $this->validation('操作失败');
                }
            } else {
                return $this->validation('信息不存在');
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
        $condition = PlatformCondition::where('status',0)->get();
        return view('system.platform.type.create',compact('condition'));
    }

    /*
    * 添加站内通信到数据库
    * */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $data['status'] = 0;
            if (empty($data['condition_id'])){
                unset($data['condition_id']);
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

    /*
     * 渲染站内通信修改界面
     * */
    public function edit(Request $request)
    {
        try {
            $cons = $this->repository->find($request->id);
            $condition = PlatformCondition::where('status',0)->get();
            if ($cons['condition_id']) {
                $cons['condition_id'] = explode(',', $cons['condition_id']);
            }
            return view('system.platform.type.edit',compact('cons','condition'));
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
            if (empty($data['condition_id'])){
                unset($data['condition_id']);
            }
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
            $result = $this->repository->destroy($request->ids);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }
}
