<?php

namespace App\Http\Controllers\Member\Base;

use App\Http\Controllers\Member\BaseController;
use App\Http\Repositories\Criteria\RelevanceCriteria;
use App\Http\Repositories\PlatformQuestionRepository;
use App\Models\FinanceRecharge;
use App\Models\DealOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class QuestionController extends BaseController
{
    public function __construct(PlatformQuestionRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
        $this->repository->pushCriteria(new RelevanceCriteria('MemberUser'));
    }

    /**
     * 页面首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::guard('MemberUser')->user();
        return view('member.base.question.index', compact('user'));
    }

    /**
     * @return array
     */
    public function lists(Request $request)
    {
        try {
            $lists = $this->repository->with([ ])
                ->withCount(['childrens'])
                ->where(function ($query) use ($request) {
                    $query->where('parent_id', 0);

                })->orderBy('finish_status', 'desc')->orderBy('created_at', 'desc')
                ->lists();
            return $this->paginate($lists);
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('member.base.question.create');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $user = $request->user('MemberUser');
            if (isset($data['no'])){
                $order=$user->orders()->where('no',$data['no'])->first();
                if (isset($order)){
                    $data['order_id']=$order->id;
                }else{
                    return $this->validation('订单编号错误');
                }
                unset($data['no']);
            }
            $result = $user->questions()->create($data);
            if ($result) {
                return $this->succeed(null, '添加成功');
            } else {
                return $this->failure(1, '添加失败', $result);
            }
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }

    public function detail(Request $request)
    {
        try {
            $data = $this->repository->find($request->id);
            return view('member.base.question.detail', compact('data'));
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 编辑页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        $user = Auth::guard('MemberUser')->user();
        if (!Cache::has('member-' . $user->id)) {
            return redirect()->to('/otc/verify');
        }
        return view('member.base.question.edit', compact('user'));
    }


    /**
     * 修改系统用户
     * @param Request $request
     * @return array|mixed
     */
    public function update(Request $request)
    {
        try {
            $data = $request->all();
            if (!isset($data['type'])) {
                unset($data['type']);
            }
            $result = $this->repository->update($data);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 修改系统用户
     * @param Request $request
     * @return array|mixed
     */
    public function finish(Request $request)
    {
        try {
            $data = $request->all();
            $result = $this->repository->finish($data);
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
            $ids = FinanceRecharge::
            where(function ($query) use ($request) {
                $query->whereIn('id', $request->ids);
                $query->where('pay_status', 9);
            })->pluck('id')->all();
            $result = $this->repository->destroy($ids);
            $result = $this->repository->destroy($request->ids);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

}

