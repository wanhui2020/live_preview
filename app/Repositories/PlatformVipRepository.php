<?php

namespace App\Http\Repositories;

use App\Models\PlatformReporttag;
use App\Models\PlatformVip;
use App\Repositories\BaseRepository;
//VIP管理
class PlatformVipRepository extends BaseRepository
{
    public function model()
    {
        return PlatformVip::class;
    }

    public function lists($addWhere = null)
    {
        $where = function ($query) {
            if (request('key')) {
                $query->where(function ($query) {
                    $query->where('name', 'like', '%' . request('key') . '%');
                });
            }
            if (request('status')!=null) {
                $query->where('status', request('status'));
            }
        };
        $this->where($where);
        if ($addWhere) {
            $this->where($addWhere);
        }
        $perPage = request('size');
        if ($perPage) {
            return parent::paginate($perPage);
        }
        return parent::paginate();
    }

}
