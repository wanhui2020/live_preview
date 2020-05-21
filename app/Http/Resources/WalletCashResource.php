<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

//金币钱包
class WalletCashResource extends JsonResource
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
            'balance' => $this->balance, //金币
            'usable' => $this->usable,//可用金额包含锁定金额
            'lock' => $this->lock,//锁定金额
            'freeze' => $this->freeze,//现金冻结金币
            'platform' => $this->platform,//现金平台冻结金币
        ];
    }
}
