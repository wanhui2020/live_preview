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
                            <input type="text" name="customer" placeholder="请输入客户名称、电话、编号" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" name="start_time" class="layui-input" id="start_time"
                                   placeholder="创建开始时间">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input" name="end_time" id="end_time" placeholder="创建结束时间">
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
                        <button lay-submit lay-filter="refresh" class="layui-btn">重置</button>
                    </div>

                </div>
            </div>
            <div class="layui-card-body">
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
                , url: '{{url('system/base/logs/business')}}' //数据接口
                , method: 'POST'
                , toolbar: '#toolbar'
                , page: true //开启分页
                , title: '业务日志'

                , cols: [[
                    {field: 'id', title: 'ID', align: 'center',width:80},
                    {
                        field: 'system_id', title: '系统用户', align: 'center', width: 100,
                        templet: function (d) {
                            if (d.system) {
                                return d.system.name;
                            }
                            return '未知';
                        }
                    }
                    , {
                        field: 'agent_id', title: '服务商', align: 'center', width: 100,
                        templet: function (d) {
                            if (d.agent) {
                                return d.agent.name;
                            }
                            return '未知';
                        }
                    }
                    , {
                        field: 'customer_id', title: '客户', align: 'center', width: 100,
                        templet: function (d) {
                            if (d.customer) {
                                return d.customer.realname;
                            }
                            return '未知';
                        }
                    }
                    , {field: 'type', title: '类型', align: 'center', width: 120}
                    , {
                        field: 'content', title: '内容',
                        templet: function (d) {
                            let html = d.name;
                            html += '<br>' + d.content;
                            return html;
                        }
                    }
                    , {field: 'created_at', title: '日期', align: 'center', width: 160}
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
                            , title: '添加公告'
                            , content: '{{url('system/base/logs/create')}}'
                            , maxmin: true
                            , area: ['100%', '100%']
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
                        axios.post("{{url('system/base/logs/status')}}", {id: data.id})
                            .then(function (response) {
                                    if (response.data.code === 0) {
                                        layer.msg(response.data.data);
                                        tableIns.reload();
                                        return
                                    }
                                    return layer.msg(response.data.data);
                                }
                            );
                    });
                } else if (layEvent === 'edit') { //编辑
                    layer.open({
                        type: 2
                        , title: '编辑公告'
                        , content: '{{url('system/base/logs/edit?id=')}}' + data.id
                        , maxmin: true
                        , area: ['100%', '100%']
                        , end: function () {
                            tableIns.reload();
                        }
                    });
                } else if (layEvent === 'del') { //删除
                    layer.confirm('确定要删除【' + data.name + '】么？', {
                        icon: 3,
                        btn: ['确定', '取消'],
                        title: "删除提示"
                    }, function (index) {
                        axios.post("{{url('system/base/logs/destroy')}}", {ids: [data.id]})
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
    </script>
@endsection

