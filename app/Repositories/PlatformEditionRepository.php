<?php

namespace App\Http\Repositories;

use App\Models\PlatformEdition;
use App\Repositories\BaseRepository;

class PlatformEditionRepository extends BaseRepository
{
    public function model()
    {
        return PlatformEdition::class;
    }

    public function lists($addWhere = null)
    {
        $where = function ($query) {
            if (request('key')) {
                $query->where(function ($query) {
                    $query->where('version', 'like', '%' . request('key') . '%');
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