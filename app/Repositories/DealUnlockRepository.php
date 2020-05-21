<?php

namespace App\Repositories;

use App\Facades\DealFacade;
use App\Facades\PlatformFacade;
use App\Facades\PushFacade;
use App\Models\DealUnlock;
use App\Models\MemberUser;
use App\Models\MemberUserRate;
use App\Models\MemberWalletGold;
use App\Models\MemberWalletRecord;
use App\Models\PlatformGift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DealUnlockRepository extends BaseRepository
{
    public function model()
    {
        return DealUnlock::class;
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
            if ($data['member_id'] == $data['to_member_id']) {
                DB::rollBack();
                return $this->validation('不能解锁自己');
            }

            $chat = DealUnlock::firstOrNew(['member_id' => $data['member_id'], 'to_member_id' => $data['to_member_id']]);
            if (!empty($chat->end_time) && Carbon::now()->lt(Carbon::parse($chat->end_time))) {
                DB::rollBack();
                return $this->succeed($chat, '解锁未到期，不需要解锁');
            }
            $chat->save();
            $goldFrom = MemberWalletGold::where('member_id', $chat->member_id)->lockForUpdate()->first();
            if (!isset($goldFrom)) {
                DB::rollBack();
                return $this->validation('发送方钱包异常');
            }
            if ($goldFrom->usable < $chat->gold) {

                $user = MemberUser::find($chat->member_id);
                if ($user->push_token) {
                    $body = [
                        'type' => 'popup'
                    ];
                    PushFacade::pushToken($user->push_token, $user->app_platform, '金币不足，请充值！', json_encode($body), $type = 'MESSAGE', ['type' => 'member', 'id' => $user->id, 'no' => $user->no, 'nickname' => $user->nick_name]);
                }

                DB::rollBack();
                return $this->validation('金币不足，请充值');
            }
            $goldFrom->usable = $goldFrom->usable - $chat->gold;
            if ($goldFrom->lock >= $chat->gold) {
                $goldFrom->lock = $goldFrom->lock - $chat->gold;
            }
            $goldFrom->save();
            //会员聊天解锁支出
            $goldFrom->records()->save(new MemberWalletRecord(['type' => 47, 'member_id' => $chat->member_id, 'money' => -$chat->gold, 'surplus' => $goldFrom->balance]));

            //判断是否有代理
            $agentId = MemberUser::where(['id' => $chat->member_id, 'is_middleman' => 1])->value('agent_id');
            $middlemanIncomeRate = MemberUserRate::where(['member_id' => $agentId])->value('middleman_income_rate');
            if ($agentId && $middlemanIncomeRate > 0) {
                //返回收入激励的
                $money = bcmul($chat->received, $middlemanIncomeRate, 2);
                $memberWalletGold = MemberWalletGold::where('member_id', $agentId)->lockForUpdate()->first();
                $memberWalletGold->usable = $memberWalletGold->usable + $money;
                $memberWalletGold->lock = $memberWalletGold->lock + $money;
                $memberWalletGold->save();
                $goldFrom->records()->save(new MemberWalletRecord(['type' => 19, 'member_id' => $agentId, 'money' => $money, 'surplus' => $memberWalletGold->balance]));
            }


            if ($chat->received > 0) {
                $goldTo = MemberWalletGold::where('member_id', $chat->to_member_id)->lockForUpdate()->first();
                if (!isset($goldTo)) {
                    DB::rollBack();
                    return $this->validation('接收方钱包异常');
                }
                $goldTo->usable = $goldTo->usable + $chat->received;
                $goldTo->save();

                //会员聊天解锁收入
                $goldTo->records()->save(new MemberWalletRecord(['type' => 38, 'member_id' => $chat->to_member_id, 'money' => $chat->received, 'surplus' => $goldTo->balance]));

                //判断是否有代理
                $agentId = MemberUser::where(['id' => $chat->to_member_id, 'is_middleman' => 1])->value('agent_id');
                $middlemanIncomeRate = MemberUserRate::where(['member_id' => $agentId])->value('middleman_income_rate');
                if ($agentId && $middlemanIncomeRate > 0) {
                    //返回收入激励的
                    $money = bcmul($chat->received, $middlemanIncomeRate, 2);
                    $memberWallet = MemberWalletGold::where('member_id', $agentId)->lockForUpdate()->first();
                    $memberWallet->usable = $memberWallet->usable + $money;
                    $memberWallet->save();
                    $goldTo->records()->save(new MemberWalletRecord(['type' => 19, 'member_id' => $agentId, 'money' => $money, 'surplus' => $memberWallet->balance]));

                }
            }
            DB::commit();
            return $this->succeed($chat, '礼物赠送成功');
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->exception($ex);
        }
    }
}
