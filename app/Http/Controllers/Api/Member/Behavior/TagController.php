<?php

namespace App\Http\Controllers\Api\Member\Behavior;


use App\Facades\OssFacade;
use App\Http\Controllers\Controller;
use App\Http\Repositories\PlatformTagRepository;
use App\Http\Resources\MemberResourceResource;
use App\Http\Resources\MemberTagResource;
use App\Http\Resources\PlatformTagResource;
use App\Models\MemberTag;
use App\Models\MemberUser;
use App\Models\PlatformTag;
use App\Repositories\DealGiftRepository;
use App\Repositories\MemberAttentionRepository;
use App\Repositories\MemberResourceRepository;
use App\Repositories\MemberTagRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 会员标签
 * Class PayController
 */
class TagController extends Controller
{
    public function __construct(MemberTagRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 我的标签
     */
    public function lists(Request $request)
    {
        try {
            $member = $request->user('ApiMember');

            $lists = $this->repository->where(function ($query) use ($request, $member) {
                $query->where('member_id', $member->id);
            })->paginate();
            return $this->succeed(PlatformTagResource::collection($lists), '获取成功!');
        } catch (\Exception $e) {
            return $this->exception($e, '会员资源返回失败，请联系管理员');
        }
    }


    public function store(Request $request, DealGiftRepository $giftRepository)
    {
        try {
            $member = $request->user('ApiMember');
            if (!$request->filled('ids')) {
                return $this->validation('标签编号不能为空');
            }
            $member->tags()->sync(explode(',', $request->ids));
            return $this->succeed(PlatformTagResource::collection($member->tags), '增加标签成功');
        } catch (\Exception $ex) {
            return $this->exception($ex, '标签异常，请联系管理员');
        }
    }


    /**
     * 删除
     */
    public function destroy(Request $request)
    {
        try {
            $member = $request->user('ApiMember');
            $ids = $member->tags()->whereIn('id', [$request->ids])->pluck('id');
            $result = $this->repository->forceDelete($ids);
            if ($result['status']) {
                return $this->succeed($result['data'], '删除成功!');
            }
            return $this->validation('删除失败');

        } catch (\Exception $e) {
            return $this->exception($e, '会员资源返回失败，请联系管理员');
        }
    }
}

