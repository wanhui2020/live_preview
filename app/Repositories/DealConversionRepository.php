<?php

namespace App\Repositories;

use App\Facades\DealFacade;
use App\Facades\PlatformFacade;
use App\Models\DealOrder;
use App\Models\DealConversion;
use App\Models\MemberUser;
use App\Models\MemberWalletCash;
use App\Models\MemberWalletGold;
use App\Models\MemberWalletRecord;
use App\Models\PlatformPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DealConversionRepository extends BaseRepository
{
    public function model()
    {
        return DealConversion::class;
    }

    public function lists($addWhere = null)
    {
        $where = function ($query) {
            if (request('key') != null) {

            }
            if (request('begin_time') != '') {
                $query->where('created_at', '>=', request('begin_time'));
            }
            if (request('end_time') != '') {
                $query->where('created_at', '<=', request('end_time'));
            }
            if (request('no') != '') {
                $query->where(function ($query) {
                    $query->where('no', 'like', '%' . request('no') . '%');
                });
            }
            if (request('out_order') != '') {
                $query->where(function ($query) {
                    $query->orWhere('out_order', 'like', '%' . request('out_order') . '%');
                    $query->orWhere('customer_no', 'like', '%' . request('out_order') . '%');
                });
            }
            if (request('merchant') != '') {
                $query->whereHas('merchant', function ($query) {
                    $query->where('no', 'like', '%' . request('merchant') . '%');
                    $query->orWhere('name', 'like', '%' . request('merchant') . '%');
                });

            }

            if (request('merchant_id') != '') {
                $query->where('merchant_id', request('merchant_id'));
            }
            if (request('member_id') != '') {
                $query->where('member_id', request('member_id'));
            }
            if (request('bank_account') != '') {
                $query->where('bank_account', 'like', '%' . request('bank_account') . '%');
            }
            if (request('pay_account') != '') {
                $query->where('pay_account', 'like', '%' . request('pay_account') . '%');
                $query->orWhereHas('payPayee', function ($query) {
                    $query->where('bank_account', 'like', '%' . request('pay_account') . '%');
                });
            }
            if (request('scene_type') != '') {
                $query->where('scene_type', request('scene_type'));
            }
            if (request('type') != null) {
                $query->where('type', request('type'));
            }

            if (request('appeal_status') != '') {
                $query->where('appeal_status', request('appeal_status'));
            }

            if (request('order_status') != '') {
                if (request('order_status') == 98) {
                    $query->whereIn('order_status', [2, 3, 9]);
                } else if (request('order_status') == 99) {
                    $query->where('timeout', '>=', 600);
                } else {
                    $query->where('order_status', request('order_status'));
                }

            }
            if (request('settle_status') != '') {
                $query->where('settle_status', request('settle_status'));

            }

            if (request('money') != null) {
                $query->where(function ($query) {
                    $query->where('total', request('money'));
                    $query->orWhere('quantity', request('money'));
                });
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
            if (!is_numeric($data['gold'])) {
                DB::rollBack();
                return $this->validation('兑换金币数量格式错误');
            }
            if ($data['gold'] < 1) {
                DB::rollBack();
                return $this->validation('兑换金币数量不能小于1');
            }

            $member = MemberUser::find($data['member_id']);
            if (!isset($member)) {
                DB::rollBack();
                return $this->failure(1, '会员不存在');
            }
            //金币账户
            $gold = MemberWalletGold::where('member_id', $data['member_id'])->lockForUpdate()->first();
            if (!isset($gold)) {
                DB::rollBack();
                return $this->failure(1, '金币账户不存在');
            }
            if (($gold->usable - $gold->lock) < $data['gold']) {
                DB::rollBack();
                return $this->validation('可兑换金币不足');
            }

            if ($data['gold'] < PlatformFacade::config('conversion_min')) {
                DB::rollBack();
                return $this->validation('最小兑换金币不得低于：' . PlatformFacade::config('conversion_min'));
            }

            $data['conversion_rate'] = PlatformFacade::config('conversion_rate');
            $data['conversion_commission'] = $data['gold'] * $data['conversion_rate'];
            $data['received_gold'] = $data['gold'] - $data['conversion_commission'];
            $data['gold_rate'] = PlatformFacade::config('gold_rate');
            $data['money'] = $data['received_gold'] / $data['gold_rate'];

            $gold->usable = $gold->usable - $data['gold'];
            $gold->save();
            //金币兑换支出
            $gold->records()->save(new MemberWalletRecord(['type' => 46, 'member_id' => $data['member_id'], 'money' => -$data['gold'], 'surplus' => $gold->balance]));


            //现金账户
            $cash = MemberWalletCash::where('member_id', $data['member_id'])->lockForUpdate()->first();
            if (!isset($cash)) {
                DB::rollBack();
                return $this->failure(1, '现金账户 不存在');
            }
            $cash->usable = $cash->usable + $data['money'];
            $cash->save();
            //余额金币兑换收入
            $cash->records()->save(new MemberWalletRecord(['type' => 12, 'member_id' => $data['member_id'], 'money' => $data['money'], 'surplus' => $cash->balance]));


            $resp = parent::store($data);
            if (!$resp['status']) {
                DB::rollBack();;
                return $this->validation('兑换错误');
            }


            DB::commit();
            return $this->succeed($resp['data'], '充值成功');

        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->exception($ex);
        }

    }

}
