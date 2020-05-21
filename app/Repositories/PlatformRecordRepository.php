<?php
namespace App\Http\Repositories;

use App\Repositories\BaseRepository;
use App\Models\PlatformRecord;
class PlatformRecordRepository extends BaseRepository
{
    public function model()
    {
        return PlatformRecord::class;
    }
    public function lists($addWhere = null)
    {
        $where = function ($query) {
            if (request('key')) {
                $query->where(function ($query) {
                    $query->where('phone', 'like', '%' . request('key') . '%');
                    $query->orwhere('name', 'like', '%' . request('key') . '%');
                    $query->orwhere('title', 'like', '%' . request('key') . '%');
                    $query->orwhere('content', 'like', '%' . request('key') . '%');
                });
            }
        };
        $this->where($where);
        if ($addWhere) {
            $this->where($addWhere);
        }
        return parent::paginate();

    }

    public function store(array $data)
    {
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