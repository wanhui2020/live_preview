<?php

//统计报表
Route::group([
    'prefix'     => 'system/report',
    'namespace'  => 'System\\Report',
    'middleware' => [
        'auth:SystemUser',
        'XSS',
    ],
], function () {
    //资金相关
    Route::group(['prefix' => 'finace'], function () {
        Route::get('/charge', 'FinaceController@charge'); //充值报表
        Route::any('/chargelist', 'FinaceController@chargeList');
        Route::get('/withdraw', 'FinaceController@withdraw'); //提现报表
        Route::any('/withdrawlist', 'FinaceController@withdrawList');
        Route::get('/deferred', 'FinaceController@deferred'); //递延费报表
        Route::any('/deferredlist', 'FinaceController@deferredList');
        Route::any('/download', 'FinaceController@csvDownload'); // 报表导出
        Route::post('/getcollect', 'FinaceController@getCollect'); // 表头汇总
    });
});
