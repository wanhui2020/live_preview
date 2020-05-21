<?php

namespace App\Http\Controllers\System\Platform;

use App\Http\Controllers\Controller;
use App\Http\Repositories\PlatformConfigRepository;
use App\Models\MemberUserParameter;
use App\Models\PlatformConfig;
use App\Repositories\SystemConfigRepository;
use App\Models\SystemConfig;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ConfigController extends Controller
{

    public function __construct(PlatformConfigRepository $rechargeRepository)
    {
        $this->repository = $rechargeRepository;
    }

    /**
     *  平台参数修改页面
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit()
    {
        try {
            $config = PlatformConfig::firstOrCreate(['id' => 1]);
            return view('system.platform.config.edit', compact('config'));
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }

    /**
     *  保存修改的平台参数
     * @param Request $request
     * @return array
     */
    public function update(Request $request)
    {
        try {
            $data = $request->all();
            \Cache::forget('PlatformConfig');
            if ($data['is_screencap'] == 1){
                $parameter = new MemberUserParameter();
                $parameter->is_screencap = 1;
                $parameter->save();
            }

            $arr = [
                'middleman_income_rate'=>$data['invite_recharge_rate'],
                'middleman_recharge_rate'=>$data['invite_consumption_rate'],
                'recommender_income_rate'=>$data['recommender_recharge_rate'],
                'recommender_recharge_rate'=>$data['recommender_income_rate'],
            ];
            \DB::table('member_user_rate')->where(['reward_customization' => 0, 'status' => 0])->update($arr);

            $result = $this->repository->update($data);
            return $result;
        } catch (\Exception $e) {
            return $this->exception($e);
        }
    }
}
