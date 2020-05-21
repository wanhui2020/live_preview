<?php

namespace App\Http\Controllers\Api\Member\User;


use App\Http\Controllers\Controller;
use App\Http\Resources\MemberDetailsResource;
use App\Http\Resources\MemberIndexResource;
use App\Http\Resources\MemberUserExtendResource;
use App\Http\Resources\MemberUserParameterResource;
use App\Http\Resources\MemberUserDetailResource;
use App\Models\MemberUser;
use App\Models\MemberUserExtend;
use App\Models\MemberUserParameter;
use App\Repositories\MemberUserExtendRepository;
use App\Repositories\MemberUserParameterRepository;
use App\Repositories\MemberUserRepository;
use Illuminate\Http\Request;

/**
 * 会员扩展
 * Class PayController
 */
class ExtendController extends Controller
{

    public function update(Request $request, MemberUserExtendRepository $repository)
    {

        try {
            $member = $request->user('ApiMember');
            $extend = MemberUserExtend::firstOrNew(['member_id' => $member->id]);
            //微信
            if ($request->filled('weixin')) {
                $extend->weixin = $request->weixin;
                $extend->weixin_verify = 2;
            }
            //QQ号
            if ($request->filled('qq')) {
                $extend->qq = $request->qq;
                $extend->qq_verify = 2;
            }
            //生日
            if ($request->filled('birthday')) {
                $extend->birthday = $request->birthday;
            }
            //兴趣爱好
            if ($request->filled('hobbies')) {
                $extend->hobbies = $request->hobbies;
            }
            //职业
            if ($request->filled('profession')) {
                $extend->profession = $request->profession;
            }
            //身高
            if ($request->filled('height')) {
                $extend->height = $request->height;
            }
            //体重
            if ($request->filled('weight')) {
                $extend->weight = $request->weight;
            }
            //星座
            if ($request->filled('constellation')) {
                $extend->constellation = $request->constellation;
            }
            //血型
            if ($request->filled('blood')) {
                $extend->blood = $request->blood;
            }
            //情感
            if ($request->filled('emotion')) {
                $extend->emotion = $request->emotion;
            }
            //收入
            if ($request->filled('income')) {
                $extend->income = $request->income;
            }
            $extend->save();
            return $this->succeed(new MemberUserExtendResource($extend), '会员信息更新成功');
        } catch (\Exception $ex) {
            return $this->exception($ex, '会员扩展信息更新异常');
        }
    }

}

