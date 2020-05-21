<?php

namespace App\Repositories;

use App\Models\MemberAttention;
use App\Models\MemberBlacklist;
use App\Models\MemberLogin;
use App\Models\MemberUser;
use App\Models\MemberUserRealname;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * 会员拉黑
 * Class MemberRealnameRepository
 * @package App\Repositories
 */
class MemberBlacklistRepository extends BaseRepository
{
    public function model()
    {
        return MemberBlacklist::class;
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
            //拉黑会员
            if (request('keys')) {
                $query->where(function ($query) {
                    $query->whereHas('tomember', function ($query) {
                        $query->orWhere('no', 'like', '%' . request('keys') . '%')
                            ->orWhere('nick_name', 'like', '%' . request('keys') . '%');
                    });
                });
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

