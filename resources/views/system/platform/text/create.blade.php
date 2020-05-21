@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">

                    <div class="layui-card-body" pad15>
                        <div class="layui-form" lay-filter="">
                            <div class="layui-form-item">
                                <label class="layui-form-label">类型</label>
                                <div class="layui-input-inline" style="z-index: 999999999;">
                                    <select name="type" lay-verify="required">
                                        <option value="1">自拍认证文本</option>
                                        <option value="2">邀请文本</option>
                                        <option value="3">关于文本</option>
                                        <option value="4">提现说明</option>
                                        <option value="5">收费说明</option>
                                        <option value="6">用户协议</option>
                                        <option value="7">隐私协议</option>
                                        <option value="8">认证文字</option>
                                        <option value="9">会员特权</option>
                                        <option value="10">公告协议</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">内容</label>
                                <div class="layui-input-block">
                                    <textarea id="editor" name="content" style="width:100%;height:500px;"
                                              class="layui-textarea"></textarea>
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
    {{--重新添加ueditor 富文本编辑器--}}
    <script type="text/javascript" charset="utf-8" src="{{ asset('ueditor/ueditor.config.js') }}"></script>
    <script type="text/javascript" charset="utf-8" src="{{ asset('ueditor/ueditor.all.min.js') }}"></script>
    <script type="text/javascript" charset="utf-8" src="{{ asset('ueditor/lang/zh-cn/zh-cn.js') }}"></script>

    <script type="application/javascript">
        var ue = UE.getEditor('editor');
        layui.use(['form', 'upload', 'layedit'], function () {
            let $ = layui.$
                , form = layui.form
                , upload = layui.upload
                , layedit = layui.layedit;
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
            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;
                    var content = UE.getEditor('editor').getContent();
                    field.content = content;
                    axios.post("{{url('system/platform/text/store')}}", field)
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
