<?php

namespace App\Repositories;

use App\Models\MemberLogin;
use App\Models\DealLike;
use App\Models\MemberUser;
use App\Models\MemberUserRealname;
use App\Models\SystemUser;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * 点赞
 * Class MemberRealnameRepository
 * @package App\Repositories
 */
class DealLikeRepository extends BaseRepository
{
    public function model()
    {
        return DealLike::class;
    }

    public function lists($addWhere = null)
    {
        $where = function ($query) {
            if (request('key')) {
                $query->where(function ($query) {
                    $query->whereHas('member', function ($query) {
                        $query->where('no', 'like', '%' . request('key') . '%')
                            ->orWhere('nick_name', 'like', '%' . request('key') . '%')
                            ->orWhere('mobile', 'like', '%' . request('key') . '%');
                    });
                });
            }
            if (request('status') != null) {
                $query->where('status', request('status'));
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

    public function audit($data,Request $request)
    {
        try {
            $social = DealLike::where('id', $data['id'])->first();
            $systemuser = $request->user('SystemUser');
            if(!isset($social)){
                return $this->validation('未找到该动态');
            }
            if (!isset($systemuser)){
                return $this->validation('请进行后台登录');
            }
            $social->audit_uid = $systemuser->id;
            $social->audit_time = Carbon::now()->toDateTimeString();
            if ($data['status'] == 1){
                $social->status = 1;
            }else{
                $social->status = 2;
            }
            $re = $social->save();
            if ($re){
                return $this->succeed($re);
            }else{
                return $this->validation('审核出错,请联系管理员');
            }
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }
}

