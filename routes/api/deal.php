<?php
//订单管理
Route::group([
    'prefix'     => 'member/deal',
    'namespace'  => 'Member\\Deal',
    'middleware' => ['auth:ApiMember', 'XSS'],
], function () {
    //余额充值
    Route::group(['prefix' => 'cash'], function () {
        Route::post('/store', 'CashController@store'); //余额充值
    });

    // 聊天解锁
    Route::group(['prefix' => 'chat'], function () {
        Route::post('/store', 'ChatController@store'); //聊天解锁
    });

    // 兑换余额
    Route::group(['prefix' => 'conversion'], function () {
        Route::post('/store', 'ConversionController@store'); //兑换余额
    });

    // 会员礼物
    Route::group(['prefix' => 'gift'], function () {
        Route::post('/lists', 'GiftController@lists'); // 会员礼物  我的礼物 别人的礼物
        Route::post('/store', 'GiftController@store'); //礼物赠送
    });

    // 会员打赏
    Route::group(['prefix' => 'give'], function () {
//        Route::post('/lists', 'GiveController@lists'); //会员打赏
        Route::post('/store', 'GiveController@store'); //会员打赏
    });

    // 能量充值返回访问记录成功"
    Route::group(['prefix' => 'gold'], function () {
        Route::post('/store', 'GoldController@store'); //能量充值
    });

    // 音视频通话
    Route::group(['prefix' => 'talk'], function () {
//        Route::post('/lists', 'TalkController@lists'); //发起语音视频
        Route::post('/store', 'TalkController@store'); //发起语音视频
        Route::post('/answer', 'TalkController@answer'); //语音视频接听
        Route::post('/hangup', 'TalkController@hangup'); //语音视频挂断
        Route::post('/query', 'TalkController@query'); //查询订单状态
    });


    // 资源查看 图片查看 视频查看
    Route::group(['prefix' => 'view'], function () {
//        Route::post('/lists', 'ViewController@lists'); //资源查看 图片查看 视频查看
        Route::post('/store', 'ViewController@store'); //资源查看 图片查看 视频查看
    });

    // vip购买
    Route::group(['prefix' => 'vip'], function () {
//        Route::post('/lists', 'VipController@lists'); //vip购买
        Route::post('/store', 'VipController@store'); //vip购买
    });

    // 余额提现
    Route::group(['prefix' => 'withdraw'], function () {
        Route::post('/lists', 'WithdrawController@lists'); //余额提现列表
        Route::post('/apply', 'WithdrawController@apply'); //余额提现申请
        Route::post('/store', 'WithdrawController@store'); //余额提现
    });
});
