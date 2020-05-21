<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 会员标签
 * Class MemberResourceResource
 * @package App\Http\Resources
 */
class MemberTagResource extends JsonResource
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
            'member_id' => $this->member_id, //会员ID
            'tag_id' => $this->tag_id,//标签编号
            'tag_name' => $this->tag->name,//标签名称
        ];
    }
}
