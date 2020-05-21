@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body" pad15>
                        <div class="layui-form" lay-filter="formData">
                                <div class="layui-form-item" >
                                    <label class="layui-form-label" >code</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="code" lay-verify="required" placeholder="请输入code" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-form-item" >
                                    <label class="layui-form-label" >类型(数字)</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="type" lay-verify="required" placeholder="请输入类型(数字)" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-form-item" >
                                    <label class="layui-form-label" >类型(中文)</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="type_en" lay-verify="required" placeholder="请输入类型(中文)" autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label">模板内容</label>
                                    <div class="layui-input-block">
                                        <textarea placeholder="请输入模板内容" name="content" class="layui-textarea"></textarea>
                                    </div>
                                </div>
                                <input type="hidden" name="id">
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
        layui.use(['form', 'upload'], function () {
            let $ = layui.$
                , form = layui.form
                , upload = layui.upload;
            form.val("formData",{!! $cons !!});
            let index = parent.layer.getFrameIndex(window.name);
            $(document).on('click', '#close', function () {
                parent.layer.close(index);
            });
            parent.layer.iframeAuto(index);
            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;
                    if(isNaN(Number(field.type))){
                        layer.alert("类型(数字)必须是数字", {icon: 2});
                        return false;
                    }
                    let index = parent.layer.getFrameIndex(window.name);
                    axios.post("{{url('system/platform/template/update')}}", field)
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
