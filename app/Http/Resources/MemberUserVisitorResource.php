<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 访问记录
 * Class MemberUserResource
 * @package App\Http\Resources
 */
class MemberUserVisitorResource extends JsonResource
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
            'head_pic' => $this->head_pic,//头像
            'nick_name' => $this->nick_name,//昵称
            'signature' => $this->signature,//签名
            'is_real' => $this->is_real,//是否实名认证 0已认证1未认证8审核中9待认证
            'is_selfie' => $this->is_selfie,//是否自拍认证 0已认证1未认证9待认证
            'vip' => new PlatformVipResource($this->vip),//VIP信息
            'sex' => $this->sex,//性别 0男 1女 9 未知
            'birthday' => $this->birthday,//生日
            'age' => $this->age,//年龄
            'vip_grade' => $this->vip_grade,//VIP等级
            'charm_integral' => $this->charm_integral,//魅力积分
            'charm_grade' => $this->charm_grade,//魅力等级
        ];
    }

}
