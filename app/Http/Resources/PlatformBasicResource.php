<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

//基础数据
class PlatformBasicResource extends JsonResource
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
            'type' => $this->type,//数据类型
            'key' => $this->key,//对应键
            'value' => $this->value,//对应值

            'status' => $this->status,//状态
            'sort' => $this->sort,//排序
        ];
    }
}
