<?php
/**
 *banner图
 */

namespace App\Http\Controllers\System\Base;

use App\Http\Controllers\Controller;
use App\Http\Repositories\SystemBannerRepository;
use App\Models\SystemBanner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function __construct(SystemBannerRepository $repository)
    {
        $this->repository = $repository;
    }

    /*
     * banner图显示列表
     * */
    public function index()
    {
        return view('system.base.banner.index');
    }

    /*
    * banner图显示列表
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
    * banner图改变状态
    * */
    public function status(Request $request)
    {
        try {
            $contract = SystemBanner::findOrFail($request->id);
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
     * banner图渲染视图
     * */
    public function create()
    {
        return view('system.base.banner.create');
    }

    /*
    * 添加banner图到数据库
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
     * 渲染banner图修改界面
     * */
    public function edit(Request $request)
    {
        try {
            $cons = $this->repository->find($request->id);
            return view('system.base.banner.edit')->with('cons', $cons);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /*
    * 修改banner图数据到数据库
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
     * 删除banner图
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
