<?php

namespace App\Repositories;

use App\Facades\SmsFacade;
use App\Models\MemberFeedback;
use App\Models\MemberFriend;
use App\Models\MemberLogin;
use App\Models\MemberSignIn;
use App\Models\MemberUser;
use App\Models\MemberUserRealname;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * 会员签到
 * Class MemberRealnameRepository
 * @package App\Repositories
 */
class MemberSigninRepository extends BaseRepository
{
    public function model()
    {
        return MemberSignIn::class;
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

