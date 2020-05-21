@extends('layouts.base')
@section('content')

    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-form layui-card-header layuiadmin-card-header-auto" lay-filter="aa">
                <div class="layui-form-item">
{{--                    <div class="layui-inline">--}}
{{--                        <div class="layui-input-inline">--}}
{{--                            <input type="text" name="key" placeholder="请输入名称" autocomplete="off" class="layui-input">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="layui-inline">--}}
{{--                        <div class="layui-input-inline">--}}
{{--                            <input type="text" name="start_time" class="layui-input" id="start_time"--}}
{{--                                   placeholder="创建开始时间">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="layui-inline">--}}
{{--                        <div class="layui-input-inline">--}}
{{--                            <input type="text" class="layui-input" name="end_time" id="end_time" placeholder="创建结束时间">--}}
{{--                        </div>--}}
{{--                    </div>--}}
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
                        <a class="layui-btn  layui-btn-sm" href="{{url('system/platform/payment/channel')}}">通道管理</a>
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
                , url: '{{url('system/platform/payment/lists')}}' //数据接口
                , method: 'POST'
                , toolbar: '#toolbar'
                , page: true //开启分页
                , title: '标签管理'
                , cols: [[
                    {field: 'id', title: 'ID', align: 'center', width: 80}
                    , {
                        field: 'channel_id', title: '所属通道', align: 'center',
                        templet: function (d) {
                            let html = '';
                            if (d.channel) {
                                html += d.channel.name;
                            }
                            return html;
                        }
                    }
                    , {
                        field: 'type', title: '支付方式', align: 'center', width: 120
                    }
                    , {field: 'name', title: '账户名称', align: 'center'}
                    , {field: 'account', title: '账号', align: 'center'}
                    , {field: 'min_money', title: '最小金额', align: 'center'}
                    , {field: 'max_money', title: '最大金额', align: 'center'}
                    , {field: 'begin_time', title: '开始时间', align: 'center'}
                    , {field: 'end_time', title: '结束时间', align: 'center'}
                    , {field: 'cost_rate', title: '成本费率', align: 'center'}
                    , {field: 'vip_min_grade', title: 'VIP等级', align: 'center'}
                    , {
                        field: 'status', title: '状态', align: 'center', width: 80,
                        templet: function (d) {
                            let html = '';
                            if (d.status === 0) {
                                html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="status">启用</a>';
                            }
                            if (d.status === 1) {
                                html += '<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="status">禁用</a>';
                            }

                            return html;
                        }
                    }
                    , {field: 'created_at', title: '创建时间', width: 200, sort: true, align: 'center'}
                    , {
                        title: '操作', width: 120, align: 'center', templet: function (d) {
                            let _c = d.status === 0 ? '禁用' : '启用';
                            let html = '';
                            html = '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">编辑</a>';
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
                            , title: '添加支付通道'
                            , content: '{{url('system/platform/payment/create')}}'
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
                if (layEvent === 'status') { //状态
                    layer.confirm('确定要修改状态?', {icon: 3, btn: ['确定', '取消'], title: "信息提示"}, function (index) {
                        axios.post("{{url('system/platform/payment/status')}}", {id: data.id})
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
                        , title: '编辑公告'
                        , content: '{{url('system/platform/payment/edit?id=')}}' + data.id
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
                        axios.post("{{url('system/platform/payment/destroy')}}", {ids: [data.id]})
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

