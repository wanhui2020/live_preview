<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 点赞
 * Class DealCommentResource
 * @package App\Http\Resources
 */
class DealLikeResource extends JsonResource
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
            'relevance_id' => $this->relevance_id,//点赞场景
            'relevance_type' => $this->relevance_type,//点赞场景
            'number' => $this->number,//点赞次数
        ];
    }
}
