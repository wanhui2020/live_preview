@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-card">

            <div class="layui-card-body" pad15>

                <div class="layui-form" lay-filter="formData" wid100>
                    <input type="hidden" name="id">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">自拍照</label>
                            <button type="button" class="layui-btn" id="btnPicture">
                                <i class="layui-icon">&#xe67c;</i>上传照片
                            </button>
                            <input type="hidden" name="picture">
                            @if($data->picture)
                                <img src="{{$data->fill_picture}}" style="width: 200px;display: block">
                            @endif
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">自拍视频</label>
                            <button type="button" class="layui-btn" id="btnVideo">
                                <i class="layui-icon">&#xe67c;</i>上传视频
                            </button>
                            <input type="hidden" name="video">
                            @if($data->video)
                                <img src="{{$data->fill_video}}" style="width: 200px;display: block">
                            @endif
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">承诺条款</label>
                            <button type="button" class="layui-btn" id="btnUndertaking">
                                <i class="layui-icon">&#xe67c;</i>上传视频
                            </button>
                            <input type="hidden" name="undertaking">
                            @if($data->undertaking)
                                <img src="{{$data->fill_undertaking}}" style="width: 200px;display: block">
                            @endif
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">认证状态</label>
                        <div class="layui-input-block">
                            <select name="status">
                                <option value="0" selected="">审核通过</option>
                                <option value="1">审核拒绝</option>
                                <option value="8">审核中</option>
                                <option value="9">待认证</option>
                            </select>
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
                    axios.post("{{url('system/member/user/selfie/update')}}", field)
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
