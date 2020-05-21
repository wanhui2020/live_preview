<?php

namespace App\Http\Controllers\Api\Member\Deal;


use App\Http\Controllers\Controller;
use App\Repositories\DealCashRepository;
use Illuminate\Http\Request;

/**
 * 余额充值
 * Class PayController
 */
class CashController extends Controller
{
    /**.
     * 余额充值
     *
     */
    public function store(Request $request, DealCashRepository $cashRepository)
    {
        try {
            $member = $request->user('ApiMember');

            $member_id = $member->id; //解锁会员id
            $money = $request->money; //充值金额
            if ($request->filled($money)) {
                return $this->validation('请传入充值金额');
            }
            $data = [
                'member_id' => $member_id,
                'money' => $money,
            ];
            $dealvip = $cashRepository->store($data);
            if ($dealvip['status']) {
                return $this->succeed(null, '余额充值成功');
            } else {
                return $this->validation($dealvip['msg']);
            }
        } catch (\Exception $ex) {
            return $this->exception($ex, '余额充值异常，请联系管理员');
        }
    }
}

