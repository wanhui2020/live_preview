@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">

                    <div class="layui-card-body" pad15>

                        <div class="layui-form" lay-filter="formData">
                            <input type="hidden" name="id">
                            <div class="layui-form-item">
                                <label class="layui-form-label">通道名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="name" lay-verify="required" placeholder="请输入支付通道名称"
                                           autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">通道图标</label>
                                <div class="layui-input-block">
                                    <input type="text" name="icon" id="icon" placeholder="支付图标" autocomplete="off"
                                           class="layui-input">
                                    <button type="button" class="layui-btn" id="upicon">
                                        <i class="layui-icon">&#xe67c;</i>上传
                                    </button>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">通道标识</label>
                                <div class="layui-input-block">
                                    <select name="code" lay-verify="required">
                                        <option value="">请选择</option>
                                        <option value="alipay">企业支付宝</option>
                                        <option value="weixin">企业微信</option>
                                        <option value="person_alipay">个人支付宝</option>
                                        <option value="person_weixin">个人微信</option>
                                        <option value="bank">银行卡</option>
                                        <option value="huichao">汇潮无卡支付</option>
                                        <option value="hengyun">恒云支付</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">类型</label>
                                <div class="layui-input-block">
                                    <select name="type">
                                        <option value="0">线上</option>
                                        <option value="1">线下</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">收款方名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="payee" placeholder="请输入收款方名称"
                                           autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">收款方二维码</label>
                                <div class="layui-input-block">
                                    <input type="text" name="payee_icon" id="icon2" placeholder="收款方二维码"
                                           autocomplete="off"
                                           class="layui-input">
                                    <button type="button" class="layui-btn" id="upicon2">
                                        <i class="layui-icon">&#xe67c;</i>上传
                                    </button>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">备注</label>
                                <div class="layui-input-block">
                                    <textarea id="editor" name="remark" style="width:100%;height:100px;"
                                              class="layui-textarea">{{ $data->remark }}</textarea>
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
    {{--重新添加ueditor 富文本编辑器--}}
    <script type="text/javascript" charset="utf-8" src="{{ asset('ueditor/ueditor.config.js') }}"></script>
    <script type="text/javascript" charset="utf-8" src="{{ asset('ueditor/ueditor.all.min.js') }}"></script>
    <script type="text/javascript" charset="utf-8" src="{{ asset('ueditor/lang/zh-cn/zh-cn.js') }}"></script>

    <script type="application/javascript">
        var ue = UE.getEditor('editor');
        layui.use(['form', 'upload', 'layedit'], function () {
            let $ = layui.$
                , form = layui.form
                , laydate = layui.laydate

            form.val("formData",{!! $data !!});
            let index = parent.layer.getFrameIndex(window.name);
            $(document).on('click', '#close', function () {
                parent.layer.close(index);
            });
            parent.layer.iframeAuto(index);
            {{--//普通图片上传--}}
            layui.upload.render({
                elem: '#upicon' //绑定元素
                , url: '{{url('common/ossput')}}' //上传接口
                , accept: 'file' //普通文件
                , before: function (obj) {
                    layui.layer.load();
                }
                , done: function (res) {
                    $("#icon").val(res.src);
                    layer.closeAll('loading');
                    layer.msg('上传成功')
                }
                , error: function () {
                    layer.alert('上传失败');
                    layer.closeAll('loading');
                }
            });
            layui.upload.render({
                elem: '#upicon2' //绑定元素
                , url: '{{url('common/ossput')}}' //上传接口
                , accept: 'file' //普通文件
                , before: function (obj) {
                    layui.layer.load();
                }
                , done: function (res) {
                    $("#icon2").val(res.src);
                    layer.closeAll('loading');
                    layer.msg('上传成功')
                }
                , error: function () {
                    layer.alert('上传失败');
                    layer.closeAll('loading');
                }
            });
            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;
                    var content = UE.getEditor('editor').getContent();
                    field.remark = content;
                    axios.post("{{url('system/platform/payment/channel/update')}}", field)
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
