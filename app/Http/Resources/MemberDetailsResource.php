<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

//主播信息详情
class MemberDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'zb_id' => $this->id, //ID
            'no' => $this->no, //会员编号
            'head_pic' => $this->fill_head_pic,//头像
            'nick_name' => $this->nick_name,//昵称
            'mobile' => $this->mobile,//手机号
            'sex' => $this->sex,//性别 0男 1女9未知
            'birthday' => $this->birthday,//生日
            'age' => $this->age,//年龄
            'province' => $this->province,//所在省
            'city' => $this->city,//所在市城市
            'district' => $this->district,//所在县区
            'aphorism' => $this->aphorism,//格言
            'signature' => $this->signature,//签名
            'is_real' => $this->is_real,//实名状态 0已认证1未认证
            'im_status' => $this->online_status,//在线状态 0在线1离线2休眠9未知
            'live_status' => $this->live_status,//忙碌状态 0空闲 1忙碌
            'api_token' => $this->api_token,
            'im_token' => $this->im_token,
            'created_at' => Carbon::parse($this->created_at)->toDateTimeString(),//注册时间
        ];
    }
}
