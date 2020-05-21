<?php

namespace App\Http\Controllers\Api\Member\User;


use App\Facades\BaseFacade;
use App\Facades\OssFacade;
use App\Facades\PlatformFacade;
use App\Http\Controllers\Controller;
use App\Http\Resources\MemberUserSearchResource;
use App\Http\Resources\MemberUserSelfieResource;
use App\Models\MemberUserSelfie;
use App\Models\PlatformConfig;
use App\Repositories\MemberUserSelfieRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * 自拍认证
 * Class PayController
 */
class SelfieController extends Controller
{
    /**
     * 认证申请
     */
    public function store(Request $request, MemberUserSelfieRepository $repository)
    {
        try {
            $member = $request->user('ApiMember');

            $selfie = MemberUserSelfie::firstOrNew(['member_id' => $member->id]);

            if ($member->is_selfie == 0) {
                return $this->validation('自拍认证已通过不可修改');
            } else {
                if (PlatformFacade::config('selfie_realname') == 0) {
                    if ($member->is_real != 0) {  //自拍认证不等于0  表示未认证
                        return $this->validation('请先进行实名认证！');
                    }
                }
            }


            //认证图片
            if ($request->filled('picture')) {
                $picture = $request->file('picture');
                if (!isset($picture)) {
                    return $this->validation('上传文件不存在');
                }
                if (!$picture->isValid()) {
                    return $this->validation('文件异常');
                }
                $file = OssFacade::putFile($picture, $member->no);
                if (!$file['status']) {
                    return $this->failure(1, '上传认证图片失败', $file);
                }
                $selfie->picture = $file['data'];
            }
            //认证视频
            if ($request->filled('video')) {
                $video = $request->file('video');
                if (!isset($video)) {
                    return $this->validation('上传文件不存在');
                }
                if (!$video->isValid()) {
                    return $this->validation('文件异常');
                }
                $file = OssFacade::putFile($video, $member->no);
                if (!$file['status']) {
                    return $this->failure(1, '上传认证视频失败', $file);
                }
                $selfie->video = $file['data'];
            }
            //承诺条款
            if ($request->filled('undertaking')) {
                $undertaking = $request->file('undertaking');
                if (!isset($undertaking)) {
                    return $this->validation('上传文件不存在');
                }
                if (!$undertaking->isValid()) {
                    return $this->validation('文件异常');
                }
                $file = OssFacade::putFile($undertaking, $member->no);
                if (!$file['status']) {
                    return $this->failure(1, '上传承诺条款失败', $file);
                }
                $selfie->undertaking = $file['data'];
            }

            //认证确认
            if ($request->filled('confirm')) {
                $selfie->status = 8;
                $member->is_selfie = 8;
                $member->save();
            }

            if ($selfie->save()) {
                if ($member->is_selfie == 8) {
                    return $this->succeed(new MemberUserSelfieResource($selfie), '自拍认证申请成功，请耐心等待');
                }
                return $this->succeed(new MemberUserSelfieResource($selfie), '上传成功');
            }
            return $this->validation('申请自拍认证失败，请联系管理员');
        } catch (\Exception $ex) {
            return $this->exception($ex, '自拍认证获取异常，请联系管理员');
        }
    }

    /**
     * 认证记录
     */
    public function detail(Request $request)
    {
        try {
            $member = $request->user('ApiMember');
            if (!isset($member->selfie)) {
                return $this->validation('自拍认证未申请');
            }
            return $this->succeed(new MemberUserSelfieResource($member->selfie), '获取认证信息成功');

        } catch (\Exception $ex) {
            return $this->exception($ex, '自拍认证获取异常，请联系管理员');
        }
    }

}

