<?php

namespace App\Http\Controllers\Api\Member\Deal;


use App\Facades\DealFacade;
use App\Facades\ImFacade;
use App\Facades\MemberFacade;
use App\Facades\PlatformFacade;
use App\Facades\PushFacade;
use App\Http\Controllers\Controller;
use App\Http\Resources\DealTalkQueryResource;
use App\Http\Resources\DealTalkResource;
use App\Models\DealGift;
use App\Models\DealTalk;
use App\Models\MemberUser;
use App\Repositories\DealTalkRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * 语音视频
 * Class TalkController
 */
class TalkController extends Controller
{
    /**
     * 创建
     * 主叫
     * 被叫
     * 类型
     *  0 视频 1 语音
     */
    public function store(Request $request, DealTalkRepository $dealTalkRepository)
    {
        try {
            $member = $request->user('ApiMember');
            if ($member->live_status == 1) {
                return $this->validation('当前正在通话，不可呼叫');
            }

            $dialing_id = $member->id; //主叫id
            $dialing = MemberUser::find($dialing_id);
            if (!isset($dialing)) {
                return $this->validation('主叫不存在');
            }
            if ($request->filled('called_no')) {
                $called = MemberUser::where('no', $request->called_no)->first();
//                if ($called['parameter']['is_answer_host_phonep'] == 1){
//                    return $this->validation('被叫方关闭接听主播电话');
//                }
                if (!isset($called)) {
                    return $this->validation('被叫方不存在');
                }
                $called_id = $called->id; //被叫id

            } else {
                if (!$request->filled('called_id')) {
                    return $this->validation('请传入被叫id');
                }
                $called_id = $request->called_id; //被叫id
            }
            if (!$request->filled('type')) {
                return $this->validation('请传入通话类型');
            }
            $type = $request->type;
            if ($dialing_id == $called_id) {
                return $this->validation('不能和自己通话');
            }

            if (!isset($called)) {
                $called = MemberUser::find($called_id) ;
            }

            $toMemberUser = MemberUser::find($called_id) ;
            $chat = PlatformFacade::config('private_chat');
            if ($chat == 1 && $member->is_selfie == 0 && $toMemberUser->is_selfie == 0 && $toMemberUser['type'] ==0 && $member['type'] == 0){
                return $this->validation('主播不能给主播发消息');
            }
            if ($chat == 1 && $member->is_selfie != 0 && $toMemberUser->is_selfie != 0 && $toMemberUser['type'] ==0 && $member['type'] == 0){
                return $this->validation('用户不能给用户发消息');
            }

            //更新双方状态
            MemberFacade::imOnlineSync([$member->no, $dialing->no]);

            if (isset($called->parameter)) {
                if ($called->parameter->is_disturb == 1) {
                    return $this->validation('对方已打开勿扰');
                }
                if ($type == 0 && $called->parameter->is_video == 1) {
                    return $this->validation('被叫方拒绝视频通话');
                }
                if ($type == 1 && $called->parameter->is_voice == 1) {
                    return $this->validation('被叫方拒绝语音通话');
                }

            }

            $data = [
                'dialing_id' => $dialing_id,
                'called_id' => $called_id,
                'type' => $type,
            ];
            $dealtalk = $dealTalkRepository->store($data);
            if ($dealtalk['status']) {
                return $this->succeed(new DealTalkResource($dealtalk['data']), '发起视频语音成功');
            }
            return $this->validation($dealtalk['msg']);
        } catch (\Exception $ex) {
            return $this->exception($ex, '语音视频异常，请联系管理员');
        }
    }

    /**
     * 接听
     * @param Request $request
     * @return mixed
     */
    public function answer(Request $request, DealTalkRepository $dealTalkRepository)
    {
        try {
            $member = $request->user('ApiMember');
            if (!$request->filled('room_id')) {
                return $this->validation('房间编号不能为空');
            }
            $roomId = $request->room_id;
            $talk = $dealTalkRepository->findWhere(function ($query) use ($member, $roomId) {
                $query->where('called_id', $member->id);
                $query->where('room_id', $roomId);
                $query->where('status', 8);
            });
            if (!isset($talk)) {
                return $this->validation('未找到待接听订单');
            }
            return DealFacade::talkAnswer($roomId);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 通话挂断
     * @param Request $request
     * @return array
     */
    public function hangup(Request $request, DealTalkRepository $dealTalkRepository)
    {
        try {
            $member = $request->user('ApiMember');
            if (!$request->filled('room_id')) {
                return $this->validation('房间编号不能为空');
            }
            $roomId = $request->room_id;

            if (!$request->filled('way')) {
                return $this->validation('挂断方式不能为空');
            }
            $way = $request->way;
            $talk = $dealTalkRepository->findWhere(function ($query) use ($member, $roomId) {
                $query->where(function ($query) use ($member) {
                    $query->orWhere('called_id', $member->id);
                    $query->orWhere('dialing_id', $member->id);
                });
                $query->where('room_id', $roomId);
                $query->whereNotIn('status', [0]);
            });

            if (!isset($talk)) {
                return $this->validation('未找到通话订单');
            }

            return DealFacade::talkHangup($roomId, $way);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }


    /**
     * 查询订单状态 返回整个订单的信息
     */
    public function query(Request $request)
    {
        try {
            /**
             * 根据API  用户的ID
             * 查询订单表
             * 如果有呼叫中的就返整个订单信息
             * 没有就返false
             */
            $member = $request->user('ApiMember');
            if ($request->filled('room_id')){
                $dealTalk = DealTalk::where('room_id',$request->room_id)->first();
            }else {
                $dealTalk = DealTalk::where(function ($query) use ($member) {
                    $query->orWhere('dialing_id', $member->id);
                    $query->orWhere('called_id', $member->id);
                })->whereNotIn('status', [0])->first();
            }
            if ($dealTalk) {
                $dealTalk['talk_duration'] = floor($dealTalk['duration']/60).'分'.($dealTalk['duration']%60).'秒';
                $dealTalk['gift'] = DealGift::where(['relevance_type' => 'DealTalk', 'relevance_id' => $dealTalk['id']])->count();
                return $this->succeed( new DealTalkQueryResource($dealTalk), '返回订单查询成功!');
            } else {
                return $this->succeed([], '返回订单查询成功!');
            }
        } catch (\Exception $ex) {
            return $this->exception($ex, '查询订单异常，请联系管理员');
        }
    }

}

