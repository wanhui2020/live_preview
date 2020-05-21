<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{BaseFacade::config('name')}}</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="{{ asset('plugins/layuiadmin/layui/css/layui.css')}}" media="all">
    <link rel="stylesheet" href="{{ asset('plugins/layuiadmin/style/admin.css')}}" media="all">
    <link rel="stylesheet" href="{{ asset('plugins/layuiadmin/style/login.css')}}" media="all">
</head>
<body>

<div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login" style="display: none;">

    <div class="layadmin-user-login-main">
        <div class="layadmin-user-login-box layadmin-user-login-header">
            <h2>{{BaseFacade::config('name')}}</h2>
            <p>管理平台</p>
        </div>
        <form role="form" method="POST" action="{{ url('/system/login') }}">
            {{ csrf_field() }}
        <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-username"
                       for="LAY-user-login-username"></label>
                <input type="text" name="email" id="LAY-user-login-email" lay-verify="required" placeholder="邮箱账号"
                       class="layui-input">
            </div>
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-password"
                       for="LAY-user-login-password"></label>
                <input type="password" name="password" id="LAY-user-login-password" lay-verify="required"
                       placeholder="密码" class="layui-input">
            </div>
{{--            <div class="layui-form-item">--}}
{{--                <label class="layadmin-user-login-icon layui-icon layui-icon-password"--}}
{{--                       for="LAY-user-login-password"></label>--}}
{{--                <input type="password" name="safety" id="LAY-user-login-password" lay-verify="required"--}}
{{--                       placeholder="安全码" class="layui-input">--}}
{{--            </div>--}}
            <div class="layui-form-item">
                <div class="layui-row">
                    <div class="layui-col-xs7">
                        <label class="layadmin-user-login-icon layui-icon layui-icon-vercode"
                               for="LAY-user-login-vercode"></label>
                        <input type="text" name="captcha" id="LAY-user-login-vercode" lay-verify="required"
                               placeholder="验证码" class="layui-input">
                    </div>
                    <div class="layui-col-xs5">
                        <div style="margin-left: 10px;">
                            <img src="{{captcha_src()}}"
                                 class="layadmin-user-login-codeimg "
                                 id="LAY-user-get-vercode" onclick="this.src='/captcha?' + Math.random()"
                                 title="点击图片重新获取验证码">
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-form-item" style="margin-bottom: 20px;">
                <input type="checkbox" name="remember" lay-skin="primary" title="记住密码">
            </div>

            <div class="layui-form-item">
                <button type="submit" class="layui-btn layui-btn-fluid"    >登 入</button>
            </div>
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        </form>
    </div>


</div>

<script src="{{ asset('js/app.js')}}"></script>
<script src="{{ asset('plugins/layuiadmin/layui/layui.js')}}"></script>
<script>
    if(top.location!==self.location){
        top.location = "{{url('system/login')}}";
	}

</script>
</body>
</html>
