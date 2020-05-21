<?php

namespace App\Http\Controllers\Api\Member\Deal;


use App\Http\Controllers\Controller;
use App\Http\Resources\DealGoldResource;
use App\Repositories\DealGoldRepository;
use Illuminate\Http\Request;

/**
 * 能量充值
 * Class PayController
 */
class GoldController extends Controller
{
    /**.
     * 能量充值
     * price_id
     */
    public function store(Request $request, DealGoldRepository $goldRepository)
    {
        try {
            $member = $request->user('ApiMember');


            if (!$request->filled('price_id')) {
                return $this->validation('请传入价格id');
            }
            if (!$request->filled('channel_id')) {
                return $this->validation('请传入支付方式');
            }
            $member_id = $member->id;
            $price_id = $request->price_id; //产品id
            $channel_id = $request->channel_id; //产品id
            $data = [
                'member_id' => $member_id,
                'price_id' => $price_id,
                'channel_id' => $channel_id,
            ];
            $resp = $goldRepository->store($data);
            if ($resp['status']) {
                return $this->succeed(new DealGoldResource($resp['data']), '能量充值成功');
            } else {
                return $this->validation($resp['msg']);
            }
        } catch (\Exception $ex) {
            return $this->exception($ex, '能量充值异常，请联系管理员');
        }
    }
}

