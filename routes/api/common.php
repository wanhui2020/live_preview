<?php


Route::group(['prefix' => 'common', 'namespace' => 'Common'], function () {
    Route::post('/code/send', 'CommonController@sendCode'); //短信发送
    Route::post('/code/verify', 'CommonController@verifyCode'); //短信效验
    Route::post('/edition', 'CommonController@edition'); //版本获取
    Route::post('/init', 'CommonController@init'); //系统初始化
    Route::post('/text/list', 'CommonController@textList'); //用户协议和隐私协议
    Route::post('/log', 'CommonController@log');// app错误信息收集
});
