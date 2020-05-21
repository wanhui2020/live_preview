<?php

namespace App\Http\Controllers\Api\Member\User;


use App\Facades\AliyunFacade;
use App\Http\Controllers\Controller;
use App\Models\MemberUserRealname;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 会员实名认证
 * Class PayController
 */
class RealnameController extends Controller
{
    /**
     * 申请实名
     */
    public function bind(Request $request)
    {
        try {
            $member = $request->user('ApiMember');
            $result = AliyunFacade::DescribeVerifyToken($member->no);
            if ($result['status']) {
                return $this->succeed($result['data'], '申请实名成功');
            }

            return $this->failure(1, '申请实名失败');
        } catch (\Exception $ex) {
            return $this->exception($ex, '申请实名异常，请联系管理员');
        }
    }

    /**
     * 实名结果查询
     */
    public function result(Request $request)
    {
        DB::beginTransaction();
        try {
            $member = $request->user('ApiMember');
            $result = AliyunFacade::DescribeVerifyResult($member->no);
            if (!$result['status']) {
                DB::rollBack();
                return $this->failure(1, '实名结果查询失败', $result);
            }
            $realname = MemberUserRealname::firstOrNew(['member_id' => $member->id]);
            $data = $result['data'];
            if (isset($data['IdCardName'])) {
                $realname->name = $data['IdCardName'];
            }
            if (isset($data['IdCardNumber'])) {
                $realname->idcard = $data['IdCardNumber'];
            }

            if (isset($data['FrontImageUrl'])) {
                $realname->idcard_front = $data['FrontImageUrl'];
            }
            if (isset($data['BackImageUrl'])) {
                $realname->idcard_back = $data['BackImageUrl'];
            }
            if (isset($data['FaceImageUrl'])) {
                $realname->idcard_hand = $data['FaceImageUrl'];
            }
            if (isset($data['Address'])) {
                $realname->address = $data['Address'];
            }
            $realname->status = 0;
            $realname->save();
            $member->is_real = 0;
            $member->save();
            DB::commit();

            return $this->succeed($realname, '实名成功,请等待管理员审核');
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->exception($ex, '申请实名异常，请联系管理员');
        }
    }

}

