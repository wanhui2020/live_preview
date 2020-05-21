<?php

namespace App\Http\Resources;


use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 社交动态列表
 * Class MemberUserResource
 * @package App\Http\Resources
 */
class DealSocialResource extends JsonResource
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
            'name' => $this->name, //标题
            'content' => $this->content,//内容
            'pic' => $this->pic,//图片
            'vido' => $this->vido,//视频
            'city' => $this->city,//所在城市
            'user'=>new MemberUserDetailResource($this->member),//用户信息
        ];
    }

}
