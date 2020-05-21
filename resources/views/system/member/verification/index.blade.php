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
                        <div class="layui-input-inline">
                            <select name="status">
                                <option value="">审核状态</option>
                                <option value="9">待审</option>
                                <option value="0">通过</option>
                                <option value="1">拒绝</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <select name="info_type">
                                <option value="">资料类型</option>
                                <option value="0">昵称</option>
                                <option value="1">个性签名</option>
                                <option value="2">格言</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input" name="dateTime" id="test" placeholder="审核时间"
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
                , url: '{{url('system/member/verification/lists')}}' //数据接口
                , method: 'POST'
                , toolbar: '#toolbar'
                , page: true //开启分页
                , title: '资料审核'
                , cols: [[
                    {field: 'id', title: 'ID', align: 'center', width: 80}
                    , {
                        field: 'member_id', title: '所属会员',
                        templet: function (d) {
                            if (d.member) {
                                return d.member.nick_name + '<br>' + d.member.no;
                            }
                            return ' ';

                        }
                    }
                    , {
                        field: 'info_type', title: '资料类型', width: 120, align: 'center',
                        templet: function (d) {
                            if (d.info_type === 0) {
                                return '昵称';
                            }
                            if (d.info_type === 1) {
                                return '个性签名';
                            }
                            if (d.info_type === 2) {
                                return '格言';
                            }
                            return '未知';

                        }
                    }
                    , {field: 'old_data', title: '原始数据', align: 'center'}
                    , {field: 'new_data', title: '新数据', align: 'center'}

                    , {
                        field: 'audit', title: '审核人', align: 'center',
                        templet: function (d) {
                            if (d.audit) {
                                return d.audit.name;
                            }
                            if (d.status !== 9) {
                                return '系统审核';
                            }
                            return '';
                        }
                    }
                    , {field: 'audit_time', title: '审核时间', align: 'center'}
                    , {
                        field: 'status', title: '审核状态', width: 120, align: 'center',
                        templet: function (d) {
                            if (d.status === 9) {
                                return '待审核';
                            }
                            if (d.status === 0) {
                                return '<span style="color: green">审核通过</span>';
                            }
                            if (d.status === 1) {
                                return '<span style="color: red">审核拒绝</span><br>' + d.audit_reason;
                            }
                            return '未知状态';
                        }
                    }
                    , {
                        title: '操作', width: 150, align: 'center', field: 'type',
                        templet: function (d) {
                            let html = '';
                            if (d.status === 9) {
                                html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="audit" >审核</a>';
                            }
                            html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">编辑</a>';
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
                            , title: '新增资料审核'
                            , content: '{{url('system/member/verification/create')}}'
                            , maxmin: true
                            , area: ['800px', '800px']
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
                if (layEvent === 'audit') { //审核
                    layer.confirm('确认审核？', {
                        btn: ['通过', '不通过']
                    }, function () {
                        axios.post("{{url('system/member/verification/audit')}}", {
                            id: data.id,
                            status: 0
                        })
                            .then(function (response) {
                                    if (response.data.status) {
                                        layer.msg(response.data.msg);
                                        table.reload('lists');
                                        return
                                    }
                                    return layer.alert(response.data.msg);
                                }
                            );
                    }, function () {
                        axios.post("{{url('system/member/verification/audit')}}", {
                            id: data.id,
                            status: 1
                        })
                            .then(function (response) {
                                    if (response.data.status) {
                                        layer.msg(response.data.msg);
                                        table.reload('lists');
                                        return
                                    }
                                    return layer.alert(response.data.msg);
                                }
                            );
                    },);
                }
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
                        , title: '编辑资料审核'
                        , content: '{{url('system/member/verification/edit?id=')}}' + data.id
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

