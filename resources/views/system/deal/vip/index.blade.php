@extends('layouts.base')
@section('content')

    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-form layui-card-header layuiadmin-card-header-auto">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <div class="layui-inline">
                            <div class="layui-input-inline">
                                <input type="text" name="key" placeholder="姓名/邮箱/手机号" autocomplete="off"
                                       class="layui-input">
                            </div>
                        </div>
                        <button class="layui-btn " lay-submit lay-filter="search">
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
        layui.use(['table'], function () {
            let table = layui.table, form = layui.form;
            let tableIns = table.render({
                elem: '#lists'
                , url: '{{url('system/deal/vip/lists')}}' //数据接口
                , method: 'POST'
                , toolbar: '#toolbar'
                , page: true //开启分页
                , title: 'vip购买记录'
                , cols: [[
                    {field: 'id', title: 'ID', align: 'center', width: 80}
                    , {
                        field: 'member_id', title: '所属会员',
                        templet: function (d) {
                            if (d.member) {
                                return d.member.nick_name + '<br>' + d.member.no;
                            } else {
                                return '未知';
                            }
                        }
                    }
                    , {
                        field: 'vip_id', title: 'vip等级', align: 'center', width: 160,
                        templet: function (d) {
                            if (d.vip) {
                                return d.vip.name;
                            }
                            return '';
                        }
                    }
                    , {
                        field: 'money', title: '价格', align: 'center',
                    }

                    , {field: 'days', title: '天数', align: 'center'}
                    , {
                        field: 'status', title: '状态', width: 120, align: 'center',
                        templet: function (d) {
                            let html = '';
                            if (d.status === 0) {
                                html = '<span style="color: green">支付成功</span>';
                            }
                            if (d.status === 1) {
                                html = '<span style="color: firebrick">支付失败</span>';
                            }
                            if (d.status === 2) {
                                html = '<span style="color: red">取消</span>';
                            }
                            if (d.status === 3) {
                                html = '<span style="color: red">系统取消</span>';
                            }
                            if (d.status === 8) {
                                html = '<span style="color: blue">支付中</span>';
                            }
                            if (d.status === 9) {
                                html = '<span style="color: yellowgreen">待支付</span>';
                            }

                            return html;
                        }
                    }
                    , {
                        field: 'audit_uid', title: '平台审核', width: 120, align: 'center',
                        templet: function (d) {
                            let html = '';
                            if (d.audit) {
                                html = d.audit.name;
                            }

                            return html;
                        }
                    }
                    , {
                        title: '操作', width: 120, align: 'center',
                        templet: function (d) {
                            let html = '';
                            if (d.status === 0) {
                            }
                            if (d.status === 1) {
                            }
                            if (d.status === 8) {
                            }
                            if (d.status === 9) {
                                html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="pay">支付</a>';
                                html += '<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="cancel">取消</a>';
                            }
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
            //监听工具栏事件
            table.on('toolbar(lists)', function (obj) {
                let checkStatus = table.checkStatus(obj.config.id);
                switch (obj.event) {
                    case 'add':
                        layer.open({
                            type: 2
                            , title: '购买VIP'
                            , content: '{{url('system/deal/vip/create')}}'
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
                if (layEvent === 'cancel') { //取消
                    layer.confirm('确认取消订单', function (index) {
                        axios.post("{{url('system/deal/vip/cancel')}}", {id: data.id })
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
                if (layEvent === 'pay') { //生成支付
                    layer.confirm('确认支付', function (index) {
                        axios.post("{{url('system/deal/vip/pay')}}", {id: data.id})
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
                if (layEvent === 'del') { //删除
                    layer.confirm('真的删除么', function (index) {
                        axios.post("{{url('system/deal/vip/destroy')}}", {ids: [data.id]})
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

