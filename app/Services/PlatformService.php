<?php

namespace App\Services;

use App\Models\PlatformBasic;
use App\Models\PlatformCharm;
use App\Models\PlatformConfig;
use App\Models\PlatformCurrency;
use App\Models\PlatformGift;
use App\Models\PlatformKeyword;
use App\Models\PlatformLegal;
use App\Models\PlatformPayment;
use App\Models\PlatformPrice;
use App\Models\PlatformTag;
use App\Models\PlatformVip;
use App\Traits\ResultTrait;
use Illuminate\Support\Facades\Cache;

class PlatformService
{

    use ResultTrait;

    public function __construct()
    {
    }


    /**
     * 平台参数
     */
    public function config($key = null)
    {
        try {
            $data = Cache::rememberForever('PlatformConfig', function () {
                return PlatformConfig::first();
            });
            if ($key) {
                return $data->$key;
            }

            return $data;
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * 关键字过滤
     */
    public function keyword($key)
    {
        try {
            $keywords = PlatformKeyword::where('status', 0)->orderBy("created_at", "DESC")->get();
            foreach ($keywords as $item) {
                //替换
                if ($item->type == 0) {
                    $key = str_replace($item->replace, $item->toreplace, $key);
                }
                //禁用
                if ($item->type == 1) {
                    if (strpos($key, $item->replace) !== false) {
                        return false;
                    }
                }
            }

            return $key;
        } catch (\Exception $ex) {
            $this->exception($ex);

            return false;
        }
    }

    /**
     * 基础数据
     */
    public function basic($type)
    {
        try {
            $data = Cache::rememberForever('PlatformBasic-'.$type,
                function () use ($type) {
                    return PlatformBasic::where('type', $type)->get();
                });

            return $data;
        } catch (\Exception $ex) {
            return false;
        }
    }


    /**
     * 会员标签
     */
    public function tags()
    {
        try {
            $data = Cache::rememberForever('PlatformTag', function () {
                return PlatformTag::where('status', 0)->get();
            });

            return $data;
        } catch (\Exception $ex) {
            return false;
        }
    }


    /**
     * VIP等级
     */
    public function vips()
    {
        try {
            $data = Cache::rememberForever('PlatformVip', function () {
                return PlatformVip::where('status', 0)->get();
            });

            return $data;
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * 平台礼物
     */
    public function gifts()
    {
        try {
            $data = Cache::rememberForever('PlatformGift', function () {
                return PlatformGift::where('status', 0)->orderBy('gold', 'desc')
                    ->get();
            });

            return $data;
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * 支付通道
     */
    public function paymends()
    {
        try {
            $data = Cache::rememberForever('PlatformPayment', function () {
                return PlatformPayment::where('status', 0)->get();
            });

            return $data;
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * 充值价格
     */
    public function prices()
    {
        try {
            $data = Cache::rememberForever('PlatformPrice', function () {
                return PlatformPrice::where('status', 0)->get();
            });

            return $data;
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * 获取魅力参数
     */
    public function charm($key = null, $grade = 0)
    {
        try {
            $data = Cache::rememberForever('PlatformCharm-'.$grade,
                function () use ($grade) {
                    return PlatformCharm::where('status', 0)
                        ->where('grade', $grade)->first();
                });
            if ($key) {
                return $data->$key;
            }

            return $data;
        } catch (\Exception $ex) {
            return false;
        }
    }
}
