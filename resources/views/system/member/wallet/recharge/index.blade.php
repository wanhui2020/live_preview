@extends('layouts.base')
@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-form layui-card-header layuiadmin-card-header-auto">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" name="no" placeholder="充值编号" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" name="key" placeholder="编号/昵称" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <select name="status">
                                <option value="">状态</option>
                                <option value="0">支付成功</option>
                                <option value="1">支付失败</option>
                                <option value="2">取消</option>
                                <option value="3">系统取消</option>
                                <option value="8">支付中</option>
                                <option value="9">待支付</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input" name="dateTime" id="test" placeholder="创建时间"
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
                        {{--                        <button class="layui-btn  layui-btn-sm" lay-event="add">新增</button>--}}
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
                , url: '{{url('system/member/wallet/recharge/lists')}}' //数据接口
                , method: 'POST'
                , toolbar: '#toolbar'
                , page: true //开启分页
                , title: '会员管理'
                , totalRow: true
                , cols: [[
                    {field: 'id', title: 'ID', align: 'center', width: 80}, {
                        field: 'no',
                        title: '充值编号',
                        width: 120,
                        align: 'center'
                    }
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
                        field: 'payment_id', title: '支付通道',
                        templet: function (d) {
                            if (d.payment) {
                                return d.payment.name + '<br>' + d.payment.account;
                            } else {
                                return '未知';
                            }
                        }
                    }
                    , {
                        field: 'relevance_type', title: '业务来源',
                        templet: function (d) {
                            if (d.relevance_type === 'DealVip') {
                                return '会员购买';
                            }
                            if (d.relevance_type === 'DealCash') {
                                return '余额充值';
                            }
                            if (d.relevance_type === 'DealGold') {
                                return '能量充值';
                            }
                            if (d.relevance_type === 'DealGive') {
                                return '会员打赏';
                            }
                        }
                    }
                    , {field: 'money', title: '充值金额', align: 'center', totalRow: true}
                    , {field: 'cost_commission', title: '成本佣金', align: 'center', totalRow: true}
                    , {
                        field: 'pay_time', title: '支付时间', width: 120, align: 'center',
                        templet: function (d) {
                            if (d.pay_time) {
                                return d.pay_time;
                            }
                            return d.created_at;
                        }
                    }
                    , {
                        field: 'pay_status', title: '支付状态', width: 100, align: 'center',
                        templet: function (d) {
                            let html = '';
                            if (d.pay_status === 0) {
                                html = '<span style="color: green">支付成功</span>';
                            }
                            if (d.pay_status === 1) {
                                html = '<span style="color: firebrick">支付失败</span>';
                            }
                            if (d.pay_status === 2) {
                                html = '<span style="color: red">取消</span>';
                            }
                            if (d.status === 3) {
                                html = '<span style="color: red">系统取消</span>';
                            }
                            if (d.pay_status === 8) {
                                html = '<span style="color: blue">支付中</span>';
                            }
                            if (d.pay_status === 9) {
                                html = '<span style="color: yellowgreen">待支付</span>';
                            }

                            return html;
                        }
                    }
                    , {
                        field: 'audit_uid', title: '平台审核', width: 100, align: 'center',
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
                            if (d.pay_status === 0) {
                            }
                            if (d.pay_status === 1) {
                            }
                            if (d.pay_status === 9) {
                                html += '<a class="layui-btn  layui-btn-danger  layui-btn-xs" lay-event="audit">审核</a>';
                                html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="pay">支付</a>';
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
            //监听工具栏事件
            table.on('toolbar(lists)', function (obj) {
                let checkStatus = table.checkStatus(obj.config.id);
                switch (obj.event) {
                    case 'add':
                        layer.open({
                            type: 2
                            , title: '新增充值'
                            , content: '{{url('system/member/wallet/recharge/create')}}'
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
                if (layEvent === 'audit') { //审核
                    layer.confirm('确认支付成功', function (index) {
                        axios.post("{{url('system/member/wallet/recharge/audit')}}", {id: data.id})
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

                if (layEvent === 'pay') { //确认支付
                    layer.confirm('确认支付成功', function (index) {
                        axios.post("{{url('system/member/wallet/recharge/pay')}}", {id: data.id})
                            .then(function (response) {
                                    if (response.data.status) {
                                        return window.open(response.data.data);
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

