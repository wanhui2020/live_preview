@extends('layouts.base')
@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-card-body">
                <script type="text/html" id="toolbar">
                    <div>
                        <button class="layui-btn  layui-btn-sm" lay-event="add">新增</button>
                    </div>
                </script>
                <table id="lists" lay-filter="lists"></table>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="application/javascript">
        layui.use(['table'], function () {
            let table = layui.table, form = layui.form;
            let tableIns = table.render({
                elem: '#lists'
                , url: '{{url('system/base/permission/lists')}}' //数据接口
                , method: 'get'
                , toolbar: '#toolbar'
                , page: true //开启分页
                , title: '权限列表'
                , cols: [[
                    {field: 'id', title: 'ID', align: 'center', width: 80}
                    , {field: 'name', title: '权限名称', align: 'center'}
                    , {
                        title: '操作', width: 150, align: 'center', field: 'type',
                        templet: function (d) {
                            let html = '';
                            html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">编辑</a>';
                            html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="delete">删除</a>';
                            return html;
                        }
                    }
                ]]
            });

            //监听工具栏事件
            table.on('toolbar(lists)', function (obj) {
                switch (obj.event) {
                    case 'add':
                        layer.open({
                            type: 2
                            , title: '新增权限'
                            , content: '{{url('system/base/permission/create')}}'
                            , maxmin: true
                            , area: ['800px', '400px']
                            , end: function () {
                                tableIns.reload();
                            }
                        });
                        break;
                }
            });

            //监听单元格编辑
            table.on('tool(lists)', function (obj) {
                let value = obj.value //得到修改后的值
                    , data = obj.data //得到所在行所有键值
                    , field = obj.field; //得到字段
                let layEvent = obj.event;
                console.log(layEvent)

                switch (layEvent) {
                    case 'edit':
                        layer.open({
                            type: 2
                            , title: '编辑权限'
                            , content: '{{url('system/base/permission')}}' + '/' + data.id + '/edit'
                            , maxmin: true
                            , area: ['800px', '400px']
                            , end: function () {
                                tableIns.reload();
                            }
                        });
                        break;
                    case 'delete':
                        axios.delete('{{ url('system/base/permission') }}' + '/' + data.id)
                            .then(function () {
                                tableIns.reload();
                            });
                        break;
                }
            });

        });

    </script>
@endsection

