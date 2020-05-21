@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">

                    <div class="layui-card-body" pad15>

                        <div class="layui-form" lay-filter="formData">
                            <div class="layui-form-item" >
                                <label class="layui-form-label">金币余额</label>
                                <div class="layui-input-block">
                                    <input type="text" name="gold_balance" placeholder="请输入金币余额" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item" >
                                <label class="layui-form-label">金币可用余额</label>
                                <div class="layui-input-block">
                                    <input type="text" name="gold_usable" placeholder="请输入金币可用余额" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item" >
                                <label class="layui-form-label">金币冻结余额</label>
                                <div class="layui-input-block">
                                    <input type="text" name="gold_freeze" placeholder="请输入金币冻结余额" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item" >
                                <label class="layui-form-label">金币平台冻结余额</label>
                                <div class="layui-input-block">
                                    <input type="text" name="gold_platform" placeholder="请输入金币平台冻结余额" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item" >
                                <label class="layui-form-label">金币余额</label>
                                <div class="layui-input-block">
                                    <input type="text" name="money_balance" placeholder="请输入金币余额" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item" >
                                <label class="layui-form-label">现金可用余额</label>
                                <div class="layui-input-block">
                                    <input type="text" name="money_usable" placeholder="请输入金币可用余额" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item" >
                                <label class="layui-form-label">现金冻结余额</label>
                                <div class="layui-input-block">
                                    <input type="text" name="money_freeze" placeholder="请输入现金冻结余额" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item" >
                                <label class="layui-form-label">现金平台冻结余额</label>
                                <div class="layui-input-block">
                                    <input type="text" name="money_platform" placeholder="请输入现金平台冻结余额" autocomplete="off" class="layui-input">
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
            form.val("formData",{!! $wallet !!});
            let index = parent.layer.getFrameIndex(window.name);
            $(document).on('click', '#close', function () {
                parent.layer.close(index);
            });
            parent.layer.iframeAuto(index);
            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;
                    let index = parent.layer.getFrameIndex(window.name);
                    axios.post("{{url('system/member/wallet/update')}}", field)
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
