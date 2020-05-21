@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">

                    <div class="layui-card-body" pad15>

                        <div class="layui-form" lay-filter="formData" wid100>

                            <input type="hidden" name="id">
                            <div class="layui-form-item"   >
                                <label class="layui-form-label" >代币名称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="name" lay-verify="required" placeholder="如：BIT"
                                           autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item"  >
                                <label class="layui-form-label" >标识</label>
                                <div class="layui-input-block">
                                    <input type="text" name="code" lay-verify="required" placeholder="标识"
                                           autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item"   >
                                <label class="layui-form-label" >钱包地址</label>
                                <div class="layui-input-block">
                                    <input type="text" name="wallet" lay-verify="required" placeholder="钱包地址"
                                           autocomplete="off" class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item"  >
                                <div class="col-md-6 mb-3" >
                                    <label class="layui-form-label">收款二维码</label>
                                    <button type="button" class="layui-btn" id="qrcode">
                                        <i class="layui-icon">&#xe67c;</i>上传图片
                                    </button>
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
        layui.use(['form', 'upload'], function () {
            let $ = layui.$
                , form = layui.form
                , upload = layui.upload;
            form.val("formData",{!! $data !!});
            let index = parent.layer.getFrameIndex(window.name);
            $(document).on('click', '#close', function () {
                parent.layer.close(index);
            });

            //普通图片上传
            var uploadInst = upload.render({
                elem: '#image'
                ,url: '{{url('common/put')}}' //上传接口
                ,before: function(obj){

                }
                ,acceptMime: 'image/*'
                ,done: function(res){
                    //如果上传失败
                    if(res.code > 0){
                        return layer.msg('上传失败');
                    }
                    //上传成功
                    $("[name='image']").val(res.src);
                    layer.closeAll('loading');
                    layer.msg('上传成功');
                }
                ,error: function(){
                    //演示失败状态，并实现重传
                    layer.alert('上传失败');
                    layer.closeAll('loading');
                }
            });
            parent.layer.iframeAuto(index);
            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;
                    axios.post("{{url('system/currency/recharge/update')}}", field)
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
