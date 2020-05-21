<?php

namespace App\Repositories;

use App\Facades\WalletFacade;
use App\Models\MemberWalletGold;
use App\Models\MemberWalletWithdraw;

class MemberWalletWithdrawRepository extends BaseRepository
{
    public function model()
    {
        return MemberWalletWithdraw::class;
    }

    public function lists($addWhere = null)
    {
        $where = function ($query) {
            if (request('key')) {
                $query->whereHas('member',function ($query) {
                    $query->where('no', 'like', '%' . request('key') . '%')
                        ->orWhere('nick_name', 'like', '%' . request('key') . '%')
                        ->orWhere('mobile', 'like', '%' . request('key') . '%');
                });
            }
            if (request('status') != null) {
                $query->where('pay_status', request('status'));
            }
            if (request('no') != null) {
                $query->where('no', request('no'));
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



    public function audit(array $data)
    {

        return WalletFacade::withdrawAudit($data['id'], $data['status']);

    }

}

