@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-card">

            <div class="layui-card-body" pad15>
                <div class="layui-form" lay-filter="">
                    <div class="layui-form-item">
                        <label class="layui-form-label">名称</label>
                        <div class="layui-input-block">
                            <input type="text" name="name" lay-verify="required" placeholder="请输入名称"
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">URL</label>
                        <div class="layui-input-block">
                            <input type="text" name="url" placeholder="请输入URL" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    @if(count($condition) > 0)
                    <div class="layui-form-item">
                        <label class="layui-form-label">条件</label>
                        <div class="layui-input-block">
                            @foreach($condition as $item)
                                <input type="checkbox" name="condition" title="{{$item['name']}}" value="{{$item['id']}}"><div class="layui-unselect layui-form-checkbox layui-form-checked"><span>{{$item['name']}}</span><i class="layui-icon layui-icon-ok"></i></div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <div class="layui-form-item">
                        <label class="layui-form-label">类型</label>
                        <div class="layui-input-block">
                            <select name="type">
                                <option value="0" selected="">首页</option>
                                <option value="1" >消息页</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">是否系统</label>
                        <div class="layui-input-block">
                            <select name="is_system">
                                <option value="0" selected="">否</option>
                                <option value="1" >是</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">排序(升序)</label>
                        <div class="layui-input-block">
                            <input type="number" name="sort" lay-verify="required" placeholder="请输入排序" value="0"
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
@stop

@section('script')
    <script type="application/javascript">
        layui.use(['form', 'upload', 'layedit'], function () {
            let $ = layui.$
                , form = layui.form
                , upload = layui.upload
                , layedit = layui.layedit;
            //创建一个编辑器
            var editIndex = layedit.build('content');
            //自定义验证规则
            form.verify({
                article_desc: function (value) {
                    layedit.sync(editIndex);
                }
            });
            let index = parent.layer.getFrameIndex(window.name);
            $(document).on('click', '#close', function () {
                parent.layer.close(index);
            });
            parent.layer.iframeAuto(index);
            //普通图片上传
            var uploadInst = upload.render({
                elem: '#ico'
                , url: '{{url('common/put')}}' //上传接口
                , before: function (obj) {
                    //预读本地文件示例，不支持ie8
                    obj.preview(function (index, file, result) {
                        $('#demo1').attr('src', result); //图片链接（base64）
                    });
                }
                , acceptMime: 'image/*'
                , done: function (res) {
                    //如果上传失败
                    if (res.code > 0) {
                        return layer.msg('上传失败');
                    }
                    //上传成功
                    $('#imgs').attr('src', res.src);
                    layer.closeAll('loading');
                    layer.msg('上传成功');
                }
                , error: function () {
                    //演示失败状态，并实现重传
                    layer.alert('上传失败');
                    layer.closeAll('loading');
                }
            });
            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;
                    var arr = new Array();
                    $("input:checkbox[name='condition']:checked").each(function (i) {
                        arr[i] = $(this).val();
                    });
                    data.field.condition_id = arr.join(",");//将数组合并成字符串
                    delete field.condition;
                    axios.post("{{url('system/platform/type/store')}}", field)
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
