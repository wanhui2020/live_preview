<?php

namespace App\Http\Controllers\Api\Member\Deal;


use App\Http\Controllers\Controller;
use App\Repositories\DealGiftRepository;
use App\Repositories\DealViewRepository;
use Illuminate\Http\Request;

/**
 * 资源查看 图片查看 视频查看
 * Class TalkController
 */
class ViewController extends Controller
{
    /**
     * 资源查看 图片查看 视频查看
     */
    public function store(Request $request, DealViewRepository $viewRepository)
    {
        try {
            $member = $request->user('ApiMember');
            $resource_id = $request->resource_id; //资源id

            if (!$request->filled('resource_id')) {
                return $this->validation('请传入资源resource_id');
            }


            $data = [
                'member_id' => $member->id,
                'resource_id' => $resource_id,
            ];

            $dealgift = $viewRepository->store($data);
            if ($dealgift['status']) {
                return $this->succeed(null, '资源查看成功');
            } else {
                return $this->validation($dealgift['msg']);
            }
        } catch (\Exception $ex) {
            return $this->exception($ex, '资源查看异常，请联系管理员');
        }
    }
}

