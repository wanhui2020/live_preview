<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 首页类型
 * Class PlatformNoticeResource
 * @package App\Http\Resources
 */
class PlatformTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $httpType = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';

        $url = $httpType.$request->server('HTTP_HOST').'/api/';
        return [
            'id' => $this->id,
            'name' => $this->name,//标题
            'url' => $url.$this->url,//外部连接
            'status' => $this->status,//状态
            'sort' => $this->sort,//排序
            'created_at' => Carbon::parse($this->created_at)->toDateTimeString(),//发布时间
        ];
    }
}
