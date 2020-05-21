<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <title>注册邀请</title>

    <!-- 最新版本的 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>
</head>
<body>
<div class="container-fluid">
    <div class="panel panel-default" style="margin-top: 10px;">
        <div class="panel-heading">
            <h3 class="panel-title">{{$parent->nick_name}}邀请注册！
            </h3>
        </div>
        <div class="panel-body">
            <form>
                <div class="form-group form-group-lg">
                 下载地址请联系客服！
                </div>
                @if($app)
                    <a class="btn btn-primary btn-lg btn-block" href="{{$app->url}}">下载APP</a>
                @endif
            </form>
        </div>
        <div class="panel-footer">{{env('APP_NAME')}}-90%都是美女的交友平台！</div>
    </div>
</div>
</body>
</html>
