<?php

namespace App\Repositories;

use App\Facades\PayFacade;
use App\Facades\WalletFacade;
use App\Models\MemberUser;
use App\Models\MemberWalletCash;
use App\Models\MemberWalletGold;
use App\Models\MemberWalletRecharge;
use App\Models\PlatformPayment;
use App\Models\PlatformPrice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MemberWalletRechargeRepository extends BaseRepository
{
    public function model()
    {
        return MemberWalletRecharge::class;

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
            //时间筛选
            if (request('dateTime') != null) {
                $dateTime = explode(' - ', request('dateTime'));
                $query->WhereBetween('created_at', [$dateTime[0], $dateTime[1]]);
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

    public function store(array $data)
    {
        if (!isset($data['price_id'])) {
            return $this->validation('充值金额未知');
        }
        $price = PlatformPrice::find($data['price_id']);
        $data['money'] = $price->money;

        $payment = PlatformPayment::where('status', 0)->where('min_money', '<=', $data['money'])->where('max_money', '>=', $data['money'])->first();
        if (!isset($payment)) {
            return $this->validation('支付通道无效');
        }
        $data['payment_id'] = $payment->id;
        return parent::store($data);
    }

    public function pay($no)
    {
        return PayFacade::pay($no);
    }


    public function audit(array $data)
    {

        return WalletFacade::rechargeAudit($data['id'], $data['status']);

    }
}

