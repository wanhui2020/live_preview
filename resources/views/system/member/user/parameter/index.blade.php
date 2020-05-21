@extends('layouts.base')
@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-form layui-card-header layuiadmin-card-header-auto">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" name="key" placeholder="编号/昵称" autocomplete="off"
                                   class="layui-input">
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
                , url: '{{url('system/member/user/parameter/lists')}}' //数据接口
                , method: 'POST'
                , toolbar: '#toolbar'
                , page: true //开启分页
                , title: '会员参数'
                , cols: [[
                    {field: 'id', title: 'ID', align: 'center', width: 80}

                    , {
                        field: 'member_id', title: '所属会员',sort:true,
                        templet: function (d) {
                            if (d.member) {
                                return d.member.nick_name + '<br>' + d.member.no;
                            } else {
                                return '未知';
                            }
                        }
                    }

                    , {
                        field: 'is_disturb', title: '勿扰', align: 'center', sort: true,
                        templet: function (d) {
                            if (d.is_disturb === 0) {
                                return '开启';
                            }
                            return '<span style="color: red; ">关闭</span>';

                        }
                    }
                    , {
                        field: 'is_location', title: '定位', align: 'center', sort: true,
                        templet: function (d) {
                            if (d.is_location === 1) {
                                return '开启';
                            }
                            return '<span style="color: red; ">关闭</span>';
                        }
                    }
                    , {
                        field: 'is_stranger', title: '陌生人信息', align: 'center', sort: true,
                        templet: function (d) {
                            if (d.is_stranger === 1) {
                                return '<span style="color: red; ">不接收</span>';
                            }
                            return '接收';
                        }
                    }
                    , {
                        field: 'is_text', title: '文本信息', align: 'center', sort: true,
                        templet: function (d) {
                            if (d.is_text === 1) {
                                return '<span style="color: red; ">不接收</span>';
                            }
                            return '接收';
                        }
                    }

                    , {
                        field: 'is_voice', title: '语音信息', align: 'center', sort: true,
                        templet: function (d) {
                            if (d.is_voice === 1) {
                                return '<span style="color: red; ">不接收</span>';
                            }
                            return '接收';
                        }
                    }
                    , {
                        field: 'is_video', title: '视频信息', align: 'center', sort: true,
                        templet: function (d) {
                            if (d.is_video === 1) {
                                return '<span style="color: red; ">不接收</span>';
                            }
                            return '接收';
                        }
                    }
                    , {
                        field: 'greeting', title: '问候语',sort:true,
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
                            , title: '新增会员参数'
                            , content: '{{url('system/member/user/parameter/create')}}'
                            , maxmin: true
                            , area: ['90%', '800px']
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
                    layer.confirm('真的删除用户-' + data.email + '么', function (index) {
                        axios.post("{{url('system/member/user/destroy')}}", {ids: [data.id]})
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
                } else if (layEvent === 'edit') { //编辑
                    layer.open({
                        type: 2
                        , title: '编辑会员参数'
                        , content: '{{url('system/member/user/parameter/edit?id=')}}' + data.id
                        , maxmin: true
                        , area: ['90%', '800px']
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

