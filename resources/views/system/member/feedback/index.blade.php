@extends('layouts.base')
@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-form layui-card-header layuiadmin-card-header-auto">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" name="key" placeholder="会员编号/昵称" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" name="keys" placeholder="回复人编号/昵称" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <select name="replay_status">
                                <option value="">回复状态</option>
                                <option value="0">未回复</option>
                                <option value="1">已回复</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input" name="dateTime" id="test" placeholder="回复时间"
                                   lay-key="17">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <button class="layui-btn " id="Search" lay-submit lay-filter="search">
                            <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>搜索
                        </button>
                        <button class="layui-btn " lay-submit lay-filter="refresh">
                            <i class="layui-icon layui-icon-return layuiadmin-button-btn"></i>重置
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
        //时间筛选
        layui.use('laydate', function () {
            var laydate = layui.laydate;

            //执行一个laydate实例
            laydate.render({
                elem: '#test'
                , type: 'datetime'
                , range: true //或 range: '~' 来自定义分割字符
            });
        });

        layui.use(['table'], function () {
            let table = layui.table, form = layui.form;
            let tableIns = table.render({
                elem: '#lists'
                , url: '{{url('system/member/feedback/lists')}}' //数据接口
                , method: 'POST'
                , toolbar: '#toolbar'
                , page: true //开启分页
                , title: '意见反馈'
                , cols: [[
                    {field: 'id', title: 'ID', align: 'center',width:80}
                    , {field: 'member_id', title: '所属会员',
                        templet: function (d) {
                            if (d.member) {
                                return d.member.nick_name+'<br>'+d.member.no;
                            } else{
                                return '未知';
                            }
                        }
                    }
                    , {field: 'content', title: '意见内容' }
                    , {field: 'replay', title: '回复内容' }
                    , {field: 'audit', title: '回复人', align: 'center',
                        templet: function (d) {
                            if (d.audit) {
                                return d.audit.name;
                            } else{
                                return '无';
                            }
                        }
                    }
                    , {
                        field: 'status', title: '回复状态', width: 120, align: 'center',
                        templet: function (d) {
                            if (d.replay_status === 0) {
                                return '未回复';
                            }else if(d.replay_status === 1){
                                return '已回复';
                            }else{
                                return '无';
                            }

                        }
                    }
                    , {field: 'replay_time', title: '回复时间', align: 'center',
                        templet: function (d) {
                            if (d.replay_time) {
                                return d.replay_time;
                            } else{
                                return '无';
                            }
                        }
                    }
                    , {
                        title: '操作', width: 120, align: 'center', templet: function (d) {
                            let html = '';
                            html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">回复</a>';
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
                            , title: '新增反馈'
                            , content: '{{url('system/member/feedback/create')}}'
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

                if (layEvent === 'detail') { //查看
                    //do somehing
                } else if (layEvent === 'del') { //删除
                    layer.confirm('真的删除么', function (index) {
                        axios.post("{{url('system/member/feedback/destroy')}}", {ids: [data.id]})
                            .then(function (response) {
                                    if (response.data.status) {
                                        layer.msg(response.data.msg);
                                        return tableIns.reload();
                                    }
                                    return layer.alert(response.data.msg);
                                }
                            );
                    });
                } else if (layEvent === 'login') {//登录
                    axios.post("{{url('')}}", {id: data.id})
                        .then(function (response) {
                                if (response.data.code === 0) {
                                    layer.msg(response.data.data);
                                    tableIns.reload();
                                    return
                                }
                                return layer.msg(response.data.data);
                            }
                        );
                    window.open('/system/member/user/login?id=' + data.id);
                } else if (layEvent === 'edit') { //回复
                    layer.open({
                        type: 2
                        , title: '回复'
                        , content: '{{url('system/member/feedback/edit?id=')}}' + data.id
                        , maxmin: true
                        , area: ['800px', '400px']
                        , end: function () {
                            tableIns.reload();
                        }
                    });
                } else if (layEvent === 'status') { //禁用启用
                    let str = data.status == 1 ? '启用' : '禁用';
                    layer.confirm('确定' + str + '用户-' + data.email + '么？', function (index) {
                        axios.post("{{url('system/member/user/status')}}", {id: data.id})
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
            });
        });

    </script>
@endsection

