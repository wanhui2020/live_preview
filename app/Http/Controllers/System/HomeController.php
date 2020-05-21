<?php

namespace App\Http\Controllers\System;

use App\Facades\MemberFacade;
use App\Facades\PushFacade;
use App\Models\DealCash;
use App\Models\DealConversion;
use App\Models\DealGift;
use App\Models\DealGive;
use App\Models\DealGold;
use App\Models\DealSocial;
use App\Models\DealTalk;
use App\Models\DealUnlock;
use App\Models\DealView;
use App\Models\DealVip;
use App\Models\DealWithdraw;
use App\Models\MemberAttention;
use App\Models\MemberBlacklist;
use App\Models\MemberFeedback;
use App\Models\MemberFriend;
use App\Models\MemberLogin;
use App\Models\MemberReport;
use App\Models\MemberResource;
use App\Models\MemberSignIn;
use App\Models\MemberTag;
use App\Models\MemberUser;
use App\Models\MemberUserExtend;
use App\Models\MemberUserParameter;
use App\Models\MemberUserRate;
use App\Models\MemberUserRealname;
use App\Models\MemberUserSelfie;
use App\Models\MemberVerification;
use App\Models\MemberVisitor;
use App\Models\MemberWalletCash;
use App\Models\MemberWalletGold;
use App\Models\MemberWalletRecharge;
use App\Models\MemberWalletRecord;
use App\Models\MemberWalletWithdraw;
use App\Models\PlatformBasic;
use App\Models\PlatformCharm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;


class HomeController extends BaseController
{

    /**
     * 页面首页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('system.index');
    }

    public function home(Request $request)
    {
        $hots     = MemberUser::where('status', 0)
            ->orderBy('updated_at', 'DESC')->take(10)->get();
        $member   = MemberUser::where('status', 0)
            //            ->groupBy('date')
            //            ->orderBy('date', 'DESC')
            ->select(DB::raw('
           count(status=0 or null) as memberCount ,
           count(online_status=0  or null ) as onlineStatus,
           count(live_status=1  or null ) as liveStatus,
           count(im_status=1  or null ) as imStatus,
           count(is_real=0  or null ) as realStatus,
           count(is_selfie=0  or null ) as  selfieStatus,
           count(im_status=0  or null ) as imStatus
           '))->first();
        $resource = MemberResource::where('status', 9)
            ->select(DB::raw('
            count(1=1)  as totalCount,
           count(type=2 or null) as coverCount ,
           count(type=0  or null ) as pictureCount,
           count(type=1  or null ) as videoCount
           '))->first();

        DB::connection()->enableQueryLog();
        //上上个月
        $beginDate =  date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m")-2,1,date("Y")));
        $endDate =  date("Y-m-d H:i:s",mktime(23,59,59,date("m")-1 ,0,date("Y")));
        //上个月的开始时间
        $beginLastMonth = date('Y-m-01 00:00:00',strtotime('-1 month'));
        $endLastMonth = date("Y-m-d 23:59:59", strtotime(-date('d').'day'));
        //昨天
        $beginYesterday=date("Y-m-d H:i:s",mktime(0,0,0,date('m'),date('d')-1,date('Y')));
        $endYesterday=date("Y-m-d H:i:s",mktime(0,0,0,date('m'),date('d'),date('Y'))-1);
        //今天
        $start = date("Y-m-d 00:00:00");
        $end = date("Y-m-d 23:59:59");

        //访问量
        $login = MemberLogin::whereBetween('created_at',[$beginLastMonth,$endLastMonth])->groupBy('member_id')->count();
        $toLogin = MemberLogin::whereBetween('created_at',[$beginDate,$endDate])->groupBy('member_id')->count();
        if($login > 0 && $toLogin > 0) {
            $data['login'] = bcmul(bcdiv($login, $toLogin, 2), 100, 2).'%';
        }else{
            $data['login'] = 0;
        }
        //平台收入
        $platformMoney = MemberWalletRecord::where(['member_id'=>0])->whereBetween('created_at',[$beginLastMonth,$endLastMonth])->sum('money');
        $platformToMoney = MemberWalletRecord::where(['member_id'=>0])->whereBetween('created_at',[$beginDate,$endDate])->sum('money');
        if($platformMoney > 0 && $platformToMoney > 0) {
            $data['platform_record_money'] = bcmul(bcdiv($platformMoney, $platformToMoney, 2), 100, 2).'%';
        }else{
            $data['platform_record_money'] = 0;
        }

        //新人
        $data['user_count_today'] = MemberUser::where(['status'=>0])->whereBetween('created_at',[$start,$end])->count('id');
        $data['user_count_yesterday'] = MemberUser::where(['status'=>0])->whereBetween('created_at',[$beginYesterday,$endYesterday])->count('id');
        //充值
        $data['recharge_today'] = MemberWalletRecord::where(['status'=>0])->whereIn('type',[11,31])->whereBetween('created_at',[$start,$end])->sum('money');
        $data['recharge_yesterday'] = MemberWalletRecord::where(['status'=>0])->whereIn('type',[11,31])->whereBetween('created_at',[$beginYesterday,$endYesterday])->sum('money');
        //在线
        $data['user_online_today'] = MemberUser::where(['status'=>0,'im_status'=>0])->whereBetween('created_at',[$start,$end])->count('id');
        $data['user_online_yesterday'] = MemberUser::where(['status'=>0,'im_status'=>0])->whereBetween('created_at',[$beginYesterday,$endYesterday])->count('id');
        //提现
        $data['withdraw_today'] = DealWithdraw::whereBetween('created_at',[$start,$end])->sum('money');
        $data['withdraw_yesterday'] = DealWithdraw::whereBetween('created_at',[$beginYesterday,$endYesterday])->sum('money');
//dd(DB::getQueryLog());
        return view('system.home', compact('member', 'resource', 'hots','data'));
    }

    /**
     * 搜索
     *
     * @param  Request  $request
     */
    public function search(Request $request)
    {
        $members = MemberUser::where(function ($query) {
            $query->where('status', 0);
            $query->where('no', 'like', '%'.request('keywords').'%');
            $query->orWhere('nick_name', 'like', '%'.request('keywords').'%');
            $query->orWhere('province', 'like', '%'.request('keywords').'%');
            $query->orWhere('city', 'like', '%'.request('keywords').'%');
        })
            ->get();
        if (count($members) == 1) {
            return response()->redirectTo('system/member/user/detail?id='
                .$members[0]->id);
        }

        return view('system.search', compact('members'));
    }


    /**
     * 等级更新
     *
     * @param  Request  $request
     */
    public function gradeSync(Request $request)
    {
        MemberFacade::vipIntegralSync();
        MemberFacade::charmIntegralSync();

        return $this->succeed();
    }

    /**
     * 清除缓存
     *
     * @param  Request  $request
     */
    public function clearCache(Request $request)
    {
        Cache::forget('PlatformConfig');
        foreach (PlatformBasic::all() as $item) {
            Cache::forget('PlatformBasic-'.$item->type);
        }
        foreach (PlatformCharm::all() as $item) {
            Cache::forget('PlatformCharm-'.$item->grade);
        }
        Cache::forget('PlatformGift');
        Cache::forget('PlatformPrice');
        Cache::forget('PlatformTag');
        Cache::forget('PlatformVip');

        return $this->succeed();
    }


    /**
     * 测试方法
     */
    public function test(Request $request)
    {
    }

    /**
     * 清理数据
     */
    public function clear(Request $request)
    {
        DB::beginTransaction();
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DealCash::truncate();
            DealUnlock::truncate();
            DealConversion::truncate();
            DealGift::truncate();
            DealGive::truncate();
            DealGold::truncate();
            DealTalk::truncate();
            DealView::truncate();
            DealVip::truncate();
            DealWithdraw::truncate();
            MemberAttention::truncate();
            MemberBlacklist::truncate();
            //            MemberCredit::truncate();
            MemberFeedback::truncate();
            MemberFriend::truncate();
            MemberReport::truncate();
            MemberResource::truncate();
            MemberSignIn::truncate();
            DealSocial::truncate();
            MemberTag::truncate();
            MemberUser::truncate();
            MemberUserExtend::truncate();
            MemberUserParameter::truncate();
            MemberUserRate::truncate();
            MemberUserRealname::truncate();
            MemberUserSelfie::truncate();
            MemberVerification::truncate();
            MemberVisitor::truncate();
            MemberWalletCash::truncate();
            MemberWalletGold::truncate();
            MemberWalletRecharge::truncate();
            MemberWalletRecord::truncate();
            MemberWalletWithdraw::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            DB::commit();

            return 'OK';
        } catch (\Exception $ex) {
            DB::rollBack();

            return $this->exception($ex);
        }
    }


    /**
     *  安全码验证
     *
     * @param  Request  $request
     *
     * @return array
     */
    public function verifySafeCode(Request $request)
    {
        try {
            /*$safecode = md5($request->safecode);
            if (config('app.safety') != $safecode) {
                return $this->validation('安全码错误,请重新输入');
            }*/
            $user = $request->user('SystemUser');
            if (!$user) {
                return $this->validation('登录异常');
            }
            if ($user->security_code == null) {
                return $this->validation('还没设置安全码，请前往个人中心设置');
            }
            if (!Hash::check($request->safecode, $user->security_code)) {
                return $this->validation('安全码错误,请重新输入');
            }

            return $this->succeed();
        } catch (\Exception $e) {
            $this->exception($e);
            $this->validation('安全码验证异常');
        }
    }


    /**
     *  Redis获取
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function redis(Request $request)
    {
        try {
            if ($request->isMethod('GET')) {
                return view('system.redis');
            }
            if ($request->user()->type != 0) {
                return $this->validation('无权操作');
            }
            if (!isset($request->key)) {
                return $this->validation('请输入Redis的Key');
            }
            if ($request->key == 'PlatformConfig') {
                $valus = Cache::get('PlatformConfig');
            } else {
                $valus = Redis::hVals($request->key);
            }

            return $this->succeed($valus);
        } catch (\Exception $e) {
            $this->exception($e);

            return $this->validation('发生异常，请联系管理员');
        }
    }



    /**
     *更新推送标签
     *
     * @param  Request  $request
     */
    public function pushTag(Request $request)
    {
        $user = MemberUser::where('status', 0)->where('is_real','<>', 0)->where('is_selfie','<>', 0)->get()->toArray();
        if (count($user) > 0) {
            $token = array_column($user, 'push_token');
            $token = implode(',', $token);
            PushFacade::bindTag($token, 'DEVICE', '用户');
        }

        return $this->succeed();
    }
}
