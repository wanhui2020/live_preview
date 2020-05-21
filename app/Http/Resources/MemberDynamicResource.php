<?php

namespace App\Http\Resources;

use App\Models\MemberUserRate;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 动态
 * Class MemberDynamicResource
 * @package App\Http\Resources
 */
class MemberDynamicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->member) {
            $age = bcsub(date('Y'), substr($this->member->birthday, 0, 4));
        }else{
            $age = mt_rand(20,35);
        }
        return [
            'id' => $this->id,
            'member_head_pic' => $this->member?$this->member->head_pic:'',
            'member_nick_name' => $this->member?$this->member->nick_name:'',
            'resident' => !empty($this->resident)?$this->resident:'',
            'member_sex' => $this->member?$this->member->sex:0,
            'member_no' => $this->member?$this->member->no:'',
            'member_charm_grade' => $this->member?$this->member->charm_grade:0,
            'member_vip_grade' => $this->member?$this->member->vip_grade:0,
            'member_age' => $age,
            'member_id'=>$this->member_id,
            'content' => $this->content,
            'type' => $this->type,
            'price' => $this->price,
            'status' => $this->status,
            'sort' => $this->sort,
            'file' => PlatformFileResource::collection($this->file),//原始资源地址
            'like'=>$this->like,
            'comment'=>$this->comment,
            'like_count'=>$this->like_number,
            'comment_count'=>$this->comment_number,
            'like_user'=>$this->like_user,
            'attention_type'=>$this->attention_type,//1为未关注0关注
            'like_type'=>$this->like_type,//1为未点赞0已点赞
            'mouth'=>$this->mouth,
            'day'=>$this->day,
            'created_at' => isset($this->time_conversion) ? $this->time_conversion : '',//注册时间

        ];

    }

}
