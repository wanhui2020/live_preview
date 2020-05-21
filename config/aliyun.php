<?php

return [
    // 实人认证
    'face'        => [
        'scene'         => env('ALIYUN_FACE_SCENE'),
        'access_key'    => env('ALIYUN_FACE_KEY_ID', env('ALI_ACCESS_KEY_ID')),
        'access_secret' => env('ALIYUN_FACE_KEY_SECRET',
            env('ALI_ACCESS_KEY_SECRET')),
    ],
    // 移动推送
    'mobile_push' => [
        'android_app_id' => env('ALI_PUSH_ANDROID'),
        'ios_app_id'     => env('ALI_PUSH_IOS'),
    ],
    // 访问控制
    'access'      => [
        'key'    => env('ALI_ACCESS_KEY_ID'),
        'secret' => env('ALI_ACCESS_KEY_SECRET'),
    ],

    // oss
    'oss'         => [
        'access_key'    => env('OSS_ACCESS_KEY_ID', env('ALI_ACCESS_KEY_ID')),
        'access_secret' => env('OSS_ACCESS_KEY_SECRET',
            env('ALI_ACCESS_KEY_SECRET')),
        'url'           => env('OSS_URL'),
        'bucket'        => env('OSS_BUCKET'),
        'directory'     => env('OSS_DIRECTORY'),
    ],

];
