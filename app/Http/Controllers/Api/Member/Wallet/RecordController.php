<?php

namespace App\Http\Controllers\Api\Member\Wallet;


use App\Http\Controllers\Controller;
use App\Http\Resources\WalletRecordResource;
use App\Models\MemberWalletRecord;
use App\Repositories\MemberWalletRecordRepository;
use Illuminate\Http\Request;

/**
 * 资金流水
 */
class RecordController extends Controller
{
    /**
     *  资金流水
     */
    public function lists(Request $request, MemberWalletRecordRepository $recordRepository)
    {
        try {
            $member = $request->user('ApiMember');
            $list = $recordRepository->where(function ($query) use ($request, $member) {
                $query->where('member_id', $member->id);
                $query->where('status', 0);
                if ($request->filled('type')) {
                    $query->where('type', $request->type);
                }
                if ($request->filled('relevance_type')) {
                    $query->where('relevance_type', $request->relevance_type);
                }
            })->paginate();
            return $this->paginate(WalletRecordResource::collection($list), '获取资金流水成功');
        } catch (\Exception $e) {
            return $this->exception($e, '资金流水获取异常，请联系管理员');
        }
    }


    /**
     * 收益
     */
    public function getRecords(Request $request, MemberWalletRecordRepository $recordRepository)
    {

        try {
            $member = $request->user('ApiMember');
            $type = [19, 20, 50, 51];
            $query=MemberWalletRecord::where(['status'=>0,'member_id'=>$member->id])->whereIn('type',$type)->select(['id','money',\DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") AS create_time')]);

            $list=\DB::table(\DB::raw("({$query->toSql()}) as A"))->mergeBindings($query->getQuery())
                ->select([\DB::raw("sum(money) as money"),\DB::raw("count(id) as number"),'create_time'])->groupBy('create_time')
                ->orderBy("create_time",'desc')->paginate();

            return $this->succeed($list->toArray()['data'], '获取收益流水成功');
        } catch (\Exception $ex) {
            return $this->exception($ex, '收益流水获取异常，请联系管理员');
        }
    }


    /**
     * 获取用户充值
     * @param Request $request
     * @return array
     */
    public function getRecharge(Request $request, MemberWalletRecordRepository $recordRepository){
        try {
            $member = $request->user('ApiMember');
            $type=[31,11];
            $list=MemberWalletRecord::where(['status'=>0])->whereIn('type',$type)->limit(20)->get();
            foreach ($list as $v){
                $v['type']='recharge';
            }

            return $this->succeed(WalletRecordResource::collection($list), '获取用户充值成功');
        } catch (\Exception $ex) {
            return $this->exception($ex, '获取异常，请联系管理员');
        }
    }
}

