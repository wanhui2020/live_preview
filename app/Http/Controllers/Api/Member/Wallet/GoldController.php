<?php

namespace App\Http\Controllers\Api\Member\Wallet;


use App\Http\Controllers\Controller;
use App\Http\Resources\WalletGoldResource;
use App\Http\Resources\WalletResource;
use App\Repositories\MemberUserRepository;
use Illuminate\Http\Request;

/**
 * 金币钱包
 */
class GoldController extends Controller
{
    /**
     *  金币钱包
     */
    public function detail(Request $request, MemberUserRepository $memberUserRepository)
    {
        try {
            $member = $request->user('ApiMember');
            return $this->succeed(new WalletGoldResource($member->gold), '获取我的金币钱包成功');
        } catch (\Exception $ex) {
            return $this->exception($ex, '我的金币钱包获取异常，请联系管理员');
        }
    }
}

