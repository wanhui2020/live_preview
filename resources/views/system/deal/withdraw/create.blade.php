@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">

                    <div class="layui-card-body" pad15>

                        <div class="layui-form" lay-filter="">
                            <div class="layui-form-item">
                                <label class="layui-form-label">提现会员</label>
                                <div class="layui-input-inline">
                                    <select name="member_id" id="member_id" lay-filter="member_id" lay-verify="required"
                                            lay-search>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>


                            <div class="layui-form-item"  >
                                <label class="layui-form-label" >提现金额</label>
                                <div class="layui-input-block">
                                    <input type="text" name="money" lay-verify="required" placeholder="提现金额"
                                           autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item"  >
                                <label class="layui-form-label" >收款人</label>
                                <div class="layui-input-block">
                                    <input type="text" name="username" lay-verify="required" placeholder="收款人"
                                           autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item"  >
                                <label class="layui-form-label" >银行名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="bank_name" lay-verify="required" placeholder="银行名称"
                                           autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item"  >
                                <label class="layui-form-label" >银行账号</label>
                                <div class="layui-input-block">
                                    <input type="text" name="bank_account" lay-verify="required" placeholder="银行账号"
                                           autocomplete="off" class="layui-input">
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
                axios.post("/system/platform/price/lists", {size: 1000}),
            ])
                .then(axios.spread(function (memberResp, paymentResp) {
                    if (memberResp.data.status) {
                        memberResp.data.data.forEach(function (value, index, array) {
                            $('#member_id').append("<option value='" + value.id + "'>" + value.nick_name + "</option>");
                        });
                    }
                    if (paymentResp.data.status) {
                        paymentResp.data.data.forEach(function (value, index, array) {
                            $('#price_id').append("<option value='" + value.id + "'>" + value.name + "</option>");
                        });
                    }
                    form.render('select');
                }));
            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;
                    axios.post("{{url('system/deal/withdraw/store')}}", field)
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
