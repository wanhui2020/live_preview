@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">

                    <div class="layui-card-body" pad15>

                        <div class="layui-form" lay-filter="">
                            <div class="layui-form-item">
                                <label class="layui-form-label">反馈人</label>
                                <div class="layui-input-inline">
                                    <select name="member_id" id="member_id"  lay-filter="member_id" lay-verify="required" lay-search>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">反馈内容</label>
                                <div class="layui-input-block">
                                    <input type="text" name="content" lay-verify="required" placeholder="请输入反馈内容" autocomplete="off"
                                           class="layui-input">
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
                            $('#member_id').append("<option value='" + value.id + "'>" + value.nick_name + '-'+ value.mobile + "</option>");
                        });
                    }
                    form.render('select');
                }));
            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;
                    axios.post("{{url('system/member/feedback/store')}}", field)
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
