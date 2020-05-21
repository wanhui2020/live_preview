<?php

namespace App\Repositories;

use App\Models\MemberUser;
use App\Models\MemberUserRealname;
use App\Models\MemberUserSelfie;
use App\Models\MemberWalletCash;
use App\Models\MemberWalletGold;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MemberUserSelfieRepository extends BaseRepository
{
    public function model()
    {
        return MemberUserSelfie::class;
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
            if (request('sex') != null) {
                $query->where('sex', request('sex'));
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


    public function audit($data)
    {
        try {
            $selfie = MemberUserSelfie::where('id', $data['id'])->first();
            if (!isset($selfie)) {
                return $this->validation('认证记录不存在!');
            }
            $systemuser = Auth::guard('SystemUser')->user();
            if (!isset($selfie)) {
                return $this->validation('未找到该自拍认证!');
            }
            if (!isset($systemuser)) {
                return $this->validation('请进行后台登录！');
            }

            $selfie->audit_uid = $systemuser->id;
            $selfie->audit_time = Carbon::now()->toDateTimeString();
            $selfie->status = $data['status'];

            if ($selfie->save()) {
                return $this->succeed($selfie);
            }
            return $this->validation('审核出错,请联系管理员');

        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

}

