<?php

namespace App\Services;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use App\Facades\OssFacade;
use App\Traits\ResultTrait;

//阿里云
class AliyunService
{

    use ResultTrait;

    private $cloud;

    private $scene;

    public function __construct()
    {
        $this->scene = config('aliyun.face.scene');
        try {
            $this->cloud
                = AlibabaCloud::accessKeyClient(
                config('aliyun.face.access_key'),
                config('aliyun.face.access_secret')
            )->regionId('cn-hangzhou')
                ->asDefaultClient();
        } catch (ClientException $e) {
            $this->exception($e);
        }
    }

    /**
     * 人脸适别
     *
     * @param $mno
     *
     * @return array
     */
    public function DescribeVerifyToken($mno)
    {
        try {
            // 访问产品 APIs
            $request = AlibabaCloud::Cloudauth()->V20190307()
                ->DescribeVerifyToken();

            $result = $request->withBizType($this->scene)
                ->withBizId($mno)
                //                ->withIdCardNumber($idcard)
                //                ->withName($name)
                ->format('JSON')
                ->connectTimeout(10)
                ->timeout(10)
                ->request();

            return $this->succeed($result->toArray());
        } catch (ClientException $e) {
            return $this->exception($e);
        } catch (ServerException $e) {
            return $this->exception($e);
        }
    }

    public function DescribeVerifyResult($mno)
    {
        try {
            // 访问产品 APIs
            $request = AlibabaCloud::Cloudauth()->V20190307()
                ->DescribeVerifyResult();
            $result  = $request->withBizType($this->scene)//创建方法参见业务设置
            ->withBizId($mno)
                ->connectTimeout(10)
                ->timeout(10)
                ->format('JSON')
                ->request();
            $data    = $result->toArray();
            $resp    = [];
            if ($data['VerifyStatus'] == 1) {
                $Material = $data['Material'];
                //                $this->logs('$Material', $Material);
                if (isset($Material)) {
                    if (isset($Material['IdCardName'])) {
                        $resp['IdCardName'] = $Material['IdCardName'];
                    }
                    if (isset($Material['IdCardNumber'])) {
                        $resp['IdCardNumber'] = $Material['IdCardNumber'];
                    }
                    if (isset($Material['FaceImageUrl'])) {
                        $putBackImageUrl
                            = OssFacade::putUrl($Material['FaceImageUrl'],
                            $mno);
                        if ($putBackImageUrl['status']) {
                            $resp['FaceImageUrl'] = $putBackImageUrl['data'];
                        }
                    }
                    if (isset($Material['IdCardInfo'])) {
                        $IdCardInfo          = $Material['IdCardInfo'];
                        $resp['Address']     = $IdCardInfo['Address'];
                        $resp['Nationality'] = $IdCardInfo['Nationality'];
                        $resp['Authority']   = $IdCardInfo['Authority'];
                        $resp['StartDate']   = $IdCardInfo['StartDate'];
                        $resp['EndDate']     = $IdCardInfo['EndDate'];
                        $resp['Birth']       = $IdCardInfo['Birth'];
                        if (isset($IdCardInfo['FrontImageUrl'])) {
                            $putFrontImageUrl
                                = OssFacade::putUrl($IdCardInfo['FrontImageUrl'],
                                $mno);
                            if ($putFrontImageUrl['status']) {
                                $resp['FrontImageUrl']
                                    = $putFrontImageUrl['data'];
                            }
                        }
                        if (isset($IdCardInfo['BackImageUrl'])) {
                            $putBackImageUrl
                                = OssFacade::putUrl($IdCardInfo['BackImageUrl'],
                                $mno);
                            if ($putBackImageUrl['status']) {
                                $resp['BackImageUrl']
                                    = $putBackImageUrl['data'];
                            }
                        }
                    }
                }

                return $this->succeed($resp);
            }

            return $this->failure($result->toArray());
        } catch (ClientException $e) {
            return $this->exception($e);
        } catch (ServerException $e) {
            return $this->exception($e);
        }
    }

}
