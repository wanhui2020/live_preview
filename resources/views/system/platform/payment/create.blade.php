@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">

                    <div class="layui-card-body" pad15>
                        <div class="layui-form" lay-filter="">
                            <div class="layui-form-item">
                                <label class="layui-form-label">账户名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="name" lay-verify="required" placeholder="请输入支付通道名称"
                                           autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">支付方式</label>
                                <div class="layui-input-block">
                                    <select name="type" lay-verify="required">
                                        <option value="" selected="">请选择</option>
                                        <option value="h5"  >H5</option>
                                        <option value="app"  >APP原生</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">类型</label>
                                <div class="layui-input-block">
                                    <select id="channel_id" name="channel_id" lay-verify="required">
                                        <option value="" selected="">请选择</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">通道账号</label>
                                <div class="layui-input-block">
                                    <input type="text" name="account" lay-verify="required" placeholder="请输入通道账号"
                                           autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">充值费率</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="recharge_rate" placeholder="请输入充值费率" lay-verify="required"
                                           autocomplete="off"
                                           class="layui-input">
                                </div>
                                <label class="layui-form-label">成本费率</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="cost_rate" placeholder="请输入成本费率" lay-verify="required"
                                           autocomplete="off"
                                           class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">最小金额</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="min_money" placeholder="请输入最小金额" lay-verify="required"
                                           autocomplete="off"
                                           class="layui-input">
                                </div>
                                <label class="layui-form-label">最大金额</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="max_money" placeholder="请输入最大金额" lay-verify="required"
                                           autocomplete="off"
                                           class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">日限额</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="day_quota" placeholder="请输入日限额" autocomplete="off"
                                           class="layui-input">
                                </div>
                                <label class="layui-form-label">VIP最小等级</label>
                                <div class="layui-input-inline">
                                    <select id="vip_min_grade" name="vip_min_grade" lay-verify="required">
                                        <option value="" selected="">请选择</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">开始时间</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="begin_time" placeholder="请输入开始时间" autocomplete="off"
                                           class="layui-input">
                                </div>
                                <label class="layui-form-label">结束时间</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="end_time" placeholder="请输入结束时间" autocomplete="off"
                                           class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">通道参数</label>
                                <div class="layui-input-block"><textarea autocomplete="off" rows="6" name="parameter"
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
    <script type="application/javascript">
        layui.use(['form', 'upload', 'layedit'], function () {
            let $ = layui.$
                , form = layui.form
                , upload = layui.upload;


            let index = parent.layer.getFrameIndex(window.name);
            $(document).on('click', '#close', function () {
                parent.layer.close(index);
            });
            parent.layer.iframeAuto(index);
            axios.all([
                axios.post("/system/platform/payment/channel/lists", {size: 1000}),
                axios.post("/system/platform/vip/lists", {size: 1000}),
            ])
                .then(axios.spread(function (channelResp, vipResp) {
                    if (channelResp.data.status) {
                        channelResp.data.data.forEach(function (value, index, array) {
                            $('#channel_id').append("<option value='" + value.id + "'>" + value.name + "</option>");
                        });
                    }
                    if (vipResp.data.status) {
                        vipResp.data.data.forEach(function (value, index, array) {
                            $('#vip_min_grade').append("<option value='" + value.grade + "'>" + value.name + "</option>");
                        });
                    }
                    form.render('select');
                }));

            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;

                    axios.post("{{url('system/platform/payment/store')}}", field)
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
