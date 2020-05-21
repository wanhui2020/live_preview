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
                                    <select name="member_id" id="member_id" lay-filter="member_id" lay-verify="required"
                                            lay-search>
                                        <option value="0"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">自拍照</label>
                                    <button type="button" class="layui-btn" id="btnPicture">
                                        <i class="layui-icon">&#xe67c;</i>上传照片
                                    </button>
                                    <input type="hidden" name="picture">
                                    @if($data->picture)
                                        <img src="{{$data->picture}}" style="width: 200px;display: block">
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
                                        <img src="{{$data->video}}" style="width: 200px;display: block">
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
                                        <img src="{{$data->undertaking}}" style="width: 200px;display: block">
                                    @endif
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
                axios.post("/system/member/user/lists", {size: 1000}),
            ])
                .then(axios.spread(function (agentResp) {
                    if (agentResp.data.status) {
                        agentResp.data.data.forEach(function (value, index, array) {
                            $('#member_id').append("<option value='" + value.id + "'>" + value.nick_name + "</option>");
                        });
                    }
                    form.render('select');
                }));

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
                    field.selfie_pic = $('#imgs').attr('src');
                    axios.post("{{url('system/member/user/selfie/store')}}", field)
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
