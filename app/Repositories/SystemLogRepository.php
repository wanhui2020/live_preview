<?php

namespace App\Http\Repositories;

use App\Models\SystemLog;
use App\Repositories\BaseRepository;

class SystemLogRepository extends BaseRepository
{
    public function model()
    {
        return SystemLog::class;
    }

    public function lists($addWhere = null)
    {
        $where = function ($query) {
            if (request('key')) {
                $query->where(function ($query) {
                    $query->where('name', 'like', '%' . request('key') . '%');
                });
            }
            if (request('customer')) {
                $query->wherehas('customer', function ($query) {
                    $query->where('no', 'like', '%' . request('customer') . '%')
                        ->orWhere('phone', 'like', '%' . request('customer') . '%')
                        ->orWhere('realname', 'like', '%' . request('customer') . '%');
                });
            }
            if (request('start_time') != null && request('end_time') != null) {
                $query->whereBetween('created_at', [request('start_time'), request('end_time')])->get();
            }
            if (request('status') != null) {
                $query->where('status', request('status'));
            }
        };
        $this->where($where);
        if ($addWhere) {
            $this->where($addWhere);
        }
        return parent::paginate();
    }

}
