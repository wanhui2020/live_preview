<?php

namespace App\Http\Controllers\Api\Member\Platform;


use AlibabaCloud\Push\Push;
use App\Facades\PlatformFacade;
use App\Http\Controllers\Controller;
use App\Http\Repositories\PlatformMessageRepository;
use App\Http\Repositories\PlatformNoticeRepository;
use App\Http\Resources\PlatformBasicResource;
use App\Http\Resources\PlatformConfigResource;
use App\Http\Resources\PlatformEdtionResource;
use App\Http\Resources\PlatformGiftResource;
use App\Http\Resources\PlatformMessageResource;
use App\Http\Resources\PlatformNoticeResource;
use App\Http\Resources\PlatformPaymentChannelResource;
use App\Http\Resources\PlatformPaymentResource;
use App\Http\Resources\PlatformPriceResource;
use App\Http\Resources\PlatformTagResource;
use App\Http\Resources\PlatformVipResource;
use App\Models\MemberUser;
use App\Models\PlatformCharm;
use App\Models\PlatformConfig;
use App\Models\PlatformEdition;
use App\Models\PlatformNotice;
use App\Models\PlatformNoticeDetail;
use App\Models\PlatformPaymentChannel;
use App\Models\PlatformPrice;
use App\Models\PlatformSendMessage;
use App\Models\PlatformText;
use App\Models\PlatformVip;
use App\Repositories\DealGiftRepository;
use App\Repositories\PlatformPaymentChannelRepository;
use App\Repositories\PlatformPaymentRepository;
use App\Services\ImService;
use Illuminate\Http\Request;

/**
 * 平台参数
 * Class PayController
 */
class PlatformController extends Controller
{
    /**
     * 平台参数
     */
    public function config(Request $request)
    {
        try {
            $config = PlatformFacade::config();
            return $this->succeed(new PlatformConfigResource($config), '获取成功！');
        } catch (\Exception $ex) {
            return $this->exception($ex, '获取异常，请联系管理员');
        }
    }

    /**
     * 基础数据
     */
    public function basic(Request $request)
    {
        try {
            if (!$request->filled('type')) {
                return $this->validation('基础数据类型不能为空');
            }
            $basics = PlatformFacade::basic($request->type);
            return $this->succeed(PlatformBasicResource::collection($basics), '获取成功！');
        } catch (\Exception $ex) {
            return $this->exception($ex, '获取异常，请联系管理员');
        }
    }

    /**
     * 版本获取
     */
    public function edtion(Request $request)
    {
        try {
            if (!$request->filled('type')) {
                return $this->validation('Type参数不能为空!');
            }
            $edtion = PlatformEdition::where('type', $request->type)->where('status', 0)->first();
            if (!isset($edtion)) {
                return $this->validation('获取失败！');
            }
            return $this->succeed(new  PlatformEdtionResource($edtion), '版本管理获取成功！');

        } catch (\Exception $e) {
            return $this->exception($e, '版本管理获取异常，请联系管理员');
        }
    }


    /**
     * 会员标签
     */
    public function tags(Request $request)
    {
        try {
            $tags = PlatformFacade::tags();
            return $this->succeed(PlatformTagResource::collection($tags), '获取成功！');
        } catch (\Exception $ex) {
            return $this->exception($ex, '获取异常，请联系管理员');
        }
    }

    /**
     * 充值金币价格项
     */
    public function prices(Request $request)
    {
        try {
            $prices = PlatformFacade::prices();
            return $this->succeed(PlatformPriceResource::collection($prices), '获取成功！');
        } catch (\Exception $ex) {
            return $this->exception($ex, '获取异常，请联系管理员');
        }
    }

    /**
     * vip类型
     */
    public function vips(Request $request)
    {
        try {
            $vips = PlatformFacade::vips();
            return $this->succeed(PlatformVipResource::collection($vips), '获取成功！');
        } catch (\Exception $ex) {
            return $this->exception($ex, '获取异常，请联系管理员');
        }
    }

    /**
     * 礼物
     */
    public function gifts(Request $request)
    {
        try {
            $gifts = PlatformFacade::gifts();
            return $this->succeed(PlatformGiftResource::collection($gifts), '获取成功！');
        } catch (\Exception $ex) {
            return $this->exception($ex, '获取异常，请联系管理员');
        }
    }

    /**
     * 公告信息
     */
    public function messages(Request $request, PlatformMessageRepository $repository)
    {
        try {
            $member = $request->user('ApiMember');
            /**
             * 根据会员
             * is_real  是否认证来返回数据  0 已认证 1未认证
             * type 类型 0所有人 1已认证的人 2未认证的人
             */
            $message = $repository->where(function ($query) use ($request, $member) {
                $query->where('status', 0);
                if ($member->is_real == 0) {
                    $query->whereIn('type', [0, 1]);
                } else {
                    $query->whereIn('type', [0, 2]);
                }
//                if ($request->filled('type')) {
//                    $query->where('type', $request->type);
//                }
                if ($request->filled('is_banner')) {
                    $query->where('is_banner', $request->is_banner);
                }

            })->paginate();
            return $this->succeed(PlatformMessageResource::collection($message), '获取成功！');
        } catch (\Exception $ex) {
            return $this->exception($ex, '获取异常，请联系管理员');
        }
    }

    /**
     * 公告信息
     */
    public function notices(Request $request, PlatformNoticeRepository $repository)
    {
        try {
            if (!$request->filled('type')) {
                return $this->validation('Type参数不能为空!');
            }
            $notices = $repository->where(function ($query) use ($request) {
                if ($request->filled('type')) {
                    $query->where('type', $request->type);
                }
                $query->where('status', 0);
            })->paginate();
            return $this->succeed(PlatformNoticeResource::collection($notices), '获取成功！');
        } catch (\Exception $ex) {
            return $this->exception($ex, '获取异常，请联系管理员');
        }
    }


    /**
     * 支付通道
     */
    public function channels(Request $request, PlatformPaymentChannelRepository $repository)
    {
        try {

            $member = $request->user('ApiMember');
            if (!$request->filled('price_id')) {
                return $this->validation('请选择价格项');
            }
            $price = PlatformPrice::find($request->price_id);
            if (!isset($price)) {
                return $this->failure(1, '价格项异常，请联系客服', $request->all());
            }
            $channels = $repository->where(function ($query) use ($request, $member, $price) {
                if ($request->filled('code')) {
                    $query->where('code', $request->code);
                }
                $query->where('status', 1);

                $query->whereHas('payments', function ($query) use ($member, $price) {
                    $query->where('status', 0);
                    $query->where('vip_min_grade', '<=', $member->vip_grade);

                    $query->where('min_money', '<=', $price->money);
                    $query->where('max_money', '>=', $price->money);
                });
            })->all();
            if ($member->cash->usable >= $price->money) {
                $item['id'] = 0;
                $item['name'] = '金币支付';
                $item['code'] = '0';
                $item['icon'] = '/images/default/pay/cash.png';
                $channels = $channels->toArray();
                array_push($channels, $item);
                $channels = json_decode(json_encode($channels));
            }
            return $this->succeed(PlatformPaymentChannelResource::collection($channels), '获取成功！');
        } catch (\Exception $ex) {
            return $this->exception($ex, '获取异常，请联系管理员');
        }
    }

    /**
     * 支付账号
     */
    public function payments(Request $request, PlatformPaymentRepository $repository)
    {
        try {

            $notices = $repository->where(function ($query) use ($request) {
                if ($request->filled('money')) {
                    $query->where('min_money', '<', $request->money);
                    $query->where('max_money', '>', $request->money);
                }

                if ($request->filled('type')) {
                    $query->where('type', $request->type);
                }
                $query->where('status', 0);
            })->paginate();
            return $this->succeed(PlatformPaymentResource::collection($notices), '获取成功！');
        } catch (\Exception $ex) {
            return $this->exception($ex, '获取异常，请联系管理员');
        }
    }


    public function textList(Request $request)
    {
        try {
            $member = $request->user('ApiMember');
            $way = $request->way;
            if (!$request->filled('way')) {
                return $this->validation('请传入way!');
            }

            $text = PlatformText::where('type', $way)->first();
            if ($way == 3) {

                $httpType = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';

                $text->content = $httpType.$request->server('HTTP_HOST').'/api/member/platform/text/helpCenter?way='.$way;
            }
            if ($way == 9) {
                $describe = PlatformVip::where("grade",$member['vip_grade'])->value("describe");
                $text['content'] = $describe??'';
                return $this->succeed($text, '获取成功！');
            }
            if ($way == 5) {
                $describe =  PlatformCharm::where("grade",$member['charm_grade'])->value("describe");
                $text['content'] = $describe??'';
                return $this->succeed($text, '获取成功！');
            }
            return $this->succeed($text, '获取成功！');
        } catch (\Exception $ex) {
            return $this->exception($ex, '获取异常，请联系管理员');
        }
    }

    /**
     * 帮助中心
     * @param Request $request
     * @return array
     */
    public function helpCenter(Request $request)
    {
        $way = $request->way;
        if (!$request->filled('way')) {
            return $this->validation('请传入way!');
        }
        if ($request->filled('type')) {
            $text = PlatformNotice::where('id', $way)->first();
        }else{

            $text = PlatformText::where('type', $way)->first();
        }

        return view('system.platform.text.text')->with('text', $text);
    }



    /**
     * 系统消息是否读取
     * @param Request $request
     * @return array
     */
    public function detail(Request $request)
    {
        if (!$request->filled('notice_id')) {
            return $this->validation('notice_id!');
        }
        try {
            $member = $request->user('ApiMember');
            $model = new PlatformNoticeDetail();
            $model->member_id = $member->id;
            $model->relevance_type = 'PlatformNotice';
            $model->relevance_id = $request->notice_id;
            $model->status = 0;
            $model->is_read = 0;
            $result = $model->save();
            if (!$result) {
                return $this->failure(1, '失败');
            }
            return $this->succeed([], '成功！');
        } catch (\Exception $ex) {
            return $this->exception($ex, '异常，请联系管理员');
        }
    }

}

