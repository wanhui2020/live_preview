@extends('layouts.base')
@section('content')

    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-form layui-card-header layuiadmin-card-header-auto" lay-filter="aa">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" name="key" placeholder="关键字" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <select name="status">
                                <option value="">全部状态</option>
                                <option value="0">禁用</option>
                                <option value="1">正常</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <button class="layui-btn " id="Search" lay-submit lay-filter="search">
                            <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>搜索
                        </button>
                        <button lay-submit lay-filter="refresh" class="layui-btn"><i class="layui-icon layui-icon-return layuiadmin-button-btn"></i>重置</button>
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
                , url: '{{url('system/platform/keyword/lists')}}' //数据接口
                , method: 'POST'
                , toolbar: '#toolbar'
                , page: true //开启分页
                , title: '关键字管理'

                , cols: [[
                    {field: 'id', title: 'ID', align: 'center', width: 80},
                    {field: 'replace', title: '替换/禁用', align: 'center'}
                    , {field: 'toreplace', title: '被替换的关键字', align: 'center'}
                    , {
                        field: 'type', title: '类型', align: 'center',
                        templet: function (d) {
                            let _c = d.type === 0 ? "替换" : "禁用";
                            return _c;
                        }
                    }
                    , {
                        field: 'status', title: '当前状态', align: 'center',
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
                    , {field: 'created_at', title: '创建时间', sort: true, align: 'center'}
                    , {
                        title: '操作', width: 220, align: 'center', templet: function (d) {
                            let html = '';
                            html = '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">编辑</a>';
                            html += '<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>';
                            return html;
                        }
                    }
                ]]
            });

            // 日期选择
            var endDate = laydate.render({
                elem: '#end_time',//选择器结束时间
                min: "1970-1-1",//设置min默认最小值
                done: function (value, date) {
                    startDate.config.max = {
                        year: date.year,
                        month: date.month - 1,//关键
                        date: date.date
                    }
                }
            });
            //日期范围
            var startDate = laydate.render({
                elem: '#start_time',
                max: "2099-12-31",//设置一个默认最大值
                done: function (value, date) {
                    endDate.config.min = {
                        year: date.year,
                        month: date.month - 1, //关键
                        date: date.date
                    };
                }
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
                            , title: '添加关键字'
                            , content: '{{url('system/platform/keyword/create')}}'
                            , maxmin: true
                            , area: ['800px', '700px']
                            , end: function () {
                                tableIns.reload();
                            }
                        });
                        break;
                    case 'delete':

                        let checkData = checkStatus.data; //得到选中的数据

                        if (checkData.length === 0) {
                            return layer.msg('请选择数据');
                        }
                        let ids = [];
                        checkData.forEach(function (value, index, array) {
                            ids.push(value.id);
                        });
                        layer.confirm('确定删除吗？', function (index) {
                            axios.post("{{url('system/member/keyword/destroy')}}", {ids: ids})
                                .then(function (response) {
                                        if (response.data.status) {
                                            table.reload('lists');
                                            return layer.msg(response.data.msg);
                                        }
                                        return layer.alert(response.data.msg);
                                    }
                                );

                        });
                        break;
                    case 'update':
                        layer.msg('编辑');
                        break;
                }
            });

            //监听行工具条
            table.on('tool(lists)', function (obj) {
                let data = obj.data;
                let layEvent = obj.event;
                let tr = obj.tr;
                if (layEvent === 'status') { //改变状态
                    axios.post("{{url('system/platform/keyword/status')}}", {id: data.id})
                        .then(function (response) {
                                if (response.data.code === 0) {
                                    layer.msg(response.data.data);
                                    tableIns.reload();
                                    return
                                }
                                return layer.msg(response.data.data);
                            }
                        );
                }
                if (layEvent === 'edit') { //编辑
                    layer.open({
                        type: 2
                        , title: '编辑关键字'
                        , content: '{{url('system/platform/keyword/edit?id=')}}' + data.id
                        , maxmin: true
                        , area: ['800px', '700px']
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
                        axios.post("{{url('system/platform/keyword/destroy')}}", {ids: [data.id]})
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

            //监听单元格编辑
            table.on('edit(lists)', function (obj) {
                let value = obj.value //得到修改后的值
                    , data = obj.data //得到所在行所有键值
                    , field = obj.field; //得到字段
                update(data);
            });

            function update(data) {
                axios.post("{{url('system/member/keyword/update')}}", data)
                    .then(function (response) {
                            if (response.data.status) {
                                layer.msg(response.data.msg);
                                return
                            }
                            return layer.alert(response.data.msg);
                        }
                    );
            }
        });

    </script>
@endsection

