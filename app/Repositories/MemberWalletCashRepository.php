<?php

namespace App\Repositories;

use App\Models\MemberWalletCash;

class MemberWalletCashRepository extends BaseRepository
{
    public function model()
    {
        return MemberWalletCash::class;
    }

    public function lists($addWhere = null)
    {
        $where = function ($query) {
            if (request('key')) {
                $query->where(function ($query) {
                    $query->whereHas('member', function ($query) {
                        $query->where('no', 'like', '%' . request('key') . '%')
                            ->orWhere('nick_name', 'like', '%' . request('key') . '%')
                            ->orWhere('mobile', 'like', '%' . request('key') . '%');
                    });
                });
            }
            if (request('status') != null) {
                $query->where('status', request('status'));
            }
            if (request('agent') != null) {
                $query->whereHas('agent', function ($query) {
                    $query->where('no', 'like', '%' . request('agent') . '%')
                        ->orWhere('name', 'like', '%' . request('agent') . '%')
                        ->orWhere('email', 'like', '%' . request('agent') . '%')
                        ->orWhere('phone', 'like', '%' . request('agent') . '%');
                });
            }
            if (request('idcard')) {
                $query->where('idcard', 'like', '%' . request('idcard') . '%');
            }
            if (is_integer(request('is_otc'))) {
                $query->where('is_otc', request('is_otc'));
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

