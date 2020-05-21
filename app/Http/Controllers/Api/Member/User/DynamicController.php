<?php

namespace App\Http\Controllers\Api\Member\User;


use App\Facades\MemberFacade;
use App\Facades\OssFacade;
use App\Facades\PlatformFacade;
use App\Facades\PushFacade;
use App\Http\Controllers\Api\Member\Behavior\ResourceController;
use App\Http\Controllers\Controller;
use App\Http\Resources\MemberDynamicResource;
use App\Http\Resources\MemberUserRateResource;
use App\Models\DealLike;
use App\Models\MemberAttention;
use App\Models\MemberDynamic;
use App\Models\PlatformFile;
use App\Repositories\MemberDynamicRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Input\Input;

/**
 * 动态
 * Class PayController
 */
class DynamicController extends Controller
{
    public function __construct(MemberDynamicRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * 上传
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {

        try {
            $member = $request->user('ApiMember');
            $type = $request->type;
            if (!$request->hasFile('file')) {
                return $this->validation('文件不能为空');
            }
            $content = $request->contents;
            if (!$request->filled('contents')) {
                return $this->validation('内容不能为空');
            }

            \DB::beginTransaction();
//            $files = $request->allFiles();
//            $this->upload($member, $type, $request->file('file'),$content);
            if (PlatformFacade::keyword($content) == false){
                return $this->validation('动态内容涉及关键字被禁用');
            }
            $dynamic = new MemberDynamic();
            $dynamic->member_id = $member->id;
            $dynamic->type = isset($type) ? $type : 0;
            $dynamic->content = PlatformFacade::keyword($content);
            $dynamic->resident = ($member->parameter->is_location== 1)?isset($request->resident)?$request->resident:$member['resident']:'';
//            $dynamic->resident = $request->resident ?? $member['resident'];
            $dynamic->save();

            $files = $request->file('file');
            if (count($files) > 0) {
                foreach ($files as $k => $v) {
                    $result = $this->upload($member, $type, $v, $dynamic);
                }
            }

            //审核图片
            if ($dynamic['type'] == 0 && PlatformFacade::config('platform_image_audit') == 1) {
                MemberFacade::DynamicAudit($dynamic['id']);
            }
            if ($dynamic['type'] == 1 && PlatformFacade::config('platform_video_audit') == 1) {
                MemberFacade::DynamicAudit($dynamic['id']);
            }

            $ret = MemberDynamic::where('id',$dynamic->id)->first();
            switch ($ret['status']) {
                case 0:
                    $status = '审核通过';
                    break;
                case 1:
                    $status = '审核拒绝';
                    break;
                default :
                    $status = '待审核';
                    break;
            }
            $user=$ret->member;
            if ($user->push_token) {
                PushFacade::pushToken($user->push_token, $user->app_platform, $user->nick_name, $status, $type = 'NOTICE', ['type' => 'member', 'id' => $user->id, 'no' => $user->no, 'nickname' => $user->nick_name]);
            }

            return $this->succeed();
        } catch (\Exception $ex) {
            \DB::rollBack();
            return $this->exception($ex);
        }
    }


    /**
     * 上传
     * @param $member
     * @param $type
     * @param $file
     * @param $dynamic
     * @return array|mixed
     */
    private function upload($member, $type, $file, $dynamic)
    {
        try {
            $resp = OssFacade::putImage($file, $member->no);
            if (!$resp['status']) {
                return $this->failure(1, '图片上传失败', $resp);
            }
            $data['member_id'] = $member->id;
            $data['type'] = $type;
            $data['url'] = $resp['data'];
            $data['dynamic'] = $dynamic;
            $result = $this->repository->store($data);
            if (!$result['status']) {
                return $this->failure(1, '保存失败', $result);
            }
            return $result;
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }


    /**
     * 列表
     * @param Request $request
     * @return array
     */
    public function lists(Request $request)
    {
        try {
            \DB::connection()->enableQueryLog();
            $member = $request->user('ApiMember');
            $where = [];
            //0全部1自己2关注的用户
            if ($request->filled('type') && $request->type == 1){
                $where['member_id']=$member->id;
            }
            $lists = MemberDynamic::where($where);
            if ($request->filled('type') && $request->type == 2){
                $attention = MemberAttention::where('member_id',$member->id)->get()->toArray();
                if (count($attention) >0){
                    $toMemberId = array_column($attention,'to_member_id');
                    $lists = $lists->whereIn('member_id', $toMemberId);
                }else{
                    return $this->succeed(MemberDynamicResource::collection([]), '获取列表成功');
                }
            }
            $lists = $lists->with(['member','file','like','comment'])->where(['status'=>0])->orderBy('created_at',"desc")->paginate();
            foreach ($lists as &$v){
                //是否关注/是否点赞
                $attention = MemberAttention::where(['member_id' => $member['id'], 'to_member_id' => $v['member_id']])->value('id');
                $like = DealLike::where(['relevance_type'=>'MemberDynamic','relevance_id'=>$v['id'],'member_id' => $member['id'], 'to_member_id' => $v['member_id']])->value('id');
                $v['attention_type'] = isset($attention) ? 0 : 1;
                $v['like_type'] = isset($like) ? 0 : 1;
                $arr = [];
                //获取点赞的前3个用户
                $like = DealLike::where(['relevance_type'=>'MemberDynamic','relevance_id'=>$v['id']])->limit(3)->with(['member'])->orderBy('created_at',"desc")->get()->toArray();
                if (count($like) > 0){
                    foreach ($like as $val) {
                        $arr[] = $val['member']['head_pic'] ?? config("user.head_pic");
                        }
                }
                $v['like_user'] = $arr;

                //时间转换
                $time = strtotime($v['created_at']);
                $dateTime = Carbon::parse($v['created_at'])->toDateTimeString();
                if ($request->filled('type') && $request->type == 1){
                    $v['day'] = substr($dateTime,'8','2');
                    $mouth = substr($dateTime,'5','2');
                    $v['mouth'] = $this->mouth($mouth);
                    if ( $v['day'] == date("d") && $mouth == date("m")){
                        $v['day'] = "今天";
                        $v['mouth'] = "今天";
                    }
                }else {
                    $v['time_conversion'] = $this->timeConversion($time);
                }
            }
            return $this->succeed(MemberDynamicResource::collection($lists), '获取列表成功');
        } catch (\Exception $ex) {
            return $this->exception($ex, '获取列表，请联系管理员');
        }
    }


    /**
     * 月份转换
     * @param $day
     * @return string
     */
    public function mouth($day){
        switch ($day){
            case 1:
                $d = "一月";
                break;
            case 2:
                $d = "二月";
                break;
            case 3:
                $d = "三月";
                break;
            case 4:
                $d = "四月";
                break;
            case 5:
                $d = "五月";
                break;
            case 6:
                $d = "六月";
                break;
            case 7:
                $d = "七月";
                break;
            case 8:
                $d = "八月";
                break;
            case 9:
                $d = "九月";
                break;
            case 10:
                $d = "十月";
                break;
            case 11:
                $d = "十一月";
                break;
            case 12:
                $d = "十二月";
                break;
        }
        return $d;
    }


    /**
     * 时间转换
     * @param $time
     * @return bool|string
     */
    function timeConversion($time){
        $NowTime = time();
        if($time > $NowTime){ return false; }
        $TimePoor = $NowTime - $time;
        if ($TimePoor <= 10) {
            $str = '刚刚';
        } else if ($TimePoor < 60 && $TimePoor > 10) {
            $str = $TimePoor . '秒之前';
        } else if ($TimePoor >= 60 && $TimePoor <= 60 * 60) {
            $str = floor($TimePoor / 60) . '分钟前';
        } else if ($TimePoor > 60 * 60 && $TimePoor <= 3600 * 24) {
            $str = floor($TimePoor / 3600) . '小时前';
        } else if ($TimePoor > 3600 * 24 && $TimePoor <= 3600 * 24 * 7) {
            if (floor($TimePoor / (3600 * 24)) == 1) {
                $str = "昨天";
            } else if (floor($TimePoor / (3600 * 24)) == 2) {
                $str = "前天";
            } else {
                $str = floor($TimePoor / (3600 * 24)) . '天前';
            }
        } else if ($TimePoor > 3600 * 24 * 7) {
            $str = date("Y-m-d", $time);
        }
        return $str;
    }


    /**
     * 删除
     * @param Request $request
     * @return array
     */
    public function destroy(Request $request)
    {
        try {
            \DB::beginTransaction();
            $member = $request->user('ApiMember');
            if (!$request->filled('id')) {
                return $this->validation('参数不能为空');
            }

            $result = MemberDynamic::where('id', $request->id)->first();
            $result->file()->forceDelete($request->id);
            $result->comment()->forceDelete($request->id);
            $result->like()->forceDelete($request->id);

            $result = MemberDynamic::where('id', $request->id)->forceDelete();

            \DB::commit();
            return $this->succeed($result, "成功删除 $result 条数据");
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->exception($e, '删除失败，请联系管理员');
        }
    }


}

