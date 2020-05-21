<?php

namespace App\Http\Controllers\Api\Member\Behavior;


use App\Http\Controllers\Controller;
use App\Models\MemberFeedback;
use App\Repositories\MemberFeedbackRepository;
use Illuminate\Http\Request;

/**
 * 会员签到
 * Class PayController
 */
class SigninController extends Controller
{
    /**
     * 会员签到
     */
    public function store(Request $request, MemberFeedbackRepository $feedbackRepository)
    {
        try {
            $member = $request->user('ApiMember');

            $content = $request->text;
            if (empty($content)) {
                return $this->validation('请输入反馈内容！');
            }
            $feed = MemberFeedback::where('member_id', $member->id)->where('replay_status', 0)->first();
            if ($feed) {
                return $this->validation('您已提交反馈，请耐心等待回复！');
            }
            $data = [
                'member_id' => $member->id,
                'content' => $content,
            ];
            $setwithdraw = $feedbackRepository->store($data);
            if ($setwithdraw) {
                return $this->succeed(null, '意见反馈提交成功!');
            }
            return $this->validation('意见反馈失败，请联系客服!');
        } catch (\Exception $e) {
            return $this->exception($e, '意见反馈失败，请联系管理员');
        }
    }
}

