<?php

namespace App\Models;

use App\Facades\CommonFacade;
use App\Facades\DealFacade;
use App\Facades\ImFacade;
use App\Facades\MapFacade;
use App\Facades\MemberFacade;
use App\Facades\PlatformFacade;
use App\Utils\Helper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


/*
 * 会员信息
 * */

class MemberUser extends Authenticatable
{
    use SoftDeletes, Notifiable;
    protected $table = 'member_users';
    protected $guarded = [];
    protected $hidden = [
        'password', 'capital_password', 'security_code',
    ];

    protected $appends = ['age', 'dispose_online', 'fill_head_pic', 'fill_cover', 'distance'];


    protected static function boot()
    {
        parent::boot();

        static::retrieved(function ($model) {
        });


        static::creating(function ($model) {

            //随机昵称
            if (empty($model->nick_name)) {
                $model->nick_name = CommonFacade::randomNickName();
            }

            if (empty($model->no)) {
                $model->no = CommonFacade::number('MemberUser', 10000000);
            }


            if (empty($model->invite_code)) {
                $model->invite_code = CommonFacade::number('MemberUserInvite', 888888);;
            }
            if (empty($model->api_token)) {
                $model->api_token = Helper::rand_str(64);
            }

            if (empty($model->im_token)) {
                //IM签名生成
                $model->im_token = ImFacade::userSign($model->no);
            }
            if (isset($model->age)) {
                $model->birthday = Carbon::now()->addYears(-$model->age)->toDateString();
                unset($model->age);
            }
            //经济人设置
            if (isset($model->parent_id)) {
                $parent = MemberUser::find($model->parent_id);
                if (isset($parent)) {
                    if ($parent->is_middleman == 0) {
                        $model->agent_id = $parent->id;
                    } else {
                        $model->agent_id = $parent->agent_id;
                    }
                } else {
                    unset($model->parent_id);
                }
            }


        });
        static::created(function ($model) {
            //生成钱包账户
            MemberWalletGold::firstOrCreate(['member_id' => $model->id]);
            MemberWalletCash::firstOrCreate(['member_id' => $model->id]);
            //会员费率
            MemberUserRate::firstOrCreate(['member_id' => $model->id]);
            //会员参数
            MemberUserParameter::firstOrCreate(['member_id' => $model->id]);
            //IM账户导入
            $im = ImFacade::userImport($model->no, $model->nick_name, $model->fill_head_pic);
            //IM资料更新
            $data = [];
            array_push($data, ["Tag" => 'Tag_Profile_Custom_Uid', "Value" => (string)$model->id]);
            array_push($data, ["Tag" => 'Tag_Profile_Custom_No', "Value" => $model->no]);
            array_push($data, ["Tag" => 'Tag_Profile_Custom_Type', "Value" => (string)$model->type]);
            ImFacade::userUpdate($model->no, $data);
        });
        static::updating(function ($model) {
           $info = ImFacade::userSetInfo($model->no, 'Tag_Profile_Custom_Type', (string)$model->type);

            //性别修改
            if ($model->isDirty('sex')) {
//                $model->api_token = Helper::rand_str(64);
            }
            //昵称待审
            if ($model->isDirty('nick_name') && PlatformFacade::config('nickname_audit') == 0) {
                MemberVerification::where('status', 9)->where('info_type', 0)->where('member_id', $model->id)->update(['status' => 1, 'audit_reason' => '系统取消']);
                $verification = MemberVerification::firstOrNew(['info_type' => 0, 'member_id' => $model->id, 'status' => 9, 'old_data' => $model->getOriginal('nick_name')]);
                $verification->new_data = $model->nick_name;
                $verification->save();
                unset($model->nick_name);
            }
            //签名
            if ($model->isDirty('signature') && PlatformFacade::config('signature_audit') == 0) {
                MemberVerification::where('status', 9)->where('info_type', 1)->where('member_id', $model->id)->update(['status' => 1, 'audit_reason' => '系统取消']);
                $verification = MemberVerification::firstOrNew(['info_type' => 1, 'member_id' => $model->id, 'status' => 9, 'old_data' => $model->getOriginal('signature')]);
                $verification->new_data = $model->signature;
                $verification->save();
                unset($model->signature);
            }
            //格言
            if ($model->isDirty('aphorism') && PlatformFacade::config('aphorism_audit') == 0) {
                MemberVerification::where('status', 9)->where('info_type', 2)->where('member_id', $model->id)->update(['status' => 1, 'audit_reason' => '系统取消']);
                $verification = MemberVerification::firstOrNew(['info_type' => 2, 'member_id' => $model->id, 'status' => 9, 'old_data' => $model->getOriginal('aphorism')]);
                $verification->new_data = $model->aphorism;
                $verification->save();
                unset($model->aphorism);
            }

        });

        static::saving(function ($model) {
            $info = ImFacade::userSetInfo($model->no, 'Tag_Profile_Custom_Type', (string)$model->type);

            if (!empty($model->app_platform)) {
                $model->app_platform = strtolower($model->app_platform);
            }
            if ($model->type != 1 && $model->isDirty('im_status')) {
                //上线
                if ($model->im_status == 0) {
                    $model->online_status = 0;
                    $model->live_status = 0; //在通话过程中将状态从忙碌改为了空闲
                }
                //离线
                if ($model->im_status == 1) {
                    $model->online_status = 1;
                    $model->live_status = 1;
                }
                //休眠
                if ($model->im_status == 2) {
                    $model->online_status = 1;
                    $model->live_status = 0;
                }
            }
            if ($model->isDirty('nick_name') && PlatformFacade::config('nickname_audit') == 0) {
                if (!$model->nick_name = PlatformFacade::keyword($model->nick_name)) {
                    return false;
                }
            }
            if ($model->isDirty('username') && PlatformFacade::config('nickname_audit') == 0) {
                if (!$model->username = PlatformFacade::keyword($model->username)) {
                    return false;
                }
            }
            //格言审核
            if ($model->isDirty('aphorism') && PlatformFacade::config('aphorism_audit') == 0) {
                if (!$model->aphorism = PlatformFacade::keyword($model->aphorism)) {
                    return false;
                }
            }
            if ($model->isDirty('signature') && PlatformFacade::config('signature_audit') == 0) {
                if (!$model->signature = PlatformFacade::keyword($model->signature)) {
                    return false;
                }
            }
            //常住城市
            if (empty($model->resident) && !empty($model->city)) {
                $model->resident = $model->city;
            }
        });


        static::saved(function ($model) {
            $info = ImFacade::userSetInfo($model->no, 'Tag_Profile_Custom_Type', (string)$model->type);
            //IM在线状态
            if ($model->isDirty('online_status')) {
                //在线时长记录
                if ($model->online_status == 0) {
                    $model->logins()->save(new MemberLogin(['login_ip' => CommonFacade::getIP() ?? '']));
                }
                if (in_array($model->online_status, [1, 2])) {
                    foreach ($model->logins()->whereNull('logout_time')->get() as $item) {
                        $item->logout_time = Carbon::now()->toDateTimeString();
                        $item->save();
                    };

                    //下线处理异常订单
                    DB::connection()->enableQueryLog();
                    $talks = DealTalk::whereNotIN('status', [0])->where('dialing_id', $model->id)->orWhere('called_id', $model->id)->get();
                    $talks1 = DealTalk::whereNotIN('status', [0])->where(function ($query) use ($model){
                        $query->where('dialing_id', $model->id)->orWhere('called_id', $model->id);
                    })->get();
                    foreach ($talks as $talk) {
                        //订单异常结束
                        DealFacade::talkHangup($talk->room_id, 4);
                    }
                }
            }

            //推送账户绑定
            if ($model->isDirty('push_token')) {
//                PushFacade::bindAccount($model->app_platform, [['token' => $model->push_token, 'account_list' => [['account' => $model->no, 'account_type' => 1]]]]);
            }
            if ($model->isDirty('nick_name')) {
                ImFacade::userSetInfo($model->no, 'Tag_Profile_IM_Nick', $model->nick_name);
            }
            if ($model->isDirty('head_pic')) {
                ImFacade::userSetInfo($model->no, 'Tag_Profile_IM_Image', $model->fill_head_pic);
            }
            if ($model->isDirty('sex')) {
                ImFacade::userSetInfo($model->no, 'Tag_Profile_IM_Gender', $model->sex);
            }
            if ($model->isDirty('resident')) {
                ImFacade::userSetInfo($model->no, 'Tag_Profile_IM_Location', $model->resident);
            }
            if ($model->isDirty('signature')) {
                ImFacade::userSetInfo($model->no, 'Tag_Profile_IM_SelfSignature', $model->signature);
            }
            if ($model->isDirty('type')) {
                $info = ImFacade::userSetInfo($model->no, 'Tag_Profile_Custom_Type', (string)$model->type);

            }

            if ($model->isDirty('charm_grade')) {
                MemberFacade::charmIntegralSync($model->id);
            }
        });

//        static::deleted(function ($model) {
//            $model->realname()->forceDelete();
//            $model->selfie()->forceDelete();
//            $model->rate()->forceDelete();
//            $model->parameter()->forceDelete();
//            $model->logins()->forceDelete();
//            $model->gold()->forceDelete();
//            $model->cash()->forceDelete();
//            $model->recharges()->forceDelete();
//            $model->recharges()->forceDelete();
//
//        });

    }

    /**
     * 年龄
     * @return string
     */
    public function getAgeAttribute()
    {
        #todo 年龄优化
        if (empty($this->birthday)) {
            return mt_rand(20,35);
        }
        return (new Carbon)->diffInYears($this->birthday, true);
    }

    /**
     * 距离
     * @return string
     */
    public function getDistanceAttribute()
    {
        if (empty($this->lng) || empty($this->lat)) {
            return -1;
        }
        $member = request()->user('ApiMember');
        if (!isset($member)) {
            return -2;
        }
        $longitude1 = $member->lng;
        $latitude1 = $member->lat;

        return MapFacade::getDistance($longitude1, $latitude1, $this->lng, $this->lat);
    }

    /**
     * 在线状态0空闲1离线2忙碌3勿扰
     * @return string
     */
    public function getDisposeOnlineAttribute()
    {
        if ($this->online_status == 1) {
            return 1;
        }
        if (isset($this->parameter) && $this->parameter->is_disturb == 1) {
            return 3;
        }
        if ($this->live_status == 1) {
            return 2;
        }
        return 0;
    }

    /**
     * 完整头像地址
     * @return string
     */
    public function getFillHeadPicAttribute()
    {
        if (empty($this->head_pic)) {
            $pic = config('user.head_pic');
            return strpos($pic, 'http') !== false
                ? $pic
                : url($pic);
        }
        if (strpos($this->head_pic, 'http') === 0) {
            return $this->head_pic;
        }
        return $this->head_pic;


    }

    /**
     * 完整封面地址
     * @return string
     */
    public function getFillCoverAttribute()
    {
        if (empty($this->cover)) {
            if (empty($this->head_pic)) {
                return url('/images/default/cover.png');
            }
            return $this->getFillHeadPicAttribute();
        }
        if (strpos($this->cover, 'http') === 0) {
            return $this->cover;
        }
        return $this->cover;


    }


    /**
     * 专属客服
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function service()
    {
        return $this->belongsTo(MemberUser::class, 'service_id', 'id');
    }


    /**
     * 代理商
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function agent()
    {
        return $this->belongsTo(MemberUser::class, 'agent_id', 'id');
    }

    /**
     * 代理商下属会员
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function agentChildrens()
    {
        return $this->hasMany(MemberUser::class, 'agent_id', 'id');
    }
    /**
     * 关联会员标签
     */
    public function userTypes()
    {
        return $this->hasMany(MemberUserType::class, 'member_id', 'id');
    }
    /**
     * 推荐人
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function parent()
    {
        return $this->belongsTo(MemberUser::class, 'parent_id', 'id');
    }

    /**
     * 邀请会员
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childrens()
    {
        return $this->hasMany(MemberUser::class, 'parent_id', 'id');
    }

    /**
     * 金币账户
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function gold()
    {
        return $this->hasOne(MemberWalletGold::class, 'member_id', 'id');
    }

    /**
     * 现金账户
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function cash()
    {
        return $this->hasOne(MemberWalletCash::class, 'member_id', 'id');
    }

    /**
     * 充值记录
     */
    public function recharges()
    {
        return $this->hasMany(MemberWalletRecharge::class, 'member_id', 'id');
    }

    /**
     * 实名
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function realname()
    {
        return $this->hasOne(MemberUserRealname::class, 'member_id', 'id');
    }

    /**
     * 自拍认证
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function selfie()
    {
        return $this->hasOne(MemberUserSelfie::class, 'member_id', 'id');
    }

    /**
     * 参数
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function parameter()
    {
        return $this->hasOne(MemberUserParameter::class, 'member_id', 'id')->withDefault(['disturb' => 0, 'location' => 0]);
    }


    /**
     * 费率
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function rate()
    {
        return $this->hasOne(MemberUserRate::class, 'member_id', 'id');
    }


    /**
     * 查看微信
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function wechat()
    {
        return $this->hasOne(WechatPayment::class, 'member_id', 'id');
    }


    /**
     * 扩展信息
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function extend()
    {
        return $this->hasOne(MemberUserExtend::class, 'member_id', 'id')->withDefault();
    }

    /**
     * VIP信息
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function vip()
    {
        return $this->belongsTo(PlatformVip::class, 'vip_grade', 'grade');
    }

    /**
     * 魅力
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function charm()
    {
        return $this->belongsTo(PlatformCharm::class, 'charm_grade', 'grade');
    }

    /**
     * 我访问会员
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function formVisitors()
    {
        return $this->hasMany(MemberVisitor::class, 'member_id', 'id');
    }

    /**
     * 访问我的会员
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function toVisitors()
    {
        return $this->hasMany(MemberVisitor::class, 'to_member_id', 'id');
    }

    /**
     * 发送的礼物
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function formGifts()
    {
        return $this->hasMany(DealGift::class, 'member_id', 'id');
    }

    /**
     * 接收的礼物
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function toGifts()
    {
        return $this->hasMany(DealGift::class, 'to_member_id', 'id');
    }

    /**
     * 资源信息
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function resources()
    {
        return $this->hasMany(MemberResource::class, 'member_id', 'id')->whereIn('type', [0, 1]);
    }

    /**
     * 封面图片
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function covers()
    {
        return $this->hasMany(MemberResource::class, 'member_id', 'id')->whereIn('type', [2]);
    }

    /**
     * 会员标签
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(PlatformTag::class, 'member_tags', 'member_id', 'tag_id');
    }

    /**
     * 我的好友
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function formFriends()
    {
        return $this->hasMany(MemberFriend::class, 'member_id', 'id');
    }

    /**
     * 加为好友会员
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function toFriends()
    {
        return $this->hasMany(MemberFriend::class, 'to_member_id', 'id');
    }

    /**
     * 我关注的会员
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function formAttentions()
    {
        return $this->belongsToMany(MemberUser::class, 'member_attentions', 'member_id', 'to_member_id')->withPivot(['status', 'member_id', 'to_member_id']);
    }

    /**
     * 关注我的会员
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function toAttentions()
    {
        return $this->belongsToMany(MemberUser::class, 'member_attentions', 'to_member_id', 'member_id')->withPivot(['status', 'member_id', 'to_member_id', 'created_at']);

    }


    /**
     * 登录日志
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function logins()
    {
        return $this->hasMany(MemberLogin::class, 'member_id', 'id');
    }

    /**
     * 我的文件
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany(PlatformFile::class, 'relevance_id', 'id');
    }

    /**
     * 系统通知
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notices()
    {
        return $this->hasMany(PlatformNotice::class, 'member_id', 'id');
    }

    /**
     * 资料变更记录
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function verifications()
    {
        return $this->hasMany(MemberVerification::class, 'member_id', 'id');
    }

    /**
     * 我的黑名单
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function formBlacklists()
    {
        return $this->hasMany(MemberBlacklist::class, 'member_id', 'id');
    }

    /**
     * 拉我黑名单的记录
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function toBlacklists()
    {
        return $this->hasMany(MemberBlacklist::class, 'to_member_id', 'id');
    }


    /**
     * 我点赞的记录
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function formLikes()
    {
        return $this->hasMany(DealLike::class, 'member_id', 'id');
    }

    /**
     * 点赞我的记录
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function toLikes()
    {
        return $this->hasMany(DealLike::class, 'to_member_id', 'id');
    }


    /**
     * 现金充值记录
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dealCashs()
    {
        return $this->hasMany(DealCash::class, 'member_id', 'id');
    }

    /**
     * 现金提现记录
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dealWithdraws()
    {
        return $this->hasMany(DealWithdraw::class, 'member_id', 'id');
    }

    /**
     * 主叫订单
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function formTalks()
    {
        return $this->hasMany(DealTalk::class, 'dialing_id', 'id');
    }

    /**
     * 被叫订单
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function toTalks()
    {
        return $this->hasMany(DealTalk::class, 'called_id', 'id');
    }

    /**
     * 提现记录
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function withdraws()
    {
        return $this->hasMany(DealWithdraw::class, 'member_id', 'id');
    }

    /**
     * 资金流水
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function walletRecords()
    {
        return $this->hasMany(MemberWalletRecord::class, 'member_id', 'id');
    }

    /**
     * 拉黑
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function blacklist()
    {
        return $this->hasMany(MemberBlacklist::class, 'member_id', 'id');
    }

    /**
     * 动态
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dynamic()
    {
        return $this->hasMany(MemberDynamic::class, 'member_id', 'id');
    }

    /**
     * 资源
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resource()
    {
        return $this->hasMany(MemberResource::class, 'member_id', 'id');
    }


}
