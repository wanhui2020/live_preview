<?php

namespace App\Repositories;

use App\Models\SystemLogin;

class SystemLoginRepository extends BaseRepository
{
    public function model()
    {
        return SystemLogin::class;
    }

    public function lists($addWhere = null)
    {
        $where = function ($query) {
            if (request('login_time') != null) {
                $query->where('login_time', request('login_time'));
            }
            if (request('logout_time') != null) {
                $query->where('logout_time', request('logout_time'));
            }
            if (request('status') != null) {
                $query->where('status', request('status'));
            }
            if (request('relevance_type') != null) {
                $query->where('relevance_type', request('relevance_type'));
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

