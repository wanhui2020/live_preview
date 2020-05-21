<?php

//系统设置
Route::group([
    'prefix'     => 'system/base',
    'namespace'  => 'System\\Base',
    'middleware' => [
        'auth:SystemUser',
        'XSS',
    ],
], function () {
    //用户管理
    Route::group(['prefix' => 'user'], function () {
        Route::get('/', 'UserController@index');
        Route::any('/lists', 'UserController@lists');
        Route::get('/create', 'UserController@create');
        Route::post('/store', 'UserController@store');
        Route::get('/edit', 'UserController@edit');
        Route::post('/update', 'UserController@update');
        Route::post('/status', 'UserController@status');
        Route::post('/destroy', 'UserController@destroy');
        Route::post('/status', 'UserController@status');
        Route::any('/info', 'UserController@info');
        Route::any('/login', 'UserController@login');

        Route::get('/assign_role/{user}', 'UserController@showAssignRoleForm');
        Route::post('/assign_role', 'UserController@assignRole');
    });

    //系统日志
    Route::group(['prefix' => 'logs'], function () {
        Route::any('/business', 'LogController@business');
        Route::any('/logins', 'LogController@logins');
    });

    //系统参数
    Route::group(['prefix' => 'config'], function () {
        Route::get('/', 'ConfigController@index');
        Route::get('/edit', 'ConfigController@edit');
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
        Route::post('/destroy', 'NoticeController@destroy');
    });

    Route::get('role/lists', 'RoleController@lists');
    Route::get('role/{role}/assign_permission', 'RoleController@showAssignPermissionForm')->name('assign_permission');
    Route::patch('role/{role}/assign_permission', 'RoleController@storeAssignRolePermission');
    Route::resource('role', 'RoleController');

    Route::get('permission/lists', 'PermissionController@lists');
    Route::resource('permission', 'PermissionController');
});
