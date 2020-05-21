<?php

namespace App\Repositories;

use App\Facades\DealFacade;
use App\Http\Resources\DealGoldResource;
use App\Models\DealGold;
use App\Models\DealVip;
use App\Models\MemberResource;
use App\Models\MemberUser;
use App\Models\MemberUserRate;
use App\Models\MemberWalletCash;
use App\Models\MemberWalletGold;
use App\Models\MemberWalletRecharge;
use App\Models\MemberWalletRecord;
use App\Models\PlatformPayment;
use App\Models\PlatformPaymentChannel;
use App\Models\PlatformPrice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 金币购买记录
 * Class MemberRealnameRepository
 * @package App\Repositories
 */
class DealGoldRepository extends BaseRepository
{
    public function model()
    {
        return DealGold::class;
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
        DB::beginTransaction();
        try {
            if (!isset($data['price_id'])) {
                DB::rollBack();;
                return $this->validation('价格项参数未知');
            }
            $price = PlatformPrice::find($data['price_id']);
            if (!isset($price)) {
                DB::rollBack();;
                return $this->validation('价格项不存在，请联系客服');
            }

            if (!isset($data['channel_id'])) {
                DB::rollBack();;
                return $this->validation('通道参数未知');
            }



            $data['name'] = $price->name;
            $data['money'] = $price->money;
            $data['gold'] = $price->money * env('PLATFORM_EXCHANGE_RATE') * $price->rate;
            $data['received'] = $data['gold'];
            $member = MemberUser::find($data['member_id']);
            if (isset($member->vip)) {
                $data['give'] = $data['received'] * $member->vip->recharge_give;
                $data['received'] = $data['received'] + $data['give'];
            }

            $resp = parent::store($data);

            if (!$resp['status']) {
                DB::rollBack();;
                return $this->validation('充值错误');
            }

            $dealGold = $resp['data'];

            //现金账户
            $cash = MemberWalletCash::where('member_id', $data['member_id'])->lockForUpdate()->first();
            if ($cash->usable >= $data['money']&&$data['channel_id']==0) {
                $dealGold->status = 0;
                $dealGold->save();
                //扣现金账户
                $cash->usable = $cash->usable - $data['money'];
                if ($cash->lock >= $data['money']) {
                    $cash->lock = $cash->lock - $data['money'];
                }

                $cash->save();
                //金币充值支出
                $cash->records()->save(new MemberWalletRecord(['type' => 23, 'member_id' => $data['member_id'], 'money' => -$data['money'], 'surplus' => $cash->balance]));


                //充值金币
                $gold = MemberWalletGold::where('member_id', $data['member_id'])->lockForUpdate()->first();
                $gold->usable = $gold->usable + $data['received'];
                $gold->lock = $gold->lock + $data['received'];
                $gold->save();
                //金币充值收入
                $gold->records()->save(new MemberWalletRecord(['type' => 31, 'member_id' => $data['member_id'], 'money' => $data['received'], 'surplus' => $gold->balance]));
                $dealGold->save();
                DB::commit();
                return $this->succeed($dealGold, '充值成功');
            } else {
                //生成充值订单
                $channel = PlatformPaymentChannel::find($data['channel_id']);
                if (!isset($channel)) {
                    DB::rollBack();;
                    return $this->validation('通道不存在，请联系客服');
                }
                $recharge = new MemberWalletRecharge();
                $recharge->member_id = $member->id;
                $recharge->money = $data['money'];
                $payment = $channel->payments()
                    ->where('min_money', '<=', $recharge->money)
                    ->where('max_money', '>=', $recharge->money)
                    ->where('status', 0)
                    ->first();
                if (!isset($payment)) {
                    DB::rollBack();
                    return $this->failure(1, '支付通道无效测试2', $dealGold);
                }
                $recharge->payment_id = $payment->id;
                $dealGold->recharge()->save($recharge);
                $dealGold->status = 8;
                $dealGold->save();
                DB::commit();
                return $this->succeed($dealGold, '订单已生成，请支付');
            }


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
        return DealFacade::goldPay($data['id']);
    }

    /**
     * 订单取消
     * @param $data
     * @return mixed
     */
    public function cancel($data)
    {
        $dealGold = $this->find($data['id']);
        if (!isset($dealGold)) {
            return $this->validation('订单不存在');
        }
        if ($dealGold->status != 9) {
            return $this->validation('订单状态异常');
        }
        $dealGold->status = $data['status'];
        $dealGold->save();
        return $this->succeed($dealGold, '订单取消成功');
    }
}

