<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 提现申请
 * Class WalletWithdrawApplyResource
 * @package App\Http\Resources
 */
class DellWithdrawApplyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'money' => $this['money'],//可提现金额
            'username' => $this['username']??'', //用户姓名
            'bank_account' => $this['bank_account']??'',//银行账号
            'bank_name' => $this['bank_name']??'',//银行名称
        ];
    }

}
