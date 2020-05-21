<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

//我的关注
class MemberAttentionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $age = bcsub(date("Y"),substr($this->tomember->birthday,0,4));
        return [
            'id' => $this->tomember->id, //ID
            'head_pic' => $this->tomember->fill_head_pic,//头像
            'fill_head_pic' => $this->tomember->fill_head_pic,//头像
            'nick_name' => $this->tomember->nick_name,//昵称
            'sex' => $this->tomember->sex,//性别 0男 1女
            'is_real' => $this->tomember->is_real,//是否实名认证 0已认证1未认证8审核中9待认证
            'is_selfie' => $this->tomember->is_selfie,//是否自拍认证 0认证通过 1认证拒绝 8审核中 9待认证
            'age'=>$age,
        ];
    }
}
