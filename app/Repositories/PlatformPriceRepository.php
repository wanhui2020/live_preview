<?php

namespace App\Repositories;

use App\Models\MemberUser;
use App\Models\PlatformPrice;

/**
 * 平台充值价格维护
 * Class MemberRealnameRepository
 * @package App\Repositories
 */
class PlatformPriceRepository extends BaseRepository
{
    public function model()
    {
        return PlatformPrice::class;
    }

    public function lists($addWhere = null)
    {
        $where = function ($query) {
            if (request('key')) {
                $query->where(function ($query) {
                    $query-> orWhere('name', 'like', '%' . request('key') . '%');
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

}

