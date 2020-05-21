<?php

Route::group([
    'prefix'     => 'member/user',
    'namespace'  => 'Member\\User',
    'middleware' => ['auth:ApiMember', 'XSS'],
], function () {
    Route::post('/my', 'UserController@my'); //我的会员详情
    Route::post('/sync', 'UserController@sync'); //会员信息同步
    Route::post('/update', 'UserController@update'); //会员信息更新
    Route::post('/detail', 'UserController@detail'); //会员信息详情
    Route::post('/lists', 'UserController@lists'); //会员信息列表
    Route::post('/services', 'UserController@services'); //在线客服
    Route::post('/search', 'UserController@search'); //首页右上角的搜索
    Route::post('/share', 'UserController@share'); //分享

    Route::post('/childrens', 'UserController@childrens'); //我的下级
    Route::post('/ranking', 'UserController@ranking'); //会员排行
    Route::post('/getWechat', 'UserController@getWechat'); //查看微信
    Route::post('/get_send_message', 'UserController@getSendMessage'); //在线客服发送消息
    Route::get('/get_types', 'UserController@getTypes'); //首页显示类型
    Route::get('/offline_payment', 'UserController@offlinePayment'); //线下支付

    //实名认证
    Route::group(['prefix' => 'realname'], function () {
        Route::post('/bind', 'RealnameController@bind'); //实名信息绑定
        Route::post('/result', 'RealnameController@result'); //实名结果查询
    });

    //访问相关
    Route::group(['prefix' => 'record'], function () {
        Route::post('/lists', 'VisitorController@lists'); //首页右上角的访问记录
        Route::post('/del', 'VisitorController@del'); //首页右上角的访问记录删除
    });
    //自拍认证
    Route::group(['prefix' => 'selfie'], function () {
        Route::post('/store', 'SelfieController@store'); // 自拍认证申请
    });
    //费率设置
    Route::group(['prefix' => 'rate'], function () {
        Route::post('/detail', 'RateController@detail'); // 会员费率获取
        Route::post('/update', 'RateController@update'); // 会员费率设置
    });
    //参数设置
    Route::group(['prefix' => 'parameter'], function () {
        Route::post('/update', 'ParameterController@update');
    });
    //扩展设置
    Route::group(['prefix' => 'extend'], function () {
        Route::post('/update', 'ExtendController@update');
    });

    // 动态
    Route::group(['prefix' => 'dynamic'], function () {
        Route::get('/lists', 'DynamicController@lists'); // 列表
        Route::post('/store', 'DynamicController@store'); // 新增
        Route::delete('/destroy', 'DynamicController@destroy'); // 删除
        Route::get('/detail', 'DynamicController@detail'); // 详情
    });
});
