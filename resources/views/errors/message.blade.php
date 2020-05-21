<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>系统信息</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="{{ asset('plugins/layuiadmin/layui/css/layui.css')}}" media="all">
    <link rel="stylesheet" href="{{ asset('plugins/layuiadmin/style/admin.css')}}" media="all">
</head>
<body>

<div class="layui-col-md4 layui-col-md-offset4  " style="margin-top: 50px;">
    <div class="layui-card">
        <div class="layui-card-body">
            <div style="text-align: center;padding: 10px;line-height: 40px;">
                <h2>{{env('APP_NAME')}}</h2>
                <span>系统提示</span>
                <p>{{$msg}}<p/>
            </div>

        </div>
    </div>

</div>

<script src="{{ asset('js/app.js')}}"></script>
<script src="{{ asset('plugins/layuiadmin/layui/layui.js')}}"></script>
<script>
    setInterval(function () {
        window.history.back();
    }, 5000);

    layui.use(['form', 'jquery', 'layer'], function () {
        let $ = layui.jquery,
            form = layui.form,
            layer = layui.layer
        ;


    });
</script>
</body>
</html>

