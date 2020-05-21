<?php

namespace App\Http\Repositories;

use App\Models\PlatformBasic;
use App\Models\PlatformReporttag;
use App\Repositories\BaseRepository;

//平台基础数据
class PlatformBasicRepository extends BaseRepository
{
    public function model()
    {
        return PlatformBasic::class;
    }

    public function lists($addWhere = null)
    {
        $where = function ($query) {
            if (request('key')) {
                $query->where(function ($query) {
                    $query->where('key', 'like', '%' . request('key') . '%')
                          ->orwhere('key', 'like', '%' . request('key') . '%');
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
        $perPage = request('size');
        if ($perPage) {
            return parent::paginate($perPage);
        }
        return parent::paginate();
    }

}
