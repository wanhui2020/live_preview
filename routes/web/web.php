<?php

Route::get('/', 'HomeController@index');
Route::get('/admin', 'HomeController@admin');
Route::any('/share/{no}', 'HomeController@share');

// 将最外层请求移动到common前缀下
//Route::get('/hooks', 'HomeController@hooks');
//Route::any('/test', 'HomeController@test');
//Route::get('/vod', 'HomeController@vod');
//Route::get('/get_video', 'HomeController@getVideo');
//Route::get('/get_video_lists', 'HomeController@getVideoLists');

Route::group(['prefix' => 'system', 'namespace' => 'System', 'as' => 'web.'],
    function () {
        Auth::routes();
        Route::group(['middleware' => ['auth:SystemUser', 'XSS']], function () {
            Route::get('/', 'HomeController@index');
            Route::any('/home', 'HomeController@home');
            Route::any('/test', 'HomeController@test');
            Route::any('/search', 'HomeController@search');
            Route::any('/clear', 'HomeController@clear');
            Route::post('/grade/sync', 'HomeController@gradeSync');
            Route::post('/cache/clear', 'HomeController@clearCache');

            Route::post('/verifysafecode', 'HomeController@verifySafeCode');
            Route::any('/push_tag', 'HomeController@pushTag');//更新推送标签
        });
    });
