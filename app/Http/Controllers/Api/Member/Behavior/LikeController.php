<?php

namespace App\Http\Controllers\Api\Member\Behavior;


use App\Http\Controllers\Controller;
use App\Http\Resources\DealLikeResource;
use App\Models\DealLike;
use App\Models\DealSocial;
use App\Models\MemberDynamic;
use App\Models\MemberUser;
use App\Repositories\DealLikeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 点赞
 * Class PayController
 */
class LikeController extends Controller
{


    /**
     * 我的点赞列表
     */
    public function lists(Request $request, DealLikeRepository $repository)
    {
        try {
            $member = $request->user('ApiMember');
            $list = $repository->with(['tomember'])->where(function ($query) use ($member) {
                $query->where('status', 0);
                $query->where('member_id', $member->id);
            })->paginate();
            return $this->succeed(DealLikeResource::collection($list), '获取我的点赞成功');

        } catch (\Exception $ex) {
            return $this->exception($ex, '我的点赞获取异常，请联系管理员');
        }
    }


    /**
     * 点赞会员
     */
    public function store(Request $request, DealLikeRepository $repository)
    {
        try {
            DB::beginTransaction();
            $member = $request->user('ApiMember');
            if (!$request->filled('dynamic_id')) {
                return $this->validation('请传入动态id!');
            }

            $dynamic = MemberDynamic::where('id', $request->dynamic_id)->first();
            if (!$dynamic) {
                return $this->validation('该动态不存在!');
            }

            $id = DealLike::where(['relevance_type' => 'MemberDynamic', 'relevance_id' => $request->dynamic_id, 'member_id' => $member->id, 'to_member_id' => $dynamic->member_id])->value("id");
            if ($id){
                return $this->validation('该动态已点赞!');
            }
            $like = DealLike::firstOrCreate(['relevance_type' => 'MemberDynamic', 'relevance_id' => $request->dynamic_id, 'member_id' => $member->id, 'to_member_id' => $dynamic->member_id]);
            $like->number++;
            $result = $like->save();
            if ($result) {
                MemberDynamic::where('id', $request->dynamic_id)->update(['like_number' => $dynamic['like_number'] + 1]);
            }
            DB::commit();
            return $this->succeed(new DealLikeResource($like), '点赞成功！');
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->exception($ex, '点赞他人异常，请联系管理员!');
        }
    }

    /**
     * 点赞动态
     */
    public function social(Request $request, DealLikeRepository $repository)
    {
        try {
            $member = $request->user('ApiMember');
            if (!$request->filled('id')) {
                return $this->validation('缺少被点赞的人id!');
            }
            $social = DealSocial::find($request->id);
            if (!isset($social)) {
                return $this->validation('你所点赞的动态不存在!');
            }
            if ($social->member_id == $member->id) {
                return $this->succeed($member, '点赞成功！');
            }
            $like = DealLike::firstOrNew(['relevance_type' => 'MemberSocial', 'relevance_id' => $social->id, 'member_id' => $member->id, 'to_member_id' => $social->member_id]);
            $like->number++;
            $like->save();

            return $this->succeed(new DealLikeResource($like), '点赞成功！');
        } catch (\Exception $ex) {
            return $this->exception($ex, '点赞他人异常，请联系管理员!');
        }
    }

    /**
     * 取消点赞
     */
    public function destroy(Request $request, DealLikeRepository $repository)
    {
        try {
            $member = $request->user('ApiMember');
            if (!$request->filled('id')) {
                return $this->validation('缺少需要取消点赞的人id!');
            }
            $toMemberid = $request->id; //取消点赞的人id
            if ($toMemberid == $member->id) {
                return $this->validation('你怎么可以取关你自己!');
            }
            $attention = DealLike::where('member_id', $member->id)->where('to_member_id', $toMemberid)->first();
            if (!$attention) {
                return $this->validation('你还没点赞，请先点赞再取关！');
            }

            $res = $repository->forceDelete([$attention->id]);
            if ($res) {
                return $this->succeed($res, '取消点赞成功！-1');
            }
            return $this->validation('取消点赞失败', $res);
        } catch (\Exception $ex) {
            return $this->exception($ex, '取消点赞失败异常，请联系管理员!');
        }
    }


}

