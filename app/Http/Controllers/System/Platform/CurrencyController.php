<?php

namespace App\Http\Controllers\System\Platform;

use App\Http\Controllers\Controller;
use App\Repositories\PlatformCurrencyRepository;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function __construct(PlatformCurrencyRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('system.platform.currency.index');
    }

    /**
     * @return array
     */
    public function lists()
    {
        try {
            $lists = $this->repository->lists();
            return $this->paginate($lists);
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }

    /**
     *  线下支付方式添加页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('system.platform.currency.create');
    }

    /**
     * 添加支付方式
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            unset($data['file']);
            $result = $this->repository->store($data);
            if ($result['status']) {
                return $this->succeed(null, '添加成功');
            } else {
                return $this->failure(1, '添加失败' . $result['msg']);
            }
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }

    /**
     * 支付方式修改页面
     */
    public function edit(Request $request)
    {
        try {
            $data = $this->repository->find($request->id);
            return view('system.platform.currency.edit', compact('data'));
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    // 支付方式修改
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
