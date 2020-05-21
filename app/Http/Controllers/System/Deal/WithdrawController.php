<?php

namespace App\Http\Controllers\System\Deal;

use App\Facades\PlatformFacade;
use App\Http\Controllers\Controller;
use App\Repositories\DealWithdrawRepository;
use Illuminate\Http\Request;

/**
 * 会员提现记录
 * Class WithdrawrecordsController
 * @package App\Http\Controllers\System\Deal
 */
class WithdrawController extends Controller
{
    public function __construct(DealWithdrawRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('system.deal.withdraw.index');
    }

    /**
     * @return array
     */
    public function lists()
    {
        try {
            $lists = $this->repository->with(['member', 'audit','withdraw'])->lists();
            return $this->paginate($lists);
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }


    public function create(Request $request)
    {
        return view('system.deal.withdraw.create');

    }

    /**
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            if (!$request->filled('member_id')) {
                return $this->validation('申请人不能为空');
            }
            if (!$request->filled('money')) {
                return $this->validation('申请提现金额不能为空');
            }

            if (!is_numeric($request->money)) {
                return $this->validation('申请提现金额格式错误');
            }
            if ($request->money < PlatformFacade::config('withdraw_min')) {
                return $this->validation('申请提现金额最小不能低于：' . PlatformFacade::config('withdraw_min'));
            }

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
            return view('system.deal.withdraw.edit', compact('data'));
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
            return view('system.deal.withdraw.detail', compact('data'));
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
     * 取消
     */
    public function cancel(Request $request)
    {
        try {
            $data = $request->all();
            $data['status'] = 2;
            $result = $this->repository->cancel($data);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }
}
