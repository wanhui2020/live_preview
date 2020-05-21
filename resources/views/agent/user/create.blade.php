@extends('layouts.agent')

@section('content')
    <div class="row ">
        <div class="col-sm-2">
            @include('agent.common.side',['page'=>'交易中心','sub'=>'agent'])
        </div>
        <div class="col-sm-10">
            <div class="layui-card">
                <div class="layui-card-header">新增渠道</div>
                <div class="layui-card-body  " pad15>
                    <div class="layui-form  " lay-filter="">
                        <div class="layui-form-item" >
                            <label class="layui-form-label">姓名</label>
                            <div class="layui-input-block">
                                <input type="text" name="name"  lay-verify="required" placeholder="请输入姓名" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item" >
                            <label class="layui-form-label"><span style="color: red">*</span>邮箱</label>
                            <div class="layui-input-block">
                                <input type="email" name="email" lay-verify="email" placeholder="请输入邮箱" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item" >
                            <label class="layui-form-label">手机号</label>
                            <div class="layui-input-block">
                                <input type="tel" name="mobile"  placeholder="请输入手机号" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item" >
                            <label class="layui-form-label"><span style="color: red">*</span>密码</label>
                            <div class="layui-input-block">
                                <input type="password" name="password" lay-verify="required" placeholder="请输入密码" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item" >
                            <label class="layui-form-label">微信号</label>
                            <div class="layui-input-block">
                                <input type="text" name="weixin"  placeholder="请输入微信号" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item" >
                            <label class="layui-form-label">支付宝号</label>
                            <div class="layui-input-block">
                                <input type="text" name="alipay"  placeholder="请输入支付宝号" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item ">
                            <div class="layui-input-block">
                                <input type="button" class="layui-btn" lay-submit lay-filter="formSubmit" value="确认">
                                <a class="layui-btn  layui-btn" href="{{url('agent/base/user/')}}" >返回</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('script')
    <script type="application/javascript">
        layui.use(['form', 'upload'], function () {
            let $ = layui.$
                , form = layui.form
                , upload = layui.upload;




            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;


                    axios.post("{{url('agent/base/user/store')}}", field)
                        .then(function (response) {
                            if (response.data.status) {
                                return window.location.href = '{{url('agent/base/user')}}';
                            }
                            return layer.msg(response.data.msg);
                        });
                }
            );

        });

    </script>
@stop

