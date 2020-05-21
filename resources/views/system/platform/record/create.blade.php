@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">

                    <div class="layui-card-body" pad15>

                        <div class="layui-form" lay-filter="">
                            <div class="layui-form-item" >
                                <label class="layui-form-label">接受人</label>
                                <div class="layui-input-inline">
                                    <select name="customer" id="customerId" lay-filter="getUser" lay-verify="required" lay-search="">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item" >
                                <label class="layui-form-label">模板</label>
                                <div class="layui-input-inline">
                                    <select name="modules" lay-verify="required" lay-search="">
                                        <option value="">请选择</option>
                                    </select>
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
        layui.use(['form'], function () {
            let $ = layui.$,
                form = layui.form;
            let index = parent.layer.getFrameIndex(window.name);
            $(document).on('click', '#close', function () {
                parent.layer.close(index);
            });
            parent.layer.iframeAuto(index);
            axios.all([
                axios.post("/system/account/customer/user/lists", {size: 1000})
            ])
            .then(axios.spread(function (paymentResp) {
                if (paymentResp.data.status) {
                    paymentResp.data.data.forEach(function (value, index, array) {
                        $('#customerId').append("<option value='" + value.id + "'>" + value.realname + '-' + value.phone + "</option>");
                    });
                }
                form.render('select');
            }));
        });
    </script>
@stop
