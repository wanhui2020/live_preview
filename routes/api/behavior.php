<?php

//不用验证登录
Route::group([
    'prefix'     => 'member/behavior',
    'namespace'  => 'Member\\Behavior',
], function () {
    Route::get('/push/lists', 'PushController@lists');//获取推送标签列表
});

//会员行为
Route::group([
    'prefix'     => 'member/behavior',
    'namespace'  => 'Member\\Behavior',
    'middleware' => ['auth:ApiMember', 'XSS'],
], function () {
    //关注
    Route::group(['prefix' => 'attention'], function () {
        Route::post('/lists', 'AttentionController@lists'); // 我的关注列表
        Route::post('/store', 'AttentionController@store'); // 点关注
        Route::post('/del', 'AttentionController@destroy'); // 取消关注
    });
    //黑名单
    Route::group(['prefix' => 'blacklist'], function () {
        Route::post('/lists', 'BlacklistController@lists'); // 我的拉黑列表
        Route::post('/store', 'BlacklistController@store'); // 拉黑别人
        Route::post('/del', 'BlacklistController@destroy'); // 取消拉黑
    });

    //好友
    Route::group(['prefix' => 'friend'], function () {
        Route::post('/lists', 'FriendController@lists'); // 我的好友列表
        Route::post('/store', 'FriendController@store'); // 添加好友
        Route::post('/del', 'FriendController@destroy'); // 取消好友
    });
    //动态列表
    Route::group(['prefix' => 'social'], function () {
        Route::post('/lists', 'SocialController@lists'); // 动态列表
        //        Route::post('/store', 'SocialController@store'); // 发布动态
    });
    //意见反馈
    Route::group(['prefix' => 'feedback'], function () {
        Route::post('/store', 'FeedbackController@store'); //意见反馈
    });
    //会员举报
    Route::group(['prefix' => 'report'], function () {
        Route::post('/store', 'ReportController@store'); //会员举报
    });
    // 会员资源
    Route::group(['prefix' => 'resource'], function () {
        Route::post('/lists', 'ResourceController@lists'); // 会员资源
        Route::post('/store', 'ResourceController@store'); // 资源新增
        Route::post('/destroy', 'ResourceController@destroy'); // 会员资源删除
        Route::post('/cover', 'ResourceController@cover'); // 设置主封面
        Route::post('/upload_video', 'ResourceController@createUploadVideo'); // 视频凭证
        Route::get('/get_video', 'ResourceController@getVideo'); // 获取视频信息
    });

    //邀请好友
    Route::group(['prefix' => 'share'], function () {
        Route::post('/lists', 'ShareController@lists'); //邀请好友列表
    });
    //会员签到
    Route::group(['prefix' => 'signin'], function () {
        Route::post('/store', 'SigninController@store'); //会员签到
    });
    //会员点赞
    Route::group(['prefix' => 'like'], function () {
        Route::post('/store', 'LikeController@store'); //点赞
        Route::post('/lists', 'LikeController@lists'); //列表
    });
    //会员标签
    Route::group(['prefix' => 'tag'], function () {
        Route::post('/lists', 'TagController@lists');
        Route::post('/store', 'TagController@store');
    });
    //推送标签
    Route::group(['prefix' => 'push'], function () {
        Route::get('/get_tag', 'PushController@getTag');//根据某个token获取标签
    });
});
