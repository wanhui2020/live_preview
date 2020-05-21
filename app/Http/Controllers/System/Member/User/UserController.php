<?php

namespace App\Http\Controllers\System\Member\User;

use App\Facades\MemberFacade;
use App\Facades\PlatformFacade;
use App\Http\Controllers\Controller;
use App\Models\DealGift;
use App\Models\DealWithdraw;
use App\Models\MemberAttention;
use App\Models\MemberFriend;
use App\Models\MemberLogin;
use App\Models\MemberUser;
use App\Models\MemberUserType;
use App\Models\MemberWalletRecharge;
use App\Models\MemberWalletRecord;
use App\Models\PlatformType;
use App\Models\SystemUser;
use App\Repositories\MemberUserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function __construct(MemberUserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 页面首页
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('system.member.user.index');
    }

    /**
     * 数据列表
     *
     * @return array
     */
    public function lists()
    {
        try {
            $list = $this->repository->with([
                'parent',
                'agent',
                'realname',
                'selfie',
                'vip',
                'gold',
                'cash',
                'toGifts',
                'toTalks',
                'recharges',
                'resources',
            ])->withCount(['childrens', 'agentChildrens'])->lists();
            foreach ($list as &$v){
                $v['wallet_record'] =  MemberWalletRecord::where(['member_id' =>0,'status'=>0])->sum('money');
                $v['age'] = bcsub(date("Y"),substr($v['birthday'],0,4));
            }

            return $this->paginate($list);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /*
     * 改变状态
     * */
    public function status(Request $request)
    {
        try {
            $contract = MemberUser::findOrFail($request->id);
            if ($contract) {
                $contract->status = $contract->status == 1 ? 0 : 1;
                if ($contract->save()) {
                    return $this->succeed('操作成功');
                } else {
                    return $this->validation('操作失败');
                }
            } else {
                return $this->validation('用户不存在');
            }
        } catch (\Exception $ex) {
            return $this->validation('操作失败', $ex);
        }
    }

    /**
     * 创建
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('system.member.user.create');
    }

    /**
     * 新增
     *
     * @param Request $request
     *
     * @return array
     */
    public function store(Request $request)
    {
        try {
            if (!$request->filled('mobile')) {
                return $this->validation('手机号不能为空');
            }
            $data = $request->all();
            unset($data['file']);
            $age = PlatformFacade::config('user_age');
            if ($data['age'] < $age) {
                return $this->validation('年龄不能小于' . $age . '岁');
            }
            $data['birthday'] = date("Y")-$data['age'].'-'.date("m-d");
            $result = $this->repository->store($data);
            if ($result['status']) {
                return $this->succeed($result);
            }

            return $this->failure(1, $result['msg']);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 编辑
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        try {
            $user = $this->repository->with(['userTypes'])->find($request->id);
            if (isset($user->userTypes)) {
                foreach ($user->userTypes as $k) {
                    $usertype[] = $k->type_id;
                }
            }
            $type = PlatformType::where('status', 0)->where('is_system', 0)->get();
            return view('system.member.user.edit', compact('type', 'usertype'))->with('user', $user);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 修改
     *
     * @param Request $request
     *
     * @return array|mixed
     */
    public function update(Request $request)
    {
        try {
            $data = $request->all();
            $type_id = array();
            $memberUser = array();
            if (!empty($data['type_id'])){
                $type_id = explode(',', $data['type_id']);
            }
            $userTypes = MemberUserType::where('member_id', $data['id'])->get();
            if (isset($userTypes)) {
                foreach ($userTypes as $item) {
                    $memberUser[] = $item->type_id;
                }
            }
            $array_A =  array_diff($type_id,$memberUser);
            $array_B =  array_diff($memberUser,$type_id);
            if (!empty($array_A)){
                foreach ($array_A as  $A){
                    $memberType = new MemberUserType();
                    $memberType->member_id = $data['id'];
                    $memberType->type_id = $A;
                    $memberType->save();
                }
            }
            if (!empty($array_B)){
                foreach ($array_B as  $B){
                    $memberType = MemberUserType::where('member_id',$data['id'])->where('type_id',$B)->first();
                    $memberType->forceDelete();
                }
            }
            unset($data['type_id']);
            $result = $this->repository->update($data);

            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 删除
     *
     * @param Request $request
     *
     * @return array|mixed
     */
    public function destroy(Request $request)
    {
        try {
            $result = $this->repository->destroy($request->ids);

            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * token单点登录
     */
    public function login(Request $request)
    {
        try {
            $user = SystemUser::find($request->id);
            if (!isset($user)) {
                return $this->validation('未找到相关用户');
            }
            Auth::guard('SystemUser')->login($user);

            return response()->redirectTo('/system');
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }


    /**
     * 会员详情
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detail(Request $request)
    {
        try {
            $member = $this->repository->with([
                'parent',
                'agent',
                'realname',
                'selfie',
                'vip',
                'gold',
                'cash',
                'toGifts',
                'toTalks',
                'recharges',
                'resources',
                'formFriends',
                'toFriends',
                'toAttentions',
                'walletRecords',
            ])->find($request->id);

            return view('system.member.user.detail', compact('member'));
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 创建机器人
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function robot(Request $request)
    {
        try {
            return MemberFacade::addRebot([]);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * Im状态检查
     */
    public function imStatus(Request $request)
    {
        try {
            $user = MemberUser::find($request->id);
            if (!isset($user)) {
                return $this->validation('未找到相关用户');
            }
            $resp = $this->repository->imCheck($request->id);

            return $resp;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * Im状态批量检查
     */
    public function imMultiCheck(Request $request)
    {
        try {
            $resp = $this->repository->imMultiCheck();

            return $resp;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }


    /**
     * 获取我的
     *
     * @param Request $request
     *
     * @return array
     */
    public function getData(Request $request)
    {
        try {
            $id = $request->id;
            if ($request->type == 1) {
                $result = MemberFriend::where('member_id', $id)->paginate();
            }
            if ($request->type == 2) {
                $result = MemberAttention::where('to_member_id', $id)
                    ->paginate();
            }
            if ($request->type == 3) {
                $result = DealGift::where('to_member_id', $id)
                    ->with(['tomember'])->paginate();
            }
            if ($request->type == 4) {
                $result = MemberWalletRecharge::where('member_id', $id)
                    ->paginate();
            }
            if ($request->type == 5) {
                $result = DealWithdraw::where('member_id', $id)->paginate();
            }
            if ($request->type == 6) {
                $result = MemberWalletRecord::where('member_id', $id)
                    ->paginate();
            }

            return $this->paginate($result);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }


    /**
     * 获取我的
     *
     * @param Request $request
     *
     * @return array
     */
    public function reportForm(Request $request)
    {
        return view('system.member.user.report_form');
    }


    /**
     * 获取我的
     *
     * @param Request $request
     *
     * @return array
     */
    public function reportFormLists(Request $request)
    {
        try {
            \DB::connection()->enableQueryLog();
            $sqlTalk = "select sum(duration)from deal_talks where deal_talks.called_id=member_users.id";//通话时长
            $sqlTalkWay = "select count(id)from deal_talks where deal_talks.called_id=member_users.id and way=0";//通话成功
            $sqlTalkWay2 = "select count(id)from deal_talks where deal_talks.called_id=member_users.id and way != 0";//通话失败
            $sqlRecord = "select sum(money)from member_wallet_records where member_wallet_records.member_id=member_users.id and `type` in (15,32)";//总收益
            $sql = "select sum(duration) from member_logins where member_logins.member_id=member_users.id";//在线时长
            $sqlGift = "select sum(total) from deal_gifts where deal_gifts.to_member_id=member_users.id"; //礼物
            $sqlAttention = "select count(*) from member_attentions where member_attentions.to_member_id=member_users.id"; //关注数

            $sqlWithdraw = "select sum(total) from deal_withdraws where deal_withdraws.member_id=member_users.id"; //提现
            $sqlWalletRecharge = "select sum(money) from member_wallet_recharges where member_wallet_recharges.member_id=member_users.id"; //充值
            $sqlDealChat = "select sum(total) from deal_chats where deal_chats.to_member_id=member_users.id"; //文本收益
            $sqlResource = "select count(*) from member_resources where member_resources.member_id=member_users.id"; //资源个数
            $sqlReport = "select count(*) from member_reports where member_reports.to_member_id=member_users.id"; //举报数
            $sqlDynamic = "select count(*) from member_dynamics where member_dynamics.member_id=member_users.id"; //动态数
            $sqlDealLikes = "select count(*) from deal_likes where deal_likes.to_member_id=member_users.id"; //点赞
            $sqlDealComments = "select count(*) from deal_comments where deal_comments.to_member_id=member_users.id"; //评论

            $list = $this->repository->where(function ($query) use ($request) {
                if ($request->filled('key')) {
                    $query->where('no', 'like', '%' . $request->key . '%');
                }
                if ($request->filled('on_time')) {
                    $dateTime = explode(' - ', request('on_time'));
                    $query->whereBetween('updated_at', [$dateTime[0], $dateTime[1]]);
                }
                if ($request->filled('on_line')) {
                    $dateTime = explode(' - ', request('on_line'));
                    $dateTime[0] = date("Y-m-d ") . $dateTime[0];
                    $dateTime[1] = date("Y-m-d ") . $dateTime[1];
                    $query->whereBetween('updated_at', [$dateTime[0], $dateTime[1]]);
                }
            });

            //通话时间
            if (request('talk') != null) {
                $list = $list->whereRaw("({$sqlTalk})=" . request('talk'));
            }
            //通话率
            if (request('rate') != null) {
                $list = $list->whereRaw("({$sqlTalkWay})/({$sqlTalkWay2})=" . request('rate'));
            }

            $list = $list->select(["*", \DB::raw("($sql) as duration"), \DB::raw("($sqlTalk) as talk_duration"), \DB::raw("($sqlTalkWay) as talk_success"), \DB::raw("($sqlTalkWay2) as talk_fail"), \DB::raw("(($sqlTalkWay)/($sqlTalkWay2)) as talk_rate"), \DB::raw("($sqlRecord) as record_money"), \DB::raw("($sqlGift) as gift_money"), \DB::raw("($sqlAttention) as attention"), \DB::raw("($sqlWithdraw) as withdraw"), \DB::raw("($sqlWalletRecharge) as wallet_recharge"), \DB::raw("($sqlDealChat) as deal_chat"), \DB::raw("($sqlResource) as resource"), \DB::raw("($sqlReport) as report"), \DB::raw("($sqlDynamic) as dynamic"), \DB::raw("($sqlDealLikes) as deal_like"), \DB::raw("($sqlDealComments) as deal_comment")])->orderBy('talk_duration', 'DESC')->orderBy('duration', 'DESC')->orderBy('talk_rate', 'DESC')->orderBy('record_money', 'DESC')->paginate();

//            dd(\DB::getQueryLog());
            return $this->paginate($list);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

}

