<?php

namespace App\Repositories;

use App\Models\PlatformSendMessage;

class PlatformSendMessageRepository extends BaseRepository
{
    public function model()
    {
        return PlatformSendMessage::class;
    }

    public function lists($addWhere = null)
    {
        $where = function ($query) {
            if (request('key')) {
                $query->where(function ($query) {
                    $query->where('content', 'like', '%' . request('key') . '%');
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