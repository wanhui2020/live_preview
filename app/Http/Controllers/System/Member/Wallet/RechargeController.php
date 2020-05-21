<?php

namespace App\Http\Controllers\System\Member\Wallet;


use App\Facades\PayFacade;
use App\Http\Controllers\Controller;
use App\Models\MemberWalletRecharge;
use App\Repositories\FinanceRechargeRepository;
use App\Repositories\MemberWalletRechargeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class RechargeController extends Controller
{
    public function __construct(MemberWalletRechargeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('system.member.wallet.recharge.index');
    }

    /**
     * @return array
     */
    public function lists()
    {
        try {
            $lists = $this->repository->with(['payment', 'member', 'audit'])->lists();
            return $this->paginate($lists);
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('system.member.wallet.recharge.create');
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
                return $this->failure(1, '添加失败', $result);
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
            return view('system.member.wallet.recharge.edit', compact('data'));
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    public function update(Request $request)
    {
        try {
            $data = $request->all();
            $result = $this->repository->update($data);
            return $result;
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
            $recharge = $this->repository->find($request->id);
            if (!isset($recharge)) {
                return $this->validation('充值记录不存在');
            }
            $url = URL::temporarySignedRoute('pay', now()->addMicros(5), ['no' => $recharge->no]);
            return $this->succeed($url);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 审核
     */
    public function audit(Request $request)
    {
        try {
            $data = $request->all();
            $data['status'] = 0;
            $result = $this->repository->audit($data);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

}
