<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

//版本管理
class PlatformEdtionResource extends JsonResource
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
            'type' => $this->type, //安卓android 苹果ios
            'url' => $this->url,//链接
            'version' => $this->version,//版本号
            'is_force' => $this->is_force,//是否强制更新0是
            'describe' => $this->describe,//描述
            'sort' => $this->sort,//排序
        ];
    }
}
