<?php

namespace App\Http\Controllers\System\Deal;

use App\Http\Controllers\Controller;
use App\Repositories\DealGiftRepository;
use App\Repositories\DealVipRepository;
use Illuminate\Http\Request;

/**
 * 礼物赠送
 * Class ViprecordsController
 * @package App\Http\Controllers\System\Deal
 */
class GiftController extends Controller
{
    public function __construct(DealGiftRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('system.deal.gift.index');
    }

    /**
     * @return array
     */
    public function lists()
    {
        try {
            $lists = $this->repository->with(['member','tomember','gift'])->lists();
            return $this->paginate($lists);
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }


    public function create(Request $request)
    {
        return view('system.deal.gift.create' );

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
                return $this->succeed(null, '添加成功');
            } else {
                return $this->failure(1, $result['msg'], $result);
            }
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }

    /**
     */
    public function edit(Request $request)
    {
        try {
            $data = $this->repository->find($request->id);
            return view('system.deal.order.edit', compact('data'));
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    public function update(Request $request)
    {
        try {
            $data = $request->all();
            unset($data['file']);
            $result = $this->repository->update($data);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }
    /**
     */
    public function detail(Request $request)
    {
        try {
            $data = $this->repository->find($request->id);
            return view('system.deal.order.detail', compact('data'));
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }
    /**
     */
    public function audit(Request $request)
    {
        try {
            $data = $request->all();
            $result = $this->repository->audit($data);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 订单强制放行
     */
    public function forcePass(Request $request)
    {
        try {
            $data = $request->all();
            $result = $this->repository->forcePass($data);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }
    /**
     *撤销结算
     */
    public function repealSettle(Request $request)
    {
        try {
            $data = $request->all();
            $result = $this->repository->repealSettle($data);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }


    /**
     * 删除
     * @param Request $request
     * @return array|mixed
     */
    public function destroy(Request $request)
    {
        try {
            $result = $this->repository->destroy($request->ids);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }
}
