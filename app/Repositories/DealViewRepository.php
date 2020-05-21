<?php

namespace App\Repositories;

use App\Facades\DealFacade;
use App\Facades\PlatformFacade;
use App\Facades\PushFacade;
use App\Facades\WalletFacade;
use App\Models\DealOrder;
use App\Models\DealView;
use App\Models\MemberResource;
use App\Models\MemberUser;
use App\Models\MemberUserRate;
use App\Models\MemberWalletCash;
use App\Models\MemberWalletGold;
use App\Models\MemberWalletRecord;
use App\Models\PlatformGift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DealViewRepository extends BaseRepository
{
    public function model()
    {
        return DealView::class;
    }

    public function lists($addWhere = null)
    {
        $where = function ($query) {
            //会员
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
            if (request('resource_id') != '') {
                $query->where('resource_id', request('resource_id'));
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

            $resource = MemberResource::find($data['resource_id']);
            if (!isset($resource)) {
                DB::rollBack();
                return $this->validation('资源不存在');
            }
            if ($data['member_id'] == $resource->member_id) {
                DB::rollBack();
                return $this->validation('不能购买自己资源');
            }
            if ($resource->type == 2) {
                DB::rollBack();
                return $this->validation('封面图片不需要购买');
            }
            $data['to_member_id'] = $resource->member_id;

            $formMember = MemberUser::find($data['member_id']);
            if (!isset($formMember)) {
                DB::rollBack();
                return $this->validation('会员记录未找到');
            }

            $toMember = MemberUser::find($data['to_member_id']);
            if (!isset($formMember)) {
                DB::rollBack();
                return $this->validation('会员记录未找到');
            }


            $dealView = DealView::where('member_id', $data['member_id'])
                ->where('to_member_id', $data['to_member_id'])
                ->where('resource_id', $data['resource_id'])
                ->where('end_time', '>', Carbon::now())->first();
            if (isset($dealView)) {
                $dealView->count += 1;
                $dealView->save();
                DB::commit();
                return $this->succeed('资源未到期');
            }

            $data['platform_way'] = getenv('PLATFORM_WAY', 1);

            $rate = $toMember->rate;
            $vip = $formMember->vip;

            $viewPictureFee = 0;
            $viewPictureRate = 0;
            $viewVideoFee = 0;
            $viewVideoRate = 0;
            if ($vip->view_picture_fee > 0) {
//                $viewPictureFee = $rate->view_picture_fee;
                $viewPictureFee = $vip->view_picture_fee;
                $viewPictureRate = $rate->view_picture_rate;
            }
            if ($vip->view_video_fee > 0) {
//                $viewVideoFee = $rate->view_video_fee;
                $viewVideoFee = $vip->view_video_fee;
                $viewVideoRate = $rate->view_video_rate;
            }

            //分层佣金方式
            if ($resource->type == 0) {
                $data['total'] = $viewPictureFee;
                $data['platform_rate'] = $viewPictureRate;
            }
            if ($resource->type == 1) {
                $data['total'] = $viewVideoFee;
                $data['platform_rate'] = $viewVideoRate;
            }


            if ($resource->price > 0) {
                $data['total'] = $resource->price;
            }

            $data['end_time'] = Carbon::now()->addDays(PlatformFacade::config('view_unlock_duration'));

            $resp = parent::store($data);
            if (!$resp['status']) {
                DB::rollBack();
                return $this->failure(1, $resp);
            }
            $view = $resp['data'];
            if ($data['total'] > 0) {

                $gold = MemberWalletGold::where('member_id', $data['member_id'])->lockForUpdate()->first();
                if (!isset($gold)) {
                    DB::rollBack();
                    return $this->validation('查看方钱包异常');
                }
                if ($gold->consumable < $data['total']) {
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
                $gold->usable = $gold->usable - $data['total'];
                if ($gold->lock >= $data['total']) {
                    $gold->lock = $gold->lock - $data['total'];
                }
                $gold->save();
                //资源查看支出
                $gold->records()->save(new MemberWalletRecord(['type' => 44, 'member_id' => $view->member_id, 'money' => -$data['total'], 'surplus' => $gold->balance]));

                if ($view->platform_way == 0) {
                    //收益能量
                    $goldCalled = MemberWalletGold::where('member_id', $view->to_member_id)->lockForUpdate()->first();
                    $goldCalled->usable = $goldCalled->usable + $view->received;
                    $goldCalled->save();
                    $goldCalled->records()->save(new MemberWalletRecord(['type' => 17, 'member_id' => $view->to_member_id, 'money' => $view->received, 'surplus' => $goldCalled->balance]));

                }
                if ($view->platform_way == 1) {
                    //收益金币
                    $cashCalled = MemberWalletCash::where('member_id', $view->to_member_id)->lockForUpdate()->first();
                    //根据平台兑换比例进行能量兑换金币
                    $cashCalled->usable = $cashCalled->usable + $view->received;
                    $cashCalled->save();
                    $cashCalled->records()->save(new MemberWalletRecord(['type' => 17, 'member_id' => $view->to_member_id, 'money' => $view->received, 'surplus' => $cashCalled->balance]));

                    //平台收益
                    $cashCalled->records()->save(new MemberWalletRecord(['type' => 17, 'member_id' => 0, 'money' => bcdiv($view->platform_commission, env('PLATFORM_EXCHANGE_RATE'), 2), 'surplus' => bcdiv($view->platform_commission, env('PLATFORM_EXCHANGE_RATE'), 2)]));

                    //判断是否有代理
                    WalletFacade::income($view->to_member_id, $view->received, $cashCalled, 19, 50);
                }

            }
            DB::commit();
            return $this->succeed($resp, '资源购买成功');
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->exception($ex);
        }
    }

}
