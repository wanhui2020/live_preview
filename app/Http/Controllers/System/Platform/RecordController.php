<?php

namespace App\Http\Controllers\System\Platform;

use App\Http\Controllers\Controller;
use App\Http\Repositories\PlatformRecordRepository;
use App\Models\PlatformRecord;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    public function __construct(PlatformRecordRepository $repository)
    {
        $this->repository = $repository;
    }

    /*
     * 显示列表 短信记录
     * */
    public function index()
    {
        return view('system.platform.record.index');
    }

    /*
    * 短信记录显示列表
    * */
    public function lists()
    {
        try {
            $list = $this->repository->lists();
            return $this->paginate($list);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }


    /*
     * 添加短信记录渲染视图
     * */
    public function create()
    {
        return view('system.platform.record.create');
    }

    /*
    * 添加短信记录到数据库
    * */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $data['status'] = 0;
            $newmultiple = $request->multiple;
            $res = PlatformRecord::where('multiple', $newmultiple)->first();
            if ($res) {
                return $this->failure(1, '不能添加已有的倍数');
            } else {
                $result = $this->repository->store($data);
                if ($result['status']) {
                    return $this->succeed($result);
                }
                return $this->failure(1, $result['msg']);
            }
        } catch (\Exception $ex) {

            return $this->exception($ex);
        }
    }

    /*
     * 渲染修改界面
     * */
    public function edit(Request $request)
    {
        try {
            $cons = $this->repository->find($request->id);
            return view('system.platform.record.edit')->with('cons', $cons);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /*
    * 修改数据到数据库
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

    /*
     * 删除创建的短信记录
     * */
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