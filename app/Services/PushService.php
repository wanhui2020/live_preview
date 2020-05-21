<?php

namespace App\Services;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Push\Push;
use App\Traits\ResultTrait;

/**
 * 推送服务
 * Class PushService
 *
 * @package App\Services
 */
class PushService
{

    use ResultTrait;

    private $cloud;


    public function __construct()
    {
        try {
            $this->cloud
                = AlibabaCloud::accessKeyClient(config('aliyun.access.key'),
                config('aliyun.access.secret'))
                ->regionId('cn-hangzhou')->asDefaultClient();
        } catch (ClientException $e) {
            $this->exception($e);
        }
    }


    /**
     * 根据标识推送
     *
     * @param $platform
     * @param $tokenAccounts
     * @param  int  $operatorType
     */
    public function pushToken(
        $token,
        $platform,
        $title,
        $body,
        $type = 'NOTICE',
        array $params = []
    ) {
        try {
            $request = Push::v20160801()->push([]);
            $request->withTitle($title);
            $request->withBody($body);
            $request->withTarget('DEVICE');
            $request->withTargetValue($token);
            $request->withPushType($type);

            if ($platform == 'android') {
                $request->withDeviceType('ANDROID');
                $request->withAppKey(config('aliyun.mobile_push.android_app_id'));
                if (count($params) > 0) {
                    $request->withAndroidExtParameters(json_encode($params));
                }
            }
            if ($platform == 'ios') {
                $request->withDeviceType('iOS');
                $request->withIOSMusic("default"); // iOS通知声音
                $request->withAppKey(config('aliyun.mobile_push.ios_app_id'));
                $request->withIOSApnsEnv('DEV');
                if (count($params) > 0) {
                    $request->withIOSExtParameters(json_encode($params));
                }
            }

            $resp = $request->request()->all();

            return $this->succeed($resp);
        } catch (\Exception $ex) {
            return $this->exception($ex, '推送失败');
        }
    }


    /**
     * 推送安卓
     *
     * @param $platform
     * @param $tokenAccounts
     * @param  int  $operatorType
     */
    public function pushAndroid(
        $token,
        $platform,
        $title,
        $body,
        $type = 'MESSAGE',
        array $params = []
    ) {
        try {
            $request = Push::v20160801()->push([]);
            $request->withTitle($title);
            $request->withBody($body);
            $request->withTarget('ALL');
            $request->withTargetValue('ALL');
            $request->withPushType($type);

//            if ($platform == 'android') {
            $request->withDeviceType('ANDROID');
            $request->withAppKey(config('aliyun.mobile_push.android_app_id'));
            if (count($params) > 0) {
                $request->withAndroidExtParameters(json_encode($params));
            }

            $resp = $request->request()->all();

            return $this->succeed($resp);
        } catch (\Exception $ex) {
            return $this->exception($ex, '推送失败');
        }
    }

    /**
     * ios推送
     *
     * @param $platform
     * @param $tokenAccounts
     * @param  int  $operatorType
     */
    public function pushIos(
        $token,
        $platform,
        $title,
        $body,
        $type = 'MESSAGE',
        array $params = []
    ) {
        try {
            $request = Push::v20160801()->push([]);
            $request->withTitle($title);
            $request->withBody($body);
            $request->withTarget('ALL');
            $request->withTargetValue('ALL');
            $request->withPushType($type);

//            if ($platform == 'ios') {
            $request->withDeviceType('iOS');
            $request->withIOSMusic("default"); // iOS通知声音
            $request->withAppKey(config('aliyun.mobile_push.ios_app_id'));
            $request->withIOSApnsEnv('DEV');
            if (count($params) > 0) {
                $request->withIOSExtParameters(json_encode($params));
            }
//            }

            $resp = $request->request()->all();

            return $this->succeed($resp);
        } catch (\Exception $ex) {
            return $this->exception($ex, '推送失败');
        }
    }


    /**
     * 绑定tag
     * @param string $token
     * @param string $keyType
     * @param string $tagName
     * @return \AlibabaCloud\Client\Result\Result
     * @throws \AlibabaCloud\Client\Exception\ServerException
     */
    public function bindTag($token = '', $keyType = 'DEVICE', $tagName = '用户')
    {
        try {
            $request = Push::v20160801()->push([]);
            $appkey = json_decode(json_encode($request->withAppKey(config('aliyun.mobile_push.android_app_id'))),true)['data']['AppKey'];
            $result = AlibabaCloud::rpc()
                ->product('Push')
                // ->scheme('https') // https | http
                ->version('2016-08-01')
                ->action('BindTag')
                ->method('POST')
                ->host('cloudpush.aliyuncs.com')
                ->options([
                    'query' => [
                        'RegionId' => "cn-hangzhou",
                        'AppKey' => $appkey,
                        'ClientKey' => $token,
                        'KeyType' => $keyType,
                        'TagName' => $tagName,
                    ],
                ])
                ->request();
            return $result;
        } catch (ClientException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        } catch (ServerException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        }
    }


    /**
     * 获取标签
     * @param string $token
     * @param string $keyType
     * @return \AlibabaCloud\Client\Result\Result
     * @throws \AlibabaCloud\Client\Exception\ServerException
     */
    public function getTag($token = '', $keyType = 'DEVICE')
    {
        try {
            $request = Push::v20160801()->push([]);
            $appkey = json_decode(json_encode($request->withAppKey(config('aliyun.mobile_push.android_app_id'))),true)['data']['AppKey'];
            $result = AlibabaCloud::rpc()
                ->product('Push')
                // ->scheme('https') // https | http
                ->version('2016-08-01')
                ->action('QueryTags')
                ->method('POST')
                ->host('cloudpush.aliyuncs.com')
                ->options([
                    'query' => [
                        'RegionId' => "cn-hangzhou",
                        'AppKey' => $appkey,
                        'ClientKey' => $token,
                        'KeyType' => $keyType,
                    ],
                ])
                ->request();
            return $result->TagInfos->TagInfo;
        } catch (ClientException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        } catch (ServerException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        }
    }

    /**
     * 获取标签列表
     * @return \AlibabaCloud\Client\Result\Result
     * @throws \AlibabaCloud\Client\Exception\ServerException
     */
    public function tagLists()
    {

        try {
            $request = Push::v20160801()->push([]);
            $appkey = json_decode(json_encode($request->withAppKey(config('aliyun.mobile_push.android_app_id'))),true)['data']['AppKey'];
            $result = AlibabaCloud::rpc()
                ->product('Push')
                // ->scheme('https') // https | http
                ->version('2016-08-01')
                ->action('ListTags')
                ->method('POST')
                ->host('cloudpush.aliyuncs.com')
                ->options([
                    'query' => [
                        'RegionId' => "cn-hangzhou",
                        'AppKey' => $appkey,
                    ],
                ])
                ->request();
            return $result->TagInfos->TagInfo;
        } catch (ClientException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        } catch (ServerException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        }
    }

}
