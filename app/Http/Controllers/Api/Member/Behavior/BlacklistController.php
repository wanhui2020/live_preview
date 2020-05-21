<?php

namespace App\Http\Controllers\Api\Member\Behavior;


use App\Http\Controllers\Controller;
use App\Http\Resources\MemberAttentionResource;
use App\Models\MemberAttention;
use App\Models\MemberBlacklist;
use App\Models\MemberUser;
use App\Repositories\MemberAttentionRepository;
use App\Repositories\MemberBlacklistRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * 黑名单
 * Class PayController
 */
class BlacklistController extends Controller
{


    /**
     * 我的黑名单列表
     */
    public function lists(Request $request, MemberBlacklistRepository $blacklistRepository)
    {
        try {
            $member = $request->user('ApiMember');
            $memberuser = $blacklistRepository->lists(function ($query) use ($member) {
                $query->where('member_id', $member->id);
            });
            if ($memberuser) {
                return $this->paginate(MemberAttentionResource::collection($memberuser), '获取我的黑名单成功');
            }
            return $this->succeed([], '获取我的黑名单成功');
        } catch (\Exception $e) {
            $this->exception($e);
            return $this->validation('我的黑名单获取异常，请联系管理员');
        }
    }


    /**
     * 拉黑他人
     */
    public function store(Request $request, MemberBlacklistRepository $blacklistRepository)
    {
        try {
            $member = $request->user('ApiMember');
            if (!$request->filled('id')) {
                return $this->validation('缺少被黑名单的人id!');
            }
            if ($member->id == $request->id) {
                return $this->validation('不能把自己加入黑名单');
            }
            $toMember = MemberUser::find($request->id);
            if (!isset($toMember)) {
                return $this->validation('你所拉黑的用户不存在!');
            }
            $blacklist = MemberBlacklist::where('member_id', $member->id)->where('to_member_id', $toMember->id)->first();
            if (!isset($blacklist)) {
                MemberBlacklist::firstOrCreate(['member_id' => $member->id, 'to_member_id' => $toMember->id]);
                return $this->succeed($blacklist, '拉黑成功！');
            }
            $blacklist->forceDelete();
            return $this->succeed($blacklist, '取消黑名单成功！');
        } catch (\Exception $ex) {
            return $this->exception($ex, '黑名单他人异常，请联系管理员!');
        }
    }

    /**
     * 取消拉黑
     */
    public function destroy(Request $request, MemberBlacklistRepository $blacklistRepository)
    {
        try {
            $member = $request->user('ApiMember');
            if (!$request->filled('id')) {
                return $this->validation('缺少需要取消黑名单的人id!');
            }
            $toMemberid = $request->id; //取消黑名单的人id
            if ($toMemberid == $member->id) {
                return $this->validation('你怎么可以取关你自己!');
            }
            $att = MemberBlacklist::where('member_id', $member->id)->where('to_member_id', $toMemberid)->first();
            if (!$att) {
                return $this->validation('你还没拉黑，请先拉黑再取关！');
            }

            $res = $blacklistRepository->forceDelete([$att->id]);
            if ($res) {
                return $this->succeed(null, '取消拉黑成功！');
            }
            return $this->validation('取消黑名单失败', $res);
        } catch (\Exception $ex) {
            return $this->exception($ex, '取消黑名单失败异常，请联系管理员!');
        }
    }


}

