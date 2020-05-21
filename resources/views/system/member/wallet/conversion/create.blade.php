@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">

                    <div class="layui-card-body" pad15>

                        <div class="layui-form" lay-filter="">
                            <div class="layui-form-item">
                                <label class="layui-form-label">所属会员</label>
                                <div class="layui-input-inline">
                                    <select name="member_id" id="member_id" lay-filter="member_id" lay-verify="required"
                                            lay-search>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">兑换方式</label>
                                <div class="layui-input-inline">
                                    <select name="way" lay-verify="required">
                                        <option value="">请选择</option>
                                        <option value="0">金币兑换现金</option>
                                        <option value="1">现金兑换金币</option>
                                    </select>
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">兑换数量</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="quantity" lay-verify="required|money" placeholder="请输入兑换数量"
                                           autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">兑换手续费</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="commission_quantity" placeholder="请输入兑换手续费"
                                           autocomplete="off"
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
                .then(axios.spread(function (memberResp, paymentResp) {
                    if (memberResp.data.status) {
                        memberResp.data.data.forEach(function (value, index, array) {
                            $('#member_id').append("<option value='" + value.id + "'>" + value.nick_name + "</option>");
                        });
                    }
                    form.render('select');
                }));
            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;
                    axios.post("{{url('system/member/wallet/conversion/store')}}", field)
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
