<?php

namespace App\Repositories;

use App\Facades\DealFacade;
use App\Facades\PlatformFacade;
use App\Models\DealGive;
use App\Models\MemberResource;
use App\Models\MemberUser;
use App\Models\MemberWalletCash;
use App\Models\MemberWalletGold;
use App\Models\PlatformGift;
use Illuminate\Support\Facades\DB;

/**
 * 主播打赏
 * Class MemberRealnameRepository
 * @package App\Repositories
 */
class DealGiveRepository extends BaseRepository
{
    public function model()
    {
        return DealGive::class;
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
        try {
            if ($data['member_id'] == $data['to_member_id']) {
                DB::rollBack();
                return $this->validation('不能给自己打赏');
            }
            if (empty($data['money']) || $data['money'] < 1) {
                DB::rollBack();
                return $this->validation('打赏金额不能小于1元');
            }
            return parent::store($data);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    public function pay($data)
    {
        return DealFacade::givePay($data['id']);
    }

    /**
     * 订单取消
     * @param $data
     * @return mixed
     */
    public function cancel($data)
    {
        $dealGive = $this->find($data['id']);
        if (!isset($dealGive)) {
            return $this->validation('订单不存在');
        }
        if ($dealGive->status != 9) {
            return $this->validation('订单状态异常');
        }
        $dealGive->status = $data['status'];
        $dealGive->save();
        return $this->succeed($dealGive, '订单取消成功');
    }
}

