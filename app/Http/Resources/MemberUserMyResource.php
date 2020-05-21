<?php

namespace App\Http\Resources;

use App\Models\MemberUserRate;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 我的用户详情
 * Class MemberUserMyResource
 * @package App\Http\Resources
 */
class MemberUserMyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id, //ID
            'no' => $this->no, //会员编号
            'type' => $this->type, //会员类型0普通用户1陪聊2客服
            'head_pic' => $this->fill_head_pic . '?x-oss-process=image/resize,m_fill,h_100,w_100',//头像
            'cover' => $this->fill_cover . '?x-oss-process=image/resize,m_fill,h_500,w_500',//封面图片
            'nick_name' => $this->nick_name,//昵称
            'mobile' => $this->mobile,//手机号
            'sex' => $this->sex,//性别 0男 1女 9 未知
            'birthday' => $this->birthday,//生日
            'age' => $this->age,//年龄
            'province'  => isset($this->province) ?$this->province: '未知',
            //所在省
            //            'city' => $this->city == null ? '未知' : $this->city,//所在市城市
            'city'      => isset($this->city) ?$this->city: '',
            //所在市城市
            'district'  => isset($this->district)?$this->district: '',
            //所在县区
            'resident'  => isset($this->resident )?$this->resident: '',

//            'resident' => $this->resident ?? '',//常驻城市
//            'province' => $this->province ?? '',//所在省
//            'city' => $this->city ?? '',//所在市城市
//            'district' => $this->district ?? '',//所在县区
            'aphorism' => $this->aphorism,//格言
            'signature' => $this->signature,//签名
            'is_middleman' => $this->is_middleman,//经济人
            'is_selfie' => $this->is_selfie,//自拍认证
            'is_real' => $this->is_real,//实名状态 0已认证1未认证
            'is_capital' => empty($this->capital_password) ? false : true,//资金密码是否有设置
            'im_status' => $this->online_status,//在线状态 0在线1离线2休眠9未知
            'live_status' => $this->live_status,//忙碌状态 0空闲 1忙碌
            'online_status' => $this->online_status,//在线状态:0在线1离线2勿扰9未知
            'dispose_online' => $this->dispose_online,//处理后在线状态:0在线1离线2勿扰9未知
            'api_token' => $this->api_token,
            'im_token' => $this->im_token,
            'vip_integral' => $this->vip_integral,//VIP积分
            'vip_grade' => $this->vip_grade,//VIP等级
            'charm_integral' => $this->charm_integral,//魅力积分
            'charm_grade' => $this->charm_grade,//魅力等级
            'vip' => new PlatformVipResource($this->vip),//VIP信息
            'charm' => new PlatformCharmResource($this->charm),//魅力信息
            'rate' => new MemberUserRateResource($this->rate),//会员费率
            'tags' => PlatformTagResource::collection($this->tags),//所属标签
            'extend' => new MemberUserExtendResource($this->extend),//扩展信息
            'resources' => MemberResourceResource::collection($this->resources->where('type', 2)),//资源列表
            'parameter' => new MemberUserParameterResource($this->parameter),//会员参数
            'wallet' => new WalletResource($this),//钱包信息
            'created_at' => Carbon::parse($this->created_at)->toDateTimeString(),//注册时间

        ];

    }

}
