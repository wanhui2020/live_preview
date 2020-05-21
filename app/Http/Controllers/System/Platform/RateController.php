<?php

namespace App\Http\Controllers\System\Platform;

use App\Http\Controllers\Controller;
use App\Http\Repositories\PlatformRateRepository;
use App\Models\PlatformRate;
use Illuminate\Http\Request;

class RateController extends Controller
{
    public function __construct(PlatformRateRepository $repository)
    {
        $this->repository = $repository;
    }

    /*
     * 显示列表
     * */
    public function index(Request $request)
    {
        return view('system.platform.rate.index');
    }

    /*
    * 费率列表
    * */
    public function lists()
    {
        try {
            $list = $this->repository->with(['relevance'])->lists();
            return $this->paginate($list);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /*
     * 编辑
     * */
    public function edit(Request $request)
    {
        try {
            $data = $this->repository->find($request->id);
            return view('system.platform.rate.edit')->with('data', $data);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /*
    * 更新
    * */
    public function update(Request $request)
    {
        try {
            $data = $request->all();
            $result = $this->repository->update($data);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

}