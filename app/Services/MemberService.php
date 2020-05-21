<?php

namespace App\Services;

use App\Facades\CommonFacade;
use App\Facades\DealFacade;
use App\Facades\GreenFacade;
use App\Facades\ImFacade;
use App\Facades\PlatformFacade;
use App\Facades\PushFacade;
use App\Models\DealTalk;
use App\Models\MemberDynamic;
use App\Models\MemberResource;
use App\Models\MemberUser;
use App\Models\MemberFileView;

use App\Models\MemberRecord;
use App\Models\MemberReward;
use App\Models\MemberSignIn;
use App\Models\MemberTag;
use App\Models\MemberTakeNow;
use App\Models\MemberTalk;
use App\Models\MemberVerification;
use App\Models\PlatformCharm;
use App\Models\PlatformConfig;
use App\Models\PlatformFile;
use App\Models\PlatformVip;
use App\Traits\ResultTrait;
use App\Utils\Helper;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

//会员相关服务实现类
class MemberService
{
    use ResultTrait;


    /**
     * IM定时同步
     * */
    public function imSync()
    {
        try {
            MemberUser::chunk(50, function ($users) {
                DB::beginTransaction();
                $nos = $users->pluck('no')->toArray();
                if (count($nos) > 0) {
//                    $imImport = ImFacade::userMultiImport($nos);
//                    if (!$imImport['status']) {
//                        return $this->failure(1, 'Im账户导入失败', $imImport);
//                    }
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

                                ImFacade::userImport($user->no, $user->nick_name, $user->head_pic);

                            }
                        }

                    }
                }
                DB::commit();
            });

            return $this->succeed();
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * IM定时同步
     * */
    public function imOnlineSync($no = [])
    {
        try {
            MemberUser:: where(function ($query) use ($no) {
                if (count($no) > 0) {
                    $query->whereIn('no', $no);
                } else {
                    $query->where('type', 0);
                }
            })->chunk(50, function ($users) {
                DB::beginTransaction();
                $nos = $users->pluck('no')->toArray();
                if (count($nos) > 0) {
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
                }
                DB::commit();
            });
            return $this->succeed();
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }


    /**
     * 会员计算
     * */
    public function vipIntegralSync($uid = 0)
    {
        try {
            $count = DB::table('member_users')
                ->where('status', '=', 0)
                ->count();
            while ($count) {
                $data = DB::table('member_users')
                    ->where('status', '=', 0)
                    ->limit(100)
                    ->get();
                !empty($data) && $this->vipDate($data);
                $count = DB::table('member_users')
                    ->where('status', '=', 0)
                    ->count();
            }
        }catch (\Exception $ex){
            $this->exception($ex);
        }
    }


    public function vipDate($users){
            $vips = PlatformVip::where('status', 0)->get();
                try {
                    foreach ($users as $user) {
                        $integral = 0;
                        //1在线时长分钟数
                        $online = $user->logins()->where(
                            function ($query) {
                                if (PlatformFacade::config('vip_period') > 0) {
                                    $query->where('created_at', '>', Carbon::now()->subDays(PlatformFacade::config('vip_period'))->toDateTimeString());
                                }
                            })->sum('duration');
                        if ($online > 0) {
                            $integral = $integral + ceil($online / 60 * PlatformFacade::config('vip_online_duration_weight'));
                        }
                        //充值金额
                        $recharges = $user->recharges()->where(
                            function ($query) {
                                if (PlatformFacade::config('vip_period') > 0) {
                                    $query->where('created_at', '>', Carbon::now()->subDays(PlatformFacade::config('vip_period'))->toDateTimeString());
                                }
                            })->where('pay_status', 0)->sum('money');
                        if ($recharges > 0) {
                            $integral = $integral + ceil($recharges * PlatformFacade::config('vip_recharge_total_weight'));
                        }

                        //通话时长
                        $formTalks = $user->formTalks()->where(
                            function ($query) {
                                if (PlatformFacade::config('vip_period') > 0) {
                                    $query->where('created_at', '>', Carbon::now()->subDays(PlatformFacade::config('vip_period'))->toDateTimeString());
                                }
                            })->where('status', 0)->sum('duration');
                        if ($formTalks > 0) {
                            $integral = $integral + ceil($formTalks / 60 * PlatformFacade::config('vip_fromtalk_duration_weight'));
                        }

                        //礼物赠送
                        $formGifts = $user->formGifts()->where(
                            function ($query) {
                                if (PlatformFacade::config('vip_period') > 0) {
                                    $query->where('created_at', '>', Carbon::now()->subDays(PlatformFacade::config('vip_period'))->toDateTimeString());
                                }
                            })->where('status', 0)->sum('total');
                        if ($formGifts > 0) {
                            $integral = $integral + ceil($formGifts * PlatformFacade::config('vip_fromgift_gold_weight'));
                        }

                        $user->vip_integral = $integral;
                        foreach ($vips as $item) {
                            if ($user->vip_integral > $item->integral && $user->vip_grade < $item->grade) {
                                $user->vip_grade = $item->grade;
                            }
                        }
                        $user->save();
                    }

                } catch (\Exception $ex) {
                    $this->exception($ex);
                }
            return $this->succeed();
    }

    /**
     * 会员魅力计算
     * */
    public function charmIntegralSync($uid = 0)
    {
        try {
            $count = DB::table('member_users')
                ->where('status', '=', 0)
                ->count();
            while ($count) {
                $data =DB::table('member_users')
                    ->where('status', '=', 0)
                    ->limit(100)
                    ->get();
                !empty($data) && $this->charmData($data);
                $count = DB::table('member_users')
                    ->where('status', '=', 0)
                    ->count();
            }
        }catch (\Exception $ex){
            $this->exception($ex);
        }
    }


    public function charmData($users){
        $charms = PlatformCharm::where('status', 0)->get();
        DB::beginTransaction();
        try {
            foreach ($users as $user) {
                $integral = 0;
                //1在线时长分钟数
                $online = $user->logins()->where(
                    function ($query) {
                        if (PlatformFacade::config('charm_period') > 0) {
                            $query->where('created_at', '>', Carbon::now()->subDays(PlatformFacade::config('charm_period'))->toDateTimeString());
                        }
                    })->where('status', 0)->sum('duration');
                if ($online > 0) {
                    $integral = $integral + ceil($online / 60 * PlatformFacade::config('charm_online_duration_weight'));
                }
                //2通话时长
                $toTalks = $user->toTalks()->where(
                    function ($query) {
                        if (PlatformFacade::config('charm_period') > 0) {
                            $query->where('created_at', '>', Carbon::now()->subDays(PlatformFacade::config('charm_period'))->toDateTimeString());
                        }
                    })->where('status', 0)->sum('duration');

                if ($toTalks > 0) {
                    $integral = $integral + ceil($toTalks / 60 * PlatformFacade::config('charm_totalk_duration_weight'));
                }

                $toTalks1 = $user->toTalks()->where(
                    function ($query) {
                        if (PlatformFacade::config('charm_period') > 0) {
                            $query->where('created_at', '>', Carbon::now()->subDays(PlatformFacade::config('charm_period'))->toDateTimeString());
                        }
                    })->where(['status'=>0,'way'=>1])->orWhere('way',2)->wherebetween('created_at',[date("Y-m-d 00:00:00"),date("Y-m-d 23:59:59"),])->get();
                if (count($toTalks1) > 0) {
                    foreach ($toTalks1 as $v) {
                        //电话未接/挂断，主播魅力下降
                        if ($v['way'] == 1 || $v['way'] == 2) {
                            $user = MemberUser::where(['id' => $v->called_id])->first();
                            $unanswered = PlatformFacade::config('unanswered');
                            if (bcsub($user->charm_integral, $unanswered) <= 0) {
                                $user->charm_integral = 0;
                            } else {
                                $user->charm_integral = bcsub($user->charm_integral, $unanswered);
                            }
                            $user->charm_grade = $this->getCharmGrade($user->charm_integral);
                            $user->save();
                        }
                    }
                }

                //3接收礼物金额
                $toGifts = $user->toGifts()->where(
                    function ($query) {
                        if (PlatformFacade::config('charm_period') > 0) {
                            $query->where('created_at', '>', Carbon::now()->subDays(PlatformFacade::config('charm_period'))->toDateTimeString());
                        }
                    })->where('status', 0)->sum('total');
                if ($toGifts > 0) {
                    $integral = $integral + ceil($toGifts * PlatformFacade::config('charm_togift_gold_weight'));
                }

                $user->charm_integral = $integral;
                $rate = $user->rate;
                foreach ($charms as $item) {
                    if ($user->charm_integral > $item->integral && $user->charm_grade <= $item->grade) {
                        $user->charm_grade = $item->grade;
                    }
                    if ($user->charm_grade == $item->grade && $rate->is_custom == 0) {
                        $rate->text_fee = $item->text_fee;
                        $rate->voice_fee = $item->voice_fee;
                        $rate->video_fee = $item->video_fee;
                        $rate->view_picture_fee = $item->view_picture_fee;
                        $rate->view_video_fee = $item->view_video_fee;

                        $rate->gift_rate = $item->gift_rate;
                        $rate->chat_rate = $item->chat_rate;
                        $rate->text_rate = $item->text_rate;
                        $rate->voice_rate = $item->voice_rate;
                        $rate->video_rate = $item->video_rate;
                        $rate->view_picture_rate = $item->view_picture_rate;
                        $rate->view_video_rate = $item->view_video_rate;
                    }
                }
                $rate->save();
                $user->save();
            }
            DB::commit();
        } catch (\Exception $ex) {
            $this->exception($ex);
        }
        return $this->succeed();
    }

    /**
     * 文本内容自动审核
     * */
    public function VerificationAudit($id = 0)
    {
        try {
            MemberVerification::where(function ($query) use ($id) {
                if ($id > 0) {
                    $query->where('id', $id);
                }
                $query->where('status', 9);
            })->chunk(50, function ($verifications) {
                try {
                    foreach ($verifications as $item) {
                        $scan = GreenFacade::textScan($item->new_data);
                        if ($scan['status']) {
                            $item->status = 0;
                        } else {
                            $item->status = 1;
                        }
                        $item->audit_reason = $scan['msg'];
                        $item->save();
                    }
                } catch (\Exception $ex) {
                    $this->exception($ex);
                }
            });

            return $this->succeed();
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 动态内容自动审核
     * */
    public function DynamicVerificationAudit($id = 0)
    {
        try {
            MemberDynamic::where(function ($query) use ($id) {
                if ($id > 0) {
                    $query->where('id', $id);
                }
                $query->where('status', 9);
            })->chunk(50, function ($verifications) {
                try {
                    foreach ($verifications as $item) {
                        $scan = GreenFacade::textScan($item->new_data);
                        if ($scan['status']) {
                            $item->status = 0;
                        } else {
                            $item->status = 1;
                        }
                        $item->audit_reason = $scan['msg'];
                        $item->save();
                    }
                } catch (\Exception $ex) {
                    $this->exception($ex);
                }
            });

            return $this->succeed();
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 会员资源自动审核
     * */
    public function ResourceAudit($id = 0)
    {
        try {
            MemberResource::where(function ($query) use ($id) {
                if ($id > 0) {
                    $query->where('id', $id);
                }
                $query->whereIn('type', [0, 2]);
                $query->where('status', 9);
            })->with('file')->chunk(50, function ($resources) {
                try {
                    Log::info("数据".json_encode($resources));
                    foreach ($resources as $item) {
                        if (isset($item->file)) {
                            if ($item->type == 1) {
                                $scan = GreenFacade::videoAsyncScan($item->file->url);
                            } else {
                                $scan = GreenFacade::imageSyncScan($item->file->url);
                            }
                            Log::info("审核视频".json_encode($scan));
                            if ($scan['status']) {
                                $item->status = 0;
                            } else {
                                $item->status = 1;
                            }
                            $item->audit_reason = $scan['msg'];
                            $item->save();
                        }
                    }
                } catch (\Exception $ex) {
                    $this->exception($ex);
                }
            });

            return $this->succeed();
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 会员资源自动审核
     * */
    public function imageAsynScan($id = 0)
    {
        try {
            MemberResource::where(function ($query) use ($id) {
                if ($id > 0) {
                    $query->where('id', $id);
                }
                $query->where('status', 9);
                $query->whereIn('type', [0, 2]);
            })->with('file')->chunk(50, function ($resources) {
                try {
                    foreach ($resources as $item) {
                        if (isset($item->file)) {
                            $scan = GreenFacade::imageAsynScan($item->file->url, url('callback/aliyun/green?id=' . $item->id));
                            $item->status = 8;
                            $item->audit_reason = '审核中';
                            $item->save();
                        }
                    }
                } catch (\Exception $ex) {
                    $this->exception($ex);
                }
            });

            return $this->succeed();
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 会员资源自动审核
     * */
    public function videoAsyncScan($id = 0)
    {
        try {
            MemberResource::where(function ($query) use ($id) {
                if ($id > 0) {
                    $query->where('id', $id);
                }
                $query->where('status', 9);
                $query->whereIn('type', [1]);
            })->with('file')->chunk(50, function ($resources) {
                try {
                    foreach ($resources as $item) {
                        $scan = GreenFacade::videoAsyncScan($item->file->url, url('callback/aliyun/green?id=' . $item->id));
                        if ($scan['status']) {
                            $item->status = 0;
                        } else {
                            $item->status = 1;
                        }
                        $item->audit_reason = $scan['msg'];
                        $item->save();
                    }
                } catch (\Exception $ex) {
                    $this->exception($ex);
                }
            });

            return $this->succeed();
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }


    /**
     * 动态图片/视频自动审核
     * */
    public function DynamicAudit($id = 0)
    {
        try {
            MemberDynamic::where(function ($query) use ($id) {
                if ($id > 0) {
                    $query->where('id', $id);
                }
                $query->where('status', 9);
            })->with('file')->chunk(50, function ($resources) {
                try {
                    foreach ($resources as $item) {
                        if (isset($item->file)) {
                            $item_file = json_decode(json_encode($item->file),true);
                            if (count($item_file) == count($item_file,1)){
                                if ($item->type == 1) {
                                    $scan = GreenFacade::videoAsyncScan($item_file['url']);
                                } else {
                                    $scan = GreenFacade::imageSyncScan($item_file['url']);
                                }

                            }else{
                                foreach ($item_file as $val) {

                                    if ($item->type == 1) {
                                        $scan = GreenFacade::videoAsyncScan($val['url']);
                                    } else {
                                        $scan = GreenFacade::imageSyncScan($val['url']);
                                        if ($scan['status']) {
                                            PlatformFile::where('id', $val['id'])->update(['status' => 0]);
                                        }
                                    }
                                }
                            }

                            $status = PlatformFile::where(['relevance_type' => 'MemberDynamic', 'relevance_id' => $item['id']])->value('status');
                            if ($status == 0) {
                                $item->status = 0;
                                $item->audit_reason = $scan['msg'];
                            } else {
                                $item->status = 1;
                                $item->audit_reason = '审核拒绝';
                            }
                            $item->save();
                        }
                    }
                } catch (\Exception $ex) {
                    $this->exception($ex);
                }
            });

            return $this->succeed();
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }


    /**
     * 会员动态自动审核
     * */
    public function imageDynamicAsynScan($id = 0)
    {
        try {
            MemberResource::where(function ($query) use ($id) {
                if ($id > 0) {
                    $query->where('id', $id);
                }
                $query->where('status', 9);
                $query->whereIn('type', [0, 1]);
            })->with('file')->chunk(50, function ($resources) {
                try {
                    foreach ($resources as $item) {
                        if (isset($item->file)) {
                            $scan = GreenFacade::imageAsynScan($item->file->url, url('callback/aliyun/green?id=' . $item->id));
                            $item->status = 8;
                            $item->audit_reason = '审核中';
                            $item->save();
                        }
                    }
                } catch (\Exception $ex) {
                    $this->exception($ex);
                }
            });

            return $this->succeed();
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 会员动态自动审核
     * */
    public function videoDynamicAsyncScan($id = 0)
    {
        try {
            MemberDynamic::where(function ($query) use ($id) {
                if ($id > 0) {
                    $query->where('id', $id);
                }
                $query->where('status', 9);
                $query->whereIn('type', [1]);
            })->with('file')->chunk(50, function ($resources) {
                try {
                    foreach ($resources as $item) {
                        $scan = GreenFacade::videoAsyncScan($item->file->url, url('callback/aliyun/green?id=' . $item->id));
                        if ($scan['status']) {
                            $item->status = 0;
                        } else {
                            $item->status = 1;
                        }
                        $item->audit_reason = $scan['msg'];
                        $item->save();
                    }
                } catch (\Exception $ex) {
                    $this->exception($ex);
                }
            });

            return $this->succeed();
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }


    /**
     * 创建陪聊
     * @param array $data
     * @return array
     */
    public function addRebot(array $data)
    {
        $data['type'] = 1;
        $data['sex'] = 1;
        $data['is_real'] = 0;
        $data['is_selfie'] = 0;
        $data['online_status'] = 0;
        $data['im_status'] = 0;
        $data['live_status'] = 0;
        $data['head_pic'] = url('images/default/headpic/' . rand(1, 10) . '.png');
        $data['cover'] = url('images/default/cover/' . rand(1, 27) . '.png');
        $data['birthday'] = rand(1988, 2000) . '-' . rand(01, 12) . '-' . rand(01, 28);
        $data['weixin_openid'] = CommonFacade::uuid('rebot');
        $data['unionid'] = $data['weixin_openid'];
        $citys = array('重庆', '上海', '成都', '广州');
        $data['resident'] = $citys[array_rand($citys, 1)];

        if (isset($data['weixin_openid'])) {
            if (MemberUser::where('weixin_openid', $data['weixin_openid'])->exists()) {
                return $this->addRebot($data);
            }
        }

        DB::beginTransaction();
        try {
            $member = new MemberUser($data);
            if ($member->save()) {
                DB::commit();
                return $this->succeed($member);
            }
            DB::rollBack();
            return $this->failure(1, '创建用户失败！');
        } catch (\Exception $ex) {
            DB::rollBack();
            $this->exception($ex);
            return $this->validation('创建用户异常，请联系管理员');
        }
    }

    /**
     * 在线陪聊
     * @return array
     */
    public function onlineRebot()
    {
        MemberUser::where('type', 1)->update(['online_status' => 1]);
        MemberUser::where('type', 1)->inRandomOrder()->take(PlatformFacade::config('online_robot'))->update(['online_status' => 0]);

    }


    /**
     * 计算魅力等级
     * @param $integral
     * @return int
     */
    public function getCharmGrade($integral){
        if ($integral == 0){
            return 0;
        }
        $grade=PlatformCharm::where('status', 0)->where('integral','<=',$integral)->orderby('integral','desc')->value('grade');
        return $grade;
    }


    /**
     * 计算vip等级
     * @param $integral
     * @return int
     */
    public function getVipGrade($integral){
        if ($integral == 0){
            return 0;
        }
        $grade=PlatformVip::where('status', 0)->where('integral','<=',$integral)->orderby('integral','desc')->value('grade');
        return $grade;
    }

    public function deleteUser(array $ids = [], $isRobot = 1)
    {

        if ($isRobot == 0) {
            $members = MemberUser::where('type', 1)->get();
        } else {
            $members = MemberUser::where('id', $ids)->get();
        }

        foreach ($members as $member) {
            DB::beginTransaction();
            try {
                $member->forceDelete();
                $member->gold()->forceDelete();
                $member->cash()->forceDelete();
                $member->recharges()->forceDelete();
                $member->realname()->forceDelete();
                $member->selfie()->forceDelete();
                $member->parameter()->forceDelete();
                $member->rate()->forceDelete();
                $member->extend()->forceDelete();
                $member->formVisitors()->forceDelete();
                $member->toVisitors()->forceDelete();
                $member->formGifts()->forceDelete();
                $member->toGifts()->forceDelete();
                $member->resources()->forceDelete();
                $member->tags()->forceDelete();
                $member->formFriends()->forceDelete();
                $member->toFriends()->forceDelete();
                $member->formAttentions()->forceDelete();
                $member->toAttentions()->forceDelete();
                $member->logins()->forceDelete();
                $member->notices()->forceDelete();
                $member->verifications()->forceDelete();
                $member->formLikes()->forceDelete();
                $member->toLikes()->forceDelete();
                $member->dealCashs()->forceDelete();
                $member->dealWithdraws()->forceDelete();
                $member->formTalks()->forceDelete();
                $member->toTalks()->forceDelete();
                $member->formLikes()->forceDelete();
                $member->formLikes()->forceDelete();
                $member->walletRecords()->forceDelete();
                $member->withdraws()->forceDelete();
                $member->blacklist()->forceDelete();
                $member->dynamic()->forceDelete();
                $member->resource()->forceDelete();
                DB::commit();
            } catch (\Exception $ex) {
                DB::rollBack();
            }
        }
        return $this->succeed();
    }


    /**
     * 不定时推送消息
     */
    public function pushMessage(){
        $startTime = date('Y-m-d 00:00:00', strtotime("-4 day"));
        $endTime = date('Y-m-d 23:59:59', strtotime("-4 day"));
        $user = MemberUser::where(['type' => 0])->where('is_selfie', '<>', 0)->whereBetween('last_time', [$startTime, $endTime])->get()->toArray();
        if (count($user) > 0) {
            foreach ($user as $v) {
                if ($v['push_token']) {
                    PushFacade::pushToken($v['push_token'], $v['app_platform'], $v['nick_name'], "亲爱的" . $v['nick_name'] . "，有好多主播都在找你聊天呢！赶快来聊天吧！", $type = 'NOTICE', ['type' => 'member', 'id' => $v['id'], 'no' => $v['no'], 'nickname' => $v['nick_name']]);
                }
            }
        }
    }
}
