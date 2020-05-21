<?php

namespace App\Http\Controllers\System\Platform;

use App\Http\Controllers\Controller;
use App\Http\Repositories\PlatformKeywordRepository;
use App\Models\PlatformKeyword;
use Illuminate\Http\Request;

//关键字
class KeywordController extends Controller
{
    public function __construct(PlatformKeywordRepository $repository)
    {
        $this->repository = $repository;
    }

    /*
     *
     * */
    public function index()
    {
        return view('system.platform.keyword.index');
    }

    /*
    * 显示列表
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
    * 改变状态
    * */
    public function status(Request $request)
    {
        try {
            $keyword = PlatformKeyword::findOrFail($request->id);
            if ($keyword) {
                $keyword->status = $keyword->status == 0 ? 1 : 0;
                if ($keyword->save()) {
                    return $this->succeed('操作成功');
                } else {
                    return $this->validation('操作失败');
                }
            } else {
                return $this->validation('用户不存在');
            }
        } catch (\Exception $ex) {
            return $this->validation('操作失败', $ex);
        }
    }

    /*
     * 添加
     * */
    public function create()
    {
        return view('system.platform.keyword.create');
    }

    /*
    * 添加
    * */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $data['status'] = 0;
            $result = $this->repository->store($data);
            if ($result['status']) {
                return $this->succeed($result);
            }
            return $this->failure(1, $result['msg']);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /*
     *
     * */
    public function edit(Request $request)
    {
        try {
            $cons = $this->repository->find($request->id);
            return view('system.platform.keyword.edit')->with('cons', $cons);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /*
    * 修改
    * */
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

    /*
     * 删除
     * */
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
