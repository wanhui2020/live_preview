@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">

                    <div class="layui-card-body" pad15>

                        <div class="layui-form" lay-filter="">
                            <div class="layui-form-item">
                                <label class="layui-form-label">所属类型</label>
                                <div class="layui-input-inline">
                                    <select name="type" lay-filter="aihao">
                                        <option value="0" selected="">所有人</option>
                                        <option value="1">已认证</option>
                                        <option value="2">未认证</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">是否主页显示</label>
                                <div class="layui-input-inline">
                                    <select name="is_banner" lay-filter="aihao">
                                        <option value="0" selected="">否</option>
                                        <option value="1">是</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">标题</label>
                                <div class="layui-input-block">
                                    <input type="text" name="title" lay-verify="required" placeholder="请输入标题" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">跳转地址</label>
                                <div class="layui-input-block">
                                    <input type="text" name="url" placeholder="请输入跳转地址" autocomplete="off" class="layui-input">
                                    app://selfie自拍认证 app://real实名认证 app://recharge充值app://share分享app://data完善资料app://phone绑定手机

                                </div>
                            </div>
                            <div class="layui-form-item" id="tupian">
                                <div class="col-md-6 mb-3">
                                    <label class="layui-form-label">标题图片</label>
                                    <img style="height: 60px; " id="cover_preview">
                                    <button type="button" class="layui-btn" id="ico">
                                        <i class="layui-icon">&#xe67c;</i>上传图片
                                    </button>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">内容</label>
                                <div class="layui-input-block">
                                    <textarea name="content" id="editor" lay-verify="article_desc" placeholder="请输入内容" class="layui-textarea"></textarea>
                                </div>
                            </div>
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
        layui.use(['form', 'upload', 'layedit'], function () {
            let $ = layui.$
                , form = layui.form
                , upload = layui.upload
                , layedit = layui.layedit;

            layedit.set({
                uploadImage: {url: '{{url('common/put/layedit')}}', type: 'post'}
            });

            //创建一个编辑器
            var editIndex = layedit.build('editor');
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
                    // obj.preview(function (index, file, result) {
                    //     $('#demo1').attr('src', result); //图片链接（base64）
                    // });
                }
                , acceptMime: 'image/*'
                , done: function (res) {
                    //如果上传失败
                    if (res.code > 0) {
                        return layer.msg('上传失败');
                    }
                    //上传成功
                    $('#cover_preview').attr('src', res.src);
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
                    field.pic = $('#cover_preview').attr('src');
                    axios.post("{{url('system/platform/message/store')}}", field)
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
