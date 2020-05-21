<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

//首页用户列表
class MemberIndexResource extends JsonResource
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
            'head_pic' => $this->head_pic,//头像
            'nick_name' => $this->nick_name,//昵称
            'sex' => $this->sex,//性别 0男 1女
            'is_real' => $this->is_real,//实名状态 0已认证1未认证
            'im_status' => $this->online_status,//在线状态 0在线1离线2休眠9未知
            'live_status' => $this->live_status,//忙碌状态 0空闲 1忙碌
            'created_at' => date('Y-m-d H:i:s', strtotime($this->created_at)),//注册时间
        ];
    }
}
