@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">

                    <div class="layui-card-body" pad15>

                        <div class="layui-form" lay-filter="">
                            <div class="layui-form-item">
                                <label class="layui-form-label">标题</label>
                                <div class="layui-input-block">
                                    <input type="text" name="title" lay-verify="required" placeholder="请输入标题"
                                           autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">所属类型</label>
                                <div class="layui-input-block">
                                    <select name="news_type" lay-filter="aihao">
                                        <option value="0"selected="">媒体报道</option>
                                        <option value="1">行业资讯</option>
                                    </select>
                                </div>
                            </div>                            <div class="layui-form-item">
                                <label class="layui-form-label">内容</label>
                                <div class="layui-input-block">
                                    <textarea id="demo" lay-verify="article_desc" class="layui-textarea" name="content"
                                              placeholder="请输入内容"></textarea>
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
        layui.use(['form', 'upload','layedit'], function () {
            let $ = layui.$
                , form = layui.form
                , upload = layui.upload
                , layedit = layui.layedit;
            //创建一个编辑器
            var editIndex = layedit.build('demo');
            //自定义验证规则
            form.verify({
                article_desc: function(value){
                    layedit.sync(editIndex);
                }
            });
            let index = parent.layer.getFrameIndex(window.name);
            $(document).on('click', '#close', function () {
                parent.layer.close(index);
            });
            parent.layer.iframeAuto(index);
            form.on('submit(formSubmit)', function (data) {
                    let field = data.field
                        , IllegalString = "[`~!#$^&*()=|{}':;',\\[\\].<>/?~！#￥……&*（）——|{}【】‘；：”“'。，、？]‘'"
                        , title = field.title
                        , index_title = title.length - 1
                        , stitle = title.charAt(index_title);
                    if (IllegalString.indexOf(stitle) >= 0) {
                        layer.alert("标题不能是特殊字符", {icon: 2});
                        return false;
                    }
                    axios.post("{{url('system/platform/news/store')}}", field)
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
