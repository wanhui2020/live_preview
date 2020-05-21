<?php

namespace App\Repositories;

use App\Facades\CommonFacade;
use App\Facades\ImFacade;
use App\Facades\MemberFacade;
use App\Facades\PlatformFacade;
use App\Facades\RiskFacade;
use App\Facades\WechatFacade;
use App\Http\Resources\MemberUserDetailResource;
use App\Http\Resources\MemberUserMyResource;
use App\Models\MemberUser;
use App\Models\MemberWalletCash;
use App\Models\MemberWalletGold;
use App\Models\MemberWalletRecord;
use App\Models\WechatPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WechatPaymentRepository extends BaseRepository
{
    public function model()
    {
        return WechatPayment::class;
    }

    public function lists($addWhere = null)
    {
        return $this->paginate();
    }

    public function store(array $data)
    {
        try {
            $resp = parent::store($data);
            DB::commit();
            return $this->succeed($resp);
        } catch (\Exception $ex) {
            DB::rollBack();
            $this->exception($ex);
            return $this->validation('创建异常，请联系管理员');
        }
    }
}

