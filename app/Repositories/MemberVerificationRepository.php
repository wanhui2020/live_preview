<?php

namespace App\Repositories;

use App\Facades\PushFacade;
use App\Models\MemberVerification;
use App\Models\MemberUser;
use App\Models\MemberUserRealname;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

/**
 * 资料审核
 * Class MemberRealnameRepository
 * @package App\Repositories
 */
class MemberVerificationRepository extends BaseRepository
{
    public function model()
    {
        return MemberVerification::class;
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
            if (request('info_type') != null) {
                $query->where('info_type', request('info_type'));
            }
            if (request('dateTime') != null) {
                $dateTime = explode(' - ', request('dateTime'));
                $query->WhereBetween('audit_time', [$dateTime[0], $dateTime[1]]);
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
        return parent::store($data);
    }

    public function update($data, $attribute = "id")
    {
        return parent::update($data, $attribute);
    }

    public function audit($data, Request $request)
    {
        try {
            $verification = MemberVerification::find($data['id']);
            if (!isset($verification)) {
                return $this->validation('无记录');
            }
            $systemuser = $request->user('SystemUser');
            if (!isset($systemuser)) {
                return $this->validation('无权操作');
            }
            $verification->audit_uid = $systemuser->id;
            $verification->audit_time = Carbon::now()->toDateTimeString();
            $verification->status = $data['status'];
            $resp = $verification->save();

            $user = MemberUser::where(['id' => $verification->member_id])->first();
            switch ($data['status']) {
                case 0:
                    $status = '资料审核通过';
                    break;
                case 1:
                    $status = '资料审核不通过';
                    break;
                default :
                    $status = '资料审核通过';
                    break;
            }
            if ($user->push_token) {
                PushFacade::pushToken($user->push_token, $user->app_platform, $user->nick_name, $status, $type = 'NOTICE', ['type' => 'member', 'id' => $user->id, 'no' => $user->no, 'nickname' => $user->nick_name]);
            }

            if ($resp) {
                return $this->succeed($resp);
            } else {
                return $this->validation('审核出错,请联系管理员');
            }
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }
}

