@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">

                    <div class="layui-card-body" pad15>
                        <div class="layui-form" lay-filter="">
                            <div class="layui-form-item">
                                <label class="layui-form-label">礼物名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="name" lay-verify="required" placeholder="请输入礼物名称"
                                           autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">消耗能量</label>
                                <div class="layui-input-block">
                                    <input type="text" name="gold" lay-verify="required|moneyMinus"
                                           placeholder="请输入能量价格"
                                           autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">动画地址</label>
                                <div class="layui-input-block">
                                    <input type="text" name="cartoon" id="cartoon" placeholder="请输入动画地址"
                                           autocomplete="off"
                                           class="layui-input">
                                    <button type="button" class="layui-btn" id="upCartoon">
                                        <i class="layui-icon">&#xe67c;</i>上传动画
                                    </button>
                                </div>
                            </div>

                            <div class="layui-form-item" id="tupian">
                                <label class="layui-form-label">图片</label>
                                <div class="layui-input-block">
                                    <input type="text" name="ico" id="ico" placeholder="请输入图片地址" autocomplete="off"
                                           class="layui-input">
                                    <img style="max-height: 100px; " id="imgs">
                                    <button type="button" class="layui-btn" id="upIco">
                                        <i class="layui-icon">&#xe67c;</i>上传图片
                                    </button>
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
        layui.use(['form', 'upload', 'layedit'], function () {
            let $ = layui.$
                , form = layui.form
                , upload = layui.upload
                , layedit = layui.layedit;
            //创建一个编辑器
            var editIndex = layedit.build('demo');
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
            //上传动画
            upload.render({
                elem: '#upCartoon'
                , url: '{{url('common/put')}}' //上传接口
                , accept: 'file'
                , before: function (obj) {
                    layer.load();
                }
                , done: function (res) {
                    //如果上传失败
                    if (res.code > 0) {
                        return layer.msg('上传失败');
                    }
                    //上传成功
                    $('#cartoon').val(res.src);
                    layer.closeAll('loading');
                    layer.msg('上传成功');
                }
                , error: function () {
                    //演示失败状态，并实现重传
                    layer.alert('上传失败');
                    layer.closeAll('loading');
                }
            });
            upload.render({
                elem: '#upIco'
                , url: '{{url('common/put')}}' //上传接口
                , before: function (obj) {
                    layer.load();
                }
                , accept: 'file'
                , done: function (res) {
                    //如果上传失败
                    if (res.code > 0) {
                        return layer.msg('上传失败');
                    }
                    //上传成功
                    $('#imgs').attr('src', res.src);
                    $('#ico').val(res.src);
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
                    axios.post("{{url('system/platform/gift/store')}}", field)
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
