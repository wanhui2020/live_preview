@extends('layouts.base')
@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-form layui-card-header layuiadmin-card-header-auto">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" name="key" placeholder="编号/昵称/手机号" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    {{--<div class="layui-inline">--}}
                        {{--<div class="layui-input-inline">--}}
                            {{--<select name="sex">--}}
                                {{--<option value="">性别</option>--}}
                                {{--<option value="0">男</option>--}}
                                {{--<option value="1">女</option>--}}
                                {{--<option value="9">未知</option>--}}
                            {{--</select>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <select name="status">
                                <option value="">全部状态</option>
                                <option value="1">正常</option>
                                <option value="0">禁用</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <button class="layui-btn " id="Search" lay-submit lay-filter="search">
                            <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>搜索
                        </button>
                        <button class="layui-btn " lay-submit lay-filter="refresh">
                            <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>重置
                        </button>
                    </div>
                </div>
            </div>
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
                , url: '{{url('system/agent/user/lists')}}' //数据接口
                , method: 'POST'
                , toolbar: '#toolbar'
                , page: true //开启分页
                , title: '渠道管理'
                , cols: [[
                    {field: 'id', title: 'ID', align: 'center', width: 80}
                    , {field: 'no', title: '编号', align: 'center'}
                    , {field: 'name', title: '名称', align: 'center'}
                    , {
                        field: 'member_id', title: '所属会员', minWidth: 140
                        ,
                        templet: function (d) {

                            let html = '';
                            if (d.member) {
                                html += d.member.nick_name + '<br>' + d.member.no;
                            }
                            return html;
                        }
                    }

                    , {field: 'mobile', title: '手机号', align: 'center'}
                    , {field: 'email', title: '邮箱', align: 'center'}
                    , {field: 'members_count', title: '会员数', align: 'center', width: 120}

                    , {
                        field: 'gold', title: '金币', width: 100, align: 'center',
                        templet: function (d) {
                            if (d.gold) {
                                return d.gold.balance;
                            }
                            return 0;
                        }
                    }
                    , {
                        field: 'cash', title: '现金', width: 100, align: 'center',
                        templet: function (d) {
                            if (d.cash) {
                                return d.cash.balance;
                            }
                            return 0;
                        }
                    }

                    , {
                        field: 'status', title: '状态', width: 80, align: 'center', sort: true,
                        templet: function (d) {
                            let html = '';
                            if (d.status === 0) {
                                html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="status">启用</a>';
                            }
                            if (d.status === 1) {
                                html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="status">禁用</a>';
                            }

                            return html;

                        }
                    }
                    , {
                        title: '操作', width: 160, align: 'center',
                        templet: function (d) {
                            let html = '';
                            html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="login" >登入</a>';
                            html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="rate">费率</a>';
                            html += '<br><a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">编辑</a>';
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
                //执行重载
                table.reload('lists', {
                    where: field
                    , page: {
                        curr: 1 //重新从第 1 页开始
                    }
                });
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
            $(document).keydown(function (e) {
                if (e.keyCode === 13) {
                    $("#Search").trigger("click");
                }
            });
            //监听工具栏事件
            table.on('toolbar(lists)', function (obj) {
                let checkStatus = table.checkStatus(obj.config.id);
                switch (obj.event) {
                    case 'add':
                        layer.open({
                            type: 2
                            , title: '新增渠道'
                            , content: '{{url('system/agent/user/create')}}'
                            , maxmin: true
                            , area: ['800px', '400px']
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
                    let _c = data.status === 1 ? '禁用' : '启用';
                    layer.confirm('确定要【' + _c + '】么?', {icon: 3, btn: ['确定', '取消'], title: "信息提示"}, function (index) {
                        axios.post("{{url('system/agent/user/status')}}", {id: data.id})
                            .then(function (response) {
                                console.log(response)
                                    if (response.data.status) {
                                        layer.msg(response.data.msg);
                                        return tableIns.reload();
                                    }
                                    return layer.alert(response.data.msg);
                                }
                            );
                    });
                }

                if (layEvent === 'del') { //删除
                    layer.confirm('真的删除用户-' + data.email + '么', function (index) {
                        axios.post("{{url('system/agent/user/destroy')}}", {ids: [data.id]})
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
                if (layEvent === 'edit') { //编辑
                    layer.open({
                        type: 2
                        , title: '编辑渠道'
                        , content: '{{url('system/agent/user/edit?id=')}}' + data.id
                        , maxmin: true
                        , area: ['800px', '400px']
                        , end: function () {
                            tableIns.reload();
                        }
                    });
                }
                if (layEvent === 'rate') { //费率
                    layer.open({
                        type: 2
                        , title: '费率设置'
                        , content: '{{url('system/agent/user/rate/edit?id=')}}' + data.id
                        , maxmin: true
                        , area: ['800px', '600px']
                        , end: function () {
                            tableIns.reload();
                        }
                    });
                }
                if (layEvent === 'parameter') {
                    layer.open({
                        type: 2
                        , title: '渠道参数'
                        , content: '{{url('system/agent/user/parameter/edit?id=')}}' + data.id
                        , maxmin: true
                        , area: ['800px', '400px']
                        , end: function () {
                            tableIns.reload();
                        }
                    });
                }
            });
        });
    </script>
@endsection

