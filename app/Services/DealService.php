<?php

namespace App\Services;


use App\Facades\DealFacade;
use App\Facades\ImFacade;
use App\Facades\PlatformFacade;
use App\Facades\PushFacade;
use App\Facades\WalletFacade;
use App\Jobs\ImJob;
use App\Models\DealCash;
use App\Models\DealGive;
use App\Models\DealGold;
use App\Models\DealMessage;
use App\Models\DealTalk;
use App\Models\DealVip;
use App\Models\DealWithdraw;
use App\Models\MemberUser;
use App\Models\MemberUserRate;
use App\Models\MemberWalletCash;
use App\Models\MemberWalletGold;
use App\Models\MemberWalletRecharge;
use App\Models\MemberWalletRecord;
use App\Models\MemberWalletWithdraw;
use App\Models\PlatformCharm;
use App\Models\PlatformConfig;
use App\Models\PlatformPayment;
use App\Traits\ResultTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 业务服务
 * Class DealService
 * @package App\Services
 */
class DealService
{
    use ResultTrait;


    /**
     * 通话扣费
     */
    public function talkDeduct($id = null)
    {
        $talks = DealTalk::where(function ($query) use ($id) {
            if ($id) {
                $query->where('id', $id);
            }
            $query->where('status', 1);
        })->get();
        foreach ($talks as $item) {
            DB::beginTransaction();
            try {
                $talk = DealTalk::where('id', $item->id)->lockForUpdate()->first();
                $talk->duration = Carbon::now()->diffInSeconds($talk->begin_time);
                $talk->save();

                $gold = MemberWalletGold::where('member_id', $talk->dialing_id)->lockForUpdate()->first();

                if ($gold->consumable < $talk->price) {
                    $this->talkHangup($talk->room_id, 4);
                } else {
                    $gold->freeze = $talk->total;
                    $gold->save();
                }
                DB::commit();
                //自定义信息进行通知
                ImFacade::sendTalkDeduction($talk->dialing->no, $talk->called->no, $talk->room_id, floor(($gold->usable - $gold->freeze) / $talk->price));
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->exception($e);
            }


        }

        return $this->succeed($talks);


    }

    /**
     * 超时未接听检查
     */
    public function talkTimeout($id = null)
    {
        try {

            $talks = DealTalk::where(function ($query) use ($id) {
                if ($id) {
                    $query->where('id', $id);
                }
                $query->whereIn('status', [8, 9]);
                $query->where('created_at', '<', Carbon::now()->addMinutes(-1));
            })->get();
            foreach ($talks as $talk) {
                $this->talkHangup($talk->room_id, 1);
            }

            return $this->succeed($talks);

        } catch (\Exception $e) {

            return $this->exception($e);
        }

    }

    /**
     * 主叫创建并加入房间
     * @param array $data
     * @return array|mixed
     */
    public function talkRoom($roomId)
    {
        DB::beginTransaction();
        try {
            $talk = DealTalk::where('room_id', $roomId)->lockForUpdate()->first();
            if (!isset($talk)) {
                DB::rollBack();
                return $this->validation('通话订单不存在');
            }
            if ($talk->status != 9) {
                DB::rollBack();
                return $this->validation('当前订单状态异常');
            }
            $talk->status = 8;
            $talk->save();
            DB::commit();
            return $this->succeed($talk);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->exception($e);
        }

    }

    /**
     * 被叫方接听
     * @param array $data
     * @return array|mixed
     */
    public function talkAnswer($roomId)
    {
        DB::beginTransaction();
        try {
            $talk = DealTalk::where('room_id', $roomId)->lockForUpdate()->first();
            if (!isset($talk)) {
                DB::rollBack();
                return $this->validation('通话订单不存在');
            }
            if ($talk->status != 8) {
                DB::rollBack();
                return $this->validation('当前订单状态异常');
            }

            //平台开启主叫付费

            $isDeductionCallingFee = PlatformFacade::config('is_deduction_calling_fee');
            //如果主叫方为主播
            $formMemberUser = MemberUser::where(['id' => $talk['dialing_id']])->first();
            $toMemberUser = MemberUser::where(['id' => $talk['called_id']])->first();
            if ($talk->dialing->type == 0 && $talk->called->type == 0 || $talk->dialing->type == 0 && $talk->called->type == 1 || $talk->dialing->type == 1 && $talk->called->type == 0 || $talk->dialing->type == 1 && $talk->called->type == 1 ) {
//                if ($isDeductionCallingFee == 1 && $formMemberUser['is_selfie'] == 0 || $isDeductionCallingFee != 0 && $toMemberUser['is_selfie'] != 0) {
                if ($isDeductionCallingFee == 1 && $toMemberUser['is_selfie'] == 0) {
                    $gold = MemberWalletGold::where('member_id', $talk['dialing_id'])->lockForUpdate()->first();
                }else{
                    $gold = MemberWalletGold::where('member_id', $talk['called_id'])->lockForUpdate()->first();
                }
                if ($gold->usable < $talk['price']) {
                    $user = MemberUser::find($talk['called_id']);
                    if ($user->push_token) {
                        $body = [
                            'type' => 'popup'
                        ];
                        PushFacade::pushToken($user->push_token, $user->app_platform, '能量不足，请充值！', json_encode($body), $type = 'MESSAGE', ['type' => 'member', 'id' => $user->id, 'no' => $user->no, 'nickname' => $user->nick_name]);
                    }
                    DealFacade::talkHangup($roomId, 4);
//                        DB::rollBack();
                    return $this->validation('当前能量不足通话一分钟');
                }
//                }
            }

            $talk->status = 1;
            $talk->begin_time = Carbon::now()->toDateTimeString();
            $talk->save();

            $gold = MemberWalletGold::where('member_id', $talk->dialing_id)->lockForUpdate()->first();
            $gold->freeze = $talk->price;
            $gold->save();

            DB::commit();
            return $this->succeed($talk);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->exception($e);
        }

    }

    /**
     * 通话挂断
     * @param array $data
     * @param int $type 0正常挂断1超时挂断2被叫拒绝3异常挂断
     * @return array|mixed
     */
    public function talkHangup($roomId, $type = 0)
    {
        set_time_limit(0);
        DB::beginTransaction();
        try {
            $talk = DealTalk::where('room_id', $roomId)->lockForUpdate()->first();
            if (!isset($talk)) {
                DB::rollBack();
                return $this->validation('通话订单不存在');
            }
            if (in_array($talk->status, [0])) {
                DB::rollBack();
                return $this->validation('当前订单已结束');
            }

            $talk->way = $type;

            //未接听挂断
            if (in_array($talk->status, [8, 9])) {
                $talk->status = 0;
                $talk->save();

                //退还预扣费
                $gold = MemberWalletGold::where('member_id', $talk->dialing_id)->lockForUpdate()->first();
                $gold->freeze = 0;
                $gold->save();
            }

            //通话结束挂断
            if (in_array($talk->status, [1])) {
                $talk->status = 0;
                $talk->end_time = Carbon::now()->toDateTimeString();
                $talk->duration = Carbon::parse($talk->end_time)->diffInSeconds($talk->begin_time);
                $talk->save();

                if ($talk->dialing->type == 0 && $talk->called->type == 0 || $talk->dialing->type == 0 && $talk->called->type == 1 || $talk->dialing->type == 1 && $talk->called->type == 0 || $talk->dialing->type == 1 && $talk->called->type == 1 ) {
                    //平台开启主叫付费
                    $isDeductionCallingFee = PlatformFacade::config('is_deduction_calling_fee');
                    //如果主叫方为主播
                    $formMemberUser = MemberUser::where(['id' => $talk->dialing_id])->first();
//                    if ($isDeductionCallingFee != 0 && $formMemberUser['is_selfie'] != 0 || $isDeductionCallingFee != 0 && $formMemberUser['is_selfie'] == 0) {
                        if ($isDeductionCallingFee == 1 && $formMemberUser['is_selfie'] == 0) {
//                if ($isDeductionCallingFee != 0 && $formMemberUser['is_real'] == 0 && $formMemberUser['is_selfie'] == 0) {
                        $goldDialing = MemberWalletGold::where('member_id', $talk->called_id)->lockForUpdate()->first();
                        $formFreeId = $talk->called_id;
                        $toFreeId = $talk->dialing_id;
                    } else {
                        $goldDialing = MemberWalletGold::where('member_id', $talk->dialing_id)->lockForUpdate()->first();
                        $formFreeId = $talk->dialing_id;
                        $toFreeId = $talk->called_id;
                    }
                    //主叫方扣费
//                $goldDialing = MemberWalletGold::where('member_id', $talk->dialing_id)->lockForUpdate()->first();
                    $goldDialing->freeze = 0;
                    $goldDialing->usable = $goldDialing->usable - $talk->total;
//                    if ($goldDialing->lock >= $talk->total) {
//                        $goldDialing->lock = $goldDialing->lock - $talk->total;
//                    }

                    $goldDialing->save();
                    $goldDialing->records()->save(new MemberWalletRecord(['type' => 41, 'member_id' => $formFreeId, 'money' => -$talk->total, 'surplus' => $goldDialing->balance]));

                    //如果主叫方为主播(扣被叫方的钱)
//                    if ($isDeductionCallingFee != 0 && $formMemberUser['is_real'] == 0 || $isDeductionCallingFee != 0 && $formMemberUser['is_selfie'] == 0) {
                    if ($isDeductionCallingFee == 1 && $formMemberUser['is_selfie'] == 0) {
                        $called = $talk->dialing;
                    } else {
                        $called = $talk->called;
                    }


                    //只有认证的用户才能生成收益
                    if ($called->is_selfie == 0) {
                        if ($talk->platform_way == 0) {
                            //被叫方收益能量
                            $goldCalled = MemberWalletGold::where('member_id', $toFreeId)->lockForUpdate()->first();
                            $goldCalled->usable = $goldCalled->usable + $talk->received;
                            $goldCalled->save();
                            $goldCalled->records()->save(new MemberWalletRecord(['type' => 32, 'member_id' => $toFreeId, 'money' => $talk->received, 'surplus' => $goldCalled->balance]));

                            //平台收益
                            $goldCalled->records()->save(new MemberWalletRecord(['type' => 32, 'member_id' => 0, 'money' => bcdiv($talk->platform_commission, env('PLATFORM_EXCHANGE_RATE'), 2), 'surplus' => bcdiv($talk->platform_commission, env('PLATFORM_EXCHANGE_RATE'), 2)]));


                            //判断是否有代理
                            WalletFacade::income($toFreeId, $talk->received, $goldCalled, 19, 50, 1);

                        } else {
                            //被叫方收益金币
                            $cashCalled = MemberWalletCash::where('member_id', $toFreeId)->lockForUpdate()->first();
                            //根据平台兑换比例进行能量兑换金币
                            $cashCalled->usable = $cashCalled->usable + $talk->received;
                            $cashCalled->save();
                            $cashCalled->records()->save(new MemberWalletRecord(['type' => 32, 'member_id' => $toFreeId, 'money' => $talk->received, 'surplus' => $cashCalled->balance]));

                            //平台收益
                            $cashCalled->records()->save(new MemberWalletRecord(['type' => 32, 'member_id' => 0, 'money' => bcdiv($talk->platform_commission, env('PLATFORM_EXCHANGE_RATE'), 2), 'surplus' => bcdiv($talk->platform_commission, env('PLATFORM_EXCHANGE_RATE'), 2)]));

                            //判断是否有代理
                            WalletFacade::income($toFreeId, $talk->received, $cashCalled, 19, 50);
                        }
                    }

                }
            }

            $dialing = $talk->dialing;
            $dialing->live_status = 0;
            $dialing->save();

            $called = $talk->called;
            $called->live_status = 0;
            $called->save();
            DB::commit();

            return $this->succeed($talk);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->exception($e);
        }

    }

    /**
     * 能量充值支付申请
     * @param array $data
     * @return array|mixed
     */
    public function goldPay($id)
    {
        DB::beginTransaction();
        try {
            $dealGold = DealGold::where('id', $id)->lockForUpdate()->first();
            if (!isset($dealGold)) {
                DB::rollBack();
                return $this->validation('订单未知');
            }
            if ($dealGold->status != 9) {
                DB::rollBack();
                return $this->validation('订单状态异常');
            }
            //扣费处理
            $cash = MemberWalletCash::where('member_id', $dealGold->member_id)->lockForUpdate()->first();
            //余额足够直接扣费
            if ($cash->usable >= $dealGold->money) {
                $dealGold->status = 0;
                $cash->usable = $cash->usable - $dealGold->money;
                if ($cash->lock >= $dealGold->money) {
                    $cash->lock = $cash->lock - $dealGold->money;
                }
                $cash->save();

                //能量充值支出
                $cash->records()->save(new MemberWalletRecord(['type' => 23, 'member_id' => $dealGold->member_id, 'money' => -$dealGold->money, 'surplus' => $cash->balance]));

                //充值金币
                $gold = MemberWalletGold::where('member_id', $dealGold->member_id)->lockForUpdate()->first();
                $gold->usable = $gold->usable + $dealGold->received;
                $gold->lock = $gold->lock + $dealGold->received;
                $gold->save();
                //能量充值收入
                $gold->records()->save(new MemberWalletRecord(['type' => 31, 'member_id' => $dealGold->member_id, 'money' => $dealGold->received, 'surplus' => $gold->balance]));
            } else {

                if (!isset($dealGold->recharge)) {
                    $dealGold->status = 8;
                    $recharge = new MemberWalletRecharge();
                    $recharge->member_id = $dealGold->member_id;
                    $recharge->money = $dealGold->money;
                    $payment = PlatformPayment::where('status', 0)->where('min_money', '<=', $recharge->money)->where('max_money', '>=', $recharge->money)->first();

                    if (!isset($payment)) {
                        DB::rollBack();
                        return $this->failure(1, '支付通道无效测试1', $dealGold);
                    }
                    $recharge->payment_id = $payment->id;
                    $dealGold->recharge()->save($recharge);
                    $dealGold->status = 8;
                }
            }

            $dealGold->save();

            DB::commit();
            return $this->succeed($dealGold, '生成支付成功');
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->exception($ex);
        }

    }

    /**
     * 聊天收费
     * @param array $data
     * @return array|mixed
     */
    public function dealChat($formId, $toId, $content)
    {
        DB::beginTransaction();
        try {
            $formMember = MemberUser::where('no', $formId)->first();

            if (!isset($formMember)) {
                DB::rollBack();
                return $this->validation('发送方异常');
            }
            $toMember = MemberUser::where('no', $toId)->first();
            if (!isset($toMember)) {
                DB::rollBack();
                return $this->validation('接收方异常');
            }

            //发送方能量消费
            if ($formMember['type'] == 0 && $toMember['type'] == 0 || $formMember['type'] == 0 && $toMember['type'] == 1 || $formMember['type'] == 1 && $toMember['type'] == 0 || $formMember['type'] == 1 && $toMember['type'] == 1 ) {
                $formGold = MemberWalletGold::where('member_id', $formMember->id)->lockForUpdate()->first();
                if (!isset($formGold)) {
                    DB::rollBack();
                    return $this->validation('能量异常');
                }
            }
                $rate = $toMember->rate;
                $textFee = 0;
                if ($rate->text_fee > 0) {
                    $textFee = $rate->text_fee;
                    $textRate = $rate->text_rate;
                } else {
                    $textRate = 0;
                }


            //认证会员给非认证会员发送信息免费
            if ($formMember->is_selfie == 0) {
                $textFee = 0;
            }


            //生成聊天记录
            $message = new DealMessage();
            $message->member_id = $formMember->id;
            $message->to_member_id = $toMember->id;
            $message->price = $textFee;
            $message->platform_way = getenv('PLATFORM_WAY', 1);
            $message->platform_rate = $textRate;
            $message->content = $content;
            $message->save();
            $message->refresh();

            if ($formMember['type'] == 2  || $toMember['type'] == 2) {
                DB::commit();
                return $this->succeed($message, '生成支付成功');
            }
            //扣发送方能量
            if ($formGold->usable < $textFee) {
                $user = MemberUser::find($formMember->id);
                if ($user->push_token) {
                    $body = [
                        'type' => 'popup'
                    ];
                    PushFacade::pushToken($user->push_token, $user->app_platform, '金币不足，请充值！', json_encode($body), $type = 'MESSAGE', ['type' => 'member', 'id' => $user->id, 'no' => $user->no, 'nickname' => $user->nick_name]);
                }

                DB::rollBack();
                return $this->validation('能量不足');
            }
            $formGold->usable = $formGold->usable - $textFee;
            if ($formGold->lock >= $textFee) {
                $formGold->lock = $formGold->lock - $textFee;
            }
            $formGold->save();
            $formGold->records()->save(new MemberWalletRecord(['type' => 43, 'member_id' => $message->member_id, 'money' => -$message->total, 'surplus' => $formGold->balance]));

            if ($message->received > 0 && $toMember->is_selfie == 0) {
                //文本信息收入能量
                if ($message->platform_way == 0) {

                    $goldTo = MemberWalletGold::where('member_id', $message->to_member_id)->lockForUpdate()->first();
                    if (!isset($goldTo)) {
                        DB::rollBack();
                        return $this->validation('接收方钱包异常');
                    }
                    $goldTo->usable = $goldTo->usable + $message->received;
                    $goldTo->save();

                    //接收收入
                    $goldTo->records()->save(new MemberWalletRecord(['type' => 33, 'member_id' => $message->to_member_id, 'money' => $message->received, 'surplus' => $goldTo->balance]));

                    //平台收益
                    $goldTo->records()->save(new MemberWalletRecord(['type' => 33, 'member_id' => 0, 'money' => bcdiv($message->platform_commission, env('PLATFORM_EXCHANGE_RATE'), 2), 'surplus' => bcdiv($message->platform_commission, env('PLATFORM_EXCHANGE_RATE'), 2)]));

                    //判断是否有代理
                    WalletFacade::income($message->to_member_id, $message->received, $goldTo, 19, 50, 1);

                }
                //收入现金
                if ($message->platform_way == 1) {
                    $cashTo = MemberWalletCash::where('member_id', $message->to_member_id)->lockForUpdate()->first();
                    if (!isset($cashTo)) {
                        DB::rollBack();
                        return $this->validation('接收方钱包异常');
                    }
                    $cashTo->usable = $cashTo->usable + $message->received;
                    $cashTo->save();

                    //礼物接收收入
                    $cashTo->records()->save(new MemberWalletRecord(['type' => 16, 'member_id' => $message->to_member_id, 'money' => $message->received, 'surplus' => $cashTo->balance]));

                    //平台收益
                    $cashTo->records()->save(new MemberWalletRecord(['type' => 16, 'member_id' => 0, 'money' => bcdiv($message->platform_commission, env('PLATFORM_EXCHANGE_RATE'), 2), 'surplus' => bcdiv($message->platform_commission, env('PLATFORM_EXCHANGE_RATE'), 2)]));

                    //判断是否有代理
                    WalletFacade::income($message->to_member_id, $message->received, $cashTo, 19, 50);
                }

            }

            DB::commit();
            return $this->succeed($message, '生成支付成功');
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->exception($ex, '发送异常，请联系管理员');
        }

    }


    /**
     * 金币充值支付
     * @param array $data
     * @return array|mixed
     */
    public function cashPay($id)
    {
        DB::beginTransaction();
        try {
            $dealCash = DealCash::where('id', $id)->lockForUpdate()->first();
            if (!isset($dealCash)) {
                DB::rollBack();
                return $this->validation('订单未知');
            }
            if ($dealCash->status != 9) {
                DB::rollBack();
                return $this->validation('订单状态异常');
            }
            if (!isset($dealCash->recharge)) {
                $recharge = new MemberWalletRecharge();
                $recharge->member_id = $dealCash->member_id;
                $recharge->money = $dealCash->money;
                $payment = PlatformPayment::where('status', 0)->where('min_money', '<=', $recharge->money)->where('max_money', '>=', $recharge->money)->first();
                if (!isset($payment)) {
                    DB::rollBack();
                    return $this->failure(1, '支付通道无效', $dealCash);
                }
                $recharge->payment_id = $payment->id;
                $dealCash->recharge()->save($recharge);
                $dealCash->status = 8;
            }

            $dealCash->save();

            DB::commit();
            return $this->succeed($dealCash, '生成支付成功');
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->exception($ex);
        }

    }


    /**
     * VIP购买支付申请
     * @param array $data
     * @return array|mixed
     */
    public function vipPay($id)
    {

        DB::beginTransaction();
        try {
            $dealVip = DealVip::where('id', $id)->lockForUpdate()->first();
            if (!isset($dealVip)) {
                DB::rollBack();
                return $this->validation('订单未知');
            }
            if ($dealVip->status != 9) {
                DB::rollBack();
                return $this->validation('订单状态异常');
            }

            //扣费处理
            $cash = MemberWalletCash::where('member_id', $dealVip->member_id)->lockForUpdate()->first();
            //余额足够直接扣费
            if ($cash->usable >= $dealVip->money) {
                $dealVip->status = 0;
                $cash->usable = $cash->usable - $dealVip->money;
                if ($cash->lock >= $dealVip->money) {
                    $cash->lock = $cash->lock - $dealVip->money;
                }
                $cash->save();

                //VIP购买支出
                $cash->records()->save(new MemberWalletRecord(['type' => 24, 'member_id' => $dealVip->member_id, 'money' => -$dealVip->money, 'surplus' => $cash->balance]));


                //会员标记
                $member = MemberUser::where('id', $dealVip->member_id)->lockForUpdate()->first();
                $member->vip_id = $dealVip->vip_id;
                $member->vip_end = $member->vip_end ? Carbon::parse($member->vip_end)->addDays($dealVip->days) : Carbon::now()->addDays($dealVip->days);
                $member->save();

            } else {
                $dealVip->status = 8;
                if (!isset($dealVip->recharge)) {
                    $recharge = new MemberWalletRecharge();
                    $recharge->member_id = $dealVip->member_id;
                    $recharge->money = $dealVip->money;
                    $payment = PlatformPayment::where('status', 0)->where('min_money', '<=', $recharge->money)->where('max_money', '>=', $recharge->money)->first();
                    if (!isset($payment)) {
                        DB::rollBack();
                        return $this->validation('支付通道无效');
                    }
                    $recharge->payment_id = $payment->id;

                    $dealVip->recharge()->save($recharge);
                    $dealVip->status = 8;
                }
            }

            $dealVip->save();
            DB::commit();
            return $this->succeed($dealVip->recharge, '生成支付成功');
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->exception($ex);
        }

    }


    /**
     * 打赏支付
     * @param array $data
     * @return array|mixed
     */
    public function givePay($id)
    {
        DB::beginTransaction();
        try {
            $dealGive = DealGive::where('id', $id)->lockForUpdate()->first();
            if (!isset($dealGive)) {
                DB::rollBack();
                return $this->failure(1, '订单不存在');
            }
            if ($dealGive->status == 9) {
                //打赏人
                $cashFrom = MemberWalletCash::where('id', $dealGive->member_id)->lockForUpdate()->first();
                if (!isset($cashFrom)) {
                    DB::rollBack();
                    return $this->validation('发送方钱包异常');
                }
                if ($cashFrom->usable >= $dealGive->money) {
                    $cashFrom->usable = $cashFrom->usable - $dealGive->money;
                    if ($cashFrom->lock >= $cashFrom->money) {
                        $cashFrom->lock = $cashFrom->lock - $dealGive->money;
                    }
                    $cashFrom->save();
                    //余额打赏支出
                    $cashFrom->records()->save(new MemberWalletRecord(['type' => 22, 'member_id' => $dealGive->member_id, 'money' => -$dealGive->money, 'surplus' => $cashFrom->balance]));


                    //接收人
                    $cashTo = MemberWalletCash::where('id', $dealGive->to_member_id)->lockForUpdate()->first();
                    if (!isset($cashTo)) {
                        DB::rollBack();
                        return $this->validation('接收方钱包异常');
                    }

                    $cashTo->usable = $cashTo->usable + $dealGive->received;
                    $cashTo->save();
                    //余额打赏收入
                    $cashTo->records()->save(new MemberWalletRecord(['type' => 13, 'member_id' => $dealGive->to_member_id, 'money' => $dealGive->received, 'surplus' => $cashTo->balance]));

                    $dealGive->status = 0;

                } else {
                    //发送方钱包余额不足生成充值订单
                    if (!isset($dealGive->recharge)) {
                        $recharge = new MemberWalletRecharge();
                        $recharge->member_id = $dealGive->member_id;
                        $recharge->money = $dealGive->money;

                        $payment = PlatformPayment::where('status', 0)->where('min_money', '<=', $recharge->money)->where('max_money', '>=', $recharge->money)->first();
                        if (!isset($payment)) {
                            DB::rollBack();
                            return $this->failure(1, '支付通道繁忙', $dealGive);
                        }
                        $recharge->payment_id = $payment->id;
                        $dealGive->recharge()->save($recharge);
                        $dealGive->status = 8;
                    }
                }

            }
            $dealGive->save();
            DB::commit();
            return $this->succeed($dealGive);

        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->exception($ex);
        }

    }


    /**
     * 提现支付申请
     * @param array $data
     * @return array|mixed
     */
    public function withdrawPay($id)
    {
        DB::beginTransaction();
        try {
            $dealWithdraw = DealWithdraw::where('id', $id)->lockForUpdate()->first();
            if (!isset($dealWithdraw)) {
                DB::rollBack();
                return $this->failure(1, '订单不存在');
            }
            if ($dealWithdraw->status == 9) {
                //提现会员
                $cash = MemberWalletCash::where('id', $dealWithdraw->member_id)->lockForUpdate()->first();
                if (!isset($cash)) {
                    DB::rollBack();
                    return $this->validation('钱包异常');
                }
                if ($cash->freeze < $dealWithdraw->money) {
                    DB::rollBack();
                    return $this->validation('提现金额异常');
                }

                if (!isset($dealWithdraw->withdraw)) {
                    $withdraw = new MemberWalletWithdraw();
                    $withdraw->member_id = $dealWithdraw->member_id;
                    $withdraw->money = $dealWithdraw->money;

                    $dealWithdraw->withdraw()->save($withdraw);
                    $dealWithdraw->status = 8;
                }

            }
            $dealWithdraw->save();
            DB::commit();
            return $this->succeed($dealWithdraw);

        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->exception($ex);
        }

    }

}
