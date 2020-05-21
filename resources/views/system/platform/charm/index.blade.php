@extends('layouts.base')
@section('content')

    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-form layui-card-header layuiadmin-card-header-auto" lay-filter="aa">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" name="key" placeholder="请输入名称" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <select name="status">
                                <option value="">全部状态</option>
                                <option value="0">正常</option>
                                <option value="1">禁用</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <button class="layui-btn " id="Search" lay-submit lay-filter="search">
                            <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>搜索
                        </button>
                        <button lay-submit lay-filter="refresh" class="layui-btn">
                            <i class="layui-icon layui-icon-return layuiadmin-button-btn"></i>重置
                        </button>
                    </div>

                </div>
            </div>
            <div class="layui-card-body">
                <script type="text/html" id="toolbar">
                    <div>
                        <button class="layui-btn  layui-btn-sm" lay-event="add">添加</button>
                    </div>
                </script>
                <table id="lists" lay-filter="lists"></table>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script type="application/javascript">
        layui.use(['table', 'laydate'], function () {
            let table = layui.table, form = layui.form;
            let laydate = layui.laydate;

            let tableIns = table.render({
                elem: '#lists'
                , url: '{{url('system/platform/charm/lists')}}' //数据接口
                , method: 'POST'
                , toolbar: '#toolbar'
                , page: true //开启分页
                , title: 'charm管理'
                , cols: [[
                    {field: 'id', title: 'ID', align: 'center', width: 80},
                    {field: 'name', title: '魅力名称', align: 'center'}
                    , {field: 'grade', title: '等级', align: 'center'}
                    , {field: 'integral', title: '魅力积分', align: 'center'}
                    , {field: 'text_fee', title: '消息收费', align: 'center'}
                    , {field: 'voice_fee', title: '语音通话收费', align: 'center'}
                    , {field: 'video_fee', title: '视频通话收费', align: 'center'}
                    // , {field: 'view_picture_fee', title: '颜照资源收费', align: 'center'}
                    // , {field: 'view_video_fee', title: '视频资源收费', align: 'center'}
                    // , {
                    //     field: 'status', title: '状态', align: 'center', width: 80,
                    //     templet: function (d) {
                    //         let html = '';
                    //         if (d.status === 0) {
                    //             html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="status">启用</a>';
                    //         }
                    //         if (d.status === 1) {
                    //             html += '<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="status">禁用</a>';
                    //         }
                    //
                    //         return html;
                    //     }
                    // }
                    , {
                        title: '操作', width: 120, align: 'center', templet: function (d) {
                            let html = '';
                            html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">编辑</a>';
                            html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="del">删除</a>';
                            return html;
                        }
                    }

                ]]
            });

            //监听排序事件
            table.on('sort(lists)', function (obj) {

                table.reload('lists', {
                    initSort: obj
                    , where: {
                        field: obj.field
                        , order: obj.type
                    }
                });
            });

            //监听搜索
            form.on('submit(search)', function (data) {
                let field = data.field;
                //field.page = 0;
                //执行重载
                table.reload('lists', {
                    where: field
                    , page: {
                        curr: 1 //重新从第 1 页开始
                    }
                });
            });
            $(document).keydown(function (e) {
                if (e.keyCode === 13) {
                    $("#Search").trigger("click");
                }
            });
            //监听重置
            form.on('submit(refresh)', function (data) {
                $('.layui-input').val('');
                $("select").val('');
                table.reload('lists', {
                    where: ''
                    , page: {
                        curr: 1 //重新从第 1 页开始
                    }
                });

            });


            //监听工具栏事件
            table.on('toolbar(lists)', function (obj) {
                let checkStatus = table.checkStatus(obj.config.id);

                switch (obj.event) {
                    case 'add':
                        layer.open({
                            type: 2
                            , title: '添加魅力'
                            , content: '{{url('system/platform/charm/create')}}'
                            , maxmin: true
                            , area: ['900px', '800px']
                            , end: function () {
                                tableIns.reload();
                            }
                        });
                        break;
                }
            });

            //监听行工具条
            table.on('tool(lists)', function (obj) {
                let data = obj.data;
                let layEvent = obj.event;
                let tr = obj.tr;

                if (layEvent === 'status') { //改变状态
                    let _c = data.status === 0 ? '禁用' : '启用';
                    layer.confirm('确定要【' + _c + '】么?', {icon: 3, btn: ['确定', '取消'], title: "信息提示"}, function (index) {
                        axios.post("{{url('system/platform/charm/status')}}", {id: data.id})
                            .then(function (response) {
                                    if (response.data.code === 0) {
                                        layer.msg(response.data.data);
                                        tableIns.reload();
                                        return
                                    }
                                    return layer.msg(response.data.msg);
                                }
                            );
                    });
                }
                if (layEvent === 'edit') { //编辑
                    layer.open({
                        type: 2
                        , title: '编辑魅力'
                        , content: '{{url('system/platform/charm/edit?id=')}}' + data.id
                        , maxmin: true
                        , area: ['900px', '800px']
                        , end: function () {
                            tableIns.reload();
                        }
                    });
                }
                if (layEvent === 'del') { //删除
                    layer.confirm('确定要删除么？', {
                        icon: 3,
                        btn: ['确定', '取消'],
                        title: "删除提示"
                    }, function (index) {
                        axios.post("{{url('system/platform/charm/destroy')}}", {ids: [data.id]})
                            .then(function (response) {
                                    if (response.data.status) {
                                        layer.msg(response.data.msg);
                                        return tableIns.reload();
                                    }
                                    return layer.alert(response.data.msg);
                                }
                            );
                    });
                }
            });

        });

        //显示图片
        function show_img(t) {
            var t = $(t).find("img");
            //页面层
            layer.open({
                type: 1,
                skin: 'layui-layer-rim', //加上边框
                area: ['60%', '50%'], //宽高
                shadeClose: true, //开启遮罩关闭
                end: function (index, layero) {
                    return false;
                },
                content: '<div style="text-align:center;height: 100%;"><img src="' + $(t).attr('src') + ' " height="100%" width="auto"/></div>'
            });
        }
    </script>
@endsection

