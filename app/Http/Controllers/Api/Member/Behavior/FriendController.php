<?php

namespace App\Http\Controllers\Api\Member\Behavior;


use App\Http\Controllers\Controller;
use App\Http\Resources\MemberAttentionResource;
use App\Models\MemberFriend;
use App\Repositories\MemberFriendRepository;
use Illuminate\Http\Request;

/**
 * 好友
 * Class PayController
 */
class FriendController extends Controller
{


    /**
     * 我的好友列表
     */
    public function lists(Request $request, MemberFriendRepository $friendRepository)
    {
        try {
            $member = $request->user('ApiMember');
            $memberuser = $friendRepository->with(['tomember'])->lists(function ($query) use ($member) {
                $query->where('member_id', $member->id);
            });
            if ($memberuser) {
                return $this->paginate(MemberAttentionResource::collection($memberuser), '获取我的好友成功');
            }
            return $this->succeed([], '获取我的好友成功');
        } catch (\Exception $e) {
            $this->exception($e);
            return $this->validation('我的好友获取异常，请联系管理员');
        }
    }


    /**
     * 好友他人
     */
    public function store(Request $request, MemberFriendRepository $friendRepository)
    {
        try {
            $member = $request->user('ApiMember');
            if (!$request->filled('id')) {
                return $this->validation('缺少被好友的人id!');
            }
            if ($member->id == $request->id) {
                return $this->validation('不能加自己为好友');
            }
            $toMemberid = $request->id;
            if ($toMemberid == $member->id) {
                return $this->validation('你为啥要好友你自己!');
            }
            $att = MemberFriend::where('member_id', $member->id)->where('to_member_id', $toMemberid)->first();
            if ($att) {
                return $this->validation('已经好友请勿重复好友！');
            }
            $data = [
                'member_id' => $member->id,
                'to_member_id' => $toMemberid,
            ];
            $res = $friendRepository->store($data);
            if ($res) {
                return $this->succeed(null, '好友成功！');
            }
            return $this->validation('好友失败', $res);
        } catch (\Exception $ex) {
            return $this->exception($ex, '好友他人异常，请联系管理员!');
        }
    }

    /**
     * 取消好友
     */
    public function destroy(Request $request, MemberFriendRepository $friendRepository)
    {
        try {
            $member = $request->user('ApiMember');
            if (!$request->filled('id')) {
                return $this->validation('缺少需要取消好友的人id!');
            }
            $toMemberid = $request->id; //取消好友的人id
            if ($toMemberid == $member->id) {
                return $this->validation('你怎么可以取关你自己!');
            }
            $att = MemberFriend::where('member_id', $member->id)->where('to_member_id', $toMemberid)->first();
            if (!$att) {
                return $this->validation('你还没好友，请先好友再取关！');
            }

            $res = $friendRepository->forceDelete([$att->id]);
            if ($res) {
                return $this->succeed(null, '取消好友成功！');
            }
            return $this->validation('取消好友失败', $res);
        } catch (\Exception $ex) {
            return $this->exception($ex, '取消好友失败异常，请联系管理员!');
        }
    }


}

