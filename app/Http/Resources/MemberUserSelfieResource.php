<?php

namespace App\Http\Resources;

use App\Models\MemberUserSelfie;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 自拍认证
 * Class MemberUserResource
 * @package App\Http\Resources
 */
class MemberUserSelfieResource extends JsonResource
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
            'member_id' => $this->member_id, // 所属会员
            'picture' => url($this->picture), // 自拍照
            'video' => url($this->video), // 自拍视频
            'undertaking' => $this->undertaking, // 承诺条款
            'audit_reason' => $this->audit_reason, // 审核意见
            'status' => $this->status, // 状态 0通过 1拒绝 9待审核
        ];
    }

}
