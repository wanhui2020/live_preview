<?php
/**
 *系统公告控制器
 */

namespace App\Http\Controllers\System\Base;

use App\Http\Controllers\Controller;
use App\Http\Repositories\SystemNoticeRepository;
use App\Models\SystemNotice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function __construct(SystemNoticeRepository $repository)
    {
        $this->repository = $repository;
    }

    /*
     * 系统公告显示列表
     * */
    public function index()
    {
        return view('system.base.notice.index');
    }

    /*
    * 系统公告显示列表
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
    * 系统公告改变状态
    * */
    public function status(Request $request)
    {
        try {
            $contract = SystemNotice::findOrFail($request->id);
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
     * 添加系统公告渲染视图
     * */
    public function create()
    {
        return view('system.base.notice.create');
    }

    /*
    * 添加系统公告到数据库
    * */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
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
     * 渲染修改系统公告界面
     * */
    public function edit(Request $request)
    {
        try {
            $cons = $this->repository->find($request->id);
            return view('system.base.notice.edit')->with('cons', $cons);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /*
    * 修改系统公告数据到数据库
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

    /**
     * 删除系统公告
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
