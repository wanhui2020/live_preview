<?php

namespace App\Http\Controllers\Api\Member\Behavior;


use App\Facades\OssFacade;
use App\Facades\PlatformFacade;
use App\Facades\VodFacade;
use App\Http\Controllers\Controller;
use App\Http\Resources\MemberResourceResource;
use App\Models\MemberDynamic;
use App\Models\MemberResource;
use App\Repositories\MemberAttentionRepository;
use App\Repositories\MemberResourceRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 会员资源
 * Class PayController
 */
class ResourceController extends Controller
{
    public function __construct(MemberResourceRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 资源上传
     * @param Request $request
     * @return array|mixed
     */
    public function store(Request $request)
    {

        try {
            $member = $request->user('ApiMember');
            if (!$request->filled('type')) {
                return $this->validation('资源类型不能为空');
            }
            $type = $request->type;
            if (!$request->hasFile('file')) {
                return $this->validation('资源图片不能为空');
            }

            DB::beginTransaction();
            $model = new MemberResource();
            $model->member_id = $member->id;
            $model->type = $type;
            $model->save();

            $files = $request->allFiles();
//            $this->logs('$files', $files);
            $this->upload($member, $type, $request->file('file'), $model);
//            //批量上传
//            foreach ($files as $file) {
//                $this->upload($member, $type, $file);
//            }
            DB::commit();
            return $this->succeed();
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->exception($ex);
        }
    }


    /**
     * 保存文件
     * @param $member
     * @param $type
     * @param $file
     * @param $model
     * @return array|mixed
     */
    private function upload($member, $type, $file, $model)
    {
        try {
            $resp = OssFacade::putImage($file, $member->no);
            if (!$resp['status']) {
                return $this->failure(1, '资源图片上传失败', $resp);
            }
            $data['member_id'] = $member->id;
            $data['type'] = $type;
            $data['url'] = $resp['data'];
            $data['model'] = $model;
            $data['type_status'] = 0;
            $data['model_type'] = 1;
            $result = $this->repository->store($data);
            if (!$result['status']) {
                return $this->failure(1, '保存失败', $result);
            }
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }


    /**
     * 会员资源
     */
    public function lists(Request $request)
    {
        try {
            $member = $request->user('ApiMember');

            $lists = $this->repository->where(function ($query) use ($request, $member) {

                if ($request->filled('type')) {
                    $query->where('type', $request->type);
                }

                if ($request->filled('member_id')) {
                    if ($request->member_id == $member->id) {
                        $query->where('member_id', $member->id);
                    } else {
                        $query->where('status', 0);
                        $query->where('member_id', $request->member_id);
                    }


                } else {

                }
            })->withCount(['views as is_lock' => function ($query) use ($request, $member) {
                $query->where('member_id', $member->id);
                $query->where('end_time', '>', Carbon::now());
                $query->select(DB::raw('count(*)'));
            }])->paginate();
            return $this->succeed(MemberResourceResource::collection($lists), '获取成功!');
        } catch (\Exception $e) {
            return $this->exception($e, '会员资源返回失败，请联系管理员');
        }
    }

    /**
     * 设置默认封面
     */
    public function cover(Request $request)
    {
        try {
            $member = $request->user('ApiMember');
            $resource = $this->repository->findWhere(function ($query) use ($request) {
                $query->where('type', 2);
                $query->where('id', $request->id);
            });
            if (!isset($resource)) {
                return $this->validation('封面不存在');
            }
            if ($resource->status != 0) {
                return $this->validation('需审核后照片才能设置为封面');
            }
            $file = $resource->file;
            if (!isset($file)) {
                return $this->validation('图片不存在');
            }
            $member->cover = $file->url;
            $member->save();
            return $this->succeed(new MemberResourceResource ($resource), '设置成功!');
        } catch (\Exception $e) {
            return $this->exception($e, '会员资源返回失败，请联系管理员');
        }
    }

    /**
     * 资源删除
     */
    public function destroy(Request $request)
    {
        try {
            $member = $request->user('ApiMember');
            if (!$request->filled('ids')) {
                return $this->validation('参数不能为空');
            }
            if (!is_array($request->ids)) {
                return $this->validation('参数ids需为数组！');
            }
            if (count($request->ids) == 0) {
                return $this->validation('参数值无效！');
            }
            $result = MemberResource::where('member_id', $member->id)->whereIn('id', $request->ids)->forceDelete();
            return $this->succeed($result, "成功删除 $result 条数据");

        } catch (\Exception $e) {
            return $this->exception($e, '会员资源返回失败，请联系管理员');
        }
    }


    /**
     * 获取上传视频的凭证
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function createUploadVideo(Request $request)
    {
        $member = $request->user('ApiMember');
        if (!$request->filled('filename')) {
            return $this->validation('文件不能为空');
        }
        if (!$request->filled('name')) {
            return $this->validation('标题不能为空');
        }
        if (!$request->filled('tags')) {
            return $this->validation('标签不能为空');
        }

        $name = $request->name ?? '';
        $fileName = $request->filename;
        $tags = $request->tags ?? '';
        $description = $request->description ?? '';
        try {
            DB::beginTransaction();
            $res = VodFacade::createUploadVideo($name, $fileName, $description, $tags);
            DB::commit();
            return $this->succeed($res);
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->exception($ex);
        }
    }


    /**
     * 获取视频
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getVideo(Request $request)
    {
        $member = $request->user('ApiMember');
        if (!$request->filled('video_id')) {
            return $this->validation('视频id不能为空');
        }
        //1为资源2为动态
        if (!$request->filled('type')) {
            return $this->validation('type不能为空');
        }

        $videoId = $request->video_id;
        try {
            DB::beginTransaction();
//            time_sleep_until(time() + 30); // 在20秒后执行后面代码
            $result = VodFacade::getVideo($videoId);
            if ($result['VideoBase']['Duration'] == 0) {
                return $this->failure(1, '上传的视频无效');
            }
            if ($result['VideoBase'] && $result['PlayInfoList']['PlayInfo'][0]['PlayURL']) {
                if ($request->type == 1) {
                    $model = new MemberResource();
                    $model->member_id = $member->id;
                    $model->type = 1;
                    $data['model_type'] = 1;//1审核的是资源，2是动态
                } else if ($request->type == 2) {
                    $model = new MemberDynamic();
                    $model->member_id = $member->id;
                    $model->type = 1;
                    $model->content = PlatformFacade::keyword($result['VideoBase']['Title']);
                    $model->resident = $request->resident ?? $member['resident'];
                    $data['model_type'] = 2;//1审核的是资源，2是动态
                }
                $model->save();

                $playUrl = $result['PlayInfoList']['PlayInfo'][0]['PlayURL'];
                $playUrl = substr($playUrl, 0, strpos($playUrl, "?"));

                $data['type_status'] = 1;
                $data['title'] = $result['VideoBase']['Title'];
//                    $data['describe'] = $description;
                $data['front_cover'] = isset($result['VideoBase']['CoverURL']) ? substr($result['VideoBase']['CoverURL'], 0, strpos($result['VideoBase']['CoverURL'], "?")) : '';
                $data['thumb'] = $playUrl;
                $data['url'] = $playUrl;
                $data['model'] = $model;
                $ret = $this->repository->store($data);
                if ($ret['code'] != 0) {
                    DB::rollBack();
                }
            }
            DB::commit();
            return $this->succeed('');
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->exception($ex);
        }
    }

}

