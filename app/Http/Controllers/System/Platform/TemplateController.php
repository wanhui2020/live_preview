<?php

namespace App\Http\Controllers\System\Platform;

use App\Http\Controllers\Controller;
use App\Http\Repositories\PlatformTemplateRepository;
use App\Models\PlatformTemplate;
use Illuminate\Http\Request;
//短信模板
class TemplateController extends Controller
{
    public function __construct(PlatformTemplateRepository $repository)
    {
        $this->repository = $repository;
    }

    /*
     * 短信模板显示列表
     * */
    public function index()
    {
        return view('system.platform.template.index');
    }

    /*
    * 短信模板显示列表
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
            $contract = PlatformTemplate::findOrFail($request->id);
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
     * 添加短信模板渲染视图
     * */
    public function create()
    {
        return view('system.platform.template.create');
    }

    /*
    * 添加短信模板到数据库
    * */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $type = $request->type;
            $isset_type = PlatformTemplate::where('type', $type)->first();
            if ($isset_type) {
                return $this->failure(1, '不能添加已有的类型(数字)');
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
            return view('system.platform.template.edit')->with('cons', $cons);
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
     * 删除创建的短信模板
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
