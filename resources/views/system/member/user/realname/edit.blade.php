@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">

                    <div class="layui-card-body" pad15>

                        <div class="layui-form" lay-filter="formData" wid100>
                            <input type="hidden" name="id" value=" {{$data->id}}">

                            <div class="layui-form-item">
                                <label class="layui-form-label">真实姓名</label>
                                <div class="layui-form-mid">
                                    {{$data->name}}
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">身份证号</label>
                                <div class="layui-form-mid">
                                    {{$data->idcard}}

                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">身份证正面照</label>
                                <div class="layui-input-block">
                                    <img src="{{$data->idcard_front}}" id="idcard_front" style="width: 200px;">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">身份证反面照</label>
                                <div class="layui-input-block">
                                    <img src="{{$data->idcard_back}}" id="idcard_front" style="width: 200px;">

                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">手持身份证</label>
                                <div class="layui-input-block">
                                    <img src="{{$data->idcard_hand}}" id="idcard_front" style="width: 200px;">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">地址</label>
                                <div class="layui-form-mid">
                                    {{$data->address}}
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">认证状态</label>
                                <div class="layui-input-block">
                                    <select name="status">
                                        <option value="0" selected="">审核通过</option>
                                        <option value="1">审核拒绝</option>
                                        <option value="8">待确认</option>
                                        <option value="9">待实名</option>
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
            let index = parent.layer.getFrameIndex(window.name);
            $(document).on('click', '#close', function () {
                parent.layer.close(index);
            });
            parent.layer.iframeAuto(index);
            //普通图片上传
            var uploadInst = upload.render({
                elem: '#idcard_ico'
                , url: '{{url('common/put')}}' //上传接口
                , accept: 'file' //普通文件
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
                , accept: 'file' //普通文件
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
                , accept: 'file' //普通文件
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
                , accept: 'file' //普通文件
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
                    let index = parent.layer.getFrameIndex(window.name);
                    axios.post("{{url('system/member/user/realname/update')}}", field)
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
