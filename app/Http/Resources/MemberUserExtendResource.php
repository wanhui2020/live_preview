<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 会员扩展信息
 * Class MemberUserResource
 * @package App\Http\Resources
 */
class MemberUserExtendResource extends JsonResource
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
            'member_id' => $this->member_id, //会员编号
            'weixin' => $this->weixin,//个人微信号
            'weixin_verify' => $this->weixin_verify,//微信号验证 0通过 1失败9未知
            'qq' => $this->qq,//QQ号
            'qq_verify' => $this->qq_verify,//QQ号验证 0通过 1失败9未知
            'hobbies' => $this->hobbies,//兴趣爱好
            'profession' => $this->profession,//职业
            'height' => $this->height,//身高(cm)
            'weight' => $this->weight,//体重(KG)
            'constellation' => $this->constellation,//星座
            'blood' => $this->blood,//血型
            'emotion' => $this->emotion,//情感
            'income' => $this->income,//收入

        ];
    }

}
