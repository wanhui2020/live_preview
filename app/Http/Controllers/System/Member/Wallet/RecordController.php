<?php

namespace App\Http\Controllers\System\Member\Wallet;


use App\Facades\PlatformFacade;
use App\Http\Controllers\Controller;
use App\Models\MemberWalletRecord;
use App\Repositories\FinanceRechargeRepository;
use App\Repositories\FinanceRecordRepository;
use App\Repositories\MemberWalletRecordRepository;
use Illuminate\Http\Request;
use function PHPSTORM_META\type;

class RecordController extends Controller
{
    public function __construct(MemberWalletRecordRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $type = \request()->type;
        $cashName = PlatformFacade::config('cash_name');
        $goldName = PlatformFacade::config('gold_name');
        return view('system.member.wallet.record.index',compact('type','cashName','goldName'));
    }

    /**
     * @return array
     */
    public function lists()
    {
        try {
            $lists = $this->repository->with(['member', 'relevance'])->orderBy('id')->lists();
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
        return view('system.member.wallet.record.create');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $result = $this->repository->store($data);
            if ($result['status']) {
                return $this->succeed(null, '添加成功');
            } else {
                return $this->failure(1, '添加失败', $result);
            }
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }

    /**
     */
    public function edit(Request $request)
    {
        try {
            $data = $this->repository->find($request->id);
            return view('system.member.wallet.record.edit', compact('data'));
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

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

    /**
     * 审核
     */
    public function audit(Request $request)
    {
        try {
            $data = $request->all();
            $result = $this->repository->audit($data);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

}
