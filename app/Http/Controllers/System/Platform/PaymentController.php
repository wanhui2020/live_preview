<?php

namespace App\Http\Controllers\System\Platform;

use App\Http\Controllers\Controller;
use App\Http\Repositories\PlatformPaymentsRepository;
use App\Repositories\PlatformPaymentRepository;
use Illuminate\Http\Request;

//通道管理
class PaymentController extends Controller
{
    public function __construct(PlatformPaymentRepository $repository)
    {
        $this->repository = $repository;
    }

    /*
     * 显示列表
     * */
    public function index()
    {
        return view('system.platform.payment.index');
    }

    /*
    * 显示列表
    * */
    public function lists()
    {
        try {
            $list = $this->repository->with(['channel'])->lists();
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
            $payment= $this->repository->find($request->id);
            if ($payment) {
                $payment->status = $payment->status == 1 ? 0 : 1;
                if ($payment->save()) {
                    return $this->succeed('操作成功');
                } else {
                    return $this->validation('操作失败');
                }
            } else {
                return $this->validation('数据不存在');
            }
        } catch (\Exception $ex) {
            return $this->validation('操作失败', $ex);
        }
    }

    /*
     * 添加渲染视图
     * */
    public function create()
    {
        return view('system.platform.payment.create');
    }

    /*
    * 添加到数据库
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
     * 渲染修改界面
     * */
    public function edit(Request $request)
    {
        try {
            $payment= $this->repository->find($request->id);
            return view('system.platform.payment.edit')->with('data', $payment);
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
