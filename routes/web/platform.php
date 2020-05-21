<?php

// 平台参数配置
Route::group([
    'prefix'     => 'system/platform',
    'namespace'  => 'System\\Platform',
    'middleware' => [
        'auth:SystemUser',
        'XSS',
    ],
], function () {
    // 平台参数
    Route::group(['prefix' => 'config'], function () {
        Route::get('/', 'ConfigController@edit');
        Route::post('/update', 'ConfigController@update');
    });
    // 系统公告
    Route::group(['prefix' => 'notice'], function () {
        Route::get('/', 'NoticeController@index');
        Route::any('/lists', 'NoticeController@lists');
        Route::get('/create', 'NoticeController@create');
        Route::post('/store', 'NoticeController@store');
        Route::get('/edit', 'NoticeController@edit');
        Route::post('/update', 'NoticeController@update');
        Route::post('/status', 'NoticeController@status');
        Route::post('/destroy', 'NoticeController@destroy');
    });
    // 新闻通知
    Route::group(['prefix' => 'message'], function () {
        Route::get('/', 'MessageController@index');
        Route::any('/lists', 'MessageController@lists');
        Route::get('/create', 'MessageController@create');
        Route::post('/store', 'MessageController@store');
        Route::get('/edit', 'MessageController@edit');
        Route::post('/update', 'MessageController@update');
        Route::post('/destroy', 'MessageController@destroy');
        Route::post('/status', 'MessageController@status');
    });
    // 关键字管理
    Route::group(['prefix' => 'keyword'], function () {
        Route::get('/', 'KeywordController@index');
        Route::any('/lists', 'KeywordController@lists');
        Route::get('/create', 'KeywordController@create');
        Route::post('/store', 'KeywordController@store');
        Route::get('/edit', 'KeywordController@edit');
        Route::post('/update', 'KeywordController@update');
        Route::post('/status', 'KeywordController@status');
        Route::post('/destroy', 'KeywordController@destroy');
    });
    // 标签管理
    Route::group(['prefix' => 'tag'], function () {
        Route::get('/', 'TagController@index');
        Route::any('/lists', 'TagController@lists');
        Route::get('/create', 'TagController@create');
        Route::post('/store', 'TagController@store');
        Route::get('/edit', 'TagController@edit');
        Route::post('/update', 'TagController@update');
        Route::post('/destroy', 'TagController@destroy');
        Route::post('/status', 'TagController@status');
    });
    // 礼物管理
    Route::group(['prefix' => 'gift'], function () {
        Route::get('/', 'GiftController@index');
        Route::any('/lists', 'GiftController@lists');
        Route::get('/create', 'GiftController@create');
        Route::post('/store', 'GiftController@store');
        Route::get('/edit', 'GiftController@edit');
        Route::post('/update', 'GiftController@update');
        Route::post('/destroy', 'GiftController@destroy');
        Route::post('/status', 'GiftController@status');
    });
    // 平台基础数据
    Route::group(['prefix' => 'basic'], function () {
        Route::get('/', 'BasicController@index');
        Route::any('/lists', 'BasicController@lists');
        Route::get('/create', 'BasicController@create');
        Route::post('/store', 'BasicController@store');
        Route::get('/edit', 'BasicController@edit');
        Route::post('/update', 'BasicController@update');
        Route::post('/status', 'BasicController@status');
        Route::post('/destroy', 'BasicController@destroy');
    });
    // VIP管理
    Route::group(['prefix' => 'vip'], function () {
        Route::get('/', 'VipController@index');
        Route::any('/lists', 'VipController@lists');
        Route::get('/create', 'VipController@create');
        Route::post('/store', 'VipController@store');
        Route::get('/edit', 'VipController@edit');
        Route::post('/update', 'VipController@update');
        Route::post('/destroy', 'VipController@destroy');
        Route::post('/status', 'VipController@status');
    });
    // 魅力管理
    Route::group(['prefix' => 'charm'], function () {
        Route::get('/', 'CharmController@index');
        Route::any('/lists', 'CharmController@lists');
        Route::get('/create', 'CharmController@create');
        Route::post('/store', 'CharmController@store');
        Route::get('/edit', 'CharmController@edit');
        Route::post('/update', 'CharmController@update');
        Route::post('/destroy', 'CharmController@destroy');
        Route::post('/status', 'CharmController@status');
    });

    // 支付通道
    Route::group(['prefix' => 'payment'], function () {
        Route::get('/', 'PaymentController@index');
        Route::any('/lists', 'PaymentController@lists');
        Route::get('/create', 'PaymentController@create');
        Route::post('/store', 'PaymentController@store');
        Route::get('/edit', 'PaymentController@edit');
        Route::post('/update', 'PaymentController@update');
        Route::post('/destroy', 'PaymentController@destroy');
        Route::post('/status', 'PaymentController@status');
        // 支付通道
        Route::group(['prefix' => 'channel'], function () {
            Route::get('/', 'PaymentChannelController@index');
            Route::any('/lists', 'PaymentChannelController@lists');
            Route::get('/create', 'PaymentChannelController@create');
            Route::post('/store', 'PaymentChannelController@store');
            Route::get('/edit', 'PaymentChannelController@edit');
            Route::post('/update', 'PaymentChannelController@update');
            Route::post('/destroy', 'PaymentChannelController@destroy');
            Route::post('/status', 'PaymentChannelController@status');
        });
    });
    // 充值价格维护
    Route::group(['prefix' => 'price'], function () {
        Route::get('/', 'PriceController@index');
        Route::any('/lists', 'PriceController@lists');
        Route::get('/create', 'PriceController@create');
        Route::post('/store', 'PriceController@store');
        Route::get('/edit', 'PriceController@edit');
        Route::post('/update', 'PriceController@update');
        Route::post('/destroy', 'PriceController@destroy');
        Route::post('/status', 'PriceController@status');
    });
    // 应用版本
    Route::group(['prefix' => 'edition'], function () {
        Route::get('/', 'EditionController@index');
        Route::any('/lists', 'EditionController@lists');
        Route::get('/create', 'EditionController@create');
        Route::post('/store', 'EditionController@store');
        Route::get('/edit', 'EditionController@edit');
        Route::post('/update', 'EditionController@update');
        Route::post('/destroy', 'EditionController@destroy');
        Route::post('/status', 'EditionController@status');
    });
    // 文本维护
    Route::group(['prefix' => 'text'], function () {
        Route::get('/', 'TextController@index');
        Route::any('/lists', 'TextController@lists');
        Route::get('/create', 'TextController@create');
        Route::post('/store', 'TextController@store');
        Route::get('/edit', 'TextController@edit');
        Route::post('/update', 'TextController@update');
        Route::post('/destroy', 'TextController@destroy');
        Route::post('/status', 'TextController@status');
    });
    // 配置文件
   Route::group(['prefix' => 'env'], function () {
        Route::get('/edit', 'EnvController@edit');
        Route::post('/update', 'EnvController@update');
    });

    // 消息内容管理
    Route::group(['prefix' => 'send'], function () {
        Route::get('/', 'SendMessageController@index');
        Route::any('/lists', 'SendMessageController@lists');
        Route::get('/create', 'SendMessageController@create');
        Route::post('/store', 'SendMessageController@store');
        Route::get('/edit', 'SendMessageController@edit');
        Route::post('/update', 'SendMessageController@update');
        Route::post('/destroy', 'SendMessageController@destroy');
        Route::post('/status', 'SendMessageController@status');
    });
    // 首页类型管理
    Route::group(['prefix' => 'type'], function () {
        Route::get('/', 'TypeController@index');
        Route::any('/lists', 'TypeController@lists');
        Route::get('/create', 'TypeController@create');
        Route::post('/store', 'TypeController@store');
        Route::get('/edit', 'TypeController@edit');
        Route::post('/update', 'TypeController@update');
        Route::post('/destroy', 'TypeController@destroy');
        Route::post('/status', 'TypeController@status');
    });
    // 首页类型条件筛选管理
    Route::group(['prefix' => 'condition'], function () {
        Route::get('/', 'ConditionController@index');
        Route::any('/lists', 'ConditionController@lists');
        Route::get('/create', 'ConditionController@create');
        Route::post('/store', 'ConditionController@store');
        Route::get('/edit', 'ConditionController@edit');
        Route::post('/update', 'ConditionController@update');
        Route::post('/destroy', 'ConditionController@destroy');
        Route::post('/status', 'ConditionController@status');
    });
});
