<?php

namespace App\Http\Resources;

use App\Repositories\DealTalkRepository;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 音视频通话
 * Class DealTalkResource
 * @package App\Http\Resources
 */
class DealTalkResource extends JsonResource
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
            'room_id' => $this->room_id,//房间编号
            'dialing_id' => $this->dialing_id,//发起方
            'dialing_no' => $this->dialing->no,//发起方编号
            'dialing_cover' => $this->dialing->cover,//封面

            'called_id' => $this->called_id,//被叫方
            'called_no' => $this->called->no,//被叫编号
            'called_cover' => $this->called->cover,//封面

            'type' => $this->type,//呼叫类型，0视频，1语音
            'begin_time' => $this->begin_time,//开始时间
            'end_time' => $this->end_time,//结束时间
            'duration' => $this->duration,//通话时间秒
            'price' => $this->price,//单价金币/分
            'total' => $this->total,//呼叫方消费金币
            'status' => $this->status,//状态（0结束1通话中 8呼叫中 9准备中）

        ];
    }
}
