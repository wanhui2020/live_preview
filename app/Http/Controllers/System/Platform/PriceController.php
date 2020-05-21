<?php

namespace App\Http\Controllers\System\Platform;

use App\Http\Controllers\Controller;
use App\Http\Repositories\PlatformPaymentsRepository;
use App\Models\PlatformPrice;
use App\Repositories\PlatformPaymentRepository;
use App\Repositories\PlatformPriceRepository;
use Illuminate\Http\Request;

//充值价格维护
class PriceController extends Controller
{
    public function __construct(PlatformPriceRepository $repository)
    {
        $this->repository = $repository;
    }

    /*
     * 显示列表
     * */
    public function index()
    {
        return view('system.platform.price.index');
    }

    /*
    * 显示列表
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
     * 添加渲染视图
     * */
    public function create()
    {
        return view('system.platform.price.create');
    }

    /*
    * 添加到数据库
    * */
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

    /*
     * 渲染修改界面
     * */
    public function edit(Request $request)
    {
        try {
            $cons = $this->repository->find($request->id);
            return view('system.platform.price.edit')->with('cons', $cons);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /*
    * 修改数据到数据库
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
   * 改变状态
   * */
    public function status(Request $request)
    {
        try {
            $contract = PlatformPrice::findOrFail($request->id);
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
