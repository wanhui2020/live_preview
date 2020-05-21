<?php

namespace App\Http\Controllers\System\Platform;

use App\Http\Controllers\Controller;
use App\Http\Repositories\PlatformPaymentsRepository;
use App\Repositories\PlatformPaymentChannelRepository;
use App\Repositories\PlatformPaymentRepository;
use Illuminate\Http\Request;

/**
 * 支付通道维护
 * Class PaymentChannelController
 * @package App\Http\Controllers\System\Platform
 */
class PaymentChannelController extends Controller
{
    public function __construct(PlatformPaymentChannelRepository $repository)
    {
        $this->repository = $repository;
    }

    /*
     *显示列表
     * */
    public function index()
    {
        return view('system.platform.payment.channel.index');
    }

    /*
    *显示列表
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
            $data= $this->repository->find($request->id);
            if ($data) {
                $data->status = $data->status == 1 ? 0 : 1;
                if ($data->save()) {
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
     * 添加站内通信渲染视图
     * */
    public function create()
    {
        return view('system.platform.payment.channel.create');
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
            $channel = $this->repository->find($request->id);
            return view('system.platform.payment.channel.edit')->with('data', $channel);
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
