<?php

namespace App\Repositories;

use App\Facades\PlatformFacade;
use App\Models\DealCash;
use App\Models\DealVip;
use App\Models\MemberResource;
use App\Models\MemberUser;
use App\Models\MemberWalletCash;
use App\Models\MemberWalletRecharge;
use App\Models\MemberWalletRecord;
use App\Models\PlatformPayment;
use App\Models\PlatformPrice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * 余额购买记录
 * Class MemberRealnameRepository
 * @package App\Repositories
 */
class DealCashRepository extends BaseRepository
{
    public function model()
    {
        return DealCash::class;
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
                $query->where('money', request('money'));
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
        $member = MemberUser::find($data['member_id']);
        if (!isset($member)) {
            return $this->validation('所充值的会员不存在');
        }
        if (empty($data['money'])) {
            return $this->validation('充值金额不能为空');
        }
        if (!is_numeric($data['money'])) {
            return $this->validation('请输入正确的金额');
        }
        if ($data['money'] < PlatformFacade::config('recharge_min')) {
            return $this->validation('最小充值金额不能低于：' . PlatformFacade::config('recharge_min'));
        }
        if ($data['money'] > PlatformFacade::config('recharge_max')) {
            return $this->validation('最小充值金额不能高于：' . PlatformFacade::config('recharge_max'));
        }


        return parent::store($data);
    }

    /**
     * 生成充值支付
     * @param array $data
     * @return array|mixed
     */
    public function pay(array $data)
    {
        DB::beginTransaction();
        try {
            $case = DealCash::find($data['id']);
            if (!isset($case)) {
                DB::rollBack();
                return $this->validation('充值金额订单未知');
            }
            if ($case->status != 9) {
                DB::rollBack();
                return $this->validation('订单状态异常');
            }
            $case->status = 8;
            $case->save();

            if (!isset($case->recharge)) {
                $recharge = new MemberWalletRecharge();
                $recharge->member_id = $case->member_id;
                $recharge->money = $case->money;

                $payment = PlatformPayment::where('status', 0)->where('min_money', '<=', $recharge->money)->where('max_money', '>=', $recharge->money)->first();
                if (!isset($payment)) {
                    DB::rollBack();
                    return $this->validation('支付通道无效');
                }
                $recharge->payment_id = $payment->id;

                $case->recharge()->save($recharge);
            }
            DB::commit();
            return $this->succeed($case->recharge, '生成支付成功');
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->exception($ex);
        }
    }

    /**
     * 订单取消
     * @param $data
     * @return mixed
     */
    public function cancel($data)
    {

        $dealCash = $this->find($data['id']);
        if (!isset($dealCash)) {
            return $this->validation('订单不存在');
        }
        if ($dealCash->status != 9) {
            return $this->validation('订单状态异常');
        }
        $dealCash->status = $data['status'];
        $dealCash->save();
        return $this->succeed($dealCash, '订单取消成功');
    }

}

