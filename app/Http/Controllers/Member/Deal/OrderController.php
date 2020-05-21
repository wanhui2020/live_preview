<?php

namespace App\Http\Controllers\Member\Deal;

use App\Http\Controllers\Member\BaseController;
use App\Http\Repositories\Criteria\MemberCriteria;
use App\Repositories\DealTalkRepository;
use Illuminate\Http\Request;

class OrderController extends BaseController
{
    public function __construct(DealTalkRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
        $this->repository->pushCriteria(new MemberCriteria());

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('member.deal.talk.index');
    }


    /**
     * @return array
     */
    public function lists(Request $request)
    {
        try {
            $lists = $this->repository->with(['entrust', 'currency', 'legal', 'buyRelevance', 'sellRelevance', 'buyWallet', 'sellWallet', 'buyPayee', 'sellPayee'])
                ->withCount(['appeals'])
                ->lists();
            return $this->paginate($lists);
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }


    public function create(Request $request)
    {
        $entrust = DealEntrust::find($request->id);
        if (!isset($entrust)) {
            return $this->failure('记录不存在');
        }
        if ($entrust->type == 0) {
            return view('member.deal.talk.sell', compact('entrust'));
        } else {
            return view('member.deal.talk.buy', compact('entrust'));
        }

    }

    /**
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        try {

            $data = $request->all();
            $data['member_id'] = $request->user('MemberUser')->id;
            $result = $this->repository->store($data);
            if ($result['status']) {
                return $this->succeed(null, '添加成功');
            } else {
                return $this->failure(1, $result['msg'], $result);
            }
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }


    /**
     */
    public function detail(Request $request)
    {
        try {
            $data = $this->repository->find($request->id);
            return view('member.deal.talk.detail', compact('data'));
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 标记支付中处理
     * @param Request $request
     * @return array|mixed
     */
    public function payWait(Request $request)
    {
        try {

            $talk = $this->repository->find($request->id);
            if (!isset($talk)) {
                return $this->failure(1, '订单不存在');
            }
            $data['id'] = $request->id;
            $data['pay_status'] = 1;

            $result = $this->repository->update($data);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }


    /**
     * 订单处理
     */
    public function audit(Request $request)
    {
        try {
            $data = $request->all();
            if ($data['talk_status'] == 0 && !isset($data['money_password'])) {
                return $this->validation('资金密码不为空');
            }
            if ($data['talk_status'] == 3 && !isset($data['pay_payee_id'])) {
                return $this->validation('付款账户不能为空');
            }
            $result = $this->repository->audit($data);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 订单接受
     */
    public function receiving(Request $request)
    {
        try {
            $data = $request->all();
            $result = $this->repository->receiving($data);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 订单支付确认
     */
    public function affirm(Request $request)
    {
        try {
            $data = $request->all();
            $result = $this->repository->affirm($data);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 订单放行
     */
    public function pass(Request $request)
    {
        try {
            $data = $request->all();
            $result = $this->repository->pass($data);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 订单取消
     */
    public function cancel(Request $request)
    {
        try {
            $data = $request->all();
            $result = $this->repository->cancel($data);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 订单未到账
     */
    public function notpay(Request $request)
    {
        try {
            $data = $request->all();
            $result = $this->repository->notpay($data);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 订单推送
     */
    public function push(Request $request)
    {
        try {
            $data = $request->all();
            $result = $this->repository->push($data);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }


    /**
     * 订单强制放行
     */
    public function forcePass(Request $request)
    {
        try {
            $data = $request->all();
            if (!isset($data['money_password'])) {
                return $this->validation('资金密码不为空');
            }
            if (!in_array($data['talk_status'], [1, 5])) {
                return $this->validation('订单强制放行只针对取消订单和退款订单');
            }
            $result = $this->repository->forcePass($data);
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
            $result = $this->repository->destroy($request->ids);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }
}
