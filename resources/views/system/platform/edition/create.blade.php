@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">

                    <div class="layui-card-body" pad15>

                        <div class="layui-form" lay-filter="">
                            <div class="layui-form-item">
                                <label class="layui-form-label">所属类别</label>
                                <div class="layui-input-block">
                                    <select name="type">
                                        <option value="android" selected="">安卓</option>
                                        <option value="ios">苹果</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">链接</label>
                                <div class="layui-input-block">
                                    <input type="text" name="url" placeholder="链接" autocomplete="off"
                                           class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">版本号</label>
                                <div class="layui-input-block">
                                    <input type="text" name="version" placeholder="版本号" autocomplete="off"
                                           class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">强制更新</label>
                                <div class="layui-input-block">
                                    <select name="is_force">
                                        <option value="1" selected="">否</option>
                                        <option value="0">是</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">直接下载地址</label>
                                <div class="layui-input-block">
                                    <input type="text" name="download" placeholder="直接下载地址" autocomplete="off"
                                           class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">升级内容</label>
                                <div class="layui-input-block">
                                    <textarea id="editor" name="describe" style="width:100%;height:200px;"
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
        layui.use(['form', 'upload'], function () {
            let $ = layui.$
                , form = layui.form
                , upload = layui.upload;
            let index = parent.layer.getFrameIndex(window.name);
            $(document).on('click', '#close', function () {
                parent.layer.close(index);
            });
            parent.layer.iframeAuto(index);
            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;
                    var content = UE.getEditor('editor').getContent();
                    field.describe = content;
                    axios.post("{{url('system/platform/edition/store')}}", field)
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
