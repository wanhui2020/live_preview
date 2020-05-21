<?php

namespace App\Http\Controllers\Callback;

use App\Facades\DealFacade;
use App\Facades\MemberFacade;
use App\Facades\PlatformFacade;
use App\Http\Controllers\Controller;
use App\Models\DealTalk;
use App\Models\MemberUser;
use App\Utils\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImController extends Controller
{

    public function index(Request $request)
    {
        try {
            $result = $request->all();

//        //逻辑处理
            $command = $result['CallbackCommand'];

            //状态变更回调
            if ($command == 'State.StateChange') {
                $info = $result['Info'];
                $no = $info['To_Account'];
                $action = $info['Action'];
                $OptPlatform = $result['OptPlatform'];
                $member = MemberUser::where('no', $no)->first();
                if (isset($member)) {
                    $member->app_platform = $OptPlatform;
                    if ($action == 'Logout') {
                        //下线
                        $member->im_status = 1;
                    }
                    if ($action == 'Login') {
                        $member->im_status = 0;
                        $member->online_status = 0;
                    }
                    if ($action == 'Disconnect') {
                        $member->im_status = 2;
                    }
                    $member->save();
                }
            }

            //发单聊消息之前回调
            if ($command == 'C2C.CallbackBeforeSendMsg') {
                Log::info("发单聊消息之前回调" . json_encode($result));
                $from = $result['From_Account'];
                $to = $result['To_Account'];

                $msgBody = $result['MsgBody'][0];
                $msgType = $msgBody['MsgType'];
                $msgContent = $msgBody['MsgContent'];

//                if ($msgType == 'TIMTextElem') {
                if ($msgType == 'TIMTextElem' || $msgType == 'TIMSoundElem' || $msgType == 'TIMCustomElem') {

                    $fromMember = MemberUser::where('no', $from)->first();
                    $toMember = MemberUser::where('no', $to)->first();

                    $chat = PlatformFacade::config('private_chat');
                    if ($chat == 1 && $fromMember->is_selfie == 0 && $toMember->is_selfie == 0 && $toMember['type'] ==0 && $fromMember['type'] == 0){
                        return [
                            'ActionStatus' => 'ok',
                            'ErrorInfo' => '主播不能给主播发消息',
                            'ErrorCode' => 120001
                        ];
                    }
                    if ($chat == 1 && $fromMember->is_selfie != 0 && $toMember->is_selfie != 0 && $toMember['type'] ==0 && $fromMember['type'] == 0){
                        return [
                            'ActionStatus' => 'ok',
                            'ErrorInfo' => '用户不能给用户发消息',
                            'ErrorCode' => 120001
                        ];
                    }

                    if (!isset($fromMember)) {
                        return [
                            'ActionStatus' => 'ok',
                            'ErrorInfo' => '发送方未找到',
                            'ErrorCode' => 120001
                        ];
                    }
                    if (isset($toMember) && $toMember['status'] !=0) {
                        return [
                            'ActionStatus' => 'ok',
                            'ErrorInfo' => '用户已被禁用',
                            'ErrorCode' => 120001
                        ];
                    }
                    if (!isset($toMember)) {
                        return [
                            'ActionStatus' => 'ok',
                            'ErrorInfo' => '接收方未找到',
                            'ErrorCode' => 120001
                        ];
                    }
                    //用户开启勿扰
                    if (isset($fromMember->parameter)) {
                        if ($fromMember->parameter->is_disturb == 1) {
                            return [
                                'ActionStatus' => 'ok',
                                'ErrorInfo' => '对方已打开勿扰',
                                'ErrorCode' => 120001
                            ];
                        }
                    }
                    if (isset($toMember->parameter)) {
                        if ($toMember->parameter->is_disturb == 1) {
                            return [
                                'ActionStatus' => 'ok',
                                'ErrorInfo' => '对方已打开勿扰',
                                'ErrorCode' => 120001
                            ];
                        }
                        //陌生人信息判断
                        if ($toMember->parameter->is_text == 1) {
                            return [
                                'ActionStatus' => 'ok',
                                'ErrorInfo' => '对方拒绝接收文本信息',
                                'ErrorCode' => 120001
                            ];
                        }
                        //陌生人信息判断
                        if ($toMember->parameter->is_stranger == 1) {
                            if (!$toMember->formAttentions()->where('to_member_id', $fromMember->id)->first()) {
                                return [
                                    'ActionStatus' => 'ok',
                                    'ErrorInfo' => '对方拒绝陌生人信息',
                                    'ErrorCode' => 120001
                                ];
                            }
                        }
                    }


                    if ($fromMember->formBlacklists()->where('status', 0)->where('to_member_id', $toMember->id)->exists()) {
                        return [
                            'ActionStatus' => 'ok',
                            'ErrorInfo' => '对方被我已拉入黑名单',
                            'ErrorCode' => 120001
                        ];
                    }

                    if ($toMember->formBlacklists()->where('status', 0)->where('to_member_id', $fromMember->id)->exists()) {
                        return [
                            'ActionStatus' => 'ok',
                            'ErrorInfo' => '我被对方已拉入黑名单',
                            'ErrorCode' => 120001
                        ];
                    }

                    if ($msgType == 'TIMTextElem') {
                        $text = $msgContent['Text'];
                    } elseif ($msgType == 'TIMSoundElem') {
//                        $text = json_encode($msgContent['Url']);
                        $text = '发送语音';
                    } elseif ($msgType == 'TIMCustomElem') {
//                        $text = '发送地址：'.$msgContent['Data']['data']['address'];
                        $text = '发送地址';
                    }

                    $formMember = MemberUser::where('no', $from)->first();
                    $toMember = MemberUser::where('no', $to)->first();
                    if ($formMember['type'] == 0 && $toMember['type'] == 0 && $msgType == 'TIMTextElem') {
                        //内容过滤
                        if (!$text = PlatformFacade::keyword($text)) {
                            return [
                                'ActionStatus' => 'ok',
                                'ErrorInfo' => '发送失败:内容屏蔽',
                                'ErrorCode' => 120001
                            ];
                        }
                    }

                    //执行扣费
                    $resp = DealFacade::dealChat($from, $to, $text);
                    if ($resp['status']) {
                        return [
                            'ActionStatus' => 'ok',
                            'ErrorInfo' => '发送成功',
                            'ErrorCode' => 0,
                            'MsgBody' => [
                                'MsgType' => 'TIMTextElem',
                                'MsgContent' => [
                                    'Text' => $text,
                                ]]
                        ];
                    }
                    return [
                        'ActionStatus' => 'ok',
                        'ErrorInfo' => '发送失败:' . $resp['msg'],
                        'ErrorCode' => 120001
                    ];
                }

                //自定义
                if ($msgType == 'TIMCustomElem') {
//                if ($msgType == 'TIMCustomElem' || $msgType == 'TIMSoundElem') {
                    $toMember = MemberUser::where('no', $to)->first();
                    if (!isset($toMember)) {
                        return [
                            'ActionStatus' => 'ok',
                            'ErrorInfo' => '接收方未找到',
                            'ErrorCode' => 120001
                        ];
                    }
//                    if (isset($toMember->parameter)) {
////                        if ($toMember->parameter->is_disturb == 1) {
////                            return [
////                                'ActionStatus' => 'ok',
////                                'ErrorInfo' => '对方已打开勿扰',
////                                'ErrorCode' => 120001
////                            ];
////                        }
////                    }


                    $content = json_decode($msgContent['Data'], true);
                    if (isset($content) && isset($content['command'])) {
                        $command = $content['command']; //指令

                    }
                }

            }//发单聊消息之后回调
            if ($command == 'C2C.CallbackAfterSendMsg') {
                Log::info("发单聊消息之后回调".json_encode($result));
//                $this->logs('发单聊消息之后回调', $result);
                $FromAccount = $result['From_Account']; //发送方
                $ToAccount = $result['To_Account']; //接收方
                $msgBody = $result['MsgBody'][0]; //内容
                $msgType = $msgBody['MsgType']; //发送之前
                $msgContent = $msgBody['MsgContent']; //
                //自定义
                if ($msgType == 'TIMCustomElem') {

                    //接收到自定义消息
                    $content = json_decode($msgContent['Data'], true);
//                    $this->logs('$content', $content);
                    if (isset($content) && isset($content['command'])) {
                        $command = $content['command']; //指令
                        if (!isset($content['data'])) {
                            return $this->failure(1, '格式异常', $content);
                        }
                        $data = $content['data'];
                        //创建通话房间{"command":"talk.begin","data":{"room_id":123}}
                        //接听来电{"command":"talk.answer","data":{"room_id":123}}
                        //已接通正常挂断{"command":"talk.hangup","data":{"room_id":123}}
                        //通话未接听挂断{"command":"talk.refuse","data":{"room_id":123}}
                        //通话取消{"command":"talk.cancel","data":{"room_id":123}}
                        //异常挂断{"command":"talk.exception","data":{"room_id":123}}
                        //通知被叫方接听{"command":"talk.notification","data":{"room_id":123}}
                        //通话扣费通知{"command":"talk.deduction","data":{"usable":3190,"room_id":"10000432","form_id":\"10000019","to_id":"10000017"}}

                        //已通话挂断{"command":"talk.hangup","data":{"room_id":123}}
                        if (isset($data['room_id'])) {
//                            switch ($command) {
//                                case 'talk.begin'://通话订单创建
//                                    DealFacade::talkRoom($data['room_id']);
//                                    $this->logs('通话订单创建', $result);
//                                    break;
//                                case 'talk.hangup'://已通话挂断
//                                    DealFacade::talkHangup($data['room_id'], 0);
//                                    $this->logs('已通话挂断', $result);
//                                    break;
//                                case 'talk.exception'://通话异常挂断
//                                    DealFacade::talkHangup($data['room_id'], 4);
//                                    $this->logs('通话异常挂断', $result);
//                                    break;
//                                case 'talk.finish'://通话结束挂断
//                                    //DealFacade::talkHangup($data['room_id'], 4);
//                                    $this->logs('通话结束挂断', $result);
//                                    break;
//                                case 'talk.refuse'://通话订单拒绝
//                                    DealFacade::talkHangup($data['room_id'], 2);
//                                    $this->logs('通话订单拒绝', $result);
//                                    break;
//                                case 'talk.cancel'://通话订单取消
//                                    DealFacade::talkHangup($data['room_id'], 3);
//                                    $this->logs('通话订单取消', $result);
//                                    break;
//                                case 'talk.nack'://通话订单未接听取消
//                                    DealFacade::talkHangup($data['room_id'], 1);
//                                    $this->logs('通话订单未接听取消', $result);
//                                    break;
//                                case 'talk.answer'://通话订单接听
//                                    DealFacade::talkAnswer($data['room_id']);
//                                    $this->logs('通话订单接听', $result);
//                                    break;
//                                case 'talk.notification'://通知被叫方接听
//                                    $this->logs('通知被叫方接听', $result);
//                                    break;
//
//                            }
                        }


                    }
                }

            }
            //创建群组之前回调
            if ($command == 'Group.CallbackBeforeCreateGroup') {

            }
            //创建群组之后回调
            if ($command == 'Group.CallbackAfterCreateGroup') {

            }
            //申请入群之前回调
            if ($command == 'Group.CallbackBeforeApplyJoinGroup') {

            }
            //拉人入群之前回调
            if ($command == 'Group.CallbackBeforeInviteJoinGroup') {

            }
            //新成员入群之后回调
            if ($command == 'Group.CallbackAfterNewMemberJoin') {

            }
            //群成员离开之后回调
            if ($command == 'Group.CallbackAfterMemberExit') {
            }
            //群内发言之前回调
            if ($command == 'Group.CallbackBeforeSendMsg') {

            }
            //群内发言之后回调
            if ($command == 'Group.CallbackAfterSendMsg') {

            }
            //群组解散之后回调
            if ($command == 'Group.CallbackAfterGroupDestroyed') {
            }
            return ['ActionStatus' => 'OK', 'ErrorCode' => 0, 'ErrorInfo' => ''];
//
        } catch (\Exception $exception) {
            return $this->exception($exception, 'IM回调异常');
        }
    }
}
