<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

//首页标签
class PlatformTagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id, //标签ID
            'name' => $this->name,//标签名称
            'ico' => $this->ico,//标签图标
            'status' => $this->status,//标签状态
            'sort' => $this->sort,//标签排序
        ];
        $member = $request->user('ApiMember');
        $data['isTrue'] = false;
        if (isset($member->tags)) {
            $data['isTrue'] = $member->tags()->where('tag_id', $this->id)->exists();
        }
        return $data;
    }
}
