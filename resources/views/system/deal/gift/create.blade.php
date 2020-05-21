@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">

                    <div class="layui-card-body" pad15>
                        <div class="layui-form" lay-filter="" wid100>

                            <div class="layui-form-item">
                                <label class="layui-form-label">赠送会员</label>
                                <div class="layui-input-block">
                                    <select name="member_id" id="member_id" class="layui-input">
                                        <option value="" selected>请选择</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">接收会员</label>
                                <div class="layui-input-block">
                                    <select name="to_member_id" id="to_member_id" class="layui-input">
                                        <option value="" selected>请选择</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">赠送礼物</label>
                                <div class="layui-input-block">
                                    <select name="gift_id" id="gift_id" class="layui-input">
                                        <option value="" selected>请选择</option>
                                    </select>
                                </div>
                            </div>


                            <div class="layui-form-item">
                                <label class="layui-form-label">数量</label>
                                <div class="layui-input-block">
                                    <input type="number" name="quantity" lay-verify="required"
                                           placeholder="礼物数量"
                                           lay-filter="price" autocomplete="off" class="layui-input">
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
                axios.post("/system/platform/gift/lists", {size: 1000}),
            ])
                .then(axios.spread(function (memberResp, giftResp) {
                    if (memberResp.data.status) {
                        memberResp.data.data.forEach(function (value, index, array) {
                            $('#member_id').append("<option value='" + value.id + "'>" + value.nick_name + "</option>");
                            $('#to_member_id').append("<option value='" + value.id + "'>" + value.nick_name + "</option>");
                        });
                    }
                    if (giftResp.data.status) {
                        giftResp.data.data.forEach(function (value, index, array) {
                            $('#gift_id').append("<option value='" + value.id + "'>" + value.name + "</option>");
                        });

                    }

                    form.render('select');
                }));


            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;
                    axios.post("{{url('system/deal/gift/store')}}", field)
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
