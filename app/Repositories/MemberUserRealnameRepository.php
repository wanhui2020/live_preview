<?php

namespace App\Repositories;

use App\Models\MemberUser;
use App\Models\MemberUserRealname;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

/**
 * 实名认证
 * Class MemberRealnameRepository
 * @package App\Repositories
 */
class MemberUserRealnameRepository extends BaseRepository
{
    public function model()
    {
        return MemberUserRealname::class;
    }

    public function lists($addWhere = null)
    {
        $where = function ($query) {
            if (request('key')) {
                $query->whereHas('member', function ($query) {
                    $query->where('no', 'like', '%' . request('key') . '%')
                        ->orWhere('nick_name', 'like', '%' . request('key') . '%');
                });
            }
            if (request('idcard')) {
                $query->where('idcard', 'like', '%' . request('idcard') . '%')
                    ->orWhere('name', 'like', '%' . request('idcard') . '%');
            }
            if (request('status') != null) {
                $query->where('status', request('status'));
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


    public function audit($data, Request $request)
    {
        try {
            $realname = MemberUserRealname::where('id', $data['id'])->first();
            $systemuser = $request->user('SystemUser');
            if (!isset($realname)) {
                return $this->validation('未找到该实名信息');
            }
            if (!isset($systemuser)) {
                return $this->validation('请进行后台登录');
            }
            $realname->audit_uid = $systemuser->id;
            $realname->audit_time = Carbon::now()->toDateTimeString();
            if ($data['status'] == 0) {
                $realname->status = 0;
            } else {
                $realname->status = 1;
            }
            $res = $realname->save();
            if ($res) {
                return $this->succeed($res);
            } else {
                return $this->validation('审核出错,请联系管理员');
            }
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }
}

