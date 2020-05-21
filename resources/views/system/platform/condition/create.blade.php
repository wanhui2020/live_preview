@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-card">

            <div class="layui-card-body" pad15>
                <div class="layui-form" lay-filter="">
                    <div class="layui-form-item">
                        <label class="layui-form-label">名称</label>
                        <div class="layui-input-block">
                            <input type="text" name="name" lay-verify="required" placeholder="请输入名称"
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">键</label>
                        <div class="layui-input-block">
                            <input type="text" name="key" lay-verify="required" placeholder="请输入键"
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">符号</label>
                        <div class="layui-input-block">
                            <input type="text" name="mark" lay-verify="required" placeholder="请输入符号"   value="="
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">值</label>
                        <div class="layui-input-block">
                            <input type="text" name="value" lay-verify="required" placeholder="请输入值"
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">排序(升序)</label>
                        <div class="layui-input-block">
                            <input type="number" name="sort" lay-verify="required" placeholder="请输入排序" value="0"
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
@stop

@section('script')
    <script type="application/javascript">
        layui.use(['form', 'upload', 'layedit'], function () {
            let $ = layui.$
                , form = layui.form
                , upload = layui.upload
                , layedit = layui.layedit;
            //创建一个编辑器
            var editIndex = layedit.build('content');
            //自定义验证规则
            form.verify({
                article_desc: function (value) {
                    layedit.sync(editIndex);
                }
            });
            let index = parent.layer.getFrameIndex(window.name);
            $(document).on('click', '#close', function () {
                parent.layer.close(index);
            });
            parent.layer.iframeAuto(index);
            //普通图片上传
            var uploadInst = upload.render({
                elem: '#ico'
                , url: '{{url('common/put')}}' //上传接口
                , before: function (obj) {
                    //预读本地文件示例，不支持ie8
                    obj.preview(function (index, file, result) {
                        $('#demo1').attr('src', result); //图片链接（base64）
                    });
                }
                , acceptMime: 'image/*'
                , done: function (res) {
                    //如果上传失败
                    if (res.code > 0) {
                        return layer.msg('上传失败');
                    }
                    //上传成功
                    $('#imgs').attr('src', res.src);
                    layer.closeAll('loading');
                    layer.msg('上传成功');
                }
                , error: function () {
                    //演示失败状态，并实现重传
                    layer.alert('上传失败');
                    layer.closeAll('loading');
                }
            });
            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;
                    axios.post("{{url('system/platform/condition/store')}}", field)
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
