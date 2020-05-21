<?php

namespace App\Http\Controllers\Api\Member\User;


use App\Facades\CommonFacade;
use App\Facades\ImFacade;
use App\Facades\OssFacade;
use App\Facades\PlatformFacade;
use App\Facades\PushFacade;
use App\Facades\RiskFacade;
use App\Http\Controllers\Controller;
use App\Http\Resources\MemberUserDetailResource;
use App\Http\Resources\MemberUserExtendResource;
use App\Http\Resources\MemberUserListResource;
use App\Http\Resources\MemberUserMyResource;
use App\Http\Resources\MemberUserSimpleResource;
use App\Http\Resources\PlatformTypeResource;
use App\Jobs\SendAutoMessageJob;
use App\Models\DealCash;
use App\Models\DealGift;
use App\Models\DealGold;
use App\Models\DealMessage;
use App\Models\MemberUser;
use App\Models\MemberUserRate;
use App\Models\MemberUserType;
use App\Models\MemberVisitor;
use App\Models\MemberWalletCash;
use App\Models\MemberWalletGold;
use App\Models\MemberWalletRecord;
use App\Models\PlatformCondition;
use App\Models\PlatformPaymentChannel;
use App\Models\PlatformSendMessage;
use App\Models\PlatformType;
use App\Models\WechatPayment;
use App\Repositories\MemberUserExtendRepository;
use App\Repositories\MemberUserRepository;
use App\Services\ImService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 用户 我的接口
 * Class PayController
 */
class UserController extends Controller
{
    /**
     * 我的信息
     * @param Request $request
     * @param MemberUserRepository $memberUserRepository
     * @return array
     */
    public function my(Request $request, MemberUserRepository $memberUserRepository)
    {
        try {
            $member = $request->user('ApiMember');

            return $this->succeed(new MemberUserMyResource($member), '我的会员返回成功');
        } catch (\Exception $e) {
            return $this->exception($e, '我的会员返回异常，请联系管理员！');
        }
    }

    /**
     * 会员列表
     */
    public function lists(Request $request, MemberUserRepository $memberUserRepository)
    {
        try {
            $member = $request->user('ApiMember');
            if (!$member->last_auto_message_at || !Carbon::parse($member->last_auto_message_at)->isToday()) {
                dispatch(new SendAutoMessageJob($member->id));
            }

            $where = function ($query) use ($member, $request) {
                //过滤客服
                $query->whereNotIn('type', [2]);

                //机器人过滤
                if ($member->is_selfie == 0 || $member->gold->balance > 0) {
                    $query->whereNotIn('type', [1]);
                }
                //正常状态
                $query->where('status', 0);

//                $query->where('sex', $member->sex == 0 ? 1 : 0);
            };

            $list = $memberUserRepository->where($where)->where(function ($query) use ($member, $request) {

                //不能查看自己
//                    $query->whereNotIn('id', [$member->id]);
                //必须有封面图片
                $query->whereNotNull('cover');

                //性别
                if ($request->filled('sex')) {
                    $query->where('sex', $request->sex);
                }

                //自拍认证
                if ($request->filled('is_selfie')) {
                    $query->where('is_selfie', $request->is_selfie);
                } else {
                    $query->where('is_selfie', 0);
                }

                //在线状态
                if ($request->filled('online_status')) {
                    $query->where('online_status', $request->online_status);
                }
                //IM在线状态
                if ($request->filled('im_status')) {
                    $query->where('im_status', $request->im_status);
                }
                //会员编号
                if ($request->filled('no')) {
                    $query->where('no', $request->no);
                }

                //关键字查询
                if ($request->filled('key')) {
                    $query->where(function ($query) {
                        $query->where('no', 'like', '%' . request('key') . '%')
                            ->orWhere('nick_name', 'like', '%' . request('key') . '%');
                    });
                }
                //省
                if ($request->filled('province')) {
                    $query->where('province', $request->province);
                }
                //市
                if ($request->filled('city')) {
                    $city = explode('-', $request->city);
                    $query->where('province', 'like', '%' . $city[0] . '%');
                    $query->where('city', 'like', '%' . $city[1] . '%');
                }
                //县
                if ($request->filled('district')) {
                    $query->where('district', $request->district);
                }
            });
            //有自定义标签
            if ($request->filled('type') && $request->type != 0) {
                $type = PlatformType::where(['id' => $request->type, 'status' => 0])->first();
                if ($type->is_system !=1){
                    $member_ids = MemberUserType::where('type_id',$request->type)->pluck('member_id');
                    $member_ids = json_decode($member_ids);
                    $list= $list->whereIn('id',$member_ids);
                }else{
                    if (!empty($type['condition_id']) && $type['type'] == 0) {
                        $conditionId = explode(',', $type['condition_id']);
                        $codition = PlatformCondition::whereIn('id', $conditionId)->orderBy('created_at', 'asc')->get();
                        foreach ($codition as $v) {
                            if ($v['key'] == 'address') {
                                if ($member->lng && $member->lat && $v['id'] == 1) {
                                    $lat = $member->lat;
                                    $lng = $member->lng;
                                    $sql = "(acos(sin(({$lat}*3.1415)/180) * sin((lat*3.1415)/180)+cos(({$lat}*3.1415)/180) * cos((lat*3.1415)/180) * cos(({$lng}*3.1415)/180 - (lng*3.1415)/180))*6370.996)";
                                    $list = $list->select(['*', DB::raw("$sql as distance")])->orderBy('distance', 'asc');
                                    $list = $list->whereRaw($sql . "<=" . $v['value']);
                                }
                            } elseif ($v['key'] == 'sql') {
                                $list->whereRaw($v['value']);
                            } elseif ($v['key'] == 'age') {
                                $sql = "(DATE_FORMAT(NOW(), '%Y') - DATE_FORMAT(birthday, '%Y') - (DATE_FORMAT(NOW(), '00-%m-%d') < DATE_FORMAT(birthday, '00-%m-%d')))";
                                $list->whereRaw($sql . $v['mark'] . $v['value']);
                            } else {
                                $sql = $v['key'] . $v['mark'] . $v['value'];
                                $list->whereRaw($sql);
                            }
                        }
                    } else if ($type['type'] == 1) {
//                    DB::connection()->enableQueryLog();
                        $sql = "select distinct login_time from member_logins where member_logins.member_id=member_users.id order by login_time  desc limit 1";
                        $list = MemberUser::select(["*", DB::raw("($sql) as login_time")])->where(['status' => 0, 'type' => 0, 'sex' => 0])->where("is_selfie", "<>", 0)->orderBy("login_time", "desc")->paginate(10);
//                    dd(\DB::getQueryLog());
                        return $this->succeed(MemberUserListResource::collection($list), '获取会员列表成功');
                    }
                }
            }
            $list = $list->orderBy('online_status', 'asc')
                //IM在线状态
                ->orderBy('im_status', 'asc')
                //热门推荐指数
                ->orderBy('hot', 'desc')
                //忙碌状态
                ->orderBy('live_status', 'asc')
                //注册时间
                ->orderBy('last_time', 'desc')
                ->paginate(10);
            //推荐
            if ($request->type == 0) {
                $list = $memberUserRepository->where($where)->where(function ($query) use ($member, $request) {
                    //正常状态
//                    $query->where('status', 0);
                    //不能查看自己
//                    $query->whereNotIn('id', [$member->id]);
                    //必须有封面图片
                    $query->whereNotNull('cover');

                    //性别
                    if ($request->filled('sex')) {
                        $query->where('sex', $request->sex);
                    }

                    //自拍认证
                    if ($request->filled('is_selfie')) {
                        $query->where('is_selfie', $request->is_selfie);
                    } else {
                        $query->where('is_selfie', 0);
                    }

                    //在线状态
                    if ($request->filled('online_status')) {
                        $query->where('online_status', $request->online_status);
                    }
                    //IM在线状态
                    if ($request->filled('im_status')) {
                        $query->where('im_status', $request->im_status);
                    }
                    //会员编号
                    if ($request->filled('no')) {
                        $query->where('no', $request->no);
                    }

                    //关键字查询
                    if ($request->filled('key')) {
                        $query->where(function ($query) {
                            $query->where('no', 'like', '%' . request('key') . '%')
                                ->orWhere('nick_name', 'like', '%' . request('key') . '%');
                        });
                    }
                    //省
                    if ($request->filled('province')) {
                        $query->where('province', $request->province);
                    }
                    //市
                    if ($request->filled('city')) {
                        $city = explode('-', $request->city);
                        $query->where('province', 'like', '%' . $city[0] . '%');
                        $query->where('city', 'like', '%' . $city[1] . '%');
                    }
                    //县
                    if ($request->filled('district')) {
                        $query->where('district', $request->district);
                    }
                })
                    //在线状态
                    ->orderBy('online_status', 'asc')
                    //IM在线状态
                    ->orderBy('im_status', 'asc')
                    //热门推荐指数
                    ->orderBy('hot', 'desc')
                    //忙碌状态
                    ->orderBy('live_status', 'asc')
                    //注册时间
                    ->orderBy('last_time', 'desc')
                    ->paginate(10);
//                dd(DB::getQueryLog());
            }

            return $this->succeed(MemberUserListResource::collection($list), '获取会员列表成功');
        } catch (\Exception $ex) {
            return $this->exception($ex, '获取会员列表异常');
        }
    }

    /**
     * 在线客服
     */
    public function services(Request $request, MemberUserRepository $memberUserRepository)
    {
        try {
            $member = $request->user('ApiMember');
            $list = $memberUserRepository->where(function ($query) use ($member, $request) {
                $query->where('type', 2);
                $query->whereIn('service_id', [0, $member->service_id]);
            })
                //在线状态
                ->orderBy('online_status', 'asc')
                //IM在线状态
                ->orderBy('im_status', 'asc')
                //热门推荐指数
                ->orderBy('hot', 'desc')
                //忙碌状态
                ->orderBy('live_status', 'asc')
                //注册时间
                ->orderBy('created_at', 'asc')
                ->paginate();
            return $this->succeed(MemberUserSimpleResource::collection($list), '获取在线客服成功');
        } catch (\Exception $ex) {
            return $this->exception($ex, '获取在线客服异常');
        }
    }

    /**
     * 我推荐的会员
     */
    public function childrens(Request $request, MemberUserRepository $memberUserRepository)
    {
        try {
            DB::connection()->enableQueryLog();
            $member = $request->user('ApiMember');

            if ($request->created_at == 2) {
                $order = 'record_money';
            } else {
                $order = 'created_at';
            }
            $id = $member->id;

            $mondy = "select sum(money) from member_wallet_records where member_wallet_records.member_id =member_users.parent_id  and member_wallet_records.to_member_id =member_users.id  and `type` in('19','20','50','51') or  member_wallet_records.member_id =member_users.agent_id  and member_wallet_records.to_member_id =member_users.id  and `type` in('19','20','50','51') ";
            $list = $memberUserRepository->where(function ($query) use ($id) {
                $query->orWhere('agent_id', $id)->orWhere('parent_id', $id);
            })->select(['*', DB::raw("($mondy) as record_money")])
                ->orderBy($order, 'DESC')->paginate();
//dd(DB::getQueryLog());
            return $this->succeed(MemberUserSimpleResource::collection($list), '获取会员列表成功');
        } catch (\Exception $ex) {
            return $this->exception($ex, '获取会员列表异常');
        }
    }

    /**
     * 会员搜索
     */
    public function search(Request $request, MemberUserRepository $memberUserRepository)
    {
        try {
            $member = $request->user('ApiMember');

            if (!$request->filled('key')) {
                return $this->validation('请输入关键词！');
            }

            $list = $memberUserRepository->where(function ($query) use ($member, $request) {
                $query->where('status', 0);
                $query->where('no', 'like', '%' . request('key') . '%');
                $query->orWhere('nick_name', 'like', '%' . request('key') . '%');
                $query->orWhere('province', 'like', '%' . request('key') . '%');
                $query->orWhere('city', 'like', '%' . request('key') . '%');
                $query->orWhere('district', 'like', '%' . request('key') . '%');
                $query->orWhere('address', 'like', '%' . request('key') . '%');
                $query->orWhere('username', 'like', '%' . request('key') . '%');
                $query->orWhere('aphorism', 'like', '%' . request('key') . '%');
                $query->orWhere('signature', 'like', '%' . request('key') . '%');

            })
                //在线状态
                ->orderBy('online_status', 'asc')
                //IM在线状态
                ->orderBy('im_status', 'asc')
                //热门推荐指数
                ->orderBy('hot', 'desc')
                //忙碌状态
                ->orderBy('live_status', 'asc')
                //注册时间
                ->orderBy('created_at', 'asc')
                ->paginate();
            return $this->succeed(MemberUserSimpleResource::collection($list), '获取会员列表成功');
        } catch (\Exception $ex) {
            return $this->exception($ex, '获取会员列表异常');
        }
    }

    /**
     * 排行
     */
    public function ranking(Request $request, MemberUserRepository $memberUserRepository)
    {
        try {
            $member = $request->user('ApiMember');
            $list = $memberUserRepository->where(function ($query) use ($member, $request) {
                $query->where('status', 0);
                $query->where('type', 0);
                $query->where('status', 0);
                //性别
                if ($request->filled('sex')) {
                    $query->where('sex', $request->sex);
                }

                //实名状态
                if ($request->filled('is_real')) {
                    $query->where('is_real', $request->is_real);
                }

                //在线状态
                if ($request->filled('online_status')) {
                    $query->where('online_status', $request->online_status);
                }
                //IM在线状态
                if ($request->filled('im_status')) {
                    $query->where('im_status', $request->im_status);
                }
                //省
                if ($request->filled('province')) {
                    $query->where('province', $request->province);
                }
                //市
                if ($request->filled('city')) {
                    $query->where('city', $request->city);
                }
                //县
                if ($request->filled('district')) {
                    $query->where('district', $request->district);
                }


            });
            if ($member->is_selfie == 0) {
            } else {

            }
            $list = $list->whereBy('sex', $member->sex == 0 ? 1 : 0);
            if ($member->sex == 0) {
                $list = $list->whereBy('is_selfie', 0);
            }

            if ($request->filled('type')) {
                //魅力
                if ($request->type == 0) {
                    $list = $list->orderBy('charm_integral', 'DESC');
                }
                //人气
                if ($request->type == 1) {
                    DB::connection()->enableQueryLog();
                    $sql = "select count(*)  from member_attentions where member_attentions.to_member_id = member_users.id";
                    $list = $list->select(['*', DB::raw("($sql) as attention_count")]);
//                    $list = $list->withCount(['toTalks as talks' => function ($query) {
//                        $query->where('status', 0);
//                        $query->where('duration', '>', 0);
//                        $query->select(DB::raw('count(*)'));
//                    }]);
//                    $list = $list->orderBy('talks', 'DESC');
//                    $list = $list->orderBy('vip_integral', 'DESC');
                    $list = $list->orderBy('attention_count', 'DESC');

                }

                //新人
                if ($request->type == 2) {
//                    $sql = MemberWalletRecord::whereIn('type',[11,12,13,14,15,16,17,18,19,20,50,51,52,31,32,33,34,35,36,37,38,39])->whereBetween('created_at',[date("Y-m-d 00:00:00")])->;
                    //本月
                    $beginThismonth = date("Y-m-d H:i:s", mktime(0, 0, 0, date('m'), 1, date('Y')));
                    $endThismonth = date("Y-m-d H:i:s", mktime(23, 59, 59, date('m'), date('t'), date('Y')));

                    $sql = "select sum(money)  from member_wallet_records where member_wallet_records.member_id = member_users.id and `type` IN (14,15,16,17,18,19,20,50,51,52/*31,32,33,34,35,36,37,38,39*/) and created_at between '{$beginThismonth}' and '{$endThismonth}'";
                    $list = $list->select(['*', DB::raw("($sql) as total_revenue")])->orderBy('total_revenue', 'DESC');
                    $list = $list->orderBy('created_at', 'DESC');
                }
            }
            $list = $list->paginate(10);

            foreach ($list as &$v) {
//                $totalRevenue = MemberWalletRecord::where(['member_id' => $v['id']])->whereIn('type',[11,12,13,14,15,16,17,18,19,20,50,51,52,31,32,33,34,35,36,37,38,39])->whereBetween('created_at',[date("Y-m-d 00:00:00")])->sum('money');
                $totalRevenue = $this->number($v['total_revenue']);
                $v['total_revenue'] = $totalRevenue ? $totalRevenue : 0;
                $v['vip_integral_text'] = $this->number($v['vip_integral']);
                $v['charm_integral_text'] = $this->number($v['charm_integral']);
            }

            return $this->succeed(MemberUserListResource::collection($list), '获取排行榜成功');
        } catch (\Exception $ex) {
            return $this->exception($ex, '获取会员列表异常');
        }
    }


    /**
     * 字符串转换
     * @param $num
     * @return string
     */
    function number($num)
    {
        if ($num < 1000) {
            return $num;
        } else if ($num >= 1000 && $num < 10000) {
            return round($num / 1000, 1) . 'k';
        } else if ($num >= 10000) {
            return round($num / 10000, 2) . 'w';
        }
    }


    /**
     * 其它会员信息查看
     */
    public function detail(Request $request, MemberUserRepository $memberUserRepository)
    {
        try {
            $member = $request->user('ApiMember');
            $id = $member->id;
            if ($request->filled('id')) {
                $id = $request->id;
            }
            $toMember = $memberUserRepository->
            with(['rate', 'vip', 'charm', 'parameter',
                'toGifts' => function ($query) {
                    $query->take(3);
                },
//                'formVisitors', 'toVisitors','formGifts',
                'resources' => function ($query) use ($request, $member) {
                    $query->where('status', 0);
                    $query->whereHas('file', function ($query) {
                        $query->whereNotNull('url');
                    });
                    $query->withCount(['views as is_lock' => function ($query) use ($request, $member) {
                        $query->where('member_id', $member->id);
                        $query->where('end_time', '>', Carbon::now());
                        $query->select(DB::raw('count(*)'));
                    }]);
                    $query->take(8);
                },
                'covers' => function ($query) use ($member) {
                    $query->where('status', 0);
                    $query->whereHas('file', function ($query) {
                        $query->whereNotNull('url');
                    });
                    $query->orderBy('created_at', 'DESC');
                    $query->take(8);
                }])
                ->withCount([
//                    'formVisitors',
                    'toVisitors',
//                    'formGifts',
                    'toGifts',
//                    'formAttentions',
                    'toAttentions',
                    'covers' => function ($query) use ($member) {
                        $query->where('status', 0);
                    },
                    'resources' => function ($query) use ($member) {
                        $query->where('status', 0);
                    },
                    'toAttentions as is_attention' => function ($query) use ($member) {
                        $query->where('no', $member->no);
                    },
                    'formBlacklists as is_blacklist' => function ($query) use ($member) {
//                        $query->where('member_id', $member->id);
                    },])
                ->findWhere(function ($query) use ($request, $id) {
                    $query->orWhere('id', $id);
                    $query->orWhere('no', $id);
                });
            if (!isset($toMember)) {
                return $this->validation('所查看的会员不存在');
            }
            //记录访问日志
            if ($member->id != $toMember->id) {
                $visitor = MemberVisitor::firstOrNew(['member_id' => $member->id, 'to_member_id' => $toMember->id]);
                $visitor->status = 0;
                $visitor->number++;
                $visitor->save();
            }

            if ($member->id == $id) {
                $toMember['is_wechat_payment'] = 1;
            } else {
                $payment = DB::table('wechat_payments')->where(['member_id' => $member->id, 'to_member_id' => $id])->value('id');
                $toMember['is_wechat_payment'] = isset($payment) ? 1 : 0;
            }
            $toMember['extend_weixin'] = 1;
            $wechatPay =  '';
            if ($toMember->parameter->wechat_view) {
                $wechatPay = PlatformFacade::config('is_wechat_pay');
            }
            if ($toMember->parameter->wechat_view || $wechatPay != 0){
                $toMember['extend_weixin'] = '';
            }

            return $this->succeed(new MemberUserDetailResource($toMember), '获取其它会员信息查看成功');
        } catch (\Exception $ex) {
            return $this->exception($ex, '其它会员信息查看异常');
        }
    }

    /**
     * 在线客服
     * @param Request $request
     * @return array
     */
    public function service(Request $request, MemberUserRepository $memberUserRepository)
    {
        try {
            $member = $request->user('ApiMember');

            $service = $memberUserRepository
                ->findWhere(function ($query) use ($request, $member) {
                    if ($member->service_id > 0) {
                        $query->where('id', $member->service_id);
                    }
                    $query->where('type', 2);
                    $query->orderBy('online_status');
                });
            return $this->succeed(new MemberUserDetailResource($service), '获取其它会员信息查看成功');
        } catch (\Exception $ex) {
            return $this->exception($ex, '其它会员信息查看异常');
        }
    }

    /**
     * 会员信息同步
     */
    public function sync(Request $request, MemberUserRepository $memberUserRepository)
    {
        try {
            $member = $request->user('ApiMember');

            //经度
            if ($request->filled('lat')) {
                $member->lat = $request->lat;
            }
            //纬度
            if ($request->filled('lng')) {
                $member->lng = $request->lng;
            }
            //地址
            if ($request->filled('address')) {
                $member->address = $request->address;
            }
            //所在省
            if ($request->filled('province')) {
                $member->province = $request->province;
            }
            //所在市城市
            if ($request->filled('city')) {
                $member->city = $request->city;
            }
            //所在县区
            if ($request->filled('district')) {
                $member->district = $request->district;
            }

            //推送token
            if ($request->filled('push_token')) {
                $member->push_token = $request->push_token;
            }
            //APP平台
            if ($request->filled('app_platform')) {
                $member->app_platform = $request->app_platform;
            }
            //APP版本号
            if ($request->filled('app_version')) {
                $member->app_version = $request->app_version;
            }
            $member->save();
            return $this->succeed(new MemberUserMyResource($member), '会员信息同步成功');
        } catch (\Exception $ex) {
            return $this->exception($ex, '会员信息同步异常');
        }
    }

    /**
     * 会员资料信息更新
     */
    public function update(Request $request)
    {
        try {
            $member = $request->user('ApiMember');
            //用户名
            if ($request->filled('username')) {
                $member->username = $request->username;
            }
            //手机号绑定
            if ($request->filled('mobile')) {
                if (!$request->filled('code')) {
                    return $this->validation('验证码不能为空');
                }
                $result = RiskFacade::verifyCode($request->mobile, $request->code);
                if (!$result) {
                    return $this->validation('验证码效验错误');
                }
                if (MemberUser::where('mobile', $request->mobile)->exists()) {
                    return $this->validation('失败，手机号已有绑定');
                }
                $member->mobile = $request->mobile;
            }
            //密码
            if ($request->filled('password')) {
                $member->password = bcrypt($request->password);
            }
            //资金密码修改
            if ($request->filled('capital_password')) {
                if (empty($member->mobile)) {
                    return $this->validation('手机号未绑定');
                }
                if (!$request->filled('code')) {
                    return $this->validation('验证码不能为空');
                }
                $result = RiskFacade::verifyCode($member->mobile, $request->code);
                if (!$result) {
                    return $this->validation('验证码效验错误');
                }
                $member->capital_password = md5($request->capital_password);
            }
            //昵称
            if ($request->filled('nick_name')) {
                if (strlen($request->nick_name) > 20) {
                    return $this->validation('昵称不能超出20个字符');
                }
                $member->nick_name = $request->nick_name;
            }
            //头像图片
            $files = $request->allFiles();
//            $this->logs('$files', $files);
            if ($request->filled('head_pic')) {

                $headPic = $request->file('head_pic');
                if (!isset($headPic)) {
                    return $this->validation('上传文件不存在');
                }
                if (!$headPic->isValid()) {
                    return $this->validation('头像文件异常');
                }
                $file = OssFacade::putFile($headPic, $member->no);
                if (!$file['status']) {
                    return $this->failure(1, '上传头像失败', $file);
                }
                #todo 图片安全检查
                $member->head_pic = $file['data'];

            }
            //封面图片
            if ($request->filled('cover')) {
                $file = OssFacade::putFile($request->cover, $member->no);
                if (!$file['status']) {
                    return $this->failure(1, '上传头像失败', $file);
                }
                #todo 图片安全检查
                $member->cover = $file['data'];
            }
            //格言
            if ($request->filled('aphorism')) {
                #todo 文本安全检查
                $member->aphorism = $request->aphorism;
            }
            //签名
            if ($request->filled('signature')) {
                #todo 文本安全检查
                $member->signature = $request->signature;
            }

            //性别
            if ($request->filled('sex') && $member->sex == 9) {
                $member->sex = $request->sex;
            }
            //生日
            if ($request->filled('birthday')) {
                $member->birthday = $request->birthday;
                if ((new Carbon)->diffInYears($request->birthday) < 18) {
                    return $this->validation('不接收未成年人使用');
                }
                $extend = $member->extend;
                //星座
                if (isset($extend)) {
//                    $extend->constellation = CommonFacade::getConstellation($member->birthday);
//                  $this->logs('星座',$extend->constellation);
//                    $extend->save();
                }
            }
            //常驻城市
            if ($request->filled('resident')) {
                $member->resident = $request->resident;
            }


            $member->save();
            $member->refresh();
            return $this->succeed(new MemberUserMyResource($member), '会员信息更新成功');
        } catch (\Exception $ex) {
            return $this->exception($ex, '会员信息更新异常');
        }
    }


    /**
     * 扩展资料信息更新
     */
    public function extend(Request $request, MemberUserExtendRepository $repository)
    {
        try {
            $member = $request->user('ApiMember');
            $extend = $member->extend;
            //微信
            if ($request->filled('weixin')) {
                $extend->weixin = $request->weixin;
            }
            //QQ号
            if ($request->filled('qq')) {
                $extend->qq = $request->qq;
            }
            //生日
            if ($request->filled('birthday')) {
                $extend->birthday = $request->birthday;
            }
            //兴趣爱好
            if ($request->filled('hobbies')) {
                $extend->hobbies = $request->hobbies;
            }
            //职业
            if ($request->filled('profession')) {
                $extend->profession = $request->profession;
            }
            //身高
            if ($request->filled('height')) {
                $extend->height = $request->height;
            }
            //体重
            if ($request->filled('weight')) {
                $extend->weight = $request->weight;
            }
            //星座
            if ($request->filled('constellation')) {
                $extend->constellation = $request->constellation;
            }
            //血型
            if ($request->filled('blood')) {
                $extend->blood = $request->blood;
            }
            //情感
            if ($request->filled('emotion')) {
                $extend->emotion = $request->emotion;
            }
            //收入
            if ($request->filled('income')) {
                $extend->income = $request->income;
            }
            $extend->save();
            return $this->succeed(new MemberUserExtendResource($extend), '会员信息更新成功');
        } catch (\Exception $ex) {
            return $this->exception($ex, '会员信息更新异常');
        }
    }


    /**
     * 分享
     */
    public function share(Request $request)
    {
        try {
            $member = $request->user('ApiMember');
            $data['title'] = $member->name . '邀请你注册！';
            $data['describe'] = '90%美女的交友平台！';
            $data['url'] = url('/share/' . $member->no);
            $data['url_img'] = $member->share_image ?:'';
            $data['pic'] = url('/images/default/share.png');
            return $this->succeed($data, '会员信息更新成功');
        } catch (\Exception $ex) {
            return $this->exception($ex, '会员信息更新异常');
        }
    }


    /**
     * 查看微信
     */
    public function getWechat(Request $request)
    {
        try {
            $member = $request->user('ApiMember');
            if (empty($request->to_member_id)) {
                return $this->validation('被查看用户不能为空');
            }
            //平台是否开启这个功能
            $wechatPay = PlatformFacade::config('is_wechat_pay');
            if ($wechatPay == 0) {
                if ($member->extend->weixin_verify !== 0 && $member->parameter->wechat_view !== 0) {
                    return $this->succeed([], '主播关闭了微信查看或者是未认证');
                }

//                //是否已查看
//                $wechatPayment = DB::table('wechat_payments')->where(['member_id' => $member->id, 'to_member_id' => $request->to_member_id])->value('id');
//                if ($wechatPayment) {
//                    DB::commit();
//                    return $this->succeed($member->extend->weixin, '已查看');
//                }

                $wechatPay = MemberUserRate::where(['member_id' => $request->to_member_id])->value('wechat_pay_money');//金额
                if ($wechatPay > 0) {
                    $wechatPayMoney = $wechatPay;
                } else {
                    $wechatPayMoney = PlatformFacade::config('wechat_pay_money');
                }

                $gold = $member->gold->balance;
                if ($gold < $wechatPayMoney) {
                    $user = MemberUser::find($member->id);
                    if ($user->push_token) {
                        $body = [
                            'type' => 'popup'
                        ];
                        PushFacade::pushToken($user->push_token, $user->app_platform, '能量不足，请充值！', json_encode($body), $type = 'MESSAGE', ['type' => 'member', 'id' => $user->id, 'no' => $user->no, 'nickname' => $user->nick_name]);
                    }

                    return $this->failure(1, '能量不足，请充值');
                }

                $wechat = new WechatPayment();
                $wechat->member_id = $member->id;
                $wechat->to_member_id = $request->to_member_id;
                $wechat->money = $wechatPayMoney;
                $wechat->save();


                $memberWallet = MemberWalletGold::where('member_id', $member->id)->lockForUpdate()->first();
                $memberWallet->usable = $memberWallet->usable - $wechatPayMoney;
                $memberWallet->balance = $memberWallet->balance - $wechatPayMoney;
                $memberWallet->save();
                $memberWallet->records()->save(new MemberWalletRecord(['type' => 53, 'member_id' => $member->id, 'money' => -$wechatPayMoney, 'surplus' => $memberWallet->balance]));
                $wechatPlatformShare = MemberUserRate::where('member_id', $request->to_member_id)->value("wechat_platform_share");
                $p = bcdiv(bcmul($wechatPayMoney, $wechatPlatformShare, 2), env('PLATFORM_EXCHANGE_RATE'), 2);
                if ($wechatPlatformShare > 0) {
                    $memberWallet->records()->save(new MemberWalletRecord(['type' => 53, 'member_id' => 0, 'money' => $p, 'surplus' => 0]));
                }

                $memberWallet = MemberWalletCash::where('member_id', $request->to_member_id)->lockForUpdate()->first();
                //主播收入金币
                $wechatPayMoney = bcsub(bcdiv($wechatPayMoney, env('PLATFORM_EXCHANGE_RATE'), 2), $p, 2);
                $memberWallet->usable = $memberWallet->usable + $wechatPayMoney;
//                $memberWallet->balance = $memberWallet->balance + $wechatPayMoney;
                $memberWallet->save();
                $memberWallet->records()->save(new MemberWalletRecord(['type' => 52, 'member_id' => $request->to_member_id, 'money' => +$wechatPayMoney, 'surplus' => $memberWallet->balance]));

                DB::commit();
                return $this->succeed('', '微信查看成功');
            }
            DB::commit();
            return $this->failure(1, '微信查看关闭');
        } catch (\Exception $ex) {
            return $this->exception($ex, '微信查看异常');
        }
    }


    /**
     * 获取首页类型
     * @param Request $request
     * @param MemberUserRepository $memberUserRepository
     * @return array
     */
    public function getTypes(Request $request)
    {
        try {
            $member = $request->user('ApiMember');
            if ($member['sex'] == 0 && $request->type != 0) {
                return $this->succeed(PlatformTypeResource::collection([]), '类型返回成功');
            }
            if ($member['is_selfie'] != 0 && $request->type != 0) {
                return $this->succeed(PlatformTypeResource::collection([]), '类型返回成功');
            }
            $type = $request->type ?? 0;
            $lists = PlatformType::where(['status' => 0, 'type' => $type])->orderBy('sort', 'asc')->paginate();
            return $this->succeed(PlatformTypeResource::collection($lists), '类型返回成功');
        } catch (\Exception $e) {
            return $this->exception($e, '类型返回异常，请联系管理员！');
        }
    }

    /**
     * 在线客服发送消息
     */
    public function getSendMessage(
        Request $request,
        MemberUserRepository $memberUserRepository
    )
    {
        try {
            if (!$request->filled('member_id')) {
                return $this->validation('请传入member_id!');
            }

            //发送方
            $fromAccount = $request->member_id;
            $member = $request->user('ApiMember');
            $id = MemberUser::where('no', $fromAccount)->value('id');

            $createdAt = DealMessage::where(['member_id' => $member->id, 'to_member_id' => $id])->value('created_at');
            //半小时内容不发送新内容
            if ($createdAt && strtotime($createdAt) > 60 * 30) {
                return $this->succeed('', '获取在线客服自定义消息成功');
            }

            //消息内容
            $sendMessage = PlatformSendMessage::where([
                'type' => 1,
                'status' => 0,
            ])->get();
            if (sizeof($sendMessage) > 0) {
                $sendSubscript = array_rand($sendMessage->toArray());
                $content = $sendMessage[$sendSubscript]['content'];
            } else {
                $content = '您好！，很高兴为你服务';
            }

            $data = ['command' => 'talk.greetings', 'data' => ['greetings' => $content]];
            $ret = ImFacade::addRoom($fromAccount, $member->no, $data, 1);
            if ($ret['ErrorCode'] == 0) {
                //生成聊天记录
                $message = new DealMessage();
                $message->member_id = $member->id;
                $message->to_member_id = $id;
                $message->price = 0;
                $message->platform_way = getenv('PLATFORM_WAY', 1);
                $message->platform_rate = 0;
                $message->content = $content;
                $message->save();
                return $this->succeed($data, '获取在线客服自定义消息成功');
            } else {
                return $this->failure(1, '获取在线客服自定义消息失败');
            }
        } catch (\Exception $ex) {
            return $this->exception($ex, '获取在线客服自定义消息异常');
        }
    }


    /**
     * 线下支付
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function offlinePayment(){
        $pay = PlatformPaymentChannel::where(['type'=>1,'status'=>0])->get();
        return view('system.platform.text.pay')->with('data', $pay);
    }
}

