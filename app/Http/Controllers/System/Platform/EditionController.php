<?php

namespace App\Http\Controllers\System\Platform;

use App\Http\Controllers\Controller;
use App\Http\Repositories\PlatformEditionRepository;
use App\Models\PlatformEdition;
use Illuminate\Http\Request;

//版本管理
class EditionController extends Controller
{
    public function __construct(PlatformEditionRepository $repository)
    {
        $this->repository = $repository;
    }

    /*
     * 显示列表 应用版本
     * */
    public function index()
    {
        return view('system.platform.edition.index');
    }

    /*
    * 应用版本显示列表
    * */
    public function lists()
    {
        try {
            $list = $this->repository->withCount(['members'])->lists();
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
            $edition = PlatformEdition::findOrFail($request->id);
            if ($edition) {
                $edition->status = $edition->status == 1 ? 0 : 1;
                if ($edition->save()) {
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
        return view('system.platform.edition.create');
    }

    /*
    * 添加
    * */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $data['status'] = 1;
            unset($data['file']);
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
     * 修改
     * */
    public function edit(Request $request)
    {
        try {
            $edition = $this->repository->find($request->id);
            return view('system.platform.edition.edit', compact('edition'));
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
