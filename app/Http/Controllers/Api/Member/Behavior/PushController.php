<?php

namespace App\Http\Controllers\Api\Member\Behavior;


use App\Facades\OssFacade;
use App\Facades\PushFacade;
use App\Http\Controllers\Controller;
use App\Http\Repositories\PlatformTagRepository;
use App\Http\Resources\MemberResourceResource;
use App\Http\Resources\MemberTagResource;
use App\Http\Resources\PlatformTagResource;
use App\Models\MemberTag;
use App\Models\MemberUser;
use App\Models\PlatformTag;
use App\Repositories\DealGiftRepository;
use App\Repositories\MemberAttentionRepository;
use App\Repositories\MemberResourceRepository;
use App\Repositories\MemberTagRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 推送
 * Class PayController
 */
class PushController extends Controller
{
    /**
     * 获取推送标签列表
     */
    public function lists(Request $request)
    {
        try {
            $lists = PushFacade::tagLists();
            return $this->succeed($lists, '获取成功!');
        } catch (\Exception $e) {
            return $this->exception($e, '获取失败，请联系管理员');
        }
    }


    /**
     * 根据某个token获取标签
     */
    public function getTag(Request $request)
    {
        try {
            $member = $request->user('ApiMember');
            if ($member['push_token']){
                $lists = PushFacade::getTag($member['push_token'], $keyType = 'DEVICE');
            }

            return $this->succeed($lists, '获取成功!');
        } catch (\Exception $e) {
            return $this->exception($e, '获取失败，请联系管理员');
        }
    }

}

