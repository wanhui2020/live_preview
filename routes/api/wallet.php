<?php

//钱包管理
Route::group([
    'prefix'     => 'member/wallet',
    'namespace'  => 'Member\\Wallet',
    'middleware' => ['auth:ApiMember', 'XSS'],
], function () {
    Route::post('/cash/detail', 'CashController@detail'); //金币钱包
    Route::post('/gold/detail', 'GoldController@detail'); //能量钱包
    Route::post('/record/lists', 'RecordController@lists'); //资金流水

    Route::get('/record/get_records', 'RecordController@getRecords'); //收益
    Route::get('/record/get_recharge', 'RecordController@getRecharge'); //获取用户充值
});
