<?php

namespace App\Http\Controllers\Common;

use App\Facades\MarketFacade;
use App\Http\Controllers\Controller;
use App\Models\PlatformCurrency;
use App\Repositories\DealEntrustRepository;
use App\Repositories\PlatformCurrencyRepository;
use Illuminate\Http\Request;

class MarketController extends Controller
{
    public function __construct(PlatformCurrencyRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 当前价格
     */
    public function nowPrice(Request $request)
    {
        $market=MarketFacade::NowPrice();
        return $this->succeed($market);
    }

}
