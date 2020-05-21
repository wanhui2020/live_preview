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

/**
 * 动态
 * Class MemberRealnameRepository
 * @package App\Repositories
 */
class MemberDynamicRepository extends BaseRepository
{
    public function model()
    {
        return MemberDynamic::class;
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
        try {
//            $resp = parent::store(['member_id' => $data['member_id'], 'type' => $data['type'], 'content' => $data['content']]);
//            if ($resp['status']) {
                $resource =  $data['dynamic'];
                $file = new PlatformFile();
                $file->url = $data['url'];
                $resource->file()->save($file);
                DB::commit();
                return $this->succeed($resource);
//            }
//            return $resp;
        } catch (\Exception $ex) {
            return $this->exception($ex, '创建资源异常');
        }

    }

    public function audit($data, Request $request)
    {
        try {

            $resource = $this->find($data['id']);
            $file = PlatformFile::where(['relevance_type'=>'MemberDynamic','relevance_id'=>$data['id']])->first();
            $file->status=0;
            $file->save();
            if (!isset($resource)) {
                return $this->validation('未找到该文件');
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

