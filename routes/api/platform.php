<?php

Route::group([
    'prefix'     => 'member/platform',
    'namespace'  => 'Member\\Platform',
    'middleware' => ['auth:ApiMember', 'XSS'],
], function () {
    Route::post('/basic', 'PlatformController@basic'); //基础数据
    Route::post('/gifts', 'PlatformController@gifts'); //礼物配置
    Route::post('/payments', 'PlatformController@payments'); //支付账号
    Route::post('/payment/channels', 'PlatformController@channels'); //支付通到
    Route::post('/prices', 'PlatformController@prices'); //金币兑换
    Route::post('/tags', 'PlatformController@tags'); //首页标签
    Route::post('/text/list', 'PlatformController@textList'); //自拍和邀请的文本
    Route::post('/vips', 'PlatformController@vips'); //vip参数列表
    Route::post('/messages', 'PlatformController@messages'); //新闻公告
    Route::get('/detail', 'PlatformController@detail'); //删除通知
    //系统通知
    Route::group(['prefix' => 'notice'], function () {
        Route::post('/lists', 'NoticeController@lists'); //通知列表
        Route::post('/destroy', 'NoticeController@destroy'); //删除通知
    });
});
