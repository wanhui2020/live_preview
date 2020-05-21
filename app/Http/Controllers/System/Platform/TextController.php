<?php

namespace App\Http\Controllers\System\Platform;

use App\Http\Controllers\Controller;
use App\Http\Repositories\PlatformTagRepository;
use App\Http\Repositories\PlatformTextRepository;
use App\Models\PlatformTag;
use App\Models\PlatformText;
use Illuminate\Http\Request;

//文本维护
class TextController extends Controller
{
    public function __construct(PlatformTextRepository $repository)
    {
        $this->repository = $repository;
    }

    /*
     * 显示列表
     * */
    public function index()
    {
        return view('system.platform.text.index');
    }

    /*
    * 列表
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
            $contract = PlatformText::findOrFail($request->id);
            if ($contract) {
                $contract->status = $contract->status == 1 ? 0 : 1;
                if ($contract->save()) {
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
        return view('system.platform.text.create');
    }

    /*
    * 添加
    * */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $text = PlatformText::where('type',$data['type'])->first();
            if (isset($text)){
                return $this->validation('请勿重复添加同一类型');
            }
            $data['status'] = 0;
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
            $cons = $this->repository->find($request->id);
            return view('system.platform.text.edit')->with('cons', $cons);
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
