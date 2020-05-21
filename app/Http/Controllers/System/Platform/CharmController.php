<?php

namespace App\Http\Controllers\System\Platform;

use App\Http\Controllers\Controller;
use App\Http\Repositories\PlatformCharmRepository;
use App\Models\MemberUser;
use App\Models\MemberUserRate;
use App\Models\PlatformCharm;
use App\Models\PlatformVip;
use Illuminate\Http\Request;

/**
 * 魅力管理
 * Class CharmController
 * @package App\Http\Controllers\System\Platform
 */
class CharmController extends Controller
{
    public function __construct(PlatformCharmRepository $repository)
    {
        $this->repository = $repository;
    }

    /*
     * 显示列表
     * */
    public function index()
    {
        return view('system.platform.charm.index');
    }

    /*
    * 显示列表
    * */
    public function lists()
    {
        try {
            $list = $this->repository->lists();
            return $this->paginate($list);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /*
    * 改变状态
    * */
    public function status(Request $request)
    {
        try {
            $contract = PlatformVip::findOrFail($request->id);
            if ($contract) {
                $contract->status = $contract->status == 1 ? 0 : 1;
                if ($contract->save()) {
                    return $this->succeed('操作成功');
                } else {
                    return $this->validation('操作失败');
                }
            } else {
                return $this->validation('用户不存在');
            }
        } catch (\Exception $ex) {
            return $this->validation('操作失败', $ex);
        }
    }

    /*
     * 添加
     * */
    public function create()
    {
        return view('system.platform.charm.create');
    }

    /*
    * 添加
    * */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $data['status'] = 0;
            $data['view_picture_fee'] = 0;
            $data['view_video_fee'] = 0;
            unset($data['file']);
            $result = $this->repository->store($data);
            if ($result['status']) {
                return $this->succeed($result);
            }
            return $this->failure(1, $result['msg']);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /*
     * 修改
     * */
    public function edit(Request $request)
    {
        try {
            $charm = $this->repository->find($request->id);
            return view('system.platform.charm.edit', compact('charm'));
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /*
    * 修改
    * */
    public function update(Request $request)
    {
        try {
            $data = $request->all();
            unset($data['file']);
            $data['view_picture_fee'] = 0;
            $data['view_video_fee'] = 0;
            foreach (PlatformCharm::all() as $item) {
                \Cache::forget('PlatformCharm-'.$item->grade);
            }

            $user = MemberUser::where(['status' => 0, 'charm_grade' => $data['grade']])->get()->toArray();
            $user = array_column($user, 'id');

            $arr = [
                'gift_rate' => $data['gift_rate'],
                'chat_rate' => $data['chat_rate'],
                'text_rate' => $data['text_rate'],
                'voice_rate' => $data['voice_rate'],
                'video_rate' => $data['video_rate'],
                'view_picture_rate' => $data['view_picture_rate'],
                'view_video_rate' => $data['view_video_rate'],
//                'middleman_income_rate'=>$data[''],
//                'middleman_recharge_rate'=>$data[''],
//                'chat_fee'=>$data[''],
                'text_fee' => $data['text_fee'],
                'voice_fee' => $data['voice_fee'],
                'video_fee' => $data['video_fee'],
                'view_picture_fee' => $data['view_picture_fee'],
                'view_video_fee' => $data['view_video_fee'],
            ];
            $rate = \DB::table('member_user_rate')->where(['is_custom' => 0, 'status' => 0])->whereIn('member_id', $user)->update($arr);

            $result = $this->repository->update($data);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    /*
     * 删除
     * */
    public function destroy(Request $request)
    {
        try {
            if ($request->ids[0] == 1){
                return $this->succeed('','该等级魅力不能删除');
            }
            $result = $this->repository->forceDelete($request->ids);
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }
}
