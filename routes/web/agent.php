<?php

//渠道管理
Route::group([
    'prefix'     => 'system/agent',
    'namespace'  => 'System\\Agent',
    'middleware' => [
        'auth:SystemUser',
        'XSS',
    ],
], function () {
    //会员账户
    Route::group(['prefix' => 'user', 'namespace' => 'User'], function () {
        Route::get('/', 'UserController@index');
        Route::any('/lists', 'UserController@lists');
        Route::get('/create', 'UserController@create');
        Route::post('/store', 'UserController@store');
        Route::get('/edit', 'UserController@edit');
        Route::post('/update', 'UserController@update');
        Route::post('/destroy', 'UserController@destroy');
        Route::post('/status', 'UserController@status');
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
    });
});
