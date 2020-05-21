<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 评论评分
 * Class DealCommentResource
 * @package App\Http\Resources
 */
class DealCommentResource extends JsonResource
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
            'relevance_id' => $this->relevance_id,//评论场景
            'relevance_type' => $this->relevance_type,//评论场景
            'content' => $this->content,//内容
            'grade' => $this->grade,//综合评分
            'real_grade' => $this->real_grade,//内容真实评分
            'serve_grade' => $this->serve_grade,//服务态度评分
            'quality_grade' => $this->quality_grade,//内容质量评分
        ];
    }
}
