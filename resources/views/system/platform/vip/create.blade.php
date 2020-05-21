@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-card">

            <div class="layui-card-body" pad15>
                <div class="layui-form" lay-filter="">
                    <div class="layui-form-item">
                        <label class="layui-form-label">VIP等级</label>
                        <div class="layui-input-inline">
                            <select name="grade">
                                <option value="0" selected="">V0</option>
                                <option value="1">V1</option>
                                <option value="2">V2</option>
                                <option value="3">V3</option>
                                <option value="4">V4</option>
                                <option value="5">V5</option>
                                <option value="6">V6</option>
                                <option value="7">V7</option>
                                <option value="8">V8</option>
                                <option value="9">V9</option>
                                <option value="10">V10</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">图标</label>
                        <div class="layui-input-block">
                            <input type="hidden" name="icon" id="icon">
                            <img src="{{url('images/logo.ico')}}" style="width: 50px;height: 50px;"
                                 id="iconView">
                            <button type="button" class="layui-btn" id="upicon">
                                <i class="layui-icon">&#xe67c;</i>上传
                            </button>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">直升机价格</label>
                        <div class="layui-input-block">
                            <input type="text" name="price" lay-verify="required" placeholder="请输入直升机价格"
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">积分数</label>
                        <div class="layui-input-block">
                            <input type="text" name="integral" lay-verify="required" placeholder="升级VIP积分数"
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">颜照库收费</label>
                        <div class="layui-input-inline">
                            <input type="text" name="view_picture_fee" lay-verify="required"
                                   placeholder="请输入颜照库收费"
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">视频库收费</label>
                        <div class="layui-input-inline">
                            <input type="text" name="view_video_fee" lay-verify="required"
                                   placeholder="请输入视频库收费"
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">特权说明</label>
                        <div class="layui-input-block">
                            <textarea id="editor" name="describe" style="width:100%;height:100px;" class="layui-textarea"></textarea>
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
    {{--重新添加ueditor 富文本编辑器--}}
    <script type="text/javascript" charset="utf-8" src = "{{ asset('ueditor/ueditor.config.js') }}" ></script>
    <script type="text/javascript" charset="utf-8" src = "{{ asset('ueditor/ueditor.all.min.js') }}" ></script>
    <script type="text/javascript" charset="utf-8" src = "{{ asset('ueditor/lang/zh-cn/zh-cn.js') }}" ></script>

    <script type="application/javascript">
        var ue = UE.getEditor('editor');
        layui.use(['form', 'upload'], function () {
            let $ = layui.$
                , form = layui.form
                , upload = layui.upload;
            //普通图片上传
            var uploadInst = upload.render({
                elem: '#upicon'
                , url: '{{url('common/put')}}' //上传接口
                , accept: 'file' //普通文件
                , before: function (obj) {
                    layui.layer.load();
                }
                , done: function (res) {
                    $('#icon').val(res.data);
                    $('#iconView').attr('src', res.src);
                    layer.closeAll('loading');
                    layer.msg('上传成功')
                }
                , error: function () {
                    layer.alert('上传失败')
                    layer.closeAll('loading');
                }
            });
            let index = parent.layer.getFrameIndex(window.name);
            $(document).on('click', '#close', function () {
                parent.layer.close(index);
            });
            parent.layer.iframeAuto(index);
            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;
                var content = UE.getEditor('editor').getContent();
                field.describe = content;
                    axios.post("{{url('system/platform/vip/store')}}", field)
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
