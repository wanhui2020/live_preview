<?php

Route::group(['prefix' => 'common', 'namespace' => 'Common'], function () {
    // 发送短信路由
    Route::post('/code', 'SmsController@sendCode')->name('sendmsg');
    // 图片上传
    //    Route::post('/put', 'OssController@putObject');
    Route::post('/put', 'OssController@ossPut');
    Route::post('/put/layedit', 'OssController@ossPutForLayedit');
    // oss图片上传
    Route::any('/ossput', 'OssController@ossput');
    // 支付回调
    Route::any('/jhpayback', 'PayController@jhPayCallback');
    //汇潮支付
    Route::any('/hcpay', 'PaymentController@hcpay');
    //恒云支付测试
    Route::any('/hypay', 'PaymentController@hypay');
    //汇潮支付成功返回页面
    Route::any('/runturnback', 'PaymentController@runturnback');
    //支付宝支付回调
    Route::any('/alipay', 'PayController@alipay');
    Route::any('/alipayback', 'PayController@alipayCallback');

    Route::post('/market', 'MarketController@nowPrice');
});

Route::group(['prefix' => 'common'], function () {
    Route::any('/test', 'HomeController@test');
});


