@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body" pad15>
                        <div class="layui-form" lay-filter="formData">
                            <div class="layui-form-item">
                                <label class="layui-form-label">内容</label>
                                <div class="layui-input-block">
                                    <input type="text" name="content" placeholder="请输入内容" autocomplete="off"
                                           class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">图片</label>
                                <div class="layui-input-block">
                                    <input type="text" name="mood" placeholder="图片" autocomplete="off"
                                           class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">视频</label>
                                <div class="layui-input-block">
                                    <input type="text" name="quantity" placeholder="视频" autocomplete="off"
                                           class="layui-input">
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
            form.val("formData",{!! $user !!});
            let index = parent.layer.getFrameIndex(window.name);
            $(document).on('click', '#close', function () {
                parent.layer.close(index);
            });
            parent.layer.iframeAuto(index);
            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;
                    let index = parent.layer.getFrameIndex(window.name);
                    axios.post("{{url('system/member/user/update')}}", field)
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
