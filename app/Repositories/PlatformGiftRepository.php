<?php

namespace App\Http\Repositories;

use App\Models\PlatformGift;
use App\Repositories\BaseRepository;

//礼物管理
class PlatformGiftRepository extends BaseRepository
{
    public function model()
    {
        return PlatformGift::class;
    }

    public function lists($addWhere = null)
    {
        $where = function ($query) {
            if (request('key')) {
                $query->where(function ($query) {
                    $query->where('name', 'like', '%' . request('key') . '%');
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

    public function store(array $data)
    {
        if (!is_numeric($data['gold'])) {
            return $this->validation('礼物金额格式错误');
        }
        if ($data['gold'] < 1) {
            return $this->validation('礼物金额不能小于1');
        }
        return parent::store($data);
    }

    public function update($data, $attribute = "id")
    {
        return parent::update($data, $attribute);
    }

    public function destroy(array $ids)
    {
        return parent::destroy($ids);
    }
}
