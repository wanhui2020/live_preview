<?php

namespace App\Http\Resources;

use App\Utils\Helper;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

/**
 * 通用会员信息列表
 * Class MemberUserResource
 *
 * @package App\Http\Resources
 */
class MemberUserListResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id'        => $this->id,
            //ID
            'no'        => $this->no,
            //会员编号
            'head_pic'  => $this->fill_head_pic
                .'?x-oss-process=image/resize,m_fill,h_100,w_100',
            //头像
            'cover'     => $this->fill_cover
                .'?x-oss-process=image/resize,m_fill,h_500,w_500',
            //封面图片
            'nick_name' => Helper::strSub($this->nick_name),
            //昵称
            'mobile'    => $this->mobile,
            //手机号
            'sex'       => $this->sex,
            //性别 0男 1女 9 未知
            'birthday'  => $this->birthday,
            //生日
            'age'       => $this->age,
            //年龄
            'province'  => isset($this->province) ?$this->province: '未知',
            //所在省
            //            'city' => $this->city == null ? '未知' : $this->city,//所在市城市
            'city'      => isset($this->city) ?$this->city: '',
            //所在市城市
            'district'  => isset($this->district)?$this->district: '',
            //所在县区
            'resident'  => isset($this->resident )?$this->resident: '',
            //常驻城市

            'aphorism'            => isset($this->aphorism)?Helper::strSub($this->aphorism):'',
            //格言
            'signature'           => isset($this->signature)?Helper::strSub($this->signature):'',
            //签名
            'distance'            => $this->when($this->distance, 0),
            //距离
            'is_middleman'        => $this->is_middleman,
            //经济人
            'is_selfie'           => $this->is_selfie,
            //自拍认证
            'is_real'             => $this->is_real,
            //实名状态 0已认证1未认证
            'im_status'           => $this->online_status,
            //在线状态 0在线1离线2休眠9未知
            'live_status'         => $this->live_status,
            //忙碌状态 0空闲 1忙碌
            'online_status'       => $this->online_status,
            //在线状态:0在线1离线9未知
            'dispose_online'      => $this->dispose_online,
            //处理后在线状态:0在线1离线2勿扰9未知
            //            'vip_integral' => $this->vip_integral,//VIP积分
            //            'vip_grade' => $this->vip_grade,//VIP等级
            'charm_integral'      => (int)$this->charm_integral,
            //魅力积分
            //            'charm_grade' => $this->charm_grade,//魅力等级
            //            'vip' => new PlatformVipResource($this->vip),//VIP信息
            //            'tags' => PlatformTagResource::collection($this->tags),//所属标签
            //            'extend' => new MemberUserExtendResource($this->extend),//扩展信息
            //            'parameter' => new MemberUserParameterResource($this->parameter),//会员参数
            //            'charm_num' => $this->charm_grade,//魅力值
            'created_at'          => Carbon::parse($this->created_at)
                ->toDateTimeString(),
            //注册时间
            'vip_integral'        => (int)$this->attention_count,
            'vip_integral_text'   => (string)$this->attention_count,
            'charm_integral_text' =>(string)$this->charm_integral_text,
            'total_revenue' => isset($this->total_revenue)?$this->total_revenue:'',
        ];

        $member = $request->user('ApiMember');
        if ($this->type == 1 && !empty($member->resident)) {
            $data['resident'] = !empty($member->resident)?$member->resident:'';
        }
        $data['resident'] = $data['resident'] == 'null'?'未知':$data['resident'];
        return $data;
    }

}
