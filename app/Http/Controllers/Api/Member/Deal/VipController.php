<?php

namespace App\Http\Controllers\Api\Member\Deal;


use App\Http\Controllers\Controller;
use App\Repositories\DealVipRepository;
use Illuminate\Http\Request;

/**
 * VIP购买
 * Class PayController
 */
class VipController extends Controller
{
    /**.
     * VIP购买
     * vip产品id
     */
    public function store(Request $request, DealVipRepository $vipRepository)
    {
        try {
            $member = $request->user('ApiMember');

            $member_id = $member->id; //解锁会员id
            $vip_id = $request->vip_id; //vip产品id
            if ($request->filled($vip_id)) {
                return $this->validation('请传入vip产品id');
            }
            $data = [
                'member_id' => $member_id,
                'vip_id' => $vip_id,
            ];
            $dealvip = $vipRepository->store($data);
            if ($dealvip['status']) {
                return $this->succeed(null, 'VIP购买成功');
            } else {
                return $this->validation($dealvip['msg']);
            }
        } catch (\Exception $ex) {
            return $this->exception($ex, 'VIP购买异常，请联系管理员');
        }
    }
}

