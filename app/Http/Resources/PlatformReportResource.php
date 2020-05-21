<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

//举报标签
class PlatformReportResource extends JsonResource
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
            'key' => $this->key,//对应键
            'value' => $this->value,//对应值
            'type' => $this->type,//数据类型
        ];
    }
}
