@extends('layouts.base')
@section('content')

    <div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login" style="display: none;">

        <div class="layadmin-user-login-main">
            <div class="layadmin-user-login-box layadmin-user-login-header">
                <h2>{{env('APP_NAME')}}</h2>
                <p>服务商入口</p>
            </div>
            <form role="form" method="POST" action="{{ url('/agent/login') }}">
                {{ csrf_field() }}
                <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
                    <div class="layui-form-item">
                        <label class="layadmin-user-login-icon layui-icon layui-icon-cellphone"
                               for="LAY-user-login-cellphone"></label>
                        <input type="tel" name="mobile" id="LAY-user-login-cellphone" lay-verify="required|phone"
                               placeholder="手机号"
                               class="layui-input">
                    </div>
                    <div class="layui-form-item">
                        <label class="layadmin-user-login-icon layui-icon layui-icon-password"
                               for="LAY-user-login-password"></label>
                        <input type="password" name="password" id="LAY-user-login-password" lay-verify="required"
                               placeholder="密码" class="layui-input">
                    </div>
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
                        <button class="layui-btn layui-btn-fluid" type="submit" lay-submit >登 入
                        </button>
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
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/layuiadmin/style/login.css') }} " media="all">
@endsection
@section('script')
    <script type="application/javascript">

        layui.use(['form'], function () {
            let $ = layui.$
                , form = layui.form;

        });
    </script>

@endsection
