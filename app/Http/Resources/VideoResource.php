<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class VideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'modify_time' => isset($this['ModifyTime']) ? $this['ModifyTime'] : '',//评论场景
            'video_id' => isset($this['VideoId']) ? $this['VideoId'] : '',//评论场景
            'Description' => isset($this['Description']) ? $this['Description'] : '',//评论场景
            'title' => isset($this['Title']) ? $this['Title'] : '',//评论场景
            'creation_time' => isset($this['CreationTime']) ? $this['CreationTime'] : '',//内容
            'duration' => isset($this['duration']) ? $this['duration'] : '',//内容真实评分
            'status' => isset($this['Status']) ? $this['Status'] : '',//服务态度评分
            'size' => isset($this['Size']) ? $this['Size'] : '',//内容质量评分
            'cate_id' => isset($this['CateId']) ? $this['CateId'] : '',//内容质量评分
            'cate_name' => isset($this['CateName']) ? $this['CateName'] : '',//内容质量评分
            'storage_location' => isset($this['StorageLocation']) ? $this['StorageLocation'] : '',//内容质量评分
            'snapshots' => isset($this['Snapshots']) ? $this['Snapshots'] : '',//内容质量评分
        ];
    }
}
