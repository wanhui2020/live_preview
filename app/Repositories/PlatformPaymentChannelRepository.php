<?php

namespace App\Repositories;

use App\Models\MemberUser;
use App\Models\PlatformPaymentChannel;

/**
 * 支付通道
 * Class MemberRealnameRepository
 * @package App\Repositories
 */
class PlatformPaymentChannelRepository extends BaseRepository
{
    public function model()
    {
        return PlatformPaymentChannel::class;
    }

    public function lists($addWhere = null)
    {
        $where = function ($query) {
            if (request('key')) {
                $query->where('name', 'like', '%' . request('key') . '%');
            }
            if (request('status') != null) {
                $query->where('status', request('status'));
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

