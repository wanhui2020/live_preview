<?php

//会员登录
Route::group([
    'prefix'    => 'member/auth',
    'namespace' => 'Member\\Auth',
], function () {
    Route::post('/login/weixin', 'AuthController@loginWeixin'); // 微信登录
    Route::post('/login/mobile', 'AuthController@loginMobile'); // 手机号登录
    Route::post('/logout', 'AuthController@logout'); // 退出
});
