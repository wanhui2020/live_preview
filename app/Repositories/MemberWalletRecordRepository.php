<?php

namespace App\Repositories;

use App\Models\MemberWalletGold;
use App\Models\MemberWalletRecord;

class MemberWalletRecordRepository extends BaseRepository
{
    public function model()
    {
        return MemberWalletRecord::class;

    }

    public function lists($addWhere = null)
    {
        $where = function ($query) {
            if (request('key')) {
                $query->whereHas('member', function ($query) {
                    $query->where('no', 'like', '%' . request('key') . '%')
                        ->orWhere('nick_name', 'like', '%' . request('key') . '%');
                });
            }
            //时间筛选
            if (request('dateTime') != null) {
                $dateTime = explode(' - ', request('dateTime'));
                $query->WhereBetween('created_at', [$dateTime[0], $dateTime[1]]);
            }
            if (request('type') != null) {
                $query->where('type', request('type'));
            }
            if (request('type_id')) {
                $data = request('type_id');
                $type = explode(',', $data);
                $query->where('type', $type[0])->orWhere('type', $type[1]);

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

