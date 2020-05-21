<?php

Route::post('/sign', 'BaseController@sign');
Route::group([
    'prefix'     => 'member/platform',
    'namespace'  => 'Member\\Platform',
], function () {
    Route::post('/config', 'PlatformController@config'); //平台参数
    Route::get('/text/helpCenter', 'PlatformController@helpCenter'); //自拍和邀请的文本
});


Route::group([
    'prefix'     => 'member/user',
    'namespace'  => 'Member\\User',
], function () {
//    Route::get('/offline_payment', 'UserController@offlinePayment'); //线下支付
});