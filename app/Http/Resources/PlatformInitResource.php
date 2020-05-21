<?php

namespace App\Http\Resources;

use App\Models\PlatformText;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 系统初始化
 * Class PlatformInitResource
 * @package App\Http\Resources
 */
class PlatformInitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data= [
            'id' => $this->id, //ID
            'type' => $this->type, //安卓android 苹果ios
            'url' => $this->url,//链接
            'version' => $this->version,//版本号
            'is_force' => $this->is_force,//是否强制更新0是
            'describe' => $this->describe,//版本描述
            'user_agreement' => $this->describe,//用户协议
            'privacy_agreement' => $this->describe,//隐私协议
        ];
        $texts=PlatformText::whereIn('type',[6,7])->get();
        foreach ($texts as $item){
            if ($item->type==6){
                $data['user_agreement']=$item->content;
            }
            if ($item->type==7){
                $data['user_agreement']=$item->content;
            }
        }
        return $data;
    }
}
