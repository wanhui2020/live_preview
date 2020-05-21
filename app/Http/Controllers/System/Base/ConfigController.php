<?php
/**
 * 系统参数
 */

namespace App\Http\Controllers\System\Base;
use App\Http\Controllers\Controller;
use App\Repositories\SystemConfigRepository;
use Illuminate\Http\Request;
use App\Models\SystemConfig;

class ConfigController extends Controller
{
    public function __construct(SystemConfigRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * 页面首页
     */
    public function index(Request $request)
    {
       return $this->edit($request);
    }
    /**
     * 页面首页
     */
    public function edit(Request $request)
    {
        try {
            $config = SystemConfig::first();
            return view('system.base.config.edit')->with('config', $config);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /**
     * 更新系统参数配置
     * @param Request $request
     * @return array|mixed
     */
    public function update(Request $request)
    {
        try {
            $data = $request->all();
            $result = $this->repository->update($data);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

}
