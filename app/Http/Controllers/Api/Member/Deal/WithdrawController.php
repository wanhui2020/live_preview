<?php

namespace App\Http\Controllers\Api\Member\Deal;


use App\Http\Controllers\Controller;
use App\Http\Resources\DealLikeResource;
use App\Http\Resources\DealWithdrawResource;
use App\Http\Resources\DellWithdrawApplyResource;
use App\Repositories\DealLikeRepository;
use App\Repositories\DealWithdrawRepository;
use App\Repositories\MemberWalletCashRepository;
use Illuminate\Http\Request;

/**
 * 金币提现
 * Class PayController
 */
class WithdrawController extends Controller
{

    /**
     * 提现明细
     */
    public function lists(Request $request, DealWithdrawRepository $repository)
    {
        try {
            $member = $request->user('ApiMember');
            $list = $repository->with(['member'])->where(function ($query) use ($member) {
                $query->where('status', 0);
                $query->where('member_id', $member->id);
            })->paginate();
            return $this->succeed(DealWithdrawResource::collection($list), '获取提现明细成功');

        } catch (\Exception $ex) {
            return $this->exception($ex, '提现明细获取异常，请联系管理员');
        }
    }


    /**
     *  提现申请
     */
    public function apply(Request $request)
    {
        try {
            $member = $request->user('ApiMember');
            if (empty($member->capital_password)) {
                return $this->validation('交易密码未设置');
            }
            $cash = $member->cash;
            if ($cash->drawing <= 0) {
                return $this->validation('无可提现金额');
            }

            $data['money'] = $cash->drawing;
            $withdraw = $member->withdraws()->where('status', 0)->first();
            if (isset($withdraw)) {
                $data['username'] = $withdraw['username'];
                $data['bank_account'] = $withdraw['bank_account'];
                $data['bank_name'] = $withdraw['bank_name'];
            }
            return $this->succeed(new DellWithdrawApplyResource($data), '提现申请成功');
        } catch (\Exception $ex) {
            return $this->exception($ex, '我的钱包获取异常，请联系管理员');
        }
    }

    /**.
     * 金币提现
     * 提现金额
     */
    public function store(Request $request, DealWithdrawRepository $withdrawRepository)
    {
        try {
            $member = $request->user('ApiMember');
            if ($member->is_real != 0) {
                return $this->validation('提现请先进行实名');
            }
            if (!$request->filled('money')) {
                return $this->validation('请传入提现金额');
            }
            if (!$request->filled('password')) {
                return $this->validation('交易密码不能为空');
            }
            if ($member->capital_password != md5($request->password)) {
                return $this->validation('交易密码错误');
            }
            if (!$request->filled('username')) {
                return $this->validation('账户名不能为空');
            }

            if (!$request->filled('bank_account')) {
                return $this->validation('银行账号不能为空');
            }

            if (!$request->filled('bank_name')) {
                return $this->validation('所属银行不能为空');
            }

            $data = [
                'member_id' => $member->id,
                'money' => $request->money,
                'username' => $request->username,
                'bank_account' => $request->bank_account,
                'bank_name' => $request->bank_name,
            ];
            $dealvip = $withdrawRepository->store($data);
            if ($dealvip['status']) {
                return $this->succeed(null, '金币提现成功');
            } else {
                return $this->validation($dealvip['msg']);
            }
        } catch (\Exception $ex) {
            return $this->exception($ex, '余额提现异常，请联系管理员');
        }
    }
}

