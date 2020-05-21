<?php

namespace App\Http\Controllers\Api\Member\Behavior;


use App\Http\Controllers\Controller;
use App\Http\Resources\MemberAttentionResource;
use App\Http\Resources\MemberUserListResource;
use App\Models\MemberAttention;
use App\Models\MemberUser;
use App\Repositories\MemberAttentionRepository;
use Illuminate\Http\Request;

/**
 * 关注
 * Class PayController
 */
class AttentionController extends Controller
{


    /**
     * 我的关注列表
     */
    public function lists(Request $request, MemberAttentionRepository $attentionRepository)
    {
        try {
            $member = $request->user('ApiMember');
            if ($request->filled('way') && $request->way == 1) {
                $list = $member->toAttentions()->wherePivot('status', 0)->paginate();
            } else {
                $list = $member->formAttentions()->wherePivot('status', 0)->paginate();
            }
            return $this->succeed(MemberUserListResource::collection($list), '获取我的关注成功');

        } catch (\Exception $ex) {
            return $this->exception($ex, '我的关注获取异常，请联系管理员');
        }
    }


    /**
     * 关注他人
     */
    public function store(Request $request, MemberAttentionRepository $attentionRepository)
    {
        try {
            $member = $request->user('ApiMember');
            $user = MemberUser::where(['id' => $request->id])->first();
            if (!isset($user)){
                return $this->validation('该用户不存在!');
            }
            if (!$request->filled('id')) {
                return $this->validation('缺少被关注的人id!');
            }
            if ($member->id == $request->id) {
                return $this->validation('不能关注自己');
            }
            $toMember = MemberUser::find($request->id);
            if (!isset($toMember)) {
                return $this->validation('你所关注的用户不存在!');
            }
            if ($toMember->id == $member->id) {
                return $this->succeed($toMember, '关注成功！');
            }
            $attentiont = MemberAttention::where('member_id', $member->id)->where('to_member_id', $toMember->id)->first();
            if (!isset($attentiont)) {
                //初次关注
                MemberAttention::firstOrCreate(['member_id' => $member->id, 'to_member_id' => $toMember->id]);
                return $this->succeed($attentiont, '关注成功！');
            }
            $attentiont->forceDelete();
            return $this->succeed($attentiont, '取消关注成功！');
        } catch (\Exception $ex) {
            return $this->exception($ex, '关注他人异常，请联系管理员!');
        }
    }

    /**
     * 取消关注
     */
    public function destroy(Request $request, MemberAttentionRepository $attentionRepository)
    {
        try {
            $member = $request->user('ApiMember');
            if (!$request->filled('id')) {
                return $this->validation('缺少需要取消关注的人id!');
            }
            $toMemberid = $request->id; //取消关注的人id
            if ($toMemberid == $member->id) {
                return $this->validation('你怎么可以取关你自己!');
            }
            $attention = MemberAttention::where('member_id', $member->id)->where('to_member_id', $toMemberid)->first();
            if (!$attention) {
                return $this->validation('你还没关注，请先关注再取关！');
            }

            $res = $attentionRepository->forceDelete([$attention->id]);
            if ($res) {
                return $this->succeed($res, '取消关注成功！-1');
            }
            return $this->validation('取消关注失败', $res);
        } catch (\Exception $ex) {
            return $this->exception($ex, '取消关注失败异常，请联系管理员!');
        }
    }


}

