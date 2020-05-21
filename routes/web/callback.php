<?php

Route::group(['prefix' => 'callback', 'namespace' => 'Callback'], function () {
    // IM回调
    Route::post('/im', 'ImController@index');
    Route::post('/aliyun/green', 'AliyunController@green');//安全检查回调
    Route::post('/pay/alipay', 'PayController@alipay');
    Route::post('/hcpayback', 'PayController@hcpayback');//汇潮支付回调
    Route::any('/hypayback', 'PayController@hypayback');//恒云支付回调
    Route::post('/pay/weixin', 'PayController@weixin');
    Route::any('/wechat', 'WechatController@index');
    Route::any('/wechat/oauth', 'WechatController@oauth');
});
