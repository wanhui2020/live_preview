<?php

namespace App\Repositories;

use App\Models\DealMessage;
use App\Models\DealUnlock;
use App\Models\MemberWalletGold;
use App\Models\MemberWalletRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DealMessageRepository extends BaseRepository
{
    public function model()
    {
        return DealMessage::class;
    }

    public function lists($addWhere = null)
    {
        $where = function ($query) {
            //发送方
            if (request('key') != null) {
                if (request('key')) {
                    $query->wherehas('member', function ($query) {
                        $query->where('no', 'like', '%' . request('key') . '%')
                            ->orWhere('nick_name', 'like', '%' . request('key') . '%')
                            ->orWhere('mobile', 'like', '%' . request('key') . '%');
                    });
                }
            }
            //接受方
            if (request('keys') != null) {
                if (request('keys')) {
                    $query->wherehas('tomember', function ($query) {
                        $query->where('no', 'like', '%' . request('keys') . '%')
                            ->orWhere('nick_name', 'like', '%' . request('keys') . '%')
                            ->orWhere('mobile', 'like', '%' . request('keys') . '%');
                    });
                }
            }
            if (request('received') != '') {
                $query->where('total',  request('received'));
            }

        };
        $this->where($where);
        if ($addWhere) {
            $this->where($addWhere);
        }
        return $this->paginate();
    }


    public function store(array $data)
    {
        DB::beginTransaction();
        try {
            if ($data['member_id'] == $data['to_member_id']) {
                DB::rollBack();
                return $this->validation('不能给自己发信息');
            }

            $chat = DealUnlock::firstOrNew(['member_id' => $data['member_id'], 'to_member_id' => $data['to_member_id']]);
            if (!empty($chat->end_time) && Carbon::now()->lt(Carbon::parse($chat->end_time))) {
                DB::rollBack();
                return $this->succeed($chat, '解锁未到期，不需要解锁');
            }
            $chat->save();
            $goldFrom = MemberWalletGold::where('member_id', $chat->member_id)->lockForUpdate()->first();
            if (!isset($goldFrom)) {
                DB::rollBack();
                return $this->validation('发送方钱包异常');
            }
            if ($goldFrom->usable < $chat->gold) {
                DB::rollBack();
                return $this->validation('金币不足，请充值');
            }
            $goldFrom->usable = $goldFrom->usable - $chat->gold;
            if ($goldFrom->lock >= $chat->gold) {
                $goldFrom->lock = $goldFrom->lock - $chat->gold;
            }
            $goldFrom->save();
            //会员聊天解锁支出
            $goldFrom->records()->save(new MemberWalletRecord(['type' => 47, 'member_id' => $chat->member_id, 'money' => -$chat->gold, 'surplus' => $goldFrom->balance]));

            if ($chat->received > 0) {
                $goldTo = MemberWalletGold::where('member_id', $chat->to_member_id)->lockForUpdate()->first();
                if (!isset($goldTo)) {
                    DB::rollBack();
                    return $this->validation('接收方钱包异常');
                }
                $goldTo->usable = $goldTo->usable + $chat->received;
                $goldTo->save();

                //会员聊天解锁收入
                $goldTo->records()->save(new MemberWalletRecord(['type' => 38, 'member_id' => $chat->to_member_id, 'money' => $chat->received, 'surplus' => $goldTo->balance]));
            }
            DB::commit();
            return $this->succeed($chat, '礼物赠送成功');
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->exception($ex);
        }
    }

}
