<?php

namespace App\Http\Resources;


use App\Models\PlatformCharm;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Yansongda\Pay\Log;

/**
 * 通用会员详情信息
 * Class MemberUserResource
 * @package App\Http\Resources
 */
class MemberUserDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        #todo 计费优先级处理
        $data = [
            'id' => $this->id, //ID
            'no' => $this->no, //会员编号
            'type' => $this->type, //会员类型0普通用户1陪聊2客服
            'head_pic' => $this->fill_head_pic,//头像
            'cover' => $this->fill_cover,//封面图片
            'nick_name' => $this->nick_name,//昵称
            'mobile' => $this->mobile,//手机号
            'sex' => $this->sex,//性别 0男 1女 9 未知
            'birthday' => $this->birthday,//生日
            'age' => $this->age,//年龄
            'province'  => isset($this->province) ?$this->province: '未知',
            'city'      => isset($this->city) ?$this->city: '',
            //所在市城市
            'district'  => isset($this->district)?$this->district: '',
            //所在县区
            'resident'  => isset($this->resident )?$this->resident: '',
            'aphorism' => $this->aphorism,//格言
            'signature' => $this->signature,//签名
            'is_middleman' => $this->is_middleman,//经济人
            'is_selfie' => $this->is_selfie,//自拍认证
            'is_real' => $this->is_real,//实名状态 0已认证1未认证
            'im_status' => $this->online_status,//在线状态 0在线1离线2休眠9未知
            'live_status' => $this->live_status,//忙碌状态 0空闲 1忙碌
            'online_status' => $this->online_status,//在线状态:0在线1离线2勿扰9未知
            'dispose_online' => $this->dispose_online,//处理后在线状态:0在线1离线2勿扰9未知
            'is_attention' => $this->is_attention,//是否关注 #todo 是否关注处理
            'is_blacklist' => $this->is_blacklist,//是否黑名单 #todo 是否黑名单

            'vip_integral' => $this->vip_integral,//VIP积分
            'vip_grade' => $this->vip_grade,//VIP等级
            'charm_integral' => $this->charm_integral,//魅力积分
            'charm_grade' => $this->charm_grade,//魅力等级

            'vip' => new PlatformVipResource($this->vip),//VIP信息
            'charm' => new PlatformCharmResource($this->charm),//魅力
            'extend' => new MemberUserExtendResource($this->extend),//扩展信息
            'parameter' => new MemberUserParameterResource($this->parameter),//会员参数
            'rate' => new MemberUserRateResource($this->rate),//会员费率

            'covers' => MemberResourceCoverResource::collection($this->covers),//封面列表
            'covers_count' => $this->covers_count,//封面数
            'resources' => MemberResourceResource::collection($this->resources),//资源列表
            'resources_count' => $this->resources_count,//资源数
            'tags' => PlatformTagResource::collection($this->tags),//所属标签

            //礼物记录
            'to_gifts' => MemberGiftResource::collection($this->toGifts),//接收礼物清单
            'to_gifts_count' => $this->to_gifts_count,//接收的礼物
//            'form_gifts' => MemberGiftResource::collection($this->formGifts),//发送礼物清单
//            'form_gifts_count' => $this->form_gifts_count,//发送的礼物
            //关注记录
//            'form_attentions' => MemberAttentionResource::collection($this->formAttentions),//我关注的
//            'form_attentions_count' => $this->form_attentions_count,//我关注的
            'to_attentions' => MemberUserListResource::collection($this->toAttentions),//关注我的
            'to_attentions_count' => $this->to_attentions_count,//关注我的

            //访问记录
//            'form_visitors' => MemberUserVisitorResource::collection($this->formVisitors),//我访问的记录
//            'form_visitors_count' => $this->form_visitors_count,//我访问的记录
//            'to_visitors' => MemberUserVisitorResource::collection($this->toVisitors),//访问我的记录
            'to_visitors_count' => $this->to_visitors_count,//访问我记录
            'is_wechat_payment' => $this->is_wechat_payment,//是否查看微信
            'created_at' => Carbon::parse($this->created_at)->toDateTimeString(),//注册时间
        ];

        $member = $request->user('ApiMember');
        if ($this->type == 1 && !empty($member->resident)) {
            $data['resident'] = !empty($member->resident)?$member->resident:'';
        }
        if ($this->extend_weixin == '') {
            $data['extend']['weixin'] = '';
        }

        $data['resident'] = $data['resident'] == 'null'?'未知':$data['resident'];

        return $data;
    }

}
