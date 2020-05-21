<?php

namespace App\Http\Controllers\System\Deal;

use App\Http\Controllers\Controller;
use App\Repositories\DealLikeRepository;
use Illuminate\Http\Request;

/**
 * 会员点赞
 * Class LoginsController
 * @package App\Http\Controllers\System\Member
 */
class LikeController extends Controller
{
    public function __construct(DealLikeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 页面首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('system.deal.like.index');
    }

    /**
     * 数据列表
     * @return array
     */
    public function lists(Request $request)
    {
        try {
            $id = $request->id;
            $list = $this->repository->with(['member:id,nick_name,no,mobile', 'tomember:id,nick_name,no,mobile']);
            if ($request->filled('id')){
                $list = $list->where(['relevance_type'=>'','relevance_id'=>$id]);
            }
            $list = $list->lists();
            return $this->paginate($list);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('system.deal.like.create');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $result = $this->repository->store($data);
            if ($result['status']) {
                return $this->succeed($result);
            }
            return $this->failure(1, $result['msg']);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * @param Request $request
     * @return array|mixed
     */
    public function destroy(Request $request)
    {
        try {
            $result = $this->repository->forceDelete($request->ids);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }
}

