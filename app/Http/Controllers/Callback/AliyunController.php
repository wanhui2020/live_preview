<?php

namespace App\Http\Controllers\Callback;

use App\Facades\DealFacade;
use App\Facades\MemberFacade;
use App\Facades\PlatformFacade;
use App\Http\Controllers\Controller;
use App\Models\DealTalk;
use App\Models\MemberUser;
use App\Utils\Helper;
use Illuminate\Http\Request;

class AliyunController extends Controller
{

    public function green(Request $request)
    {
        try {
            $key='se4L7Qsz$XkTY6Y7VYCDqTFf-1rTiIj';
            $result = $request->all();
            $this->logs('过滤回调', $result);

            return ['ActionStatus' => 'OK', 'ErrorCode' => 0, 'ErrorInfo' => ''];
//
        } catch (\Exception $exception) {
            return $this->exception($exception, 'IM回调异常');
        }
    }
}
