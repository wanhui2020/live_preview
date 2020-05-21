@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">

                    <div class="layui-card-body" pad15>

                        <div class="layui-form" lay-filter="">
                            <div class="layui-form-item">
                                <label class="layui-form-label">所属会员</label>
                                <div class="layui-input-inline">
                                    <select name="member_id" id="member_id"  lay-filter="member_id"  lay-verify="required" lay-search>
                                        <option value="0"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">身份证号</label>
                                <div class="layui-input-block">
                                    <input type="text" name="idcard"  lay-verify="required" placeholder="请输入身份证号" autocomplete="off"
                                           class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">真实姓名</label>
                                <div class="layui-input-block">
                                    <input type="text" name="name"  lay-verify="required" placeholder="请输入真实姓名" autocomplete="off"
                                           class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item" >
                                <label class="layui-form-label" >身份证正面照</label>
                                <div class="layui-input-block">
                                    <input type="text" name="idcard_front"  lay-verify="required" id="idcard_front"  placeholder="身份证正面照" autocomplete="off" class="layui-input">
                                    <button type="button" class="layui-btn" id="idcard_ico">
                                        <i class="layui-icon">&#xe67c;</i>上传
                                    </button>
                                </div>
                            </div>
                            <div class="layui-form-item" >
                                <label class="layui-form-label" >身份证反面照</label>
                                <div class="layui-input-block">
                                    <input type="text" name="idcard_back"  lay-verify="required" id="idcard_back"  placeholder="身份证反面照" autocomplete="off" class="layui-input">
                                    <button type="button" class="layui-btn" id="idcard_backico">
                                        <i class="layui-icon">&#xe67c;</i>上传
                                    </button>
                                </div>
                            </div>
                            <div class="layui-form-item" >
                                <label class="layui-form-label" >手持身份证</label>
                                <div class="layui-input-block">
                                    <input type="text" name="idcard_hand"  lay-verify="required" id="idcard_hand"  placeholder="手持身份证" autocomplete="off" class="layui-input">
                                    <button type="button" class="layui-btn" id="idcard_handico">
                                        <i class="layui-icon">&#xe67c;</i>上传
                                    </button>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">地址</label>
                                <div class="layui-input-block">
                                    <input type="text" name="address"  lay-verify="required"   placeholder="请输入地址"
                                           autocomplete="off" class="layui-input">
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
            axios.all([
                axios.post("/system/member/user/lists?way=1", {size: 1000}),
            ])
                .then(axios.spread(function (agentResp) {
                    if (agentResp.data.status) {
                        agentResp.data.data.forEach(function (value, index, array) {
                            $('#member_id').append("<option value='" + value.id + "'>" + value.nick_name + "</option>");
                        });
                    }
                    form.render('select');
                }));
            //普通图片上传
            var uploadInst = upload.render({
                elem: '#idcard_ico'
                , url: '{{url('common/put')}}' //上传接口
                ,accept: 'file' //普通文件
                , before: function (obj) {
                    layui.layer.load();
                }
                , done: function (res) {
                    $('#idcard_front').val(res.data);
                    layer.closeAll('loading');
                    layer.msg('上传成功')
                }
                , error: function () {
                    layer.alert('上传失败')
                    layer.closeAll('loading');
                }
            });
            //普通图片上传
            var uploadInst = upload.render({
                elem: '#idcard_backico'
                , url: '{{url('common/put')}}' //上传接口
                ,accept: 'file' //普通文件
                , before: function (obj) {
                    layui.layer.load();
                }
                , done: function (res) {
                    $('#idcard_back').val(res.data);
                    layer.closeAll('loading');
                    layer.msg('上传成功')
                }
                , error: function () {
                    layer.alert('上传失败')
                    layer.closeAll('loading');
                }
            });
            //普通图片上传
            var uploadInst = upload.render({
                elem: '#idcard_handico'
                , url: '{{url('common/put')}}' //上传接口
                ,accept: 'file' //普通文件
                , before: function (obj) {
                    layui.layer.load();
                }
                , done: function (res) {
                    $('#idcard_hand').val(res.data);
                    layer.closeAll('loading');
                    layer.msg('上传成功')
                }
                , error: function () {
                    layer.alert('上传失败')
                    layer.closeAll('loading');
                }
            });
            //普通图片上传
            var uploadInst = upload.render({
                elem: '#selfie_ico'
                , url: '{{url('common/put')}}' //上传接口
                ,accept: 'file' //普通文件
                , before: function (obj) {
                    layui.layer.load();
                }
                , done: function (res) {
                    $('#selfie_pic').val(res.data);
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
                    axios.post("{{url('system/member/user/realname/store')}}", field)
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
@stop
