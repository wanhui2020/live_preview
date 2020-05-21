<?php

namespace App\Repositories;

use App\Facades\PushFacade;
use App\Facades\WalletFacade;
use App\Models\DealGift;
use App\Models\DealGold;
use App\Models\MemberUser;
use App\Models\MemberUserRate;
use App\Models\MemberWalletCash;
use App\Models\MemberWalletGold;
use App\Models\MemberWalletRecharge;
use App\Models\MemberWalletRecord;
use App\Models\PlatformGift;
use App\Models\PlatformPayment;
use Illuminate\Support\Facades\DB;

/**
 * 礼物赠送
 * Class MemberRealnameRepository
 * @package App\Repositories
 */
class DealGiftRepository extends BaseRepository
{
    public function model()
    {
        return DealGift::class;
    }

    public function lists($addWhere = null)
    {
        $where = function ($query) {
            //被赠送人
            if (request('key') != null) {
                if (request('key')) {
                    $query->wherehas('member', function ($query) {
                        $query->where('no', 'like', '%' . request('key') . '%')
                            ->orWhere('nick_name', 'like', '%' . request('key') . '%')
                            ->orWhere('mobile', 'like', '%' . request('key') . '%');
                    });
                }
            }
            //资源所属
            if (request('keys') != null) {
                if (request('keys')) {
                    $query->wherehas('tomember', function ($query) {
                        $query->where('no', 'like', '%' . request('keys') . '%')
                            ->orWhere('nick_name', 'like', '%' . request('keys') . '%')
                            ->orWhere('mobile', 'like', '%' . request('keys') . '%');
                    });
                }
            }
            if (request('received') != '') {
                $query->where('total', request('received'));
            }
            if (request('relevance_type') != '') {
                $query->where('relevance_type', request('relevance_type'));
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
            if ($data['member_id'] == $data['to_member_id']) {
                DB::rollBack();
                return $this->validation('不能给自己发送礼物');
            }
            $data['quantity'] = intval($data['quantity']);
            if ($data['quantity'] == 0) {
                DB::rollBack();
                return $this->validation('礼物数量格式错误');
            }
            if ($data['quantity'] < 1 || $data['quantity'] > 200) {
                DB::rollBack();
                return $this->validation('礼物数量只能1-200之间');
            }

            $gift = PlatformGift::find($data['gift_id']);
            if (!isset($gift)) {
                DB::rollBack();
                return $this->failure(1, '礼物未知', $data);
            }
            $formMember = MemberUser::find($data['member_id']);
            if (!isset($formMember)) {
                DB::rollBack();
                return $this->validation('会员记录未找到');
            }
            if (empty($data['relevance_type'])) {
                $data['relevance_type'] = 'MemberUser';
                $data['relevance_id'] = $formMember->id;
            }


            $toMember = MemberUser::find($data['to_member_id']);
            if (!isset($toMember)) {
                DB::rollBack();
                return $this->validation('会员记录未找到');
            }

            if ($formMember->formBlacklists()->where('status', 0)->where('to_member_id', $toMember->id)->exists()) {
                DB::rollBack();
                return $this->validation('对方被我已拉入黑名单');

            }

            if ($toMember->formBlacklists()->where('status', 0)->where('to_member_id', $formMember->id)->exists()) {
                DB::rollBack();
                return $this->validation('我被对方已拉入黑名单');
            }


            $data['platform_way'] = getenv('', 1);
            if ($toMember->is_selfie == 0) {
                $data['platform_way'] = 1;
            }
            $rate = $toMember->rate;
            if ($rate->gift_rate > 0) {
                $data['platform_rate'] = $rate->gift_rate;
            }

            $data['name'] = $gift->name;
            $data['price'] = $gift->gold;
            $resp = parent::store($data);
            if (!$resp['status']) {
                DB::rollBack();
                return $this->failure(1, $resp);
            }
            $gift = $resp['data'];

            $formGold = MemberWalletGold::where('member_id', $data['member_id'])->lockForUpdate()->first();
            if (!isset($formGold)) {
                DB::rollBack();
                return $this->validation('发送方钱包异常');
            }
            if ($formGold->consumable < $gift->total) {
                $user = MemberUser::find($data['member_id']);
                if ($user->push_token) {
                    $body = [
                        'type' => 'popup'
                    ];
                    PushFacade::pushToken($user->push_token, $user->app_platform, '能量不足，请充值！', json_encode($body), $type = 'MESSAGE', ['type' => 'member', 'id' => $user->id, 'no' => $user->no, 'nickname' => $user->nick_name]);
                }
                DB::rollBack();
                return $this->validation('能量不足，请充值');
            }
            $formGold->usable = $formGold->usable - $gift->total;
            if ($formGold->lock >= $gift->total) {
                $formGold->lock = $formGold->lock - $gift->total;
            }
            $formGold->save();
            //礼物赠送支出
            $formGold->records()->save(new MemberWalletRecord(['type' => 45, 'member_id' => $gift->member_id, 'money' => -$gift->total, 'surplus' => $formGold->balance]));

            //平台收益
            $formGold->records()->save(new MemberWalletRecord(['type' => 35, 'member_id' => 0, 'money' => bcdiv($gift->platform_commission, env('PLATFORM_EXCHANGE_RATE'), 2), 'surplus' => bcdiv($gift->platform_commission, env('PLATFORM_EXCHANGE_RATE'), 2)]));

            if ($gift->received > 0 && $toMember->is_selfie == 0) {
                //礼物收入能量
                if ($gift->platform_way == 0) {

                    $goldTo = MemberWalletGold::where('member_id', $gift->to_member_id)->lockForUpdate()->first();
                    if (!isset($goldTo)) {
                        DB::rollBack();
                        return $this->validation('接收方钱包异常');
                    }
                    $goldTo->usable = $goldTo->usable + $gift->received;
                    $goldTo->save();

                    //礼物接收收入
                    $goldTo->records()->save(new MemberWalletRecord(['type' => 35, 'member_id' => $gift->to_member_id, 'money' => $gift->received, 'surplus' => $goldTo->balance]));

                    //判断是否有代理
                    WalletFacade::income($gift->to_member_id, $gift->received, $goldTo, 19, 50, 1);

                }
                if ($gift->platform_way == 1) {
                    //礼物收入现金
                    $cashTo = MemberWalletCash::where('member_id', $gift->to_member_id)->lockForUpdate()->first();
                    if (!isset($cashTo)) {
                        DB::rollBack();
                        return $this->validation('接收方钱包异常');
                    }
                    $cashTo->usable = $cashTo->usable + $gift->received;
                    $cashTo->save();

                    //礼物接收收入
                    $cashTo->records()->save(new MemberWalletRecord(['type' => 35, 'member_id' => $gift->to_member_id, 'money' => $gift->received, 'surplus' => $cashTo->balance]));


                    //判断是否有代理
                    WalletFacade::income($gift->to_member_id, $gift->received, $cashTo, 19, 50);
                }

            }

            DB::commit();
            return $this->succeed($resp, '礼物赠送成功');
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->exception($ex);
        }
    }

}

