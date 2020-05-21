<?php

namespace App\Repositories;

use App\Models\MemberAttention;
use App\Models\MemberLogin;
use App\Models\MemberUser;
use App\Models\MemberUserRealname;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * 会员关注
 * Class MemberRealnameRepository
 * @package App\Repositories
 */
class MemberAttentionRepository extends BaseRepository
{
    public function model()
    {
        return MemberAttention::class;
    }

    public function lists($addWhere = null)
    {
        $where = function ($query) {
            //会员
            if (request('key')) {
                $query->where(function ($query) {
                    $query->whereHas('member', function ($query) {
                        $query->where('no', 'like', '%' . request('key') . '%')
                            ->orWhere('nick_name', 'like', '%' . request('key') . '%');
                    });
                });
            }
            //被关注的会员
            if (request('keys')) {
                $query->where(function ($query) {
                    $query->whereHas('tomember', function ($query) {
                        $query->where('no', 'like', '%' . request('keys') . '%')
                            ->orWhere('nick_name', 'like', '%' . request('keys') . '%');
                    });
                });
            }
            //时间筛选
            if (request('dateTime') != null) {
                $dateTime = explode(' - ', request('dateTime'));
                $query->WhereBetween('updated_at', [$dateTime[0], $dateTime[1]]);
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


}

