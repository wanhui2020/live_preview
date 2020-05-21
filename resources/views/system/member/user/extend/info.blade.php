@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-card">

            <div class="layui-card-body" pad15>

                <div class="layui-form" lay-filter="formData" wid100>
                    <input type="hidden" name="id">

                    <div class="layui-form-item">
                        <label class="layui-form-label">微信</label>
                        <div class="layui-input-inline">
                            <input type="text" name="weixin" lay-verify="required" placeholder="微信"
                                   autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">qq</label>
                        <div class="layui-input-inline">
                            <input type="text" name="qq" lay-verify="required" placeholder="qq"
                                   autocomplete="off"
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
@stop

@section('script')
    <script type="application/javascript">
        layui.use(['form', 'upload'], function () {
            let $ = layui.$
                , form = layui.form
                , upload = layui.upload;
            form.val("formData",{!! $data !!});
            form.render('select');
            let index = parent.layer.getFrameIndex(window.name);
            $(document).on('click', '#close', function () {
                parent.layer.close(index);
            });
            parent.layer.iframeAuto(index);
            //自拍照片
            upload.render({
                elem: '#btnPicture'
                , url: '{{url('common/put')}}' //上传接口
                , before: function (obj) {
                    layui.layer.load();
                }
                , acceptMime: 'image/*'
                , done: function (res) {
                    //上传成功
                    $('#picture').val(res.data);
                    layer.closeAll('loading');
                    layer.msg('上传成功')
                }
                , error: function () {
                    layer.alert('上传失败');
                    layer.closeAll('loading');
                }
            });
            //自拍视频
            upload.render({
                elem: '#btnVideo'
                , url: '{{url('common/put')}}' //上传接口
                , before: function (obj) {
                    layui.layer.load();
                }
                , done: function (res) {
                    //上传成功
                    $('#video').val(res.data); //图片链接（base64）
                    layer.closeAll('loading');
                    layer.msg('上传成功');
                }
                , error: function () {
                    layer.alert('上传失败');
                    layer.closeAll('loading');
                }
            });

            //承诺条款
            upload.render({
                elem: '#btnUndertaking'
                , url: '{{url('common/put')}}' //上传接口
                , before: function (obj) {
                    layui.layer.load();
                }
                , done: function (res) {
                    //上传成功
                    $('#undertaking').val(res.data);
                    layer.closeAll('loading');
                    layer.msg('上传成功');
                }
                , error: function () {
                    layer.alert('上传失败');
                    layer.closeAll('loading');
                }
            });
            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;
                    axios.post("{{url('system/member/user/extend/update')}}", field)
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
