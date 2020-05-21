<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 会员资源
 * Class MemberResourceResource
 * @package App\Http\Resources
 */
class MemberResourceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id, //ID
            'type' => $this->type,//类型
            'url' => isset($this->file)?$this->file->url:'',//原始资源地址
            'thumb' => isset($this->file)?$this->file->url . '?x-oss-process=image/resize,m_fill,h_500,w_500':'',//缩略地址
            'is_lock' => $this->is_lock,//解锁记录
            'gold' => $this->price,
            'status' => $this->status,//状态(0审核通过 1审核拒绝9待审核)
            'title' => isset($this->file->title) ? $this->file->title : '',//
            'describe' => isset($this->file->describe) ? $this->file->describe : '',//
            'front_cover' => isset($this->file->front_cover) ? $this->file->front_cover : '',//
        ];
        if ($this->type == 1) {
            $data['thumb'] = $this->file->thumb . '?x-oss-process=video/snapshot,t_1000,f_jpg,w_0,h_0,m_fast';
        }

        //是否有个性费率设置
        if ($data['gold'] == 0 && isset($this->member)) {
            $member = $this->member;
            if ($this->type == 0) {
                $data['gold'] = $member->rate->view_picture_fee;
            } else {
                $data['gold'] = $member->rate->view_video_fee;
            }
        }

        return $data;
    }
}
