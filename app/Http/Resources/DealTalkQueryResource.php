<?php

namespace App\Http\Resources;

use App\Repositories\DealTalkRepository;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 订单查询用户信息
 * Class DealTalkResource
 * @package App\Http\Resources
 */
class DealTalkQueryResource extends JsonResource
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
            'dialing_no' => $this->dialing ? $this->dialing->no : '',//发起方编号
            'dialing_cover' => $this->dialing ? $this->dialing->cover : '',//发起方封面图片
            'dialing_nick_name' => $this->dialing ? $this->dialing->nick_name : '',//发起方封面图片
            'dialing_head_pic' => $this->dialing ? $this->dialing->head_pic : '',//头像图片
            'dialing_sex' => $this->dialing->sex == 0 ? '男' : ($this->dialing->sex == 1 ? '女' : '未知'),//姓别
            'dialing_birthday' => $this->dialing ? $this->dialing->birthday : '',//生日
            'dialing_age' => $this->dialing ? bcsub(date("Y"),substr($this->dialing->birthday,0,4)) : '',

            'called_id' => $this->called_id,//被叫方
            'called_no' => $this->called ? $this->called->no : '',//被叫编号
            'called_cover' => $this->called ? $this->called->cover : '',//被叫封面图片
            'called_nick_name' => $this->called ? $this->called->nick_name : '',//发起方封面图片
            'called_head_pic' => $this->called ? $this->called->head_pic : '',//头像图片
            'called_sex' => $this->called->sex == 0 ? '男' : ($this->called->sex == 1 ? '女' : '未知'),//姓别
            'called_birthday' => $this->called ? $this->called->birthday : '',//生日
            'called_age' => $this->called ? bcsub(date("Y"),substr($this->called->birthday,0,4)) : '',//生日

            'type' => $this->type,//呼叫类型，0视频，1语音
            'begin_time' => $this->begin_time,//开始时间
            'end_time' => $this->end_time,//结束时间
            'duration' => $this->duration,//通话时间秒
            'price' => $this->price,//单价金币/分
            'total' => $this->total,//呼叫方消费金币
            'status' => $this->status,//状态（0结束1通话中 8呼叫中 9准备中）
            'received' => $this->received,
            'gift' => $this->gift,
            'talk_duration' => $this->talk_duration,//通话时间秒
        ];

    }
}
