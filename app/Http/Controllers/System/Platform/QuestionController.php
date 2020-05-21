<?php

namespace App\Http\Controllers\System\Platform;

use App\Http\Controllers\Controller;
use App\Http\Repositories\PlatformQuestionRepository;
use App\Models\DealOrder;
use App\Models\PlatformProblem;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function __construct(PlatformQuestionRepository $repository)
    {
        $this->repository = $repository;
    }

    /*
     * 工单管理显示列表
     * */
    public function index()
    {
        return view('system.platform.question.index');
    }

    public function lists(Request $request)
    {
        try {
            $lists = $this->repository->with(['relevance', 'parent','childrens'])
                ->withCount(['childrens'])
                ->where(function ($query) use ($request) {
                    $query->where('parent_id', 0);

                    if (isset($request->id)) {
                        $query->where('order_id', $request->id);
                    }
                })
                ->lists();
            return $this->paginate($lists);
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }

    /*
     * 添加工单管理渲染视图
     * */
    public function create()
    {
        return view('system.platform.question.create');
    }

    /*
    * 添加工单管理到数据库
    * */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $user=$request->user('SystemUser');
            if (isset($data['no'])){
                $order=DealOrder::where('no',$data['no'])->first();
                if (isset($order)){
                    $data['order_id']=$order->id;
                }else{
                    return $this->validation('订单编号错误');
                }
                unset($data['no']);
            }
            $result =  $user->questions()->create($data);
            if ($result) {
                return $this->succeed($result);
            }
            return $this->failure(1, $result );
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }
    public function detail(Request $request)
    {
        try {
            $data = $this->repository->find($request->id);
            return view('system.platform.question.detail', compact('data'));
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }
    /*
     * 渲染工单管理修改界面
     * */
    public function edit(Request $request)
    {
        try {
            $cons = $this->repository->find($request->id);
            return view('system.platform.question.edit')->with('cons', $cons);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /*
    * 修改工单管理数据到数据库
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
     * 删除工单管理
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