<?php

namespace App\Http\Controllers\System\Base;

use App\Http\Controllers\Controller;
use App\Http\Repositories\SystemLogRepository;
use App\Repositories\SystemLoginRepository;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function __construct()
    {

    }


    /*
    * 系统业务日志列表
    * */
    public function business(Request $request, SystemLogRepository $repository)
    {
        try {
            if ($request->isMethod('GET')) {
                return view('system.base.logs.business');
            }
            $list = $repository->with(['relevance'])->lists();
            return $this->paginate($list);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
    * 系统业务日志列表
    * */
    public function logins(Request $request, SystemLoginRepository $repository)
    {
        try {
            if ($request->isMethod('GET')) {
                return view('system.base.logs.login');
            }
            $list = $repository->with(['relevance'])->lists();
            return $this->paginate($list);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }
}
