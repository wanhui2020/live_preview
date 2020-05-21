<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

//会员钱包
class WalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'wallet_id' => $this->id, //ID
            'cash_balance' => isset($this->cash) ? $this->cash->balance : 0,//金币余额
            'cash_usable' => isset($this->cash) ? $this->cash->usable : 0,//金币可用
            'cash_withdraw' => isset($this->cash) ? ($this->cash->usable - $this->cash->lock).'' : 0,//金币可提
            'gold_balance' => isset($this->gold) ? $this->gold->balance : 0,//能量余额
            'gold_usable' => isset($this->gold) ? $this->gold->usable : 0,//能量可用
        ];
    }
}
