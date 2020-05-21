<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

/**
 * 系统通知
 * Class PlatformNoticeResource
 * @package App\Http\Resources
 */
class PlatformNoticeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'member_id' => $this->member_id,//所属类型
            'name' => $this->name,//标题
            'content' => $this->content,//内容
            'url' => $this->url,//外部连接
            'status' => $this->status,//状态
            'sort' => $this->sort,//排序
            'title' => $this->title,
            'is_read' => $this->is_read,
            'created_at' => Carbon::parse($this->created_at)->toDateTimeString(),//发布时间
        ];
    }
}
