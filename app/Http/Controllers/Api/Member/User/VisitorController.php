<?php

namespace App\Http\Controllers\Api\Member\User;


use App\Http\Controllers\Controller;
use App\Http\Resources\MemberUserVisitorResource;
use App\Models\MemberUser;
use App\Models\MemberVisitor;
use App\Repositories\MemberUserRepository;
use App\Repositories\MemberVisitorRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 访问接口
 * Class PayController
 */
class VisitorController extends Controller
{


    /**
     * 访问记录
     */
    public function lists(Request $request, MemberUserRepository $userRepository)
    {
        try {
            $member = $request->user('ApiMember');
            $list = $userRepository->where(function ($query) use ($member, $request) {
                $query->where('status', 0);
                $query->whereHas('toVisitors', function ($query) use ($member) {
                    $query->where('status', 0);
                    $query->where('member_id', $member->id);
                });
            })->paginate(10);

            return $this->paginate(MemberUserVisitorResource::collection($list), '返回访问记录成功');
        } catch (\Exception $ex) {
            return $this->exception($ex, '获取访问记录异常');
        }
    }


    /**
     * 删除访问记录
     */
    public function del(Request $request, MemberVisitorRepository $visitorRepository)
    {
        try {
            $member = $request->user('ApiMember');
            //删除标记
            $visitors = MemberVisitor::where('member_id', $member->id)->update(['status' => 1]);
            return $this->succeed($visitors, '删除成功');
        } catch (\Exception $ex) {
            return $this->exception($ex, '删除访问记录异常');
        }
    }
}

