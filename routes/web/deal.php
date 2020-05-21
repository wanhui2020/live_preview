<?php

Route::group([
    'prefix'     => 'system/deal',
    'namespace'  => 'System\\Deal',
    'middleware' => [
        'auth:SystemUser',
        'XSS',
    ],
], function () {
    //视频
    Route::group(['prefix' => 'talk'], function () {
        Route::get('/', 'TalkController@index');
        Route::any('/lists', 'TalkController@lists');
        Route::get('/create', 'TalkController@create');
        Route::post('/store', 'TalkController@store');
        Route::get('/edit', 'TalkController@edit');
        Route::post('/update', 'TalkController@update');
        Route::post('/destroy', 'TalkController@destroy');
        Route::post('/answer', 'TalkController@answer');
        Route::post('/hangup', 'TalkController@hangup');
        Route::post('/refuse', 'TalkController@refuse');
        Route::post('/finish', 'TalkController@finish');
    });

    //聊天解锁
    Route::group(['prefix' => 'unlock'], function () {
        Route::get('/', 'UnlockController@index');
        Route::any('/lists', 'UnlockController@lists');
        Route::get('/create', 'UnlockController@create');
        Route::post('/store', 'UnlockController@store');
        Route::get('/edit', 'UnlockController@edit');
        Route::post('/update', 'UnlockController@update');
        Route::post('/destroy', 'UnlockController@destroy');
    });
    //聊天计费
    Route::group(['prefix' => 'chat'], function () {
        Route::get('/', 'ChatController@index');
        Route::any('/lists', 'ChatController@lists');
        Route::get('/create', 'ChatController@create');
        Route::post('/store', 'ChatController@store');
        Route::get('/edit', 'ChatController@edit');
        Route::post('/update', 'ChatController@update');
        Route::post('/destroy', 'ChatController@destroy');
    });
    //聊天记录
    Route::group(['prefix' => 'message'], function () {
        Route::get('/', 'MessageController@index');
        Route::any('/lists', 'MessageController@lists');
        Route::get('/create', 'MessageController@create');
        Route::post('/store', 'MessageController@store');
        Route::get('/edit', 'MessageController@edit');
        Route::post('/update', 'MessageController@update');
        Route::post('/destroy', 'MessageController@destroy');
    });


    //资源查看
    Route::group(['prefix' => 'view'], function () {
        Route::get('/', 'ViewController@index');
        Route::any('/lists', 'ViewController@lists');
        Route::get('/create', 'ViewController@create');
        Route::post('/store', 'ViewController@store');
        Route::get('/edit', 'ViewController@edit');
        Route::post('/update', 'ViewController@update');
        Route::post('/destroy', 'ViewController@destroy');
        Route::get('/detail', 'ViewController@detail');
    });
    //vip购买记录
    Route::group(['prefix' => 'vip'], function () {
        Route::get('/', 'VipController@index');
        Route::any('/lists', 'VipController@lists');
        Route::get('/create', 'VipController@create');
        Route::post('/store', 'VipController@store');
        Route::get('/edit', 'VipController@edit');
        Route::post('/update', 'VipController@update');
        Route::post('/destroy', 'VipController@destroy');
        Route::post('/pay', 'VipController@pay');
        Route::post('/cancel', 'VipController@cancel');
    });
    //社交动态
    Route::group(['prefix' => 'social'], function () {
        Route::get('/', 'SocialController@index');
        Route::any('/lists', 'SocialController@lists');
        Route::get('/create', 'SocialController@create');
        Route::post('/store', 'SocialController@store');
        Route::get('/edit', 'SocialController@edit');
        Route::post('/update', 'SocialController@update');
        Route::post('/destroy', 'SocialController@destroy');
        Route::post('/audit', 'SocialController@audit');
    });
    //会员评论
    Route::group(['prefix' => 'comment'], function () {
        Route::get('/', 'CommentController@index');
        Route::any('/lists', 'CommentController@lists');
        Route::get('/create', 'CommentController@create');
        Route::post('/store', 'CommentController@store');
        Route::get('/edit', 'CommentController@edit');
        Route::post('/update', 'CommentController@update');
        Route::post('/destroy', 'CommentController@destroy');
        Route::post('/audit', 'CommentController@audit');
    });
    //会员点赞
    Route::group(['prefix' => 'like'], function () {
        Route::get('/', 'LikeController@index');
        Route::any('/lists', 'LikeController@lists');
        Route::get('/create', 'LikeController@create');
        Route::post('/store', 'LikeController@store');
        Route::get('/edit', 'LikeController@edit');
        Route::post('/update', 'LikeController@update');
        Route::post('/destroy', 'LikeController@destroy');
        Route::post('/audit', 'LikeController@audit');
    });

    //主播打赏
    Route::group(['prefix' => 'give'], function () {
        Route::get('/', 'GiveController@index');
        Route::any('/lists', 'GiveController@lists');
        Route::get('/create', 'GiveController@create');
        Route::post('/store', 'GiveController@store');
        Route::get('/edit', 'GiveController@edit');
        Route::post('/update', 'GiveController@update');
        Route::post('/destroy', 'GiveController@destroy');
        Route::post('/pay', 'GiveController@pay');
        Route::post('/cancel', 'GiveController@cancel');
    });
    //礼物赠送
    Route::group(['prefix' => 'gift'], function () {
        Route::get('/', 'GiftController@index');
        Route::any('/lists', 'GiftController@lists');
        Route::get('/create', 'GiftController@create');
        Route::post('/store', 'GiftController@store');
        Route::get('/edit', 'GiftController@edit');
        Route::post('/update', 'GiftController@update');
        Route::post('/destroy', 'GiftController@destroy');
    });
    //金币购买记录
    Route::group(['prefix' => 'gold'], function () {
        Route::get('/', 'GoldController@index');
        Route::any('/lists', 'GoldController@lists');
        Route::get('/create', 'GoldController@create');
        Route::post('/store', 'GoldController@store');
        Route::get('/edit', 'GoldController@edit');
        Route::post('/update', 'GoldController@update');
        Route::post('/destroy', 'GoldController@destroy');
        Route::post('/pay', 'GoldController@pay');
        Route::post('/cancel', 'GoldController@cancel');
    });
    //余额兑换记录
    Route::group(['prefix' => 'conversion'], function () {
        Route::get('/', 'ConversionController@index');
        Route::any('/lists', 'ConversionController@lists');
        Route::get('/create', 'ConversionController@create');
        Route::post('/store', 'ConversionController@store');
        Route::get('/edit', 'ConversionController@edit');
        Route::post('/update', 'ConversionController@update');
        Route::post('/destroy', 'ConversionController@destroy');
        Route::post('/audit', 'CashController@audit');
    });
    //余额购买记录
    Route::group(['prefix' => 'cash'], function () {
        Route::get('/', 'CashController@index');
        Route::any('/lists', 'CashController@lists');
        Route::get('/create', 'CashController@create');
        Route::post('/store', 'CashController@store');
        Route::get('/edit', 'CashController@edit');
        Route::post('/update', 'CashController@update');
        Route::post('/destroy', 'CashController@destroy');
        Route::post('/pay', 'CashController@pay');
        Route::post('/cancel', 'CashController@cancel');
    });
    //余额提现记录
    Route::group(['prefix' => 'withdraw'], function () {
        Route::get('/', 'WithdrawController@index');
        Route::any('/lists', 'WithdrawController@lists');
        Route::get('/create', 'WithdrawController@create');
        Route::post('/store', 'WithdrawController@store');
        Route::get('/edit', 'WithdrawController@edit');
        Route::post('/update', 'WithdrawController@update');
        Route::post('/destroy', 'WithdrawController@destroy');
        Route::post('/pay', 'WithdrawController@pay');
        Route::post('/cancel', 'WithdrawController@cancel');
    });
});
