<?php

namespace App\Http\Controllers\Api\Member\Behavior;


use App\Http\Controllers\Controller;
use App\Models\MemberFeedback;
use App\Models\MemberReport;
use App\Repositories\MemberFeedbackRepository;
use App\Repositories\MemberReportRepository;
use App\Repositories\MemberUserRepository;
use Illuminate\Http\Request;

/**
 * 会员举报
 * Class PayController
 */
class ReportController extends Controller
{
    /**
     * 会员举报
     * 举报人
     * 被举报人
     * 举报类型
     * 举报说明
     */
    public function store(Request $request, MemberReportRepository $reportRepository)
    {
        try {
            $member = $request->user('ApiMember');

            $member_id = $member->id; //举报人id
            if ($member->id == $request->to_member_id) {
                return $this->validation('不能举报自己');
            }

            $to_member_id = $request->to_member_id; //被举报人id
            $report_id = $request->report_id; //举报类型id
            $content = $request->text; //举报的内容
            $res = MemberReport::where('to_member_id',$to_member_id)->whereIn('status',[1,9])->first();
            if ($res){
                return $this->validation('您的举报我们正在积极处理，请勿重复操作!');
            }
            if ($request->filled($to_member_id)){
                return $this->validation('缺少被举报人的id!');
            }
            if ($request->filled($report_id)){
                return $this->validation('缺少举报类型id');
            }
            $data = [
                'member_id'=>$member_id,
                'to_member_id'=>$to_member_id,
                'report_id'=>$report_id,
                'content'=>$content,
            ];
            $report = $reportRepository->store($data);
            if ($report){
                return $this->succeed(null,'举报成功，你的举报我们会酌情处理!');
            }
            return $this->validation('举报失败！',$report);
        } catch (\Exception $e) {
            return $this->exception($e,'会员举报失败，请联系管理员');
        }
    }
}

