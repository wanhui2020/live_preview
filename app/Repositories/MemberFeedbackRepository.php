<?php

namespace App\Repositories;

use App\Models\MemberFeedback;
use App\Models\MemberFriend;
use App\Models\MemberLogin;
use App\Models\MemberUser;
use App\Models\MemberUserRealname;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * 意见反馈
 * Class MemberRealnameRepository
 * @package App\Repositories
 */
class MemberFeedbackRepository extends BaseRepository
{
    public function model()
    {
        return MemberFeedback::class;
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
            if (request('replay_status')!=null){
                $query->where('replay_status',request('replay_status'));
            }
            //回复人
            if (request('keys')) {
                $query->where(function ($query) {
                    $query->whereHas('audit', function ($query) {
                        $query->orWhere('name', 'like', '%' . request('keys') . '%');
                    });
                });
            }
            //时间筛选
            if (request('dateTime') != null) {
                $dateTime = explode(' - ', request('dateTime'));
                $query->WhereBetween('replay_time', [$dateTime[0], $dateTime[1]]);
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
}

