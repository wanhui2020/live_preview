<?php

namespace App\Repositories;

use App\Facades\DealFacade;
use App\Facades\PlatformFacade;
use App\Facades\WalletFacade;
use App\Models\DealOrder;
use App\Models\DealWithdraw;
use App\Models\MemberWalletCash;
use App\Models\MemberWalletRecharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DealWithdrawRepository extends BaseRepository
{
    public function model()
    {
        return DealWithdraw::class;
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
            if (request('status') != null) {
                $query->where('status', request('status'));
            }
            if (request('money') != null) {
                $query->where('bank_account', 'like', '%' . request('money') . '%');
                $query->orWhere('bank_name', 'like', '%' . request('money') . '%');
            }
            if (request('no') != null) {
                $query->where('no', 'like', '%' . request('no') . '%');
            }

        };
        $this->where($where);
        if ($addWhere) {
            $this->where($addWhere);
        }
        return $this->paginate();
    }

    public function store(array $data)
    {
        DB::beginTransaction();
        try {

            if (count(DealWithdraw::where('member_id', $data['member_id'])->whereIn('status', [8, 9])->get()) > 0) {
                DB::rollBack();
                return $this->validation('提现有未完成订单，不可提现');
            }

            if ($data['money'] < PlatformFacade::config('withdraw_min')) {
                DB::rollBack();
                return $this->validation('提现金额不能低于' . PlatformFacade::config('withdraw_min'), PlatformFacade::config('withdraw_min'));
            }
            $cash = MemberWalletCash::where('id', $data['member_id'])->lockForUpdate()->first();
            if ($cash->drawing < $data['money']) {
                DB::rollBack();
                return $this->validation('可提金额不足', $cash->drawing);
            }
            if ($cash->drawing < $data['money']) {
                DB::rollBack();
                return $this->validation('可提现金额不足');
            }

            $cash->usable = $cash->usable - $data['money'];
            $cash->freeze = $cash->freeze + $data['money'];
            $cash->save();
            $resp = parent::store($data);
            if ($resp['status']) {
                DB::commit();
                return $this->succeed($resp['data']);
            }
            DB::rollBack();

            return $this->validation('申请提现失败');
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->exception($ex);
        }
    }


    /**
     * 生成支付
     * @param array $data
     * @return array|mixed
     */
    public function pay($data)
    {
        return DealFacade::withdrawPay($data['id']);
    }


    /**
     * 取消
     * @param $data
     * @return mixed
     */
    public function cancel($data)
    {
        $dealWithdraw = $this->find($data['id']);
        if (!isset($dealWithdraw)) {
            return $this->validation('支付订单不存在');
        }
        if ($dealWithdraw->status != 9) {
            return $this->validation('订单状态异常');
        }
        DB::beginTransaction();
        try {
            $cash = MemberWalletCash::where('member_id', $dealWithdraw->member_id)->lockForUpdate()->first();
            if ($cash->freeze < $dealWithdraw->money) {
                DB::rollBack();
                return $this->validation('资金账户冻结异常，请联系管理员');
            }
            $cash->usable = $cash->usable + $dealWithdraw->money;
            $cash->freeze = $cash->freeze - $dealWithdraw->money;
            $cash->save();
            $dealWithdraw->status = $data['status'];
            $dealWithdraw->save();
            DB::commit();
            return $this->succeed($dealWithdraw, '取消成功');
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->exception($ex);
        }
    }

}
