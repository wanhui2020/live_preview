<?php

namespace App\Http\Controllers\System\Deal;

use App\Http\Controllers\Controller;
use App\Repositories\DealVipRepository;
use Illuminate\Http\Request;

/**
 * VIP 购买记录
 * Class ViprecordsController
 * @package App\Http\Controllers\System\Deal
 */
class VipController extends Controller
{
    public function __construct(DealVipRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('system.deal.vip.index');
    }

    /**
     * @return array
     */
    public function lists()
    {
        try {
            $lists = $this->repository->with(['member','vip','recharge'])->lists();
            return $this->paginate($lists);
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }


    public function create(Request $request)
    {
        return view('system.deal.vip.create');

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
                return $this->succeed(null, '添加成功');
            } else {
                return $this->failure(1, $result['msg'], $result);
            }
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }

    /**
     */
    public function edit(Request $request)
    {
        try {
            $data = $this->repository->find($request->id);
            return view('system.deal.vip.edit', compact('data'));
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

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

    /**
     */
    public function detail(Request $request)
    {
        try {
            $data = $this->repository->find($request->id);
            return view('system.deal.vip.detail', compact('data'));
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
     * 支付
     */
    public function pay(Request $request)
    {
        try {
            $data = $request->all();
            $result = $this->repository->pay($data);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 审核
     */
    public function cancel(Request $request)
    {
        try {
            $data = $request->all();
            $data['status']=2;
            $result = $this->repository->cancel($data);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }
}
