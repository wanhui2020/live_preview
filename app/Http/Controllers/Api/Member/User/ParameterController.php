<?php

namespace App\Http\Controllers\Api\Member\User;


use App\Http\Controllers\Controller;
use App\Http\Resources\MemberDetailsResource;
use App\Http\Resources\MemberIndexResource;
use App\Http\Resources\MemberUserParameterResource;
use App\Http\Resources\MemberUserDetailResource;
use App\Models\MemberUser;
use App\Models\MemberUserParameter;
use App\Repositories\MemberUserParameterRepository;
use App\Repositories\MemberUserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * 会员参数
 * Class PayController
 */
class ParameterController extends Controller
{

    public function update(Request $request, MemberUserParameterRepository $repository)
    {
        try {
            $member = $request->user('ApiMember');
            $data['member_id'] = $member->id;
            if ($request->filled('is_disturb')) {
                $data['is_disturb'] = $request->is_disturb;
            }
            if ($request->filled('is_location')) {
                $data['is_location'] = $request->is_location;
            }
            if ($request->filled('is_stranger')) {
                $data['is_stranger'] = $request->is_stranger;
            }
            if ($request->filled('is_text')) {
                $data['is_text'] = $request->is_text;
            }
            if ($request->filled('is_voice')) {
                $data['is_voice'] = $request->is_voice;
            }
            if ($request->filled('is_video')) {
                $data['is_video'] = $request->is_video;
            }
            if ($request->filled('greeting')) {
                $data['greeting'] = $request->greeting;
            }
            if ($request->filled('wechat_view')) {
                $data['wechat_view'] = $request->wechat_view;
            }
            if ($request->filled('is_screencap')) {
                $data['is_screencap'] = $request->is_screencap;
            }
            if ($request->filled('is_answer_host_phonep')) {
                $data['is_answer_host_phonep'] = $request->is_answer_host_phonep;
            }
            $resp = $repository->update($data);
            if ($resp['status']) {
                $parameter = $resp['data'];
                return $this->succeed(new MemberUserParameterResource($parameter), '返回主播参数成功！');
            }
            return $resp;
        } catch (\Exception $ex) {
            return $this->exception($ex, '主播参数异常，请联系管理员!');
        }
    }

}

