<?php

namespace App\Http\Controllers\Api\Member\Platform;


use App\Facades\OssFacade;
use App\Http\Controllers\Controller;
use App\Http\Repositories\PlatformNoticeRepository;
use App\Http\Resources\MemberResourceResource;
use App\Http\Resources\PlatformNoticeResource;
use App\Models\PlatformNoticeDetail;
use App\Repositories\MemberAttentionRepository;
use App\Repositories\MemberResourceRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 系统通知
 * Class PayController
 */
class NoticeController extends Controller
{
    public function __construct(PlatformNoticeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 会员资源
     */
    public function lists(Request $request)
    {
        try {
            $member = $request->user('ApiMember');
            $lists = $this->repository->where(function ($query) use ($request, $member) {
                $query->where('member_id', $member->id);
                $query->orWhere('type', 0);

            })->paginate();
            foreach ($lists as &$v){
                $v['title'] = strstr($v['content'],'<img')?'这里是图片':strip_tags($v['content']);
                $id = DB::table('platform_notice_details')->where(['relevance_type'=>'PlatformNotice','relevance_id'=>$v['id'],'member_id'=>$member->id])->value('id');
                if ($request->filled('limit') && $request->limit != 1){
                    if (!isset($id)) {
                        $member = $request->user('ApiMember');
                        $model = new PlatformNoticeDetail();
                        $model->member_id = $member->id;
                        $model->relevance_type = 'PlatformNotice';
                        $model->relevance_id = $v['id'];
                        $model->status = 0;
                        $model->is_read = 0;
                        $model->save();
                    }
                }
                $v['is_read']=isset($id)?0:1;
            }
            return $this->succeed(PlatformNoticeResource::collection($lists), '获取成功!');
        } catch (\Exception $e) {
            return $this->exception($e, '会员资源返回失败，请联系管理员');
        }
    }

    /**
     * 删除
     */
    public function destroy(Request $request)
    {
        try {
            $member = $request->user('ApiMember');
            $ids = $member->notices()->whereIn('id', [$request->ids])->pluck('id');
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

