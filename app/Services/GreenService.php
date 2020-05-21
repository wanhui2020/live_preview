<?php

namespace App\Service;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use AlibabaCloud\Green\Green;
use App\Traits\ResultTrait;

/**
 * 绿色安全过滤服务
 *
 * @package App\Http\Service
 */
class GreenService
{

    use ResultTrait;

    private $OssClient;

    public function __construct()
    {
        try {
            $this->cloud = AlibabaCloud::accessKeyClient(
                config('aliyun.access.key'),
                config('aliyun.access.secret')
            )->regionId('cn-hangzhou')
                ->asDefaultClient();
        } catch (ClientException $e) {
            $this->exception($e);
        }
    }

    /**
     * 文本同步检查
     *
     * @param $text
     *
     * @return array
     */
    public function textScan($text)
    {
        if (empty($text)) {
            return $this->validation('内容不能为空');
        }

        $request = Green::v20180509()->textScan([
            'body' => json_encode([
                "scenes" => ["antispam"],
                "tasks"  => [
                    "content" => $text,
                ],
            ]),
        ]);
        try {
            $response = $request->request()->all();

            if ($response['code'] == 200) {
                $data   = $response['data'][0];
                $result = $data['results'][0];

                if (in_array($result['label'], [
                    'spam',
                    'ad',
                    'politics',
                    'terrorism',
                    'abuse',
                    'porn',
                    'flood',
                    'contraband',
                    'meaningless',
                    'customized',
                ])
                ) {
                    return $this->validation('三方审核失败', $result);
                }
                if ($result['suggestion'] == 'pass') {
                    return $this->succeed($result);
                }
                if ($result['suggestion'] == 'review') {
                    return $this->validation('需人工审核', $result);
                }

                return $this->failure(1, '内容违规', $result);
            }

            return $this->failure(1, '垃圾内容检查失败', $response);
        } catch (ClientException $exception) {
            return $this->exception($exception);
        } catch (ServerException $exception) {
            return $this->exception($exception);
        }
    }


    /**
     * 图片同步检查
     *
     * @param $text
     *
     * @return array
     */
    public function imageSyncScan($url)
    {
        if (empty($url)) {
            return $this->validation('内容不能为空');
        }

        $request = Green::v20180509()->imageSyncScan([
            'body' => json_encode([
                "scenes" => ["porn", 'terrorism'],
                "tasks"  => [
                    "url" => $url,
                ],
            ]),
        ]);
        try {
            $response = $request->request()->all();
            if ($response['code'] == 200) {
                $data   = $response['data'][0];
                $result = $data['results'][0];
                if (in_array($result['label'], [
                    'spam',
                    'ad',
                    'politics',
                    'terrorism',
                    'abuse',
                    'porn',
                    'flood',
                    'contraband',
                    'meaningless',
                    'customized',
                ])
                ) {
                    return $this->validation('三方审核失败', $result);
                }
                if ($result['suggestion'] == 'pass') {
                    return $this->succeed($result, '系统审核通过');
                }
                if ($result['suggestion'] == 'review') {
                    return $this->validation('需人工审核', $result);
                }

                return $this->failure(1, '内容违规', $result);
            }

            return $this->failure(1, '垃圾内容检查失败', $response);
        } catch (ClientException $exception) {
            return $this->exception($exception);
        } catch (ServerException $exception) {
            return $this->exception($exception);
        }
    }

    /**
     * 图片异步检查
     *
     * @param $text
     *
     * @return array
     */
    public function imageAsynScan($url, $callback = "")
    {
        if (empty($url)) {
            return $this->validation('内容不能为空');
        }

        $request = Green::v20180509()->imageAsyncScanResults([
            'body' => json_encode([
                "scenes" => ["porn", 'terrorism'],
                //            "callback" => $callback,
                //            'time' => round(microtime(true)*1000),
                "tasks"  => [
                    "url" => $url,
                ],
            ]),
        ]);
        try {
            $response = $request->request()->all();
            if ($response['code'] == 200) {
                $data   = $response['data'][0];
                $result = $data['results'][0];
                if (in_array($result['label'], [
                    'spam',
                    'ad',
                    'politics',
                    'terrorism',
                    'abuse',
                    'porn',
                    'flood',
                    'contraband',
                    'meaningless',
                    'customized',
                ])
                ) {
                    return $this->validation('三方审核失败', $result);
                }
                if ($result['suggestion'] == 'pass') {
                    return $this->succeed($result, '系统审核通过');
                }
                if ($result['suggestion'] == 'review') {
                    return $this->validation('需人工审核', $result);
                }

                return $this->failure(1, '内容违规', $result);
            }

            return $this->failure(1, '垃圾内容检查失败', $response);
        } catch (ClientException $exception) {
            return $this->exception($exception);
        } catch (ServerException $exception) {
            return $this->exception($exception);
        }
    }

    /**
     * 视频异步检查
     *
     * @param $text
     *
     * @return array
     */
    public function videoAsyncScan($url, $callback = "")
    {
        if (empty($url)) {
            return $this->validation('内容不能为空');
        }
        \Log::info("我晕");
        $request = Green::v20180509()->videoAsyncScan([
            'body' => json_encode([
                "scenes"   => ["porn", 'terrorism'],
                "callback" => $callback,
                "tasks"    => [
                    "url" => $url,
                ],
            ]),
        ]);
        try {
            $response = $request->request()->all();
            \Log::info("审核视频记录".json_encode($response));
            if ($response['code'] == 200) {
                $data   = $response['data'][0];
                $result = $data['results'][0];
                if (in_array($result['label'], [
                    'spam',
                    'ad',
                    'politics',
                    'terrorism',
                    'abuse',
                    'porn',
                    'flood',
                    'contraband',
                    'meaningless',
                    'customized',
                ])
                ) {
                    return $this->validation('三方审核失败', $result);
                }
                if ($result['suggestion'] == 'pass') {
                    return $this->succeed($result, '系统审核通过');
                }
                if ($result['suggestion'] == 'review') {
                    return $this->validation('需人工审核', $result);
                }

                return $this->failure(1, '内容违规', $result);
            }

            return $this->failure(1, '垃圾内容检查失败', $response);
        } catch (ClientException $exception) {
            return $this->exception($exception);
        } catch (ServerException $exception) {
            return $this->exception($exception);
        }
    }
}
