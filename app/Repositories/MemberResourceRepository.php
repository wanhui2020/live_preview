<?php

namespace App\Repositories;

use App\Facades\MemberFacade;
use App\Facades\PlatformFacade;
use App\Facades\PushFacade;
use App\Models\MemberDynamic;
use App\Models\MemberResource;
use App\Models\MemberUser;
use App\Models\MemberUserRealname;
use App\Models\PlatformFile;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 会员资源
 * Class MemberRealnameRepository
 * @package App\Repositories
 */
class MemberResourceRepository extends BaseRepository
{
    public function model()
    {
        return MemberResource::class;
    }

    public function lists($addWhere = null)
    {
        $where = function ($query) {
            if (request('key')) {
                $query->where(function ($query) {
                    $query->whereHas('member', function ($query) {
                        $query->where('no', 'like', '%' . request('key') . '%')
                            ->orWhere('nick_name', 'like', '%' . request('key') . '%');
                    });
                });
            }
            if (request('status') != null) {
                $query->where('status', request('status'));
            }
            if (request('type') != null) {
                $query->where('type', request('type'));
            }
            //时间筛选
            if (request('dateTime') != null) {
                $dateTime = explode(' - ', request('dateTime'));
                $query->WhereBetween('created_at', [$dateTime[0], $dateTime[1]]);
            }
        };
        $this->where($where);
        if ($addWhere) {
            $this->where($addWhere);
        }
        if (request('size') != null) {
            $perPage = request('size');
            return $this->paginate($perPage);
        }
        return $this->paginate();
    }

    public function store(array $data)
    {
//        DB::beginTransaction();
        try {
//            $resp = parent::store(['member_id' => $data['member_id'], 'type' => $data['type']]);
//            if ($resp['status']) {
                $resource = $data['model'];
                $file = new PlatformFile();
                $file->url = $data['url'];
                if ($data['type_status'] == 1){
                    $file->title = $data['title'];
//                    $file->name = $data['name'];
//                    $file->describe = $data['describe'];
                    $file->front_cover = $data['front_cover'];
                    $file->thumb = $data['thumb'];
                }
                $resource->file()->save($file);
                if ($resource->status == 0 && !isset($data['type_status'])) {
                    $member = $resource->member;
                    if (empty($member->cover)) {
                        $member->cover = $file->url;
                        $member->save();
                    }

                }
                DB::commit();

                //资源审核
                if ($resource->status == 9) {
                    if (in_array($resource->type, [0, 2]) && PlatformFacade::config('platform_image_audit') == 1 || in_array($resource->type, [1]) && PlatformFacade::config('platform_video_audit') == 1) {
                        if ($data['model_type'] == 1){
                            MemberFacade::ResourceAudit($resource->id);
                        }elseif($data['model_type'] == 2){
                            MemberFacade::DynamicAudit($resource->id);
                        }

                    }
                }
            if ($data['model_type'] == 1){
                $resource = MemberResource::where('id',$resource->id)->first();
            }elseif($data['model_type'] == 2){
                $resource = MemberDynamic::where('id',$resource->id)->first();
            }
                switch ($resource['status']) {
                    case 0:
                        $status = '审核通过';
                        break;
                    case 1:
                        $status = '审核拒绝';
                        break;
                    default :
                        $status = '待审核';
                        break;
                }
                $user=$resource->member;

                if ($user->push_token) {
                    PushFacade::pushToken($user->push_token, $user->app_platform, $user->nick_name, $status, $type = 'NOTICE', ['type' => 'member', 'id' => $user->id, 'no' => $user->no, 'nickname' => $user->nick_name]);
                }

                return $this->succeed($resource);
//            }
//            return $resp;
        } catch (\Exception $ex) {
//            DB::rollBack();
            return $this->exception($ex, '创建资源异常');
        }

    }

    public function audit($data, Request $request)
    {
        try {

            $resource = $this->find($data['id']);
            if (!isset($resource)) {
                return $this->validation('未找到该资源');
            }
            $system = $request->user('SystemUser');
            if (!isset($system)) {
                DB::rollBack();
                return $this->validation('请进行后台登录');
            }
            $resource->audit_uid = $system->id;
            $resource->audit_time = Carbon::now()->toDateTimeString();
            if ($data['status'] == 0) {
                $resource->status = 0;
                $member = $resource->member;
                if (isset($resource->file) && !strstr($resource->file['url'],'outin-46d0eabc635811eaa4b500163e1c60dc.oss-cn-shanghai.aliyuncs.com')) {
                    if (empty($member->cover)) {
                        $member->cover = $resource->file['url'];
                        $member->save();
                    }
                }
                $resource->save();
                if ($resource->save()) {
                    switch ($data['status']) {
                        case 0:
                            $status = '审核通过';
                            break;
                        case 1:
                            $status = '审核拒绝';
                            break;
                        default :
                            $status = '待审核';
                            break;
                    }
                    if ($member->push_token) {
                        PushFacade::pushToken($member->push_token, $member->app_platform, $member->nick_name, $status, $type = 'NOTICE', ['type' => 'member', 'id' => $member->id, 'no' => $member->no, 'nickname' => $member->nick_name]);
                    }
                    return $this->succeed($resource);
                }
            } else {
                $resource->forceDelete();
                return $this->succeed();
            }



            return $this->validation('审核出错,请联系管理员');

        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }
}

