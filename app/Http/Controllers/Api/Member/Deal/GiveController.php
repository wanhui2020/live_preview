<?php

namespace App\Http\Controllers\Api\Member\Deal;


use App\Http\Controllers\Controller;
use App\Repositories\DealGiveRepository;
use Illuminate\Http\Request;

/**
 * 会员打赏
 * Class TalkController
 */
class GiveController extends Controller
{
    /**
     * 会员打赏
     * 赠送会员
     * 接收会员
     * 打赏金额
     */

    public function store(Request $request, DealGiveRepository $giveRepository)
    {
        try {
            $member = $request->user('ApiMember');

            $member_id = $member->id; //赠送会员id
            $to_member_id = $request->to_member_id; //接收会员id
            $money = $request->money; //打赏金额
            if ($request->filled($to_member_id)) {
                return $this->validation('请传入接收会员id');
            }
            if ($request->filled($money)) {
                return $this->validation('请传入打赏金额');
            }
            $data = [
                'member_id' => $member_id,
                'to_member_id' => $to_member_id,
                'money' => $money,
            ];
            $dealgift = $giveRepository->store($data);
            if ($dealgift['status']) {
                return $this->succeed(null, '会员打赏成功');
            } else {
                return $this->validation($dealgift['msg']);
            }
        } catch (\Exception $ex) {
            return $this->exception($ex, '会员打赏异常，请联系管理员');
        }
    }

}

