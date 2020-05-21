<?php

namespace App\Http\Controllers\Api\Member\Deal;


use App\Http\Controllers\Controller;
use App\Repositories\DealConversionRepository;
use Illuminate\Http\Request;

/**
 * 兑换余额
 * Class PayController
 */
class ConversionController extends Controller
{
    /**.
     * 兑换余额
     * 金币数量
     */
    public function store(Request $request, DealConversionRepository $conversionRepository)
    {
        try {
            $member = $request->user('ApiMember');

            $member_id = $member->id; //解锁会员id
            $gold = $request->gold; //能量数量
            if ($request->filled($gold)) {
                return $this->validation('请传入能量数量');
            }
            $data = [
                'member_id' => $member_id,
                'gold' => $gold,
            ];
            $dealvip = $conversionRepository->store($data);
            if ($dealvip['status']) {
                return $this->succeed(null, '兑换余额成功');
            } else {
                return $this->validation($dealvip['msg']);
            }
        } catch (\Exception $ex) {
            return $this->exception($ex, '兑换余额异常，请联系管理员');
        }
    }
}

