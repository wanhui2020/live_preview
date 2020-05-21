@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">

                    <div class="layui-card-body" pad15>

                        <div class="layui-form" lay-filter="formData" wid100>
                            <input type="hidden" name="agent_id">
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">邀请充值奖励比</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="recharge_rate" autocomplete="off"
                                               class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">邀请消费奖励比</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="consumption_rate" autocomplete="off"
                                               class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">邀请注册奖励元</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="register_award" autocomplete="off"
                                               class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">普通消息收费</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="text_fee" autocomplete="off" class="layui-input">
                                    </div>
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
            form.val("formData",{!! $data !!});
            let index = parent.layer.getFrameIndex(window.name);
            $(document).on('click', '#close', function () {
                parent.layer.close(index);
            });
            parent.layer.iframeAuto(index);
            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;
                    let index = parent.layer.getFrameIndex(window.name);
                    axios.post("{{url('system/agent/user/rate/update')}}", field)
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
