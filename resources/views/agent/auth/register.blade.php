@extends('layouts.merchant')
@section('content')
    <div class="row justify-content-md-center">
        <div class="col-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    商户注册入住
                </div>
                <div class="card-body">
                    <form>
                        <div class="form-group">
                            <label for="name">商户/店铺名称</label>
                            <input type="text" class="form-control" id="name" aria-describedby="emailHelp"
                                   placeholder="请输入商户名称" v-model="member.name">
                            <small id="emailHelp" class="form-text text-muted">交易时所显示的商户名称或店铺名</small>
                        </div>
                        <div class="form-group">
                            <label for="email">邮箱</label>
                            <input type="email" class="form-control" id="email" placeholder="请输入注册的邮箱"
                                   v-model="member.email">
                        </div>
                        <div class="form-group">
                            <label for="mobile">手机号</label>
                            <input type="text" class="form-control" id="mobile" placeholder="请输入注册的手机号"
                                   v-model="member.mobile">
                        </div>
                        <div class="form-group">
                            <label for="password">密码</label>
                            <input type="password" class="form-control" id="password" placeholder="请输入登录密码"
                                   v-model="member.password">
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">确认密码</label>
                            <input type="password" class="form-control" id="password_confirmation" placeholder="请确认登录密码"
                                   v-model="member.password_confirmation">
                        </div>
                        <button type="button" class="btn btn-primary" v-on:click="register">注册</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="application/javascript">

        let vm = new Vue({
            el: '#app',
            data: {
                member: {},

            },
            created: function () {

                let self = this;


            },
            methods: {
                register: function () {
                    if (!this.member.name) {
                        return layer.msg('商户名称不能为空')
                    }
                    if (!this.member.email) {
                        return layer.msg('邮箱不能为空')
                    }
                    if (!this.member.mobile) {
                        return layer.msg('手机号不能为空')
                    }
                    if (!this.member.password) {
                        return layer.msg('密码不能为空')
                    }

                    axios.post("/agent/register", this.member)
                        .then(function (response) {
                                if (response.data.status) {
                                    layer.msg(response.data.msg);
                                }
                            }
                        );
                }
            }
        });
    </script>

@endsection


{{--<!DOCTYPE html>--}}
{{--<html>--}}
{{--<head>--}}
{{--    <meta charset="utf-8">--}}
{{--    <title>商户注册</title>--}}
{{--    <meta name="renderer" content="webkit">--}}
{{--    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">--}}
{{--    <meta name="csrf-token" content="{{ csrf_token() }}">--}}
{{--    <meta name="viewport"--}}
{{--          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">--}}

{{--    <link rel="stylesheet" href="{{ asset('plugins/layuiadmin/layui/css/layui.css')}}" media="all">--}}
{{--    <link rel="stylesheet" href="{{ asset('plugins/layuiadmin/style/admin.css')}}" media="all">--}}
{{--    <link rel="stylesheet" href="{{ asset('plugins/layuiadmin/style/login.css')}}" media="all">--}}
{{--</head>--}}
{{--<body>--}}
{{--<div class="layui-row">--}}
{{--    <div class="layui-col-md6 layui-col-md-offset3">--}}
{{--        <div class="layui-card" style="margin-top: 50px;">--}}
{{--            <div class="layui-card-body">--}}
{{--                <div class="layadmin-user-login-box layadmin-user-login-header">--}}
{{--                    <h2>{{env('APP_NAME')}} ChainPay1.0</h2>--}}
{{--                    <p>商户注册</p>--}}
{{--                </div>--}}
{{--                <div class=" layui-form">--}}
{{--                    <div class="layui-form-item">--}}

{{--                        <input type="text" name="name" lay-verify="required" placeholder="商户名称"--}}
{{--                               class="layui-input">--}}
{{--                    </div>--}}
{{--                    <div class="layui-form-item">--}}

{{--                        <input type="tel" name="mobile" lay-verify="required|phone"--}}
{{--                               placeholder="手机号登录名"--}}
{{--                               class="layui-input">--}}
{{--                    </div>--}}
{{--                    <div class="layui-form-item">--}}

{{--                        <input type="tel" name="email" lay-verify="required|email"--}}
{{--                               placeholder="邮箱"--}}
{{--                               class="layui-input">--}}
{{--                    </div>--}}

{{--                    <div class="layui-form-item">--}}

{{--                        <input type="password" name="password" lay-verify="pass" placeholder="密码"--}}
{{--                               class="layui-input">--}}
{{--                    </div>--}}
{{--                    <div class="layui-form-item">--}}

{{--                        <input type="password" name="password_confirmation"--}}
{{--                               lay-verify="required" placeholder="确认密码"--}}
{{--                               class="layui-input">--}}
{{--                    </div>--}}

{{--                    <div class="layui-form-item">--}}
{{--                        <input type="checkbox" name="agreement" lay-skin="primary" title="同意用户协议" checked>--}}
{{--                    </div>--}}
{{--                    <div class="layui-form-item">--}}
{{--                        <button class="layui-btn layui-btn-fluid" lay-submit lay-filter="register">注 册</button>--}}
{{--                    </div>--}}
{{--                    <div class="layui-trans layui-form-item layadmin-user-login-other">--}}
{{--                        --}}{{--<label>社交账号注册</label>--}}
{{--                        --}}{{--<a href="javascript:;"><i class="layui-icon layui-icon-login-qq"></i></a>--}}
{{--                        --}}{{--<a href="javascript:;"><i class="layui-icon layui-icon-login-wechat"></i></a>--}}
{{--                        --}}{{--<a href="javascript:;"><i class="layui-icon layui-icon-login-weibo"></i></a>--}}

{{--                        <a href="login" class="layadmin-user-jump-change layadmin-link layui-hide-xs">用已有帐号登入</a>--}}
{{--                        --}}{{--<a href="login"--}}
{{--                        --}}{{--class="layadmin-user-jump-change layadmin-link layui-hide-sm layui-show-xs-inline-block">登入</a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--    </div>--}}
{{--</div>--}}
{{--<script src="{{ asset('js/app.js')}}"></script>--}}
{{--<script src="{{ asset('plugins/layuiadmin/layui/layui.js')}}"></script>--}}
{{--<script>--}}
{{--    layui.use(['form',], function () {--}}
{{--        let $ = layui.$--}}
{{--            , form = layui.form;--}}
{{--        form.render();--}}
{{--        //提交--}}
{{--        form.on('submit(register)', function (obj) {--}}
{{--            let field = obj.field;--}}

{{--            //确认密码--}}
{{--            if (field.password !== field.password_confirmation) {--}}
{{--                return layer.msg('两次密码输入不一致');--}}
{{--            }--}}

{{--            if (field.password.length < 8) {--}}
{{--                return layer.msg('密码至少8位');--}}
{{--            }--}}
{{--            //是否同意用户协议--}}
{{--            if (!field.agreement) {--}}
{{--                return layer.msg('你必须同意用户协议才能注册');--}}
{{--            }--}}
{{--            axios.post("{{url('/merchant/register')}}", field)--}}
{{--                .then(function (response) {--}}
{{--                        if (response.data.status) {--}}
{{--                            layer.msg('注册成功', {--}}
{{--                                offset: '15px'--}}
{{--                                , icon: 1--}}
{{--                                , time: 1000--}}
{{--                            }, function () {--}}
{{--                                location.href = '{{url('merchant')}}'; //跳转到主页--}}
{{--                            });--}}

{{--                        } else {--}}
{{--                            layer.msg('请求失败', response.data.msg);--}}
{{--                        }--}}

{{--                    }--}}
{{--                ).catch(function (err) {--}}
{{--                layer.msg('请求异常', {--}}
{{--                    offset: '15px'--}}
{{--                    , icon: 1--}}
{{--                    , time: 1000--}}
{{--                });--}}
{{--            });--}}
{{--            return false;--}}
{{--        });--}}


{{--    });--}}
{{--</script>--}}
{{--</body>--}}
{{--</html>--}}