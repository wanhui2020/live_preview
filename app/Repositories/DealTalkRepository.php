<?php

namespace App\Repositories;

use App\Facades\DealFacade;
use App\Facades\PlatformFacade;
use App\Facades\PushFacade;
use App\Models\DealOrder;
use App\Models\DealTalk;
use App\Models\MemberUser;
use App\Models\MemberWalletGold;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DealTalkRepository extends BaseRepository
{
    public function model()
    {
        return DealTalk::class;
    }

    public function lists($addWhere = null)
    {
        $where = function ($query) {
            if (request('key') != null) {

            }
            if (request('begin_time') != '') {
                $query->where('begin_time', '>=', request('begin_time'));
            }
            if (request('end_time') != '') {
                $query->where('end_time', '<=', request('end_time'));
            }
            if (request('no') != '') {
                $query->where(function ($query) {
                    $query->where('no', 'like', '%' . request('no') . '%');
                });
            }
            if (request('room_id') != '') {
                $query->where(function ($query) {
                    $query->where('room_id', 'like', '%' . request('room_id') . '%');
                });
            }
            //主叫
            if (request('called_id') != '') {
                $query->whereHas('called', function ($query) {
                    $query->where('no', 'like', '%' . request('called_id') . '%');
                    $query->orWhere('nick_name', 'like', '%' . request('called_id') . '%');
                });

            }
            //被叫
            if (request('dialing_id') != '') {
                $query->whereHas('dialing', function ($query) {
                    $query->where('no', 'like', '%' . request('dialing_id') . '%');
                    $query->orWhere('nick_name', 'like', '%' . request('dialing_id') . '%');
                });

            }
            //通话时长
            if (request('duration') != '') {
                $query->where('duration', request('duration'));
            }

            if (request('status') != '') {
                $query->where('status', request('status'));
            }

            if (request('money') != null) {
                $query->where(function ($query) {
                    $query->where('total', request('money'));
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
            //主叫方
            $dialing = MemberUser::where('id', $data['dialing_id'])->lockForUpdate()->first();
            if (!isset($dialing)) {
                DB::rollBack();
                return $this->validation('主叫方不存在');
            }
            if ($dialing->online_status != 0) {
                DB::rollBack();
                return $this->validation('主叫方不在线，请稍后再拨');
            }
            if ($dialing->live_status != 0) {
                DB::rollBack();
                return $this->validation('主叫方当前忙碌，请稍后再拨');
            }
            if ($dialing->formBlacklists()->where('status', 0)->where('to_member_id', $data['called_id'])->exists()) {
                DB::rollBack();
                return $this->validation('对方已被我拉入黑名单');
            }
            $dialing->live_status = 1;
            $dialing->save();

            //被叫方
            $called = MemberUser::where('id', $data['called_id'])->lockForUpdate()->first();
            if (!isset($called)) {
                DB::rollBack();
                return $this->validation('被叫方不存在');
            }
            if ($called->online_status != 0) {
                DB::rollBack();
                if ($called->push_token) {
                    PushFacade::pushToken($called->push_token, $called->app_platform, ($dialing->sex == 0 ? '【小哥哥】' : '【小姐姐】') . $dialing->nick_name, '呼叫您未接听！', $type = 'NOTICE', ['type' => 'member', 'id' => $dialing->id, 'no' => $dialing->no, 'nickname' => $dialing->nick_name]);
                }
                return $this->validation('被叫方不在线，请稍后再拨');
            }

            if ($called->live_status != 0) {
                DB::rollBack();
                if ($called->push_token) {
                    PushFacade::pushToken($called->push_token, $called->app_platform, ($dialing->sex == 0 ? '【小哥哥】' : '【小姐姐】') . $dialing->nick_name, '呼叫您未接听！', $type = 'NOTICE', ['type' => 'member', 'id' => $dialing->id, 'no' => $dialing->no, 'nickname' => $dialing->nick_name]);
                }
                return $this->validation('被叫方当前忙碌，请稍后再拨');
            }
            if ($called->formBlacklists()->where('status', 0)->where('to_member_id', $data['dialing_id'])->exists()) {
                DB::rollBack();
                return $this->validation('我被对方已拉入黑名单');
            }
            $called->live_status = 1;
            $called->save();

            if ($called->is_selfie == 0) {
                $data['platform_way'] = 1;
            }

            //根据费率设置价格
            $rate = $called->rate;
            if ($rate->video_fee > 0 && $data['type'] == 0) {
                $data['price'] = $rate->video_fee;
                $data['platform_rate'] = $rate->video_rate;
            }
            if ($rate->voice_fee > 0 && $data['type'] == 1) {
                $data['price'] = $rate->voice_fee;
                $data['platform_rate'] = $rate->voice_rate;
            }

            if ($dialing['type'] == 0 && $called['type'] == 0 || $dialing['type'] == 0 && $called['type'] == 1 || $dialing['type'] == 1 && $called['type'] == 0 || $dialing['type'] == 1 && $called['type'] == 1 ) {
                $isDeductionCallingFee = PlatformFacade::config('is_deduction_calling_fee');
                if ($isDeductionCallingFee == 1 && $dialing['is_selfie'] == 0) {
                    $gold = MemberWalletGold::where('member_id', $data['called_id'])->lockForUpdate()->first();
                }else{
                    $gold = MemberWalletGold::where('member_id', $data['dialing_id'])->lockForUpdate()->first();
                }
                if ($gold->usable < $data['price']) {
                    $user = MemberUser::find($data['dialing_id']);
                    if ($user->push_token) {
                        $body = [
                            'type' => 'popup'
                        ];
                        PushFacade::pushToken($user->push_token, $user->app_platform, '能量不足，请充值！', json_encode($body), $type = 'MESSAGE', ['type' => 'member', 'id' => $user->id, 'no' => $user->no, 'nickname' => $user->nick_name]);
                    }
                    DB::rollBack();
                    return $this->validation('当前能量不足通话一分钟');
                }
                //扣第一分钟费用
                $gold->freeze = $data['price'];
                $gold->save();
            }

            $resp = parent::store($data);
            DB::commit();
            return $resp;

        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->exception($ex);
        }
    }

    /**
     * 接听
     * @param array $data
     * @return array|mixed
     */
    public function answer(array $data)
    {
        return DealFacade::talkAnswer($data['room_id']);

    }

    /**
     * 挂断
     * @param array $data
     * @return array|mixed
     */
    public function hangup(array $data)
    {
        return DealFacade::talkHangup($data['room_id']);

    }

    /**
     * 通话拒绝接听
     * @param array $data
     * @return array|mixed
     */
    public function refuse(array $data)
    {
        return DealFacade::talkHangup($data['room_id'], 2);

    }

    /**
     * 强制挂断
     * @param array $data
     * @return array|mixed
     */
    public function finish(array $data)
    {
        return DealFacade::talkHangup($data['room_id'], 5);

    }

}
