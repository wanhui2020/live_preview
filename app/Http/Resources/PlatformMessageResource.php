<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

//新闻信息
class PlatformMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id, //bannerID
            'type' => $this->type,//所属类型
            'is_banner' => $this->is_banner,//是否主页显示
            'pic' => $this->pic,//标题图片
            'title' => $this->title,//标题
            'content' => $this->content,//内容
            'url' => $this->url,//外部连接
            'status' => $this->status,//状态
            'sort' => $this->sort,//排序
        ];
    }
}
