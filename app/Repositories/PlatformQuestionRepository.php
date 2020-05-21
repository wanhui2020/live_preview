<?php

namespace App\Http\Repositories;

use App\Models\PlatformQuestion;
use App\Repositories\BaseRepository;
use Carbon\Carbon;

class PlatformQuestionRepository extends BaseRepository
{
    public function model()
    {
        return PlatformQuestion::class;
    }

    public function lists($addWhere = null)
    {
        $where = function ($query) {
            if (request('key')) {
                $query->where(function ($query) {
                    $query->orwhere('title', 'like', '%' . request('key') . '%');
                    $query->orwhere('content', 'like', '%' . request('key') . '%');
                });
            }
            if (request('start_time') != null && request('end_time') != null) {
                $query->whereBetween('created_at', [request('start_time'), request('end_time')])->get();
            }
            if (request('finish_status') != null) {
                $query->where('finish_status', request('finish_status'));
            }
        };
        $this->where($where);
        if ($addWhere) {
            $this->where($addWhere);
        }
        return parent::paginate();
    }

    public function finish($data, $attribute = "id")
    {
        $data['finish_status'] = 0;
        $data['finish_time'] = Carbon::now()->toDateTimeString();

        return parent::update($data, $attribute);
    }
}