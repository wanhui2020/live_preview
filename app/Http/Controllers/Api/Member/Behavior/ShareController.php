<?php

namespace App\Http\Controllers\Api\Member\Behavior;


use App\Http\Controllers\Controller;
use App\Http\Resources\MemberIndexResource;
use App\Repositories\MemberUserRepository;
use Illuminate\Http\Request;

/**
 * 分享
 * Class PayController
 */
class ShareController extends Controller
{
    /**
     * 邀请好友列表
     */
    public function lists(Request $request, MemberUserRepository $memberUserRepository)
    {
        try {
            $member = $request->user('ApiMember');

            $sharefriends = $memberUserRepository->orderBy('sort', 'desc')->lists(function ($query) use ($member) {
                $query->where('parent_id', $member->id); //推荐人的id
//                    $query->where('is_real',0);//是否实名认证
            });
            if (!$sharefriends->isEmpty()) {
                return $this->paginate(MemberIndexResource::collection($sharefriends), '获取邀请好友列表成功');
            }
            return $this->succeed([], '邀请好友列表返回成功');
        } catch (\Exception $e) {
            $this->exception($e);
            return $this->validation('获取邀请好友列表异常，请联系管理员');
        }
    }
}

