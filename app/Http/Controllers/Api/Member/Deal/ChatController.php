<?php

namespace App\Http\Controllers\Api\Member\Deal;


use App\Facades\PlatformFacade;
use App\Http\Controllers\Controller;
use App\Models\MemberUser;
use App\Models\PlatformFile;
use App\Repositories\DealUnlockRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * 聊天
 * Class PayController
 */
class ChatController extends Controller
{
    /**.
     * 聊天
     */
    public function store(Request $request,DealUnlockRepository $chatRepository)
    {
        try{
            $member = $request->user('ApiMember');

            if ($request->filled('member_id')){
                $member_id = $request->member_id; //会员id
            }else{
                $member_id = $member->id; //会员id
            }
            $to_member_id = $request->to_member_id; //被会员id

            if ($request->filled($to_member_id)){
                return $this->validation('请传入被会员id');
            }
            $data = [
                'member_id'=>$member_id,
                'to_member_id'=>$to_member_id,
            ];
            $dealtalk = $chatRepository->store($data);
            if ($dealtalk['status']) {
                return $this->succeed(null, '信息发送成功');
            } else {
                return $this->validation( $dealtalk['msg']);
            }
        } catch (\Exception $ex) {
            $this->exception($ex);
            return $this->validation('聊天异常，请联系管理员');
        }
    }

}

