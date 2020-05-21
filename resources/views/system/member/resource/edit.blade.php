@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">

                    <div class="layui-card-body" pad15>

                        <div class="layui-form" lay-filter="formData">
                            <div class="layui-form-item">
                                <label class="layui-form-label">类型</label>
                                <div class="layui-input-block">
                                    <select name="type">
                                        <option value="0" selected="">图片</option>
                                        <option value="1">视频</option><option value="2">封面</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item" >
                                <label class="layui-form-label">资源地址</label>
                                <div class="layui-input-block">
                                    <input type="text" name="url"  placeholder="请输入原始资源地址" autocomplete="off" class="layui-input">
                                </div>
                            </div>
{{--                            <div class="layui-form-item" >--}}
{{--                                <label class="layui-form-label">缩略地址</label>--}}
{{--                                <div class="layui-input-block">--}}
{{--                                    <input type="text" name="thumb"  placeholder="请输入缩略地址" autocomplete="off" class="layui-input">--}}
{{--                                </div>--}}
{{--                            </div>--}}
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
            form.val("formData",{!! $resource !!});
            let index = parent.layer.getFrameIndex(window.name);
            $(document).on('click', '#close', function () {
                parent.layer.close(index);
            });
            parent.layer.iframeAuto(index);
            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;
                    let index = parent.layer.getFrameIndex(window.name);
                    axios.post("{{url('system/member/resource/update')}}", field)
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
