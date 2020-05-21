@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body" pad15>
                        <div class="layui-form" lay-filter="">
                            <div class="layui-form-item">
                                <label class="layui-form-label">数据类型</label>
                                <div class="layui-input-inline">
                                    <select name="type" lay-verify="required">
                                        <option value="report">举报理由</option>
                                        <option value="blood">血型</option>
                                        <option value="emotion">情感</option>
                                        <option value="income">收入</option>
                                        <option value="profession">职业</option>
                                        <option value="hobbies">兴趣爱好</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">标签</label>
                                <div class="layui-input-block">
                                    <input type="text" name="key" lay-verify="required|Tags" placeholder="请输入标签名称"
                                           autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">值</label>
                                <div class="layui-input-block">
                                    <input type="text" name="value" lay-verify="required" placeholder="请输入标签值"
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
        layui.use(['form', 'upload', 'layedit'], function () {
            let $ = layui.$
                , form = layui.form;

            let index = parent.layer.getFrameIndex(window.name);
            $(document).on('click', '#close', function () {
                parent.layer.close(index);
            });
            parent.layer.iframeAuto(index);

            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;

                    axios.post("{{url('system/platform/basic/store')}}", field)
                        .then(function (response) {
                            if (response.data.status) {
                                parent.layer.msg(response.data.msg);
                                return parent.layer.close(index);
                            }
                            return layer.msg(response.data.msg);
                        });
                }
            );
            form.verify({ // 验证
                Tags: function (value, item) {
                    if (!new RegExp("^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$").test(value)) {
                        return '标签名不能有特殊字符';
                    }
                }
            });
        });
    </script>
@stop
