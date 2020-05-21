<?php


namespace App\Http\Controllers\Api\Common;

use App\Facades\RiskFacade;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\CommonRequest;
use App\Http\Requests\Api\CreateLogRequest;
use App\Http\Resources\PlatformEdtionResource;
use App\Http\Resources\PlatformInitResource;
use App\Models\AppErrorLog;
use App\Models\PlatformEdition;
use App\Models\PlatformText;
use Illuminate\Http\Request;

class CommonController extends ApiController
{

    /**
     *  发送验证码
     *
     * @param  CommonRequest  $request
     *
     * @return array
     */
    public function sendCode(CommonRequest $request)
    {
        try {
            if (!$request->filled('mobile')) {
                return $this->validation('手机号不能为空');
            }
            $result = RiskFacade::sendCode($request->mobile);
            if ($result['status']) {
                return $this->succeed($result, $result['msg']);
            }

            return $this->failure(1, '验证码发送失败', $result);
        } catch (\Exception $ex) {
            return $this->exception($ex, '发送短信异常，请联系管理员');
        }
    }

    /**
     *  效验验证码
     *
     * @param  CommonRequest  $request
     *
     * @return array
     */
    public function verifyCode(CommonRequest $request)
    {
        try {
            if (!$request->filled('mobile')) {
                return $this->validation('手机号不能为空');
            }

            if (!$request->filled('code')) {
                return $this->validation('验证码不能为空');
            }

            $success = RiskFacade::verifyCode($request->mobile, $request->code);

            return $success
                ? $this->succeed()
                : $this->failure(1, '效验验证码失败', $success);
        } catch (\Exception $ex) {
            return $this->exception($ex, '效验验证码失败');
        }
    }

    /**
     *  下载图片
     *
     * @param  Request  $request
     *
     * @return bool
     */
    public function downloadImg(Request $request)
    {
        if (empty($request->filename)) {
            return false;
        }
        $file_xp = substr($request->filename, strrpos($request->filename, '.'));
        $type    = str_replace('.', '', $file_xp);
        header("Content-type: image/{$type}");
        $data = file_get_contents($request->filename); //获取OSS URL 图片
        $name = empty($request->name) ? date('YmdHis').$file_xp
            : $request->name.$file_xp;
        //输出页面下载头部
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Content-Length: ".strlen($data));
        header("Content-Disposition: attachment; filename=".$name);
        echo $data;
        exit();
    }


    /**
     * 版本获取
     */
    public function edition(Request $request)
    {
        try {
            if (!$request->filled('type')) {
                return $this->validation('Type参数不能为空!');
            }
            $edtion = PlatformEdition::where('type', $request->type)
                ->where('status', 0)->first();
            if (!isset($edtion)) {
                return $this->validation('获取失败！');
            }

            return $this->succeed(new  PlatformEdtionResource($edtion),
                '版本管理获取成功！');
        } catch (\Exception $e) {
            return $this->exception($e, '版本管理获取异常，请联系管理员');
        }
    }

    /**
     * 系统初始化
     */
    public function init(Request $request)
    {
        try {
            if (!$request->filled('type')) {
                return $this->validation('Type参数不能为空!');
            }
            $edtion = PlatformEdition::where('type', $request->type)
                ->where('status', PlatformEdition::OPEN)->first();
            if (!isset($edtion)) {
                return $this->validation('获取失败！');
            }

            return $this->succeed(new  PlatformInitResource($edtion),
                '版本管理获取成功！');
        } catch (\Exception $e) {
            return $this->exception($e, '版本管理获取异常，请联系管理员');
        }
    }

    /**
     * 获取用户协议和隐私协议
     * way  6是用户协议
     * way  7是隐私协议
     */
    public function textList(Request $request)
    {
        try {
            $way = $request->way;
            if (!$request->filled('way')) {
                return $this->validation('请传入way!');
            }
            $text = PlatformText::where('type', $way)->first();

            return $this->succeed($text, '获取成功！');
        } catch (\Exception $ex) {
            return $this->exception($ex, '获取异常，请联系管理员');
        }
    }

    public function log(CreateLogRequest $request)
    {
        AppErrorLog::create(
            $request->only(['platform', 'version', 'content'])
        );
    }

}
