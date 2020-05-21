@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">

                    <div class="layui-card-body" pad15>

                        <div class="layui-form" lay-filter="formData">
                            <div class="layui-form-item">
                                <label class="layui-form-label">性别</label>
                                <div class="layui-input-block">
                                    <select name="sex">
                                        <option value="0">男</option>
                                        <option value="1">女</option>
                                        <option value="9">未知</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item" >
                                <label class="layui-form-label">昵称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="nick_name"  placeholder="请输入昵称" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item" >
                                <label class="layui-form-label">邮箱</label>
                                <div class="layui-input-block">
                                    <input type="text" name="email"  disabled  lay-verify="email" placeholder="请输入邮箱" autocomplete="off" class="layui-input layui-disabled">
                                </div>
                            </div>
                            <div class="layui-form-item" >
                                <label class="layui-form-label">手机号</label>
                                <div class="layui-input-block">
                                    <input type="text" name="mobile" placeholder="请输入手机号" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item" >
                                <label class="layui-form-label">密码</label>
                                <div class="layui-input-block">
                                    <input type="password" name="password"  placeholder="请输入密码" autocomplete="new-password" class="layui-input">
                                </div>
                            </div>
                            <input type="hidden" name="id">
                            <div class="layui-form-item ">
                                <div class="layui-input-block">
                                    <input type="button" class="layui-btn" lay-submit lay-filter="formSubmit" value="确认">
                                    <input type="button" class="layui-btn layui-btn-primary " id="close" value="关闭">
                                </div>
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
            form.val("formData",{!! $user !!});
            let index = parent.layer.getFrameIndex(window.name);
            $(document).on('click', '#close', function () {
                parent.layer.close(index);
            });
            parent.layer.iframeAuto(index);
            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;
                    let index = parent.layer.getFrameIndex(window.name);
                    axios.post("{{url('system/member/user/update')}}", field)
                        .then(function (response) {
                            if (response.data.status) {
                                parent.layer.msg(response.data.msg);
                                return parent.layer.close(index);
                            }
                            return layer.msg(response.data.msg);
                        });
                }
            );
        });

    </script>
@endsection
