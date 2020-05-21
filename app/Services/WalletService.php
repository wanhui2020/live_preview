<?php

namespace App\Services;


use App\Facades\PushFacade;
use App\Models\DealWithdraw;
use App\Models\MemberUser;
use App\Models\MemberUserRate;
use App\Models\MemberWalletCash;
use App\Models\MemberWalletGold;
use App\Models\MemberWalletRecharge;
use App\Models\MemberWalletRecord;
use App\Models\MemberWalletWithdraw;
use App\Traits\ResultTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 资金服务
 * @package App\Http\Service
 */
class WalletService
{
    use ResultTrait;


    public function __construct()
    {

    }

    /**
     * 充值审核
     * @param $id
     * @param int $status
     * @return array
     */
    public function rechargeAudit($id, $status = 0)
    {
        DB::beginTransaction();
        try {
            $recharge = MemberWalletRecharge::where('id', $id)->lockForUpdate()->first();
            if (!isset($recharge)) {
                DB::rollBack();
                return $this->validation('充值记录不存在');
            }
            if ($recharge->pay_status != 9) {
                DB::rollBack();
                return $this->validation('充值记录状态异常');
            }

            $recharge->pay_status = $status;
            if ($recharge->pay_status == 0) {
                $recharge->pay_time = Carbon::now()->toDateTimeString();
                $cash = MemberWalletCash::where('member_id', $recharge->member_id)->lockForUpdate()->first();

                //充值余额
                if ($recharge->relevance_type == 'DealCash') {
                    $dealCash = $recharge->relevance;
                    if ($dealCash->status == 8) {
                        $dealCash->status = 0;
                        $dealCash->save();

                        //生成充值记录

                        $cash->usable = $cash->usable + $dealCash->received;
                        $cash->lock = $cash->lock + $dealCash->received;
                        $cash->save();
                        $cash->records()->save(new MemberWalletRecord(['type' => 11, 'member_id' => $dealCash->member_id, 'money' => $dealCash->received, 'surplus' => $cash->balance]));
                        //平台收益
//                        $cash->records()->save(new MemberWalletRecord(['type' => 20, 'member_id' => 0, 'money' => bcsub($dealCash->money, $dealCash->received, 2), 'surplus' => bcsub($dealCash->money, $dealCash->received, 2)]));


                        //判断是否有代理
                        $this->income($dealCash->member_id, $dealCash->received, $cash, 20, 51);

                    }


                }

                //充值会员
                if ($recharge->relevance_type == 'DealVip') {
                    $dealVip = $recharge->relevance;
                    if ($dealVip->status == 8) {
                        $dealVip->status = 0;
                        $dealVip->save();

                        //生成充值记录
                        $cash->usable = $cash->usable + $dealVip->money;
                        $cash->lock = $cash->lock + $dealVip->money;
                        $cash->save();
                        $cash->records()->save(new MemberWalletRecord(['type' => 11, 'member_id' => $dealVip->member_id, 'money' => $dealVip->money, 'surplus' => $cash->balance]));


                        //充值会员余额支出
                        $cash->usable = $cash->usable - $dealVip->money;
                        if ($cash->lock >= $dealVip->money) {
                            $cash->lock = $cash->lock - $dealVip->money;
                        }
                        $cash->save();
                        $cash->records()->save(new MemberWalletRecord(['type' => 24, 'member_id' => $dealVip->member_id, 'money' => -$dealVip->money, 'surplus' => $cash->balance]));


                        //会员标记
                        $member = MemberUser::where('id', $dealVip->member_id)->lockForUpdate()->first();
                        $member->vip_id = $dealVip->vip_id;
                        $member->vip_end = $member->vip_end ? Carbon::parse($member->vip_end)->addDays($dealVip->days) : Carbon::now()->addDays($dealVip->days);
                        $member->save();
                    }
                }

                //充值金币
                if ($recharge->relevance_type == 'DealGold') {
                    $dealGold = $recharge->relevance;
                    if ($dealGold->status == 8) {
                        $dealGold->status = 0;
                        $dealGold->save();

                        //生成充值记录
                        $cash->usable = $cash->usable + $dealGold->money;
                        $cash->lock = $cash->lock + $dealGold->money;
                        $cash->save();
                        $cash->records()->save(new MemberWalletRecord(['type' => 11, 'member_id' => $dealGold->member_id, 'money' => $dealGold->money, 'surplus' => $cash->balance]));

//                        //平台收益
//                        $cash->records()->save(new MemberWalletRecord(['type' => 11, 'member_id' => 0, 'money' => bcsub($dealGold->money, $dealGold->received,2), 'surplus' => $cash->balance]));

                        //判断是否有代理
                        $this->income($dealGold->member_id, $dealGold->money, $cash, 20, 51);

                        //余额支出
                        $cash->usable = $cash->usable - $dealGold->money;
                        if ($cash->lock >= $dealGold->money) {

                            $cash->lock = $cash->lock - $dealGold->money;
                        }
                        $cash->save();
                        $cash->records()->save(new MemberWalletRecord(['type' => 23, 'member_id' => $dealGold->member_id, 'money' => -$dealGold->money, 'surplus' => $cash->balance]));

                        //充值金币
                        $gold = MemberWalletGold::where('member_id', $dealGold->member_id)->lockForUpdate()->first();

                        $gold->usable = $gold->usable + $dealGold->received;
                        $gold->lock = $gold->lock + $dealGold->received;
                        $gold->save();
                        $gold->records()->save(new MemberWalletRecord(['type' => 31, 'member_id' => $recharge->member_id, 'money' => +$dealGold->received, 'surplus' => $gold->balance]));


                    }
                }
                //会员打赏
                if ($recharge->relevance_type == 'DealGive') {
                    $dealGive = $recharge->relevance;
                    if ($dealGive->status == 8) {
                        $dealGive->status = 0;
                        $dealGive->save();

                        //发送方充值记录
                        $cash->usable = $cash->usable + $dealGive->money;
                        $cash->lock = $cash->lock + $dealGive->money;
                        $cash->save();
                        $cash->records()->save(new MemberWalletRecord(['type' => 11, 'member_id' => $dealGive->member_id, 'money' => $dealGive->money, 'surplus' => $cash->balance]));


                        //余额支出
                        $cash = MemberWalletCash::where('member_id', $dealGive->member_id)->lockForUpdate()->first();
                        $cash->usable = $cash->usable - $dealGive->money;
                        if ($cash->lock >= $dealGive->money) {
                            $cash->lock = $cash->lock - $dealGive->money;
                        }
                        $cash->save();
                        $cash->records()->save(new MemberWalletRecord(['type' => 22, 'member_id' => $dealGive->member_id, 'money' => -$dealGive->money, 'surplus' => $cash->balance]));


                        //余额收入
                        $cashTo = MemberWalletCash::where('member_id', $dealGive->to_member_id)->lockForUpdate()->first();
                        $cashTo->usable = $cashTo->usable + $dealGive->received;

                        $cashTo->save();
                        $cashTo->records()->save(new MemberWalletRecord(['type' => 13, 'member_id' => $dealGive->to_member_id, 'money' => $dealGive->received, 'surplus' => $cashTo->balance]));


                    }
                }

            }

            if (Auth::guard('SystemUser')->check()) {
                $user = Auth::guard('SystemUser')->user();
                $recharge->audit_uid = $user->id;
                $recharge->audit_name = $user->name;
                $recharge->audit_time = Carbon::now()->toDateTimeString();
            }
            $recharge->save();

            $body=['type'=>'recharge','data'=>[[
                'icon'=> isset($recharge['member']['head_pic']) ? $recharge['member']['head_pic'] : '',
                'nick_name'=> isset($recharge['member']['nick_name']) ? $recharge['member']['nick_name'] : '',
                'gold'=>$recharge['money']
            ],]];

            $title = '用户充值';
            PushFacade::pushAndroid('ALL', '', $title, json_encode($body), $type = 'MESSAGE', []);
            $a = PushFacade::pushIos('ALL', '', $title, json_encode($body), $type = 'MESSAGE', []);

            DB::commit();
            return $this->succeed($recharge, '审核成功');
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->exception($ex);
        }
    }

    /**
     * 提现审核
     * @param $id
     * @param int $status
     * @return array
     */
    public function withdrawAudit($id, $status = 0)
    {
        DB::beginTransaction();
        try {
            $withdraw = MemberWalletWithdraw::where('id', $id)->lockForUpdate()->first();
            if (!isset($withdraw)) {
                DB::rollBack();
                return $this->validation('提现记录不存在');
            }
            if ($withdraw->pay_status != 9) {
                DB::rollBack();
                return $this->validation('提现记录状态异常');
            }

            $withdraw->pay_status = $status;
            if ($withdraw->pay_status == 0) {
                $withdraw->pay_time = Carbon::now()->toDateTimeString();
                $cash = MemberWalletCash::where('member_id', $withdraw->member_id)->lockForUpdate()->first();
                if ($cash->freeze < $withdraw->money) {
                    DB::rollBack();
                    return $this->validation('资金账户冻结异常，请联系管理员');
                }
                $cash->freeze = $cash->freeze - $withdraw->money;
                $cash->save();
                $cash->records()->save(new MemberWalletRecord(['type' => 21, 'member_id' => $withdraw->member_id, 'money' => +$withdraw->money, 'surplus' => $cash->balance]));

                //提现订单修正
                if ($withdraw->relevance_type == 'DealWithdraw') {
                    $dealWithdraw = $withdraw->relevance;
                    if ($dealWithdraw->status == 8) {
                        $dealWithdraw->status = 0;
                        $dealWithdraw->save();
                    }
                }


            } else {  //审核拒绝\
                /*
                 * 提现转出的支付状态为支付失败
                 * 钱包里面的提现显示支付失败  status 1
                 */
                $dealWithdraw = $withdraw->relevance;
                $dealWithdraw->status = 1;
                $dealWithdraw->save();
            }
            if (in_array($withdraw->pay_status, [1, 2, 3])) {
                $cash = MemberWalletCash::where('member_id', $withdraw->member_id)->lockForUpdate()->first();
                if ($cash->freeze < $withdraw->money) {
                    DB::rollBack();
                    return $this->validation('资金账户冻结异常，请联系管理员');
                }
                $cash->usable = $cash->usable + $withdraw->money;
                $cash->freeze = $cash->freeze - $withdraw->money;
                $cash->save();
            }

            if (Auth::guard('SystemUser')->check()) {
                $user = Auth::guard('SystemUser')->user();
                $withdraw->audit_uid = $user->id;
                $withdraw->audit_name = $user->name;
                $withdraw->audit_time = Carbon::now()->toDateTimeString();
            }
            $withdraw->save();

            DB::commit();
            return $this->succeed($withdraw, '审核成功');
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->exception($ex);
        }
    }


    /**
     * 上级收益
     * @param $id 下单人的id
     * @param $dealMoney 获得的钱
     * @param $cash 是cash 还是gold
     * @param $agentType 经济人类型
     * @param $parentType 推荐人类型
     */
    public function income($id, $dealMoney, $cash, $agentType = 19, $parentType = 50, $type = 0)
    {
        //判断是否有代理
        $user = MemberUser::where(['id' => $id])->first();
        //判断收益时间
        if (bcsub(time(), strtotime($user->created_at)) < (24 * 60 * 60 * $user->rate->superior_revenue_time) || $user->rate->superior_revenue_time == 0) {
            $middlemanRechargeRate = 0;
            $middlemanRate = MemberUserRate::where(['member_id' => $user['agent_id']])->first();
            if ($agentType == 19) {
                $middlemanRechargeRate = $middlemanRate['middleman_recharge_rate'];//充值
            }
            if ($agentType == 20) {
                $middlemanRechargeRate = $middlemanRate['middleman_income_rate'];//激励
            }

            if ($user['agent_id'] && $middlemanRechargeRate > 0) {
                //返回充值的
                $money = bcmul($dealMoney, $middlemanRechargeRate, 2);
                if ($money > 0) {
                    if ($type == 1) {
                        $memberWallet = MemberWalletGold::where('member_id', $user['agent_id'])->lockForUpdate()->first();
                        $memberWallet->usable = $memberWallet->usable + $money;
                    } else {
                        $memberWallet = MemberWalletCash::where('member_id', $user['agent_id'])->lockForUpdate()->first();
                        $memberWallet->usable = $memberWallet->usable + $money;
//                        $memberWallet->lock = $memberWallet->lock + $money;
                    }

                    $memberWallet->save();
                    $cash->records()->save(new MemberWalletRecord(['type' => $agentType, 'member_id' => $user['agent_id'], 'money' => $money, 'surplus' => $memberWallet->balance, 'to_member_id' => $id]));

                    //推送
                    $memberUser = MemberUser::where(['id' => $user['agent_id']])->first();
                    if ($memberUser->push_token) {
                        PushFacade::pushToken($memberUser->push_token, $memberUser->app_platform, $memberUser->nick_name, "您有推广收益，请注意查收", $type = 'NOTICE', ['type' => 'member', 'id' => $memberUser->id, 'no' => $memberUser->no, 'nickname' => $memberUser->nick_name]);
                    }
                }
            }


            //判断是否有推荐人
            $recommenderIncomeRate = 0;
            $recommenderRate = MemberUserRate::where(['member_id' => $user['parent_id']])->first();
            if ($parentType == 51) {
                $recommenderIncomeRate = $recommenderRate['recommender_recharge_rate'];//充值
            }
            if ($parentType == 50) {
                $recommenderIncomeRate = $recommenderRate['recommender_income_rate'];//激励
            }
            if ($user['parent_id'] && $recommenderIncomeRate > 0) {
                //返回收入激励的
                $money = bcmul($dealMoney, $recommenderIncomeRate, 2);
                if ($money > 0) {
                    if ($type == 1) {
                        $memberWallet = MemberWalletGold::where('member_id', $user['parent_id'])->lockForUpdate()->first();
                        $memberWallet->usable = $memberWallet->usable + $money;
                    } else {
                        $memberWallet = MemberWalletCash::where('member_id', $user['parent_id'])->lockForUpdate()->first();
                        $memberWallet->usable = $memberWallet->usable + $money;
//                        $memberWallet->lock = $memberWallet->lock + $money;
                    }

                    $memberWallet->save();
                    $cash->records()->save(new MemberWalletRecord(['type' => $parentType, 'member_id' => $user['parent_id'], 'money' => $money, 'surplus' => $memberWallet->balance, 'to_member_id' => $id]));

                    //推送
                    $memberUser = MemberUser::where(['id' => $user['parent_id']])->first();
                    if ($memberUser->push_token) {
                        PushFacade::pushToken($memberUser->push_token, $memberUser->app_platform, $memberUser->nick_name, "您有推广收益，请注意查收", $type = 'NOTICE', ['type' => 'member', 'id' => $memberUser->id, 'no' => $memberUser->no, 'nickname' => $memberUser->nick_name]);
                    }
                }
            }
        }
    }
}
