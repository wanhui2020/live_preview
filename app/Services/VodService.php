<?php

namespace App\Service;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use AlibabaCloud\Vod\Vod;
use App\Traits\ResultTrait;

define('VOD_CLIENT_NAME', 'AliyunVodClientDemo');

/**
 * 视频服务
 *
 * @package App\Http\Service
 */
class VodService
{

    use ResultTrait;

    public function __construct()
    {
        $accessKeyId     = config('aliyun.access.key');
        $accessKeySecret = config('aliyun.access.secret');
        $this->init($accessKeyId, $accessKeySecret);
    }

    public function init($accessKeyId, $accessKeySecret)
    {
        $regionId = 'cn-shanghai';
        try {
            AlibabaCloud::accessKeyClient($accessKeyId, $accessKeySecret)
                ->regionId($regionId)
                ->connectTimeout(1)
                ->timeout(3)
                ->name(VOD_CLIENT_NAME);
        } catch (ClientException $e) {
            $this->exception($e);
        }
    }

    /**
     * 获取视频上传地址和凭证
     *
     * @param $title
     * @param $fileName
     * @param $tags
     *
     * @return array
     */
    public function createUploadVideo($title, $fileName, $description,$tags)
    {
        try {
            $request = Vod::v20170321()->createUploadVideo([]);

            $request->withTitle($title);
            $request->withFileName($fileName);
            $request->withDescription($description);

            //            $request->withCoverURL("http://img.alicdn.com/tps/TB1qnJ1PVXXXXXCXXXXXXXXXXXX-700-700.png");
            $request->withTags($tags);

            return $request->client(VOD_CLIENT_NAME)->request()->all();
        } catch (ClientException $e) {
            \Log::error($e->getMessage());

            return $this->exception($e);
        } catch (ServerException $e) {
            \Log::error($e->getMessage());

            return $this->exception($e);
        }
    }


    /**
     * 获取视频和凭证
     * @param $videoId
     * @return array
     */
    public function getVideo($videoId)
    {
        try {
            $request = Vod::v20170321()->getPlayInfo([]);

            $request->setVideoId($videoId);
            $request->setAuthTimeout(3600*24);
            $request->setAcceptFormat('JSON');
            return $request->client(VOD_CLIENT_NAME)->request()->all();
        } catch (ClientException $e) {
            \Log::error($e->getMessage());

            return $this->exception($e);
        } catch (ServerException $e) {
            \Log::error($e->getMessage());

            return $this->exception($e);
        }
    }


    /**
     * 获取视频和凭证
     * @param $videoId
     * @return array
     */
    public function getVideoLists()
    {
        try {
            $request = Vod::v20170321()->getVideoList([]);

//            $request->setVideoId($videoId);
//            $request->setAuthTimeout(3600*24);
//            $request->setAcceptFormat('JSON');
            return $request->client(VOD_CLIENT_NAME)->request()->all();
        } catch (ClientException $e) {
            \Log::error($e->getMessage());

            return $this->exception($e);
        } catch (ServerException $e) {
            \Log::error($e->getMessage());

            return $this->exception($e);
        }
    }


}
