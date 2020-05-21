<?php

namespace App\Http\Controllers\Common;

use App\Facades\MarketFacade;
use App\Facades\SmsFacade;
use App\Http\Controllers\Controller;
use App\Models\PlatformCurrency;
use App\Repositories\DealEntrustRepository;
use App\Repositories\PlatformCurrencyRepository;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function __construct()
    {

    }

    /**
     * 发送短信
     */
    public function sendCode(Request $request)
    {
        $resp = SmsFacade::sendCode($request->mobile);
        return $resp;

    }

}
