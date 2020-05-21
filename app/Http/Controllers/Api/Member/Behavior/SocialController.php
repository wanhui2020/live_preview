<?php

namespace App\Http\Controllers\Api\Member\Behavior;


use App\Http\Controllers\Controller;
use App\Http\Resources\DealSocialResource;
use App\Repositories\DealSocialRepository;
use Illuminate\Http\Request;

/**
 * 社交动态列表
 * Class PayController
 */
class SocialController extends Controller
{
    /**
     * 社交动态列表
     */
    public function lists(Request $request, DealSocialRepository $socialRepository)
    {
        try {
            $member = $request->user('ApiMember');
            $social = $socialRepository->lists();
            return $this->succeed(DealSocialResource::collection($social), '社交动态返回成功!');
        } catch (\Exception $e) {
            return $this->exception($e, '社交动态返回失败，请联系管理员');
        }
    }
}

