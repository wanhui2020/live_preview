<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

//金币钱包
class WalletGoldResource extends JsonResource
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
            'member_id' => $this->member_id, //所属会员
            'balance' => $this->balance, //余额
            'usable' => $this->usable,//可用余额包含锁定金币
            'lock' => $this->lock,//锁定不可兑换金币
            'freeze' => $this->freeze,//金币通话冻结
            'platform' => $this->platform,//金币平台冻结
        ];
    }
}
