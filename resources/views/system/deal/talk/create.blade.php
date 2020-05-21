@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">

                    <div class="layui-card-body" pad15>

                        <div class="layui-form" lay-filter="formData" wid100>

                            <div class="layui-form-item">
                                <label class="layui-form-label">主叫</label>
                                <div class="layui-input-inline">
                                    <select name="dialing_id" id="dialing_id" lay-filter="dialing_id"
                                            lay-verify="required" lay-search>
                                        <option value="0"></option>
                                    </select>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">被叫</label>
                                <div class="layui-input-inline">
                                    <select name="called_id" id="called_id" lay-filter="called_id" lay-verify="required"
                                            lay-search>
                                        <option value="0"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">类型</label>
                                <div class="layui-input-inline">
                                    <select name="type" lay-verify="required">
                                        <option value="">请选择</option>
                                        <option value="1">语音</option>
                                        <option value="0">视频</option>
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
            let index = parent.layer.getFrameIndex(window.name);
            $(document).on('click', '#close', function () {
                parent.layer.close(index);
            });
            parent.layer.iframeAuto(index);

            axios.all([
                axios.post("/system/member/user/lists", {size: 1000}),
            ])
                .then(axios.spread(function (memberResp) {
                    if (memberResp.data.status) {
                        memberResp.data.data.forEach(function (value, index, array) {
                            $('#dialing_id').append("<option value='" + value.id + "'>" + value.nick_name + "</option>");
                            $('#called_id').append("<option value='" + value.id + "'>" + value.nick_name + "</option>");
                        });
                    }
                    form.render('select');
                }));
            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;
                    axios.post("{{url('system/deal/talk/store')}}", field)
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
