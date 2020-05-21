<?php

namespace App\Http\Controllers\Api\Member\User;


use App\Http\Controllers\Controller;
use App\Http\Resources\MemberResourceResource;
use App\Http\Resources\MemberUserRateResource;
use App\Models\MemberUserRate;
use App\Models\MemberUserSelfie;
use App\Repositories\MemberResourceRepository;
use App\Repositories\MemberUserRepository;
use Illuminate\Http\Request;

/**
 * 会员费率设置
 * Class PayController
 */
class RateController extends Controller
{

    /**
     * 获取会员费率详情
     */
    public function detail(Request $request)
    {
        try {
            $member = $request->user('ApiMember');
            $rate = $member->rate;
            return $this->succeed(new MemberUserRateResource($rate), '获取会员费率成功');
        } catch (\Exception $ex) {
            return $this->exception($ex, '设置会员费率异常，请联系管理员');
        }
    }

    /**
     * 设置会员费率
     */
    public function update(Request $request)
    {
        try {
            $member = $request->user('ApiMember');
            if ($member->is_selfie != 0) {
                return $this->validation('未自拍认证不可设置');
            }

            $rate = MemberUserRate::firstOrNew(['member_id' => $member->id]);
            if ($request->filled('text_fee')) {
                $rate->text_fee = $request->text_fee;
            }
            if ($request->filled('voice_fee')) {
                $rate->voice_fee = $request->voice_fee;
            }
            if ($rate->save()) {
                return $this->succeed($rate, '设置会员费率成功');
            }
            return $this->validation('设置会员费率失败，请联系管理员');
        } catch (\Exception $ex) {
            return $this->exception($ex, '设置会员费率异常，请联系管理员');
        }
    }
}

