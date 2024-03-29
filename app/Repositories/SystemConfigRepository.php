<?php

namespace App\Repositories;

use App\Models\SystemConfig;

class SystemConfigRepository extends BaseRepository
{

    public function model()
    {
        return SystemConfig::class;
    }

    public function lists($addWhere = null)
    {

        $where = function ($query) {
            if (request('name')) {
                $query->where('name', 'like', '%' . request('name') . '%');
            }
        };
        $this->where($where);
        if ($addWhere) {
            $this->where($addWhere);
        }
        return parent::paginate();

    }

    public function destroy(array $ids)
    {
        if (request()->user('SystemUser')->type != 0) {
            return $this->validation('无权操作');
        }
        return parent::destroy($ids);
    }
}