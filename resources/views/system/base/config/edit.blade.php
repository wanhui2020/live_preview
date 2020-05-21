@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">系统参数</div>

                    <div class="layui-card-body" pad15>

                        <div class="layui-form" lay-filter="formData" wid100>
                            <div class="layui-form-item">
                                <label class="layui-form-label">系统名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="name" lay-verify="required" placeholder="系统名称" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">标志</label>
                                <div class="layui-input-block">
                                    <input type="text" name="logo" lay-verify="required" placeholder="标志" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">平台域名</label>
                                <div class="layui-input-block">
                                    <input type="text" name="domain" lay-verify="required" placeholder="平台域名" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">客服热线</label>
                                <div class="layui-input-block">
                                    <input type="text" name="tel" lay-verify="required" placeholder="客服热线" autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item" >
                                <label class="layui-form-label" >备注</label>
                                <div class="layui-input-block">
                                    <textarea  name="remark"  placeholder="请输入备注" class="layui-textarea"></textarea>
                                </div>
                            </div>
                            <input type="hidden" name="id">
                            <div class="layui-form-item ">
                                <div class="layui-input-block">
                                    <input type="button" class="layui-btn" lay-submit lay-filter="formSubmit"
                                           value="确认">
                                    {{--<input type="button" class="layui-btn layui-btn-primary " id="close" value="关闭">--}}
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
        layui.use(['form', 'upload', 'laydate'], function () {
            let $ = layui.$
                , form = layui.form
                , laydate = layui.laydate
                , upload = layui.upload;
            form.val("formData",{!! $config !!});
            //时间选择器
            laydate.render({
                elem: '#autocolse_at'
                , type: 'time'
            });
            laydate.render({
                elem: '#deferred_at'
                , type: 'time'
            });
            laydate.render({
                elem: '#entrust_end'
                , type: 'time'
            });
            laydate.render({
                elem: '#entrust_start'
                , type: 'time'
            });
            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;
                    axios.post("{{url('system/base/config/update')}}", field)
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
