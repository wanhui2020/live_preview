@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-card-body" pad15>
                <div class="layui-form" lay-filter="">
                    <div class="layui-form-item">
                        <label class="layui-form-label">魅力等级</label>
                        <div class="layui-input-inline">
                            <select name="grade">
                                <option value="0" selected="">M0</option>
                                <option value="1">M1</option>
                                <option value="2">M2</option>
                                <option value="3">M3</option>
                                <option value="4">M4</option>
                                <option value="5">M5</option>
                                <option value="6">M6</option>
                                <option value="7">M7</option>
                                <option value="8">M8</option>
                                <option value="9">M9</option>
                                <option value="10">M10</option>
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
                        <div class="layui-form-item">
                            <label class="layui-form-label">积分数</label>
                            <div class="layui-input-block">
                                <input type="text" name="integral" lay-verify="required" placeholder="升级魅力积分数"
                                       autocomplete="off" class="layui-input">
                            </div>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">普通消息收费</label>
                        <div class="layui-input-inline">
                            <input type="text" name="text_fee" lay-verify="required" placeholder="请输入普通消息收费"
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">语音消息收费</label>
                        <div class="layui-input-inline">
                            <input type="text" name="voice_fee" lay-verify="required" placeholder="请输入语音消息收费"
                                   autocomplete="off" class="layui-input">
                        </div>

                        <label class="layui-form-label">视频消息收费</label>
                        <div class="layui-input-inline">
                            <input type="text" name="video_fee" lay-verify="required" placeholder="请输入视频消息收费"
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    {{--<div class="layui-form-item">--}}
                        {{--<label class="layui-form-label">颜照库收费</label>--}}
                        {{--<div class="layui-input-inline">--}}
                            {{--<input type="text" name="view_picture_fee" lay-verify="required"--}}
                                   {{--placeholder="请输入颜照库收费"--}}
                                   {{--autocomplete="off" class="layui-input">--}}
                        {{--</div>--}}

                        {{--<label class="layui-form-label">视频库收费</label>--}}
                        {{--<div class="layui-input-inline">--}}
                            {{--<input type="text" name="view_video_fee" lay-verify="required"--}}
                                   {{--placeholder="请输入视频库收费"--}}
                                   {{--autocomplete="off" class="layui-input">--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <hr>
                    <div class="layui-form-item">
                        <label class="layui-form-label">礼物赠送分成</label>
                        <div class="layui-input-inline">
                            <input type="text" name="gift_rate" lay-verify="required"
                                   placeholder="请输入礼物平台分成占比"
                                   autocomplete="off" class="layui-input">
                        </div>

                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">解锁聊天分成</label>
                        <div class="layui-input-inline">
                            <input type="text" name="chat_rate" lay-verify="required"
                                   placeholder="请输入聊天解锁分成占比"
                                   autocomplete="off" class="layui-input">
                        </div>

                        <label class="layui-form-label">消息发送分成</label>
                        <div class="layui-input-inline">
                            <input type="text" name="text_rate" lay-verify="required"
                                   placeholder="请输入消息收费分成占比"
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">语音通话分成</label>
                        <div class="layui-input-inline">
                            <input type="text" name="voice_rate" lay-verify="required"
                                   placeholder="请输入语音通话消费分成占比"
                                   autocomplete="off" class="layui-input">
                        </div>

                        <label class="layui-form-label">视频通话分成</label>
                        <div class="layui-input-inline">
                            <input type="text" name="video_rate" lay-verify="required"
                                   placeholder="请输入视频通话消费分成占比"
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">图片查看分成</label>
                        <div class="layui-input-inline">
                            <input type="text" name="view_picture_rate" lay-verify="required"
                                   placeholder="请输入图片查看分成占比"
                                   autocomplete="off" class="layui-input">
                        </div>

                        <label class="layui-form-label">视频查看分成</label>
                        <div class="layui-input-inline">
                            <input type="text" name="view_video_rate" lay-verify="required"
                                   placeholder="请输入视频查看分成占比"
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
        layui.use(['form', 'upload' ], function () {
            let $ = layui.$
                , form = layui.form
                , upload = layui.upload ;
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
                    axios.post("{{url('system/platform/charm/store')}}", field)
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
