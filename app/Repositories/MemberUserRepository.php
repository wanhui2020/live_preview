<?php

namespace App\Repositories;

use App\Facades\CommonFacade;
use App\Facades\ImFacade;
use App\Facades\MemberFacade;
use App\Facades\PlatformFacade;
use App\Facades\RiskFacade;
use App\Facades\WechatFacade;
use App\Http\Resources\MemberUserMyResource;
use App\Jobs\GenerateShareImageJob;
use App\Models\MemberUser;
use App\Models\MemberWalletCash;
use App\Models\MemberWalletGold;
use App\Models\MemberWalletRecord;
use App\Models\PlatformSendMessage;
use App\Services\ImService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Log;

class MemberUserRepository extends BaseRepository
{
    public function model()
    {
        return MemberUser::class;
    }

    public function lists($addWhere = null)
    {
        $where = function ($query) {
            if (request('key')) {
                $query->where('no', 'like', '%' . request('key') . '%')
                    ->orWhere('nick_name', 'like', '%' . request('key') . '%')
                    ->orWhere('mobile', 'like', '%' . request('key') . '%');
            }
            if (request('type') || request('type') == 0) {
                Log::info('进了吗'.request('type'));
                $query->where('type', request('type'));
            }
            if (request('is_selfie') != null) {
                $query->where('is_selfie', request('is_selfie'));
            }
            if (request('is_real') != null) {
                $query->where('is_real', request('is_real'));
            }
            if (request('is_middleman') != null) {
                $query->where('is_middleman', request('is_middleman'));
            }
            if (request('sex') || request('sex') == 0 && request('sex') != '') {
                $query->where('sex', request('sex'));
            }
            //渠道编号/名称
            if (request('agent')) {
                $query->whereHas('agent', function ($query) {
                    $query->where('nick_name', 'like', '%' . request('agent') . '%')
                    ->orWhere('no', 'like', '%' . request('agent') . '%');
                });
            }
            //能量/金币
            if (request('balance')) {
                $query->whereHas('gold', function ($query) {
                    $query->where('balance', 'like', '%' . request('balance') . '%');
                });
                $query->whereHas('cash', function ($query) {
                    $query->where('balance', 'like', '%' . request('balance') . '%');
                });
            }
            //VIP/魅力
            if (request('grade')) {
                $query->where('vip_grade', request('grade'));
                $query->orWhere('charm_grade', request('grade'));
            }
            //在线状态
            if (request('im_status') || request('im_status') == 0 && request('im_status') != '') {
                $query->where('im_status', request('im_status'));
            }
            $query->whereNotNull('last_time');
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

    public function loginMobile(array $data)
    {
        try {
            $newData = [];
            if (empty($data['mobile'])) {
                return $this->validation('手机号不能为空');
            }
            if (!preg_match("/^1\d{10}$/", $data['mobile'])) {
                return $this->validation('手机号格式错误');
            }
            if (empty($data['code'])) {
                return $this->validation('验证码不能为空');
            }

            if($data['code'] != config('sms.admin_verification_code')){
                $result = RiskFacade::verifyCode($data['mobile'], $data['code']);
                if (!$result) {
                    return $this->validation('验证码效验错误');
                }
            }

            $member = MemberUser::where('mobile', $data['mobile'])->first();
            if (isset($member)) {
                if ($member->status != 0) {
                    return $this->validation('用户已被禁用');
                }
                return $this->succeed(new MemberUserMyResource($member));
            }
            $newData['mobile'] = $data['mobile'];
            $newData['sex'] = 9;
            $resp = $this->store($newData);
            if ($resp['status']) {
                return $this->succeed(new MemberUserMyResource($resp['data']));
            }
            return $resp;
        } catch (\Exception $ex) {
            return $this->exception($ex, '创建用户异常，请联系管理员');
        }
    }

    public function loginWeixin(array $data)
    {
        try {
            $newData = [];
            if (empty($data['openid'])) {
                return $this->validation('微信OPENID不能为空');
            }
            if (empty($data['access_token'])) {
                return $this->validation('微信access_token不能为空');
            }
            $response = WechatFacade::getUserInfo($data['openid'], $data['access_token']);
            if (!$response['status']) {
                return $this->validation('微信登录失败');
            }
            $userinfo = $response['data'];
            if (!isset($userinfo['openid'])) {
                return $this->failure(1, '获取微信信息失败', $response);
            }
            if (isset($userinfo['unionid'])) {
                $member = MemberUser::where('unionid', $userinfo['unionid'])->first();
            } else {
                $member = MemberUser::where('weixin_openid', $userinfo['openid'])->first();
            }
            if (isset($member)) {
                if ($member->status != 0) {
                    return $this->validation('用户已被禁用');
                }
                //首次登录获得收益
                if (empty($member->last_time)){
                    $this->inviteRegister($member);
                }

                return $this->succeed(new MemberUserMyResource($member));
            }

            if (isset($userinfo['unionid'])) {
                $newData['unionid'] = $userinfo['unionid'];
            }
            if (isset($userinfo['openid'])) {
                $newData['weixin_openid'] = $userinfo['openid'];
            }
            if (isset($userinfo['nickname'])) {
                $newData['nick_name'] = $userinfo['nickname'];
            }
            if (isset($userinfo['headimgurl']) && empty($member->head_pic)) {
                $newData['head_pic'] = $userinfo['headimgurl'];
            }
            if (isset($userinfo['sex'])) {
                $newData['sex'] = 9;
            }
            if (isset($userinfo['city'])) {
                $newData['city'] = $userinfo['city'];
            }
            if (isset($userinfo['province'])) {
                $newData['province'] = $userinfo['province'];
            }

            $resp = $this->store($newData);
            if ($resp['status']) {
                return $this->succeed(new MemberUserMyResource($resp['data']));
            }
            return $resp;

        } catch (\Exception $ex) {
            return $this->exception($ex, '创建用户异常，请联系管理员');
        }
    }


    /**
     * 邀请注册
     * @param $data
     */
    public function inviteRegister($data)
    {
        //推荐注册奖励
        if (isset($data['parent_id'])) {
            $parentGold = MemberWalletGold::where('member_id', $data['parent_id'])->lockForUpdate()->first();
            if (isset($parentGold) && PlatformFacade::config('invite_register_award') > 0) {
                $parentGold->usable = $parentGold->usable + PlatformFacade::config('invite_register_award');
//                        $parentGold->lock = $parentGold->lock + PlatformFacade::config('invite_register_award');
                $parentGold->save();
                //邀请注册金币奖励
                $parentGold->records()->save(new MemberWalletRecord(['type' => 36, 'member_id' => $data['parent_id'], 'money' => PlatformFacade::config('invite_register_award'), 'surplus' => $parentGold->balance]));
            }
            //赠送现金
            $cash = MemberWalletCash::where('member_id', $data['parent_id'])->lockForUpdate()->first();
            if (isset($cash) && PlatformFacade::config('invite_cash') > 0) {
                $cash->usable = $cash->usable + PlatformFacade::config('invite_cash');
//                        $parentGold->lock = $parentGold->lock + PlatformFacade::config('invite_register_award');
                $cash->save();
                //邀请注册金币奖励
                $cash->records()->save(new MemberWalletRecord(['type' => 55, 'member_id' => $data['parent_id'], 'money' => PlatformFacade::config('invite_cash'), 'surplus' => $cash->balance]));
            }
        }
        //首次注册获得能量
        $memberWalletGold = MemberWalletGold::where('member_id', $data['id'])->lockForUpdate()->first();
        if (isset($memberWalletGold) && PlatformFacade::config('register_energy') > 0) {
            $memberWalletGold->usable = $memberWalletGold->usable + PlatformFacade::config('register_energy');
            $memberWalletGold->lock = $memberWalletGold->lock + PlatformFacade::config('register_energy');
            $memberWalletGold->save();
            $memberWalletGold->records()->save(new MemberWalletRecord(['type' => 39, 'member_id' => $data['id'], 'money' => PlatformFacade::config('register_energy'), 'surplus' => $memberWalletGold->balance]));
        }
        $memberWalletCash = MemberWalletCash::where('member_id', $data['id'])->lockForUpdate()->first();
        if (isset($memberWalletCash) && PlatformFacade::config('register_cash') > 0) {
            $memberWalletCash->usable = $memberWalletCash->usable + PlatformFacade::config('register_cash');
//                    $memberWalletGold->lock = $memberWalletGold->lock + PlatformFacade::config('register_energy');
            $memberWalletCash->save();
            $memberWalletCash->records()->save(new MemberWalletRecord(['type' => 56, 'member_id' => $data['id'], 'money' => PlatformFacade::config('register_cash'), 'surplus' => $memberWalletCash->balance]));
        }
    }


    /**
     * 推送信息
     *
     * @param MemberUser $member
     */
    public function sendMessage($member)
    {
        //随机选出5名主播
        $sex  = $member->sex == 0 ? 1 : 0;
        if ($member->is_selfie == 0){
            return;
        }
        $user = MemberUser::where([
            'type'          => 0,
            'sex'           => $sex,
            'online_status' => 0,
            'im_status'     => 0,
            'is_selfie'     => 0,
        ])->limit(20)->get();

        if (!count($user)) {
            return;
        }

        for ($i = 0; $i < 5 && $i <= count($user); $i++) {
            $subscript   = array_rand($user->toArray());
            $fromAccount = $user[$subscript]['no'];
            $msg         = $user[$subscript]->parameter->greeting;

            if (!$msg) {
                $sendMessage = PlatformSendMessage::where([
                    'type'   => 0,
                    'status' => 0,
                ])->get();
                if (count($sendMessage) > 0) {
                    $sendSubscript = array_rand($sendMessage->toArray());
                    $content  = $sendMessage[$sendSubscript]['content'];
                } else {
                    $content = ['你好','欢迎你来看我的直播','快来看我的直播','您好','就差你啦'];
                    $rand = array_rand($content);
                    $content = $content[$rand];
                }
            } else {
                $content = $msg;
            }

            $ser  = new ImService();
            $data = [
                'command' => 'talk.greetings',
                'data'    => ['greetings' => $content],
            ];
            $ret  = $ser->addRoom($fromAccount, $member->no, $data, 1);
        }

        $member->last_auto_message_at = now()->toDateTimeString();
        $member->save();
    }

    public function store(array $data)
    {
        //是否陪聊
        if (isset($data['type']) && $data['type'] == 1) {

            $newData['nick_name'] = CommonFacade::getGuid();

            if (empty($data['nick_name'])) {

            }
            if (empty($data['head_pic'])) {


            }
            if (empty($data['cover'])) {

            }
        }

        if (isset($data['unionid'])) {
            if (MemberUser::where('unionid', $data['unionid'])->exists()) {
                return $this->validation('此微信已注册');
            }
        }
        if (isset($data['mobile'])) {
            if (MemberUser::where('mobile', $data['mobile'])->exists()) {
                return $this->validation('手机号已注册');
            }
        }
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        if (isset($data['parent_no']) && !empty($data['parent_no'])) {
            $parent = MemberUser::where('invite_code', $data['parent_no'])->first();
            if (isset($parent)) {
                $data['parent_id'] = $parent->id;
                if ($parent->is_middleman == 0) {
                    $data['agent_id'] = $parent->id;
                } else {
                    $data['agent_id'] = $parent->agent_id;
                }
            }
            unset($data['parent_no']);
        }else{
            unset($data['parent_no']);
        }

        DB::beginTransaction();
        try {
            $resp = parent::store($data);
            if ($resp['status']) {
                $member = $resp['data'];

                if (isset($data['mobile'])) {
                    //首次注册获得能量
                    $memberWalletGold = MemberWalletGold::where('member_id', $resp['data']['id'])->lockForUpdate()->first();
                    if (isset($memberWalletGold) && PlatformFacade::config('register_energy') > 0) {
                        $memberWalletGold->usable = $memberWalletGold->usable + PlatformFacade::config('register_energy');
                        $memberWalletGold->lock = $memberWalletGold->lock + PlatformFacade::config('register_energy');
                        $memberWalletGold->save();
                        $memberWalletGold->records()->save(new MemberWalletRecord(['type' => 39, 'member_id' => $resp['data']['id'], 'money' => PlatformFacade::config('register_energy'), 'surplus' => $memberWalletGold->balance]));
                    }
                    $memberWalletCash = MemberWalletCash::where('member_id', $resp['data']['id'])->lockForUpdate()->first();
                    if (isset($memberWalletCash) && PlatformFacade::config('register_cash') > 0) {
                        $memberWalletCash->usable = $memberWalletCash->usable + PlatformFacade::config('register_cash');
//                    $memberWalletGold->lock = $memberWalletGold->lock + PlatformFacade::config('register_energy');
                        $memberWalletCash->save();
                        $memberWalletCash->records()->save(new MemberWalletRecord(['type' => 56, 'member_id' => $resp['data']['id'], 'money' => PlatformFacade::config('register_cash'), 'surplus' => $memberWalletCash->balance]));
                    }
                }

                DB::commit();
                $this->imCheck($member->id);
                dispatch(new GenerateShareImageJob($member->id));
                event(new Registered($member));
                return $this->succeed($member);
            }


            DB::rollBack();
            return $this->failure(1, '创建用户失败！', $resp['data']);
        } catch (\Exception $ex) {
            DB::rollBack();
            $this->exception($ex);
            return $this->validation('创建用户异常，请联系管理员');
        }
    }


    public function update($data, $attribute = "id")
    {
        if (isset($data['file'])) {
            unset($data['file']);
        }
        if (isset($data['mobile'])) {
            unset($data['mobile']);
        }
        if (isset($data['agent_id']) && $data['agent_id'] !=0) {
            $data['agent_id'] = MemberUser::where('no',$data['agent_id'])->value('id');
//            $member = MemberUser::find($data[$attribute]);
//            $agent = MemberUser::find($data['agent_id']);
//            if (isset($agent)) {
//                if ($agent->is_middleman != 0) {
//                    return $this->validation('设置渠道不是经济人');
//                }
//                if ($member->id < $agent->id) {
//                    return $this->validation('设置的渠道晚于当前用户注册');
//                }
//            }else{
//                unset($data['agent_id']);
//            }
        }

        if (isset($data['parent_no']) && !empty($data['parent_no'])) {
            $parent = MemberUser::where('invite_code', $data['parent_no'])->first();
            if (isset($parent)) {
                $data['parent_id'] = $parent->id;
                if ($parent->is_middleman == 0) {
                    $data['agent_id'] = $parent->id;
                } else {
                    $data['agent_id'] = $parent->agent_id;
                }
            }
            unset($data['parent_no']);
        }else{
            unset($data['parent_no']);
        }


        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        if (isset($data['parent_no']) && !empty($data['parent_no'])) {
            $parent = MemberUser::where('invite_code', $data['parent_no'])->first();
            if (isset($parent)) {
                $data['parent_id'] = $parent->id;
                if ($parent->is_middleman == 0) {
                    $data['agent_id'] = $parent->id;
                } else {
                    $data['agent_id'] = $parent->agent_id;
                }
            }
            unset($data['parent_no']);
        }else{
            unset($data['parent_no']);
        }

        //修改vip/魅力积分/等级
        $user = MemberUser::find($data['id']);
        $data['vip_integral'] = bcadd($user['vip_integral'],$data['change_vip_integral']);
        if ($data['vip_integral']<0) {
            $data['vip_integral']=0;
        }
        $grade = MemberFacade::getVipGrade($data['vip_integral']);
        $data['vip_grade'] = $grade;
        $data['charm_integral'] = bcadd($user['charm_integral'],$data['change_charm_integral']);
        if ($data['charm_integral']< 0) {
            $data['charm_integral']=0;
        }
        $grade = MemberFacade::getCharmGrade($data['charm_integral']);
        $data['charm_grade'] = $grade;
        unset($data['file']);

        $user = MemberUser::where('id' ,$data['id'])->update($data);
        if ($user) {
            return $this->succeed(json_encode($data));
        }
        return $this->failure(1, '更新失败');
//        return parent::update($data, $attribute);
    }

    /**
     * IM状态检查
     * @param $id
     * @return array
     */
    public function imCheck($id)
    {
        $user = $this->find($id);
        if (!isset($user)) {
            return $this->validation('未找到相关用户');
        }
        $imCheck = ImFacade::userCheck([['UserID' => $user->no]]);

        if (!$imCheck['status']) {
            $imImport = ImFacade::userImport($user->no);
            $this->logs('$imImport', $imImport);
            if (!$imImport['status']) {
                return $this->failure(1, 'Im账户导入失败');
            }
        }

        //在线状态检查
        $imStatus = ImFacade::userStatus([$user->no]);
        $user->im_status = 9;
        $this->logs('im', $imStatus);
        if ($imStatus['status']) {
            $datas = $imStatus['data'];
            foreach ($datas as $item) {
                if ($item['To_Account'] == $user->no) {
                    if ($item['State'] == 'Online') {
                        $user->im_status = 0;
                    }
                    if ($item['State'] == 'Offline') {
                        $user->im_status = 1;
                    }
                    if ($item['State'] == 'PushOnline') {
                        $user->im_status = 2;
                    }

                }
            }

        }

        $user->save();
        return $this->succeed($user);

    }


    /**
     * IM批量检查
     * @param $id
     * @return array
     */
    public function imMultiCheck()
    {
        try {
            MemberUser::chunk(50, function ($users) {
                DB::beginTransaction();
                $datas = [];
                $nos = $users->pluck('no')->toArray();
                $imImport = ImFacade::userMultiImport($nos);
                if (!$imImport['status']) {
                    return $this->failure(1, 'Im账户导入失败', $imImport);
                }
                $imStatus = ImFacade::userStatus($nos);
                if (!$imStatus['status']) {
                    return $this->failure(1, 'Im账户状态检查失败', $imStatus);
                }

                foreach ($users as $user) {
                    foreach ($imStatus['data'] as $item) {
                        if ($item['To_Account'] == $user->no) {
                            if ($item['State'] == 'Online') {
                                $user->im_status = 0;
                            }
                            if ($item['State'] == 'Offline') {
                                $user->im_status = 1;
                            }
                            if ($item['State'] == 'PushOnline') {
                                $user->im_status = 2;
                            }
                            $user->save();

                        }
                    }

                }

                DB::commit();
            }
            );

            return $this->succeed();
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }

    }

    public function destroy(array $ids)
    {
        return MemberFacade::deleteUser($ids);
    }

}

