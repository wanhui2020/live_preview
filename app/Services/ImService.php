<?php

namespace App\Services;

use App\Traits\ResultTrait;
use App\Utils\TLSSigAPIv2;

//IM服务
class ImService
{

    use ResultTrait;

    private $config;

    private $client;

    public function __construct()
    {
        $this->config = [
            'base_url'   => 'https://console.tim.qq.com/v4',
            'appid'      => env('TENCENT_IM_APPID'),
            'key'        => env('TENCENT_IM_KEY'),
            'identifier' => env('TENCENT_IM_IDENTIFIER'),
            'token'      => env('TENCENT_IM_TOKEN'),
        ];
    }

    /**
     *   生成 UserSig
     */
    public function userSign($Identifier)
    {
        try {
            $sign = new TLSSigAPIv2($this->config['appid'],
                $this->config['key']);

            return $sign->genSig($Identifier);
        } catch (\Exception $ex) {
            $this->exception($ex);

            return false;
        }
    }

    /**
     * 单个帐号导入接口
     *
     * @param  string  $Identifier  用户名，长度不超过32字节
     * @param  string  $Nick  用户昵称
     * @param  string  $FaceUrl  用户头像 URL
     * @param  string  $Type  帐号类型，开发者默认无需填写，值0表示普通帐号，1表示机器人帐号
     *
     * @return mixed https://cloud.tencent.com/document/product/269/1608
     */
    public function userImport($Identifier, $Nick = '', $FaceUrl = '')
    {
        try {
            $data   = [
                "Identifier" => $Identifier.'',
                "Nick"       => $Nick,
                "FaceUrl"    => $FaceUrl,
            ];
            $result = $this->requestPost('/im_open_login_svc/account_import',
                $data);

            if ($result['ErrorCode'] == 0) {
                if ($result['ActionStatus'] == 'OK') {
                    return $this->succeed($result, '单个帐号导入成功');
                }
            }

            return $this->failure(1, '单个帐号导入失败', [$result, $data]);
        } catch (\Exception $ex) {
            return $this->exception($ex);
        }
    }

    function requestPost($url, $data = null)
    {
        //        $usersig = $this->userSign($this->config['identifier']);
        $params = http_build_query([
            'sdkappid'    => $this->config['appid'],
            'identifier'  => $this->config['identifier'],
            'usersig'     => $this->config['token'],
            'random'      => rand(10000000, 99999999).rand(10000000, 99999999)
                .rand(10000000, 99999999).rand(10000000, 99999999),
            'contenttype' => 'json',
        ]);
        $url    = $this->config['base_url'].$url.'?'.$params;
        $curl   = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if (!empty($data)) {
            if (is_array($data)) {
                $data = json_encode($data);
            }
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($curl);
        curl_close($curl);
        $output = json_decode($output, true);

        return $output;
    }

    /**
     * 批量帐号导入接口
     *
     * @param  array  $Accounts  用户名，单个用户名长度不超过32字节，单次最多导入100个用户名
     *
     * @return mixed
     */
    public function userMultiImport($Accounts = [])
    {
        $data   = [
            "Accounts" => $Accounts,
        ];
        $result = $this->requestPost('/im_open_login_svc/multiaccount_import',
            $data);
        if ($result['ErrorCode'] == 0) {
            return $this->succeed($result['FailAccounts'], '批量帐号导入成功');
        }

        return $this->failure(1, '批量帐号导入失败', [$result, $data]);
    }

    /**
     * 帐号登录态失效接口
     *
     * @param  array  $accounts
     *
     * @return mixed
     */
    public function userKick($Identifier = [])
    {
        return $this->requestPost('/im_open_login_svc/kick', [
            "Identifier" => $Identifier,
        ]);
    }

    /**
     * 获取用户在线状态
     *
     * @param  array  $accounts
     *
     * @return mixed
     */
    public function userStatus($accounts = [])
    {
        try {
            $data   = [
                "To_Account" => $accounts,
            ];
            $result = $this->requestPost('/openim/querystate', $data);
            if ($result['ErrorCode'] == 0) {
                return $this->succeed($result['QueryResult'], '获取用户在线状态成功');
            }

            return $this->failure(1, '获取用户在线状态失败', [$result, $data]);
        } catch (\Exception $exception) {
            return $this->exception($exception, '获取用户在线状态异常');
        }
    }

    /**
     * 帐号检查是否导入接口
     *
     * @param  array  $accounts
     *
     * @return mixed
     */
    public function userCheck($accounts = [])
    {
        try {
            $data   = [
                'CheckItem' => $accounts,
            ];
            $result = $this->requestPost('/im_open_login_svc/account_check',
                $data);
            if ($result['ErrorCode'] == 0) {
                return $this->succeed($result['ResultItem'], '已导入');
            }

            return $this->failure(1, '帐号检查失败', [$result, $data]);
        } catch (\Exception $exception) {
            return $this->exception($exception, '帐号检查接口异常');
        }
    }

    /**
     * 拉取资料
     *
     * @param  array  $accounts
     *
     * @return mixed
     */
    public function userGetInfo($To_Account, $TagList)
    {
        return $this->requestPost('/profile/portrait_get', [
            "To_Account" => $To_Account,
            "TagList"    => $TagList,
        ]);
    }

    /**
     * 设置资料
     *
     * @param  array  $accounts
     *
     * @return mixed
     */
    public function userSetInfo($From_Account, $tag, $value)
    {
        return $this->requestPost('/profile/portrait_set', [
            "From_Account" => $From_Account,
            "ProfileItem"  => [
                ["Tag" => $tag, "Value" => $value,],
            ],

        ]);
    }

    /**
     * 用户信息更新
     *
     * @param  array  $accounts
     *
     * @return mixed
     */
    public function userUpdate($From_Account, $tags)
    {
        $resp = $this->requestPost('/profile/portrait_set', [
            "From_Account" => $From_Account,
            "ProfileItem"  => $tags,
        ]);

        return $resp;
    }

    /**
     * 测试环境账户删除
     *
     * @param  array  $accounts
     *
     * @return mixed
     */
    public function batchDelete()
    {
        $items = [];
        for ($i = 10000000; $i < 10000100; $i++) {
            array_push($items, ['UserID' => $i.'']);
        }
        $resp = $this->requestPost('/im_open_login_svc/account_delete', [
            "DeleteItem" => ['UserID' => $i.''],
        ]);

        return $resp;
    }

    /**
     * 发送广播
     */
    public function sendNotification(
        $command,
        $data,
        $To_Account,
        $From_Account = null,
        $sync = 1
    ) {
        try {
            $data = [
                "To_Account"       => $To_Account,
                "MsgRandom"        => rand(1000000, 9999999),
                "MsgBody"          => [
                    [
                        "MsgType"    => 'TIMCustomElem',
                        "MsgContent" => [
                            "Data"  => json_encode([
                                'command' => $command,
                                'data'    => $data,
                            ]),
                            "Desc"  => "notification",
                            "Ext"   => "url",
                            "Sound" => "dingdong.aiff",
                        ],
                    ],
                ],
                "SyncOtherMachine" => $sync,
                "MsgLifeTime"      => 604800,
                "MsgTimeStamp"     => time(),
            ];
            if ($From_Account) {
                $data["From_Account"] = $From_Account;
            }

            $result = $this->requestPost('/openim/sendmsg', $data);
            if ($result['ErrorCode'] == 0) {
                return $this->succeed($result, '发送成功');
            }

            return $this->failure(1, '发送失败', [$result, $data]);
        } catch (\Exception $ex) {
            return $this->exception($ex, '通话请求自定义信息发送异常');
        }
    }

    /**
     * 发送广播
     */
    public function sendBatchNotification(
        $command,
        $data,
        array $To_Account,
        $From_Account = null,
        $sync = 1
    ) {
        try {
            $data = [
                "To_Account"       => $To_Account,
                "MsgRandom"        => rand(1000000, 9999999),
                "MsgBody"          => [
                    [
                        "MsgType"    => 'TIMCustomElem',
                        "MsgContent" => [
                            "Data"  => json_encode([
                                'command' => $command,
                                'data'    => $data,
                            ]),
                            "Desc"  => "notification",
                            "Ext"   => "url",
                            "Sound" => "dingdong.aiff",
                        ],
                    ],
                ],
                "SyncOtherMachine" => $sync,
                "MsgLifeTime"      => 604800,
                "MsgTimeStamp"     => time(),
            ];
            if ($From_Account) {
                $data["From_Account"] = $From_Account;
            }
            //            $this->logs('sendBatchNotification', $data);

            $result = $this->requestPost('/openim/batchsendmsg', $data);
            if ($result['ErrorCode'] == 0) {
                return $this->succeed($result, '发送成功');
            }

            return $this->failure(1, '发送失败', [$result, $data]);
        } catch (\Exception $ex) {
            return $this->exception($ex, '通话请求自定义信息发送异常');
        }
    }


    /**
     * 通话计费
     */
    public function sendTalkDeduction(
        $From_Account,
        $To_Account,
        $roomId,
        $usable
    ) {
        try {
            $data = [
                "To_Account"       => $To_Account,
                "MsgRandom"        => rand(1000000, 9999999),
                "MsgBody"          => [
                    [
                        "MsgType"    => 'TIMCustomElem',
                        "MsgContent" => [
                            "Data" => json_encode([
                                'command' => 'talk.deduction',
                                'data'    => [
                                    'usable'  => $usable,//可通话分钟数
                                    'room_id' => $roomId,
                                    'form_id' => $From_Account,
                                    'to_id'   => $To_Account,
                                ],
                            ]),
                            //                        "Desc" => "notification",
                            //                        "Ext" => "url",
                            //                        "Sound" => "dingdong.aiff"
                        ],
                    ],
                ],
                "SyncOtherMachine" => 1,
                "From_Account"     => $From_Account,
                "MsgLifeTime"      => 604800,
                "MsgTimeStamp"     => time(),
                "OfflinePushInfo"  => [
                    "PushFlag"    => 0,
                    "Title"       => "这是推送标题",
                    "Desc"        => "这是离线推送内容",
                    "Ext"         => "这是透传的内容",
                    "AndroidInfo" => [
                        "Sound"         => "android.mp3",
                        "OPPOChannelID" => "test_OPPO_channel_id",
                    ],
                    "ApnsInfo"    => [
                        "Sound"     => "apns.mp3",
                        "BadgeMode" => 1,
                        "Title"     => "apns title",
                        "SubTitle"  => "apns subtitle",
                        "Image"     => "www.image.com",
                    ],
                ],
            ];

            return $this->requestPost('/openim/sendmsg', $data);
        } catch (\Exception $ex) {
            return $this->exception($ex, '通话请求自定义信息发送异常');
        }
    }

    public function sendCustom($To_Account, $data, $From_Account = '')
    {
        $data = [
            "To_Account"       => $To_Account,
            "MsgRandom"        => time().rand(),
            "MsgBody"          => [
                [
                    "MsgType"    => 'TIMCustomElem',
                    "MsgContent" => [
                        "Data"  => $data,
                        "Desc"  => "notification",
                        "Ext"   => "url",
                        "Sound" => "dingdong.aiff",
                    ],
                ],
            ],
            "SyncOtherMachine" => 1,
            "From_Account"     => $From_Account,
            "MsgLifeTime"      => 604800,
            "MsgTimeStamp"     => time(),
            "OfflinePushInfo"  => [
                "PushFlag"    => 0,
                "Title"       => "这是推送标题",
                "Desc"        => "这是离线推送内容",
                "Ext"         => "这是透传的内容",
                "AndroidInfo" => [
                    "Sound"         => "android.mp3",
                    "OPPOChannelID" => "test_OPPO_channel_id",
                ],
                "ApnsInfo"    => [
                    "Sound"     => "apns.mp3",
                    "BadgeMode" => 1,
                    "Title"     => "apns title",
                    "SubTitle"  => "apns subtitle",
                    "Image"     => "www.image.com",
                ],
            ],
        ];

        return $this->requestPost('/openim/sendmsg', $data);
    }

    /**
     * 创建群组
     *
     * @param $roomId
     * @param $formAccount
     *
     * @return bool|mixed|string
     */
    public function createGroup($name, $type = 'AVChatRoom', $formAccount = '')
    {
        $data = [
            'Owner_Account' => $formAccount,
            'Type'          => $type,
            'Name'          => $name,
            //            'GroupId' => (string)$roomId
        ];

        $resp = $this->requestPost('/group_open_http_svc/create_group', $data);
        if ($resp['ErrorCode'] == 0) {
            return $this->succeed($resp, '创建成功');
        }

        return $this->failure(1, '创建房间异常', [$resp, $data]);
    }

    /**
     * 获取群信息
     *
     * @param $groups
     *
     * @return bool|mixed|string
     */
    public function getGroupInfo($groups = [])
    {
        $data = [
            'GroupIdList' => $groups,
        ];
        $resp = $this->requestPost('/group_open_http_svc/get_group_info',
            $data);
        if ($resp['ErrorCode'] == 0) {
            return $this->succeed($resp, '成功');
        }

        return $this->failure(1, '异常', [$resp, $data]);
    }

    /**
     * 解散房间
     *
     * @param $roomId
     *
     * @return bool|mixed|string
     */
    public function destroyRoom($roomId)
    {
        $data = [
            'GroupId' => $roomId,
        ];
        $resp = $this->requestPost('/group_open_http_svc/destroy_group', $data);
        if ($resp['ErrorCode'] == 0) {
            return $this->succeed($resp, '创建成功');
        }

        return $this->failure(1, '解散房间异常', [$resp, $data]);
    }

    /**
     * 获取 App 中的所有群组
     *
     * @return bool|mixed|string
     */
    public function getGroupList()
    {
        $resp = $this->requestPost('/group_open_http_svc/get_appid_group_list');
        if ($resp['ErrorCode'] == 0) {
            return $this->succeed($resp, '获取成功');
        }

        return $this->failure(1, '获取 App 中的所有群组异常', [$resp]);
    }

    public function addRoom($From_Account, $ToAccount, $Data = [])
    {
        $MsgRandom  = rand(1000000, 9999999);
        $MsgType    = 'TIMCustomElem';
        $MsgContent = ['Data' => json_encode($Data)];

        return $this->sendMsg($ToAccount, $MsgRandom, $MsgType, $MsgContent,
            $From_Account);
    }

    //HTTP请求（支持HTTP/HTTPS，支持GET/POST）

    /**
     * 单发单聊消息
     *
     * @param $To_Account 消息接收方 Identifier
     * @param  int  $MsgRandom  消息随机数，由随机函数产生（标记该条消息，用于后台定位问题）
     * @param  array  $MsgBody
     * @param $MsgType TIM
     *     消息对象类型，目前支持的消息对象包括：TIMTextElem(文本消息)，TIMFaceElem(表情消息)，TIMLocationElem(位置消息)，TIMCustomElem(自定义消息)
     * @param $MsgContent 对于每种 MsgType 用不同的 MsgContent 格式，具体可参考 消息格式描述
     * @param $SyncOtherMachine
     * @param $From_Account 消息发送方 Identifier（用于指定发送消息方帐号）
     * @param $MsgLifeTime
     * @param $MsgTimeStamp 消息时间戳，UNIX 时间戳（单位：秒）
     * @param $OfflinePushInfo
     *
     * @return mixed https://cloud.tencent.com/document/product/269/2282
     */
    public function sendMsg(
        $To_Account,
        $MsgRandom,
        $MsgType = 'TIMTextElem',
        $MsgContent,
        $From_Account = '',
        $SyncOtherMachine = 1,
        $MsgLifeTime = 604800,
        $MsgTimeStamp = '',
        $OfflinePushInfo = ''
    ) {
        $data = [
            "To_Account"       => $To_Account,
            "MsgRandom"        => $MsgRandom,
            "MsgBody"          => [
                [
                    "MsgType"    => $MsgType,
                    "MsgContent" => $MsgContent,
                ],
            ],
            "SyncOtherMachine" => $SyncOtherMachine,
            //            "From_Account" => $From_Account,
            "MsgLifeTime"      => $MsgLifeTime,
            //            "MsgTimeStamp" => time(),
            //            "OfflinePushInfo" => $OfflinePushInfo,
        ];
        if ($From_Account) {
            $data['From_Account'] = $From_Account;
        }
//        if ($MsgType == 'TIMCustomElem') {
//            $data['SyncOtherMachine'] = 1;
//            $data['MsgLifeTime']      = 604800;
//        }
        if ($MsgType == 'TIMTextElem') {
            $data['OfflinePushInfo'] = [
                "PushFlag"    => 0,
                "Title"       => "这是推送标题",
                "Desc"        => "这是离线推送内容",
                "Ext"         => "这是透传的内容",
                "AndroidInfo" => [
                    "Sound" => "android.mp3",
                ],
                "ApnsInfo"    => [
                    "Sound"     => "apns.mp3",
                    "BadgeMode" => 1,
                    "Title"     => "apns title",
                    "SubTitle"  => "apns subtitle",
                    "Image"     => "www.image.com",
                ],
            ];
        }

        return $this->requestPost('/openim/sendmsg', $data);
    }

}
