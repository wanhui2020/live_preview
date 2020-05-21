<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

//会员封面
class MemberResourceCoverResource extends JsonResource
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
            'type' => $this->type,//类型
            'url' => $this->file->url,//原始资源地址
            'thumb' => $this->file->thumb,//缩略地址
            'sort' => $this->sort,//排序
        ];
    }
}
