<?php

namespace App\Repositories;

use App\Facades\DealFacade;
use App\Facades\PayFacade;
use App\Models\DealGold;
use App\Models\DealVip;
use App\Models\MemberResource;
use App\Models\MemberUser;
use App\Models\MemberWalletRecharge;
use App\Models\PlatformPayment;
use App\Models\PlatformVip;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * vip购买记录
 * Class MemberRealnameRepository
 * @package App\Repositories
 */
class DealVipRepository extends BaseRepository
{
    public function model()
    {
        return DealVip::class;
    }

    public function lists($addWhere = null)
    {
        $where = function ($query) {
            if (request('key')) {
                $query->where(function ($query) {
                    $query->where('no', 'like', '%' . request('key') . '%')
                        ->orWhere('mobile', 'like', '%' . request('key') . '%')
                        ->orWhere('name', 'like', '%' . request('key') . '%');
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

    public function store(array $data)
    {
        $vip = PlatformVip::find($data['vip_id']);
        if (!isset($vip)) {
            return $this->validation('VIP产品不存在');
        }
        $data['money'] = $vip->current;
        $data['days'] = $vip->days;
        $data['status'] = 9;

        return parent::store($data);


    }


    /**
     * 生成支付
     * @param array $data
     * @return array|mixed
     */
    public function pay($data)
    {
        return DealFacade::vipPay($data['id']);
    }


    /**
     * 订单取消
     * @param $data
     * @return mixed
     */
    public function cancel($data)
    {
        $dealVip = DealVip::find($data['id']);
        if (!isset($dealVip)) {
            return $this->validation('订单不存在');
        }
        if ($dealVip->status != 9) {
            return $this->validation('订单状态异常');
        }
        $dealVip->status = $data['status'];
        $dealVip->save();
        return $this->succeed($dealVip, '订单取消成功');
    }
}

