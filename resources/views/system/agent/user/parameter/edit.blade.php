@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">

                    <div class="layui-card-body" pad15>

                        <div class="layui-form" lay-filter="formData" wid100>
                            <input type="hidden" name="member_id"  >
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">勿扰</label>
                                    <div class="layui-input-inline">
                                        <select name="disturb">
                                            <option value="0">关闭</option>
                                            <option value="1">打开</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">开启定位</label>
                                    <div class="layui-input-inline">
                                        <select name="location">
                                            <option value="0">关闭</option>
                                            <option value="1">打开</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">浮动窗口</label>
                                    <div class="layui-input-inline">
                                        <select name="small_play">
                                            <option value="0">关闭</option>
                                            <option value="1">打开</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
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
            form.val("formData",{!! $data !!});
            form.render('select');
            let index = parent.layer.getFrameIndex(window.name);
            $(document).on('click', '#close', function () {
                parent.layer.close(index);
            });
            parent.layer.iframeAuto(index);
            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;
                    let index = parent.layer.getFrameIndex(window.name);
                    axios.post("{{url('system/member/user/parameter/update')}}", field)
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
