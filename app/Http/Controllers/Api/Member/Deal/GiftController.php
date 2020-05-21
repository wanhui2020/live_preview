<?php

namespace App\Http\Controllers\Api\Member\Deal;


use App\Http\Controllers\Controller;
use App\Http\Resources\MemberGiftResource;
use App\Models\DealGift;
use App\Models\MemberUser;
use App\Repositories\DealGiftRepository;
use Illuminate\Http\Request;

/**
 * 礼物赠送
 * Class TalkController
 */
class GiftController extends Controller
{
    /**
     * 赠送礼物
     * 赠送会员
     * 接收会员
     * 礼物
     */

    public function store(Request $request, DealGiftRepository $giftRepository)
    {
        try {
            $member = $request->user('ApiMember');

            if (!$request->filled('to_member_id') && !$request->filled('to_member_no')) {
                return $this->validation('请传入接收会员');
            }

            if (!$request->filled('gift_id')) {
                return $this->validation('请传入礼物id');
            }
            if (!$request->filled('quantity')) {
                return $this->validation('请传入礼物数量');
            }
//            if (!$request->filled('relevance_type')) {
//                return $this->validation('请传入礼物赠送场景');
//            }
//            if (!$request->filled('relevance_id')) {
//                return $this->validation('请传入礼物赠送编号');
//            }


            $member_id = $member->id; //赠送会员id

            if ($request->filled('to_member_no')) {
                $member = MemberUser::where('no', $request->to_member_no)->first();
                if (!isset($member)) {
                    return $this->validation('接收会员不存在');
                }
                if ($member->is_selfie != 0) {
                    return $this->validation('未认证不能接受礼物！');
                }

                $to_member_id = $member->id; //接收会员id
            } else {
                $to_member_id = $request->to_member_id; //接收会员id
            }

            $gift_id = $request->gift_id; //礼物id
            $quantity = $request->quantity; //数量
            $relevance_type = $request->relevance_type;
            $relevance_id = $request->relevance_id;
            $data = [
                'member_id' => $member_id,
                'to_member_id' => $to_member_id,
                'relevance_type' => $relevance_type,
                'relevance_id' => $relevance_id,
                'gift_id' => $gift_id,
                'quantity' => $quantity,
            ];
            $dealgift = $giftRepository->store($data);
            if ($dealgift['status']) {
                return $this->succeed(null, '赠送礼物成功');
            } else {
                return $this->validation($dealgift['msg']);
            }
        } catch (\Exception $ex) {
            return $this->exception($ex, '赠送礼物异常，请联系管理员');
        }
    }

    public function lists(Request $request, DealGiftRepository $giftRepository)
    {
        try {
            $member = $request->user('ApiMember');
            $sql="any_value(id) as id,any_value(name) as name,any_value(no) as no,any_value(price) as price,any_value(gift_id) as gift_id,count(quantity) as quantity,max(created_at) as created_at";
            $list = $giftRepository->select(\DB::raw($sql))->where(function ($query) use ($request, $member) {
                if ($request->filled('member_id')) {
                    $query->where('to_member_id', $request->member_id);
                } else {
                    $query->where('to_member_id', $member->id);
                }
            })->groupBy('gift_id')->paginate();
            return $this->succeed(MemberGiftResource::collection($list), '礼物获取成功!');

        } catch (\Exception $ex) {
            return $this->exception($ex, '礼物获取异常，请联系管理员');
        }
    }
}

