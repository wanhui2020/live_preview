<?php

namespace App\Http\Controllers\Api\Member\Wallet;


use App\Http\Controllers\Controller;
use App\Http\Resources\WalletCashResource;
use App\Http\Resources\WalletGoldResource;
use App\Http\Resources\WalletResource;
use App\Repositories\MemberUserRepository;
use Illuminate\Http\Request;

/**
 * 金币钱包
 */
class CashController extends Controller
{
    /**
     *  我的钱包
     */
    public function detail(Request $request, MemberUserRepository $memberUserRepository)
    {
        try {
            $member = $request->user('ApiMember');
            return $this->succeed(new WalletCashResource($member->cash), '获取我的金币钱包成功');
        } catch (\Exception $ex) {
            return $this->exception($ex, '我的钱包获取异常，请联系管理员');
        }
    }

}

