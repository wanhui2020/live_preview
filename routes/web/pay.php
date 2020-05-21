<?php

Route::group(['prefix' => 'pay', 'namespace' => 'Payment'], function () {
    Route::get('/test/{no}', 'PaymentController@index');
    //    Route::get('/test', 'PaymentController@test');
    Route::post('/cancel', 'PaymentController@cancel');
    Route::get('/detail', 'PaymentController@detail');
    Route::any('/confirm', 'PaymentController@confirm');
    Route::any('/succeed', 'PaymentController@success');
    Route::any('/backlog', 'PaymentController@backlog')
        ->name('pay.backlog');//未完成订单
    Route::get('/{no}', 'PaymentController@index')->name('pay');//订单支付
});
