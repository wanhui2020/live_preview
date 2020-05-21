<?php

namespace App\Http\Resources;

use App\Repositories\DealTalkRepository;
use App\Repositories\DealWithdrawRepository;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 提现记录
 * Class DealTalkResource
 * @package App\Http\Resources
 */
class DealWithdrawResource extends JsonResource
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
            'no' => $this->no, //订单编号
            'money' => $this->money,//申请金额
            'total' => $this->total,//提现合计
            'received' => $this->received,//实到
            'username' => $this->username,//用户姓名
            'bank_account' => $this->bank_account,//银行账号
            'bank_name' => $this->bank_name,//银行名称
            'status' => $this->status,//状态 0成功 1失败2用户取消3系统取消 8支付中 9待支付
            'created_at' => date('Y-m-d H:i:s', strtotime($this->created_at)),//发生时间
            'relevance_type' => '人民币',//账户类型
            'type' => '提现转出',//摘要
        ];
    }
}
