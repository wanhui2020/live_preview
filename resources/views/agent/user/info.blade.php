@extends('layouts.agent')

@section('content')
    <div class="row ">
        <div class="col-sm-2">
            @include('agent.common.side',['page'=>'交易中心','sub'=>'agent'])
        </div>
        <div class="col-sm-10">
            <div class="layui-card">
                <div class="layui-card-header">渠道信息</div>
                <div class="layui-card-body  " pad15>
                    <div class="layui-form  " lay-filter="formData">

                        <div class="layui-form-item">

                            <label class="layui-form-label">卖出放行</label>
                            <div class="layui-input-inline layui-form-mid">
                                {{$user->sell_auto_pass}}
                            </div>
                            <div class="layui-input-inline layui-form-mid">
                                {{$user->priority}}
                            </div>
                        </div>

                        <div class="layui-form-item"><label class="layui-form-label">流量控制</label>
                            <div class="layui-input-inline layui-form-mid">
                                {{$user->flow_control}}
                            </div>

                            <label class="layui-form-label">风险等级</label>
                            <div class="layui-input-inline layui-form-mid">
                                {{$user->risk_grade}}
                            </div>
                        </div>
                        <div class="layui-form-item">

                            <label class="layui-form-label">交易状态</label>
                            <div class="layui-input-inline">
                                <select name="deal_status" lay-verify="required">
                                    <option value="9">禁止交易</option>
                                    <option value="0">可买卖</option>
                                    <option value="1">可买入</option>
                                    <option value="2">可卖出</option>
                                </select>
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

            form.val("formData",{!! $user !!});


            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;


                    axios.post("{{url('agent/base/user/update')}}", field)
                        .then(function (response) {
                            if (response.data.status) {
                                layer.msg(response.data.msg);
                                return window.location.reload;
                            }
                            return layer.msg(response.data.msg);
                        });
                }
            );

        });

    </script>
@stop

