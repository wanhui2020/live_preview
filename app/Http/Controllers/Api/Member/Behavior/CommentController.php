<?php

namespace App\Http\Controllers\Api\Member\Behavior;


use App\Http\Controllers\Controller;
use App\Http\Resources\DealCommentResource;
use App\Models\DealComment;
use App\Models\MemberUser;
use App\Repositories\DealCommentRepository;
use Illuminate\Http\Request;

/**
 * 评论
 * Class PayController
 */
class CommentController extends Controller
{


    /**
     * 我的评论列表
     */
    public function lists(Request $request, DealCommentRepository $attentionRepository)
    {
        try {
            $member = $request->user('ApiMember');
            $list = $attentionRepository->with(['tomember'])->where(function ($query) use ($member) {
                $query->where('status', 0);
                $query->where('member_id', $member->id);
            })->paginate();
            return $this->succeed(DealCommentResource::collection($list), '获取我的评论成功');

        } catch (\Exception $ex) {
            return $this->exception($ex, '我的评论获取异常，请联系管理员');
        }
    }


    /**
     * 评论他人
     */
    public function store(Request $request,DealCommentRepository $attentionRepository)
    {
        try {
            $member = $request->user('ApiMember');
            if (!$request->filled('id')) {
                return $this->validation('缺少被评论的人id!');
            }
            if ($member->id == $request->id) {
                return $this->validation('不能评论自己');
            }
            $toMember = MemberUser::find($request->id);
            if (!isset($toMember)) {
                return $this->validation('你所评论的用户不存在!');
            }
            if ($toMember->id == $member->id) {
                return $this->succeed($toMember, '评论成功！');
            }
            $attentiont = DealComment::where('member_id', $member->id)->where('to_member_id', $toMember->id)->first();
            if (!isset($attentiont)) {
                //初次评论
                DealComment::firstOrCreate(['member_id' => $member->id, 'to_member_id' => $toMember->id]);
                return $this->succeed($attentiont, '评论成功！');
            }
            if ($attentiont->status == 0) {
                $attentiont->status = 1;
            } else {
                $attentiont->status = 0;
            }
            if ($attentiont->save()) {
                if ($attentiont->status == 0) {
                    return $this->succeed($attentiont, '评论成功！');
                }
                return $this->succeed($attentiont, '取消评论成功1！');
            }
            return $this->validation('评论失败', $attentiont);
        } catch (\Exception $ex) {
            return $this->exception($ex, '评论他人异常，请联系管理员!');
        }
    }

    /**
     * 取消评论
     */
    public function destroy(Request $request, DealCommentRepository $attentionRepository)
    {
        try {
            $member = $request->user('ApiMember');
            if (!$request->filled('id')) {
                return $this->validation('缺少需要取消评论的人id!');
            }
            $toMemberid = $request->id; //取消评论的人id
            if ($toMemberid == $member->id) {
                return $this->validation('你怎么可以取关你自己!');
            }
            $attention = DealComment::where('member_id', $member->id)->where('to_member_id', $toMemberid)->first();
            if (!$attention) {
                return $this->validation('你还没评论，请先评论再取关！');
            }

            $res = $attentionRepository->forceDelete([$attention->id]);
            if ($res) {
                return $this->succeed($res, '取消评论成功！-1');
            }
            return $this->validation('取消评论失败', $res);
        } catch (\Exception $ex) {
            return $this->exception($ex, '取消评论失败异常，请联系管理员!');
        }
    }


}

