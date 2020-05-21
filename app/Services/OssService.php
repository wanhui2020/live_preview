<?php

namespace App\Service;

use App\Facades\CommonFacade;
use App\Traits\ResultTrait;
use App\Utils\Result;
use OSS\Core\OssException;
use OSS\OssClient;

/**
 * 文件服务
 *
 * @package App\Http\Service
 */
class OssService
{

    use ResultTrait;

    private $OssClient;

    public function __construct()
    {
        $accessKeyId     = config('aliyun.oss.access_key');
        $accessKeySecret = config('aliyun.oss.access_secret');
        $endpoint        = config('aliyun.oss.url');
        try {
            $this->OssClient = new OssClient($accessKeyId, $accessKeySecret,
                $endpoint, true);
            $this->OssClient->setTimeout(3600);
            $this->OssClient->setConnectTimeout(10);
        } catch (OssException $e) {
            $this->exception($e);
            print $e->getMessage();
        }
    }

    public static function putBytes($uploadData, $path = 'base')
    {
        try {
            $OssClient = new OssService();
            $oss       = $OssClient->OssClient;
            //生成文件名
            $object = config('aliyun.oss.directory').'/'.$path.'/'.date('Y/m/d')
                .'/'
                .CommonFacade::uuid();

            $res = $oss->putObject($OssClient->getBucket(), $object,
                $uploadData);
            if (isset($res['info'])) {
                $info = $res['info'];

                return [
                    'status' => true,
                    'code'   => 0,
                    'src'    => $info['url'],
                    'data'   => config('aliyun.oss.url').'/'.$object,
                ];
            }

            return ['status' => false, 'code' => 1, 'data' => '上传失败'];
        } catch (OssException $e) {
            return [
                'status' => false,
                'code'   => -1,
                'data'   => $e->getMessage(),
            ];
        }
    }

    public function getBucket()
    {
        return config('aliyun.oss.bucket');
    }

    public static function putFile($file, $path = 'base')
    {
        try {
            $OssClient = new OssService();
            $oss       = $OssClient->OssClient;

            //获取上传图片的临时地址
            $filePath = $file->getRealPath();
            //生成文件名
            $object = config('aliyun.oss.directory').'/'.$path.'/'.date('Y/m/d')
                .'/'
                .CommonFacade::uuid().'.'.$file->getClientOriginalExtension();

            $res = $oss->uploadFile($OssClient->getBucket(), $object,
                $filePath);

            if (isset($res['info'])) {
                $info = $res['info'];

                return [
                    'status' => true,
                    'code'   => 0,
                    'src'    => $info['url'],
                    'data'   => config('aliyun.oss.url').'/'.$object,
                ];
            }

            return ['status' => false, 'code' => 1, 'data' => '上传失败'];
        } catch (OssException $e) {
            return [
                'status' => false,
                'code'   => -1,
                'data'   => $e->getMessage(),
            ];
        }
    }

    public static function download($object)
    {
        try {
            $OssClient = new OssService();
            $oss       = $OssClient->OssClient;


            $res = $oss->getObject($OssClient->getBucket(), $object);

            return $res;

            if (isset($res['info'])) {
                $info = $res['info'];

                return ['status' => true, 'code' => 0, 'src' => $info['url']];
            }

            return ['status' => false, 'code' => 1, 'data' => '上传失败'];
        } catch (OssException $e) {
            return [
                'status' => false,
                'code'   => -1,
                'data'   => $e->getMessage(),
            ];
        }
    }

    public function putObject($file)
    {
        try {
            //获取上传图片的临时地址
            $tmppath = $file->getRealPath();
            //生成文件名
            $fileName = str_random(5).$file->getFilename().time().date('ymd')
                .'.'.$file->getClientOriginalExtension();
            //拼接上传的文件夹路径(按照日期格式1810/17/xxxx.jpg)
            $pathName = config('aliyun.oss.directory').'/'.date('Y-m/d').'/'
                .$fileName;


            $fileName = $file->getClientOriginalName();
            $extend   = strtolower(substr(strrchr($fileName, "."), 1));

            $object     = config('aliyun.oss.directory').'/'
                .CommonFacade::uuid();
            $options    = [
                OssClient::OSS_HEADERS => [
                    'Content-Type'        => $file->getClientMimeType(),
                    'fileName'            => $object,
                    'Content-Disposition' => 'attachment; filename="'.$fileName
                        .'"',
                ],
            ];
            $content    = file_get_contents($file);
            $res        = $this->OssClient->putObject($this->getBucket(),
                $object, $content, $options);
            $res['src'] = $res['info']['url'];

            return $this->succeed($res['src'], '上传成功');
        } catch (OssException $e) {
            return $this->exception($e);
        }
    }

    public function putImage($file, $path = 'base')
    {
        try {
            $object      = config('aliyun.oss.directory').'/'.$path.'/'
                .CommonFacade::uuid().'.'.$file->getClientOriginalExtension();
            $options     = [
                OssClient::OSS_HEADERS => [
                    'Content-Type'        => $file->getMimeType(),
                    'fileName'            => $object,
                    'Content-Disposition' => 'inline"',
                ],
            ];
            $content     = file_get_contents($file);
            $res         = $this->OssClient->putObject($this->getBucket(),
                $object, $content, $options);
            $res['src']  = $res['info']['url'];
            $res['data'] = config('aliyun.oss.url').'/'.$object;

            return $this->succeed($res['src'], '上传成功');
        } catch (OssException $e) {
            return $this->exception($e);
        }
    }

    /**
     * 转存远程图片
     *
     * @param $url
     *
     * @return array|string
     */
    public function putUrl($url, $path = 'base')
    {
        try {
            $OssClient = new OssService();
            $oss       = $OssClient->OssClient;
            $object    = config('aliyun.oss.directory').'/'.$path.'/'
                .date('Y/m/d').'/'
                .CommonFacade::uuid();
            $content   = file_get_contents($url, true);
            $res       = $oss->putObject($this->getBucket(), $object, $content);
            if (isset($res['info'])) {
                $info = $res['info'];

                return [
                    'status' => true,
                    'code'   => 0,
                    'src'    => $info['url'],
                    'data'   => config('aliyun.oss.url').'/'.$object,
                ];
            }

            return $this->failure(1, '上传成功', $res);
        } catch (OssException $e) {
            return $e->getMessage();
        }
    }

}
