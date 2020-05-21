@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">

                    <div class="layui-card-body" pad15>

                        <div class="layui-form" lay-filter="">
                            <div class="layui-form-item">
                                <label class="layui-form-label">会员类型</label>
                                <div class="layui-input-block">
                                    <select name="type">
                                        <option value="0" selected="">普通</option>
                                        <option value="1" >陪聊</option>
                                        <option value="2" >客服</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">昵称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="nick_name" lay-verify="required" placeholder="请输入昵称"
                                           autocomplete="off"
                                           class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">头像</label>
                                <div class="layui-input-block">
                                    <input type="hidden" name="head_pic" id="head_pic">
                                    <img src="{{url('images/logo.ico')}}" style="width: 50px;height: 50px;"
                                         id="headView">
                                    <button type="button" class="layui-btn" id="headPic">
                                        <i class="layui-icon">&#xe67c;</i>上传
                                    </button>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">性别</label>
                                <div class="layui-input-block">
                                    <select name="sex">
                                        <option value="0" selected="">男</option>
                                        <option value="1">女</option>
                                        <option value="9">未知</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">手机号</label>
                                <div class="layui-input-block">
                                    <input type="text" name="mobile" lay-verify="phone" placeholder="请输入手机号"
                                           autocomplete="off"
                                           class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">年龄</label>
                                <div class="layui-input-block">
                                    <input type="text" name="age" placeholder="请输入年龄"
                                           class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">邀请码</label>
                                <div class="layui-input-block">
                                    <input type="text" name="parent_no" placeholder="请输入邀请人邀请码"
                                           class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item ">
                                <div class="layui-input-block">
                                    <input type="button" class="layui-btn" lay-submit lay-filter="formSubmit"
                                           value="确认">
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
            let index = parent.layer.getFrameIndex(window.name);
            $(document).on('click', '#close', function () {
                parent.layer.close(index);
            });
            parent.layer.iframeAuto(index);

            //普通图片上传
            var uploadInst = upload.render({
                elem: '#headPic'
                , url: '{{url('common/put')}}' //上传接口
                , accept: 'file' //普通文件
                , before: function (obj) {
                    layui.layer.load();
                }
                , done: function (res) {
                    $('#head_pic').val(res.data);
                    $('#headView').attr('src', res.src);
                    layer.closeAll('loading');
                    layer.msg('上传成功')
                }
                , error: function () {
                    layer.alert('上传失败')
                    layer.closeAll('loading');
                }
            });

            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;
                    layer.load();
                    axios.post("{{url('system/member/user/store')}}", field)
                        .then(function (response) {
                            layer.closeAll();
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
@stop
