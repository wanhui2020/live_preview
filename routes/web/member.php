<?php

Route::group([
    'prefix'     => 'system/member',
    'namespace'  => 'System\\Member',
    'middleware' => [
        'auth:SystemUser',
        'XSS',
    ],
], function () {
    //会员账户
    Route::group(['prefix' => 'user', 'namespace' => 'User'], function () {
        Route::get('/', 'UserController@index');
        Route::any('/lists', 'UserController@lists');
        Route::get('/detail', 'UserController@detail');
        Route::get('/create', 'UserController@create');
        Route::post('/store', 'UserController@store');
        Route::get('/edit', 'UserController@edit');
        Route::post('/update', 'UserController@update');
        Route::post('/destroy', 'UserController@destroy');
        Route::post('/status', 'UserController@status');
        Route::post('/robot', 'UserController@robot');//创建机器人
        Route::post('/im/status', 'UserController@imStatus');
        Route::post('/im/multi', 'UserController@imMultiCheck');
        Route::post('/getData', 'UserController@getData');
        //用户报表
        Route::get('/report_form', 'UserController@reportForm');
        Route::any('/report_form_lists', 'UserController@reportFormLists');

        //会员参数
        Route::group(['prefix' => 'parameter'], function () {
            Route::get('/', 'ParameterController@index');
            Route::any('/lists', 'ParameterController@lists');
            Route::get('/create', 'ParameterController@create');
            Route::post('/store', 'ParameterController@store');
            Route::get('/edit', 'ParameterController@edit');
            Route::post('/update', 'ParameterController@update');
            Route::post('/destroy', 'ParameterController@destroy');
            Route::post('/audit', 'ParameterController@audit'); //审核
        });

        //实名认证
        Route::group(['prefix' => 'realname'], function () {
            Route::get('/', 'RealnameController@index');
            Route::any('/lists', 'RealnameController@lists');
            Route::get('/create', 'RealnameController@create');
            Route::post('/store', 'RealnameController@store');
            Route::get('/edit', 'RealnameController@edit');
            Route::post('/update', 'RealnameController@update');
            Route::post('/destroy', 'RealnameController@destroy');
            Route::post('/audit', 'RealnameController@audit'); //审核
        });
        //自拍认证
        Route::group(['prefix' => 'selfie'], function () {
            Route::get('/', 'SelfieController@index');
            Route::any('/lists', 'SelfieController@lists');
            Route::get('/create', 'SelfieController@create');
            Route::post('/store', 'SelfieController@store');
            Route::get('/edit', 'SelfieController@edit');
            Route::post('/update', 'SelfieController@update');
            Route::post('/destroy', 'SelfieController@destroy');
            Route::post('/audit', 'SelfieController@audit'); //审核
        });
        //会员费率
        Route::group(['prefix' => 'rate'], function () {
            Route::get('/', 'RateController@index');
            Route::any('/lists', 'RateController@lists');
            Route::get('/create', 'RateController@create');
            Route::post('/store', 'RateController@store');
            Route::get('/edit', 'RateController@edit');
            Route::post('/update', 'RateController@update');
            Route::post('/destroy', 'RateController@destroy');
        });
        //会员扩展
        Route::group(['prefix' => 'extend'], function () {
            Route::get('/', 'ExtendController@index');
            Route::any('/lists', 'ExtendController@lists');
            Route::get('/create', 'ExtendController@create');
            Route::post('/store', 'ExtendController@store');
            Route::get('/edit', 'ExtendController@edit');
            Route::post('/update', 'ExtendController@update');
            Route::post('/destroy', 'ExtendController@destroy');
        });
    });

    //资料审核
    Route::group(['prefix' => 'verification'], function () {
        Route::get('/', 'VerificationController@index');
        Route::any('/lists', 'VerificationController@lists');
        Route::get('/create', 'VerificationController@create');
        Route::post('/store', 'VerificationController@store');
        Route::get('/edit', 'VerificationController@edit');
        Route::post('/update', 'VerificationController@update');
        Route::post('/destroy', 'VerificationController@destroy');
        Route::post('/audit', 'VerificationController@audit'); //审核
    });

    //登录日志
    Route::group(['prefix' => 'login'], function () {
        Route::get('/', 'LoginController@index');
        Route::any('/lists', 'LoginController@lists');
        Route::get('/create', 'LoginController@create');
        Route::post('/store', 'LoginController@store');
        Route::get('/edit', 'LoginController@edit');
        Route::post('/update', 'LoginController@update');
        Route::post('/destroy', 'LoginController@destroy');
    });

    //会员关注
    Route::group(['prefix' => 'attention'], function () {
        Route::get('/', 'AttentionController@index');
        Route::any('/lists', 'AttentionController@lists');
        Route::get('/create', 'AttentionController@create');
        Route::post('/store', 'AttentionController@store');
        Route::get('/edit', 'AttentionController@edit');
        Route::post('/update', 'AttentionController@update');
        Route::post('/destroy', 'AttentionController@destroy');
    });
    //会员拉黑
    Route::group(['prefix' => 'blacklist'], function () {
        Route::get('/', 'BlacklistController@index');
        Route::any('/lists', 'BlacklistController@lists');
        Route::get('/create', 'BlacklistController@create');
        Route::post('/store', 'BlacklistController@store');
        Route::get('/edit', 'BlacklistController@edit');
        Route::post('/update', 'BlacklistController@update');
        Route::post('/destroy', 'BlacklistController@destroy');
    });
    //会员好友
    Route::group(['prefix' => 'friend'], function () {
        Route::get('/', 'FriendController@index');
        Route::any('/lists', 'FriendController@lists');
        Route::get('/create', 'FriendController@create');
        Route::post('/store', 'FriendController@store');
        Route::get('/edit', 'FriendController@edit');
        Route::post('/update', 'FriendController@update');
        Route::post('/destroy', 'FriendController@destroy');
    });
    //意见反馈
    Route::group(['prefix' => 'feedback'], function () {
        Route::get('/', 'FeedbackController@index');
        Route::any('/lists', 'FeedbackController@lists');
        Route::get('/create', 'FeedbackController@create');
        Route::post('/store', 'FeedbackController@store');
        Route::get('/edit', 'FeedbackController@edit');
        Route::post('/update', 'FeedbackController@update');
        Route::post('/destroy', 'FeedbackController@destroy');
    });
    //会员举报
    Route::group(['prefix' => 'report'], function () {
        Route::get('/', 'ReportController@index');
        Route::any('/lists', 'ReportController@lists');
        Route::get('/create', 'ReportController@create');
        Route::post('/store', 'ReportController@store');
        Route::get('/edit', 'ReportController@edit');
        Route::post('/update', 'ReportController@update');
        Route::post('/destroy', 'ReportController@destroy');
    });

    //会员签到
    Route::group(['prefix' => 'signin'], function () {
        Route::get('/', 'SigninController@index');
        Route::any('/lists', 'SigninController@lists');
        Route::get('/create', 'SigninController@create');
        Route::post('/store', 'SigninController@store');
        Route::get('/edit', 'SigninController@edit');
        Route::post('/update', 'SigninController@update');
        Route::post('/destroy', 'SigninController@destroy');
    });
    //访问记录
    Route::group(['prefix' => 'visitor'], function () {
        Route::get('/', 'VisitorController@index');
        Route::any('/lists', 'VisitorController@lists');
        Route::get('/create', 'VisitorController@create');
        Route::post('/store', 'VisitorController@store');
        Route::get('/edit', 'VisitorController@edit');
        Route::post('/update', 'VisitorController@update');
        Route::post('/destroy', 'VisitorController@destroy');
    });
    //会员资源
    Route::group(['prefix' => 'resource'], function () {
        Route::get('/', 'ResourceController@index');
        Route::any('/lists', 'ResourceController@lists');
        Route::get('/create', 'ResourceController@create');
        Route::post('/store', 'ResourceController@store');
        Route::get('/edit', 'ResourceController@edit');
        Route::post('/update', 'ResourceController@update');
        Route::post('/destroy', 'ResourceController@destroy');
        Route::post('/audit', 'ResourceController@audit');
    });
    //会员动态
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
    //会员钱包
    Route::group(['prefix' => 'wallet', 'namespace' => 'Wallet'], function () {
        Route::get('/', 'WalletController@index');
        Route::any('/lists', 'WalletController@lists');
        Route::get('/create', 'WalletController@create');
        Route::post('/store', 'WalletController@store');
        Route::get('/edit', 'WalletController@edit');
        Route::post('/update', 'WalletController@update');
        Route::post('/destroy', 'WalletController@destroy');
        //现金钱包
        Route::group(['prefix' => 'cash'], function () {
            Route::get('/', 'CashController@index');
            Route::any('/lists', 'CashController@lists');
        });
        //金币钱包
        Route::group(['prefix' => 'gold'], function () {
            Route::get('/', 'GoldController@index');
            Route::any('/lists', 'GoldController@lists');
        });

        //充值
        Route::group(['prefix' => 'recharge'], function () {
            Route::get('/', 'RechargeController@index');
            Route::any('/lists', 'RechargeController@lists');
            Route::get('/create', 'RechargeController@create');
            Route::post('/store', 'RechargeController@store');
            Route::get('/edit', 'RechargeController@edit');
            Route::post('/update', 'RechargeController@update');
            Route::post('/destroy', 'RechargeController@destroy');
            Route::post('/audit', 'RechargeController@audit');
            Route::post('/pay', 'RechargeController@pay');
        });
        //提现
        Route::group(['prefix' => 'withdraw'], function () {
            Route::get('/', 'WithdrawController@index');
            Route::any('/lists', 'WithdrawController@lists');
            Route::get('/create', 'WithdrawController@create');
            Route::post('/store', 'WithdrawController@store');
            Route::get('/edit', 'WithdrawController@edit');
            Route::post('/update', 'WithdrawController@update');
            Route::post('/destroy', 'WithdrawController@destroy');
            Route::post('/audit', 'WithdrawController@audit');
        });

        //金币明细账
        Route::group(['prefix' => 'record'], function () {
            Route::get('/', 'RecordController@index');
            Route::any('/lists', 'RecordController@lists');
            Route::post('/destroy', 'RecordController@destroy');
        });

    });


});
