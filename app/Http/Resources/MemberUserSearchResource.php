<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 首页右上角会员搜索
 * Class MemberUserResource
 * @package App\Http\Resources
 */
class MemberUserSearchResource extends JsonResource
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
            'head_pic' => $this->fill_head_pic,//头像
            'nick_name' => $this->nick_name,//昵称
            'signature' => $this->signature,//签名
            'is_real' => $this->is_real,//是否实名认证 0已认证1未认证8审核中9待认证
            'is_selfie' => $this->is_selfie,//是否自拍认证 0已认证1未认证9待认证
            'vip' => new PlatformVipResource($this->vip),//VIP信息
        ];
    }

}
