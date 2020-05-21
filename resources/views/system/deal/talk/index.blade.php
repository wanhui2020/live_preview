@extends('layouts.base')
@section('content')

    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-form layui-card-header layuiadmin-card-header-auto">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" name="no" placeholder="订单编号" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" name="room_id" placeholder="房间编号" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" name="money" placeholder="消费金额" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" name="dialing_id" placeholder="主叫编号/昵称" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" name="called_id" placeholder="被叫编号/昵称" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" name="duration" placeholder="通话时间" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>

                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" name="begin_time" id="begin_time" placeholder="开始日期" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" name="end_time" id="end_time" placeholder="结束日期" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>

                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <select name="status">
                                <option value="">通话状态</option>
                                <option value="0">结束</option>
                                <option value="1">通话中</option>
                                <option value="8">呼叫中</option>
                                <option value="9">准备中</option>
                            </select>
                        </div>
                    </div>


                    <div class="layui-inline">
                        <button class="layui-btn " lay-submit lay-filter="search">
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
            let table = layui.table
                , form = layui.form,
                laydate = layui.laydate;
            laydate.render({
                elem: '#begin_time'
                , type: 'datetime'

            });
            laydate.render({
                elem: '#end_time'
                , type: 'datetime'

            });

            let tableIns = table.render({
                elem: '#lists'
                , url: '{{url('system/deal/talk/lists')}}' //数据接口
                , method: 'POST'
                , toolbar: '#toolbar'
                , page: true //开启分页
                , title: '语音视频管理'
                , totalRow: true
                , cols: [[
                    {field: 'id', title: 'ID', align: 'center', width: 80},
                    {
                        field: 'no', title: '订单编号', sort: true, width: 120
                        ,
                        templet: function (d) {
                            let html = '<a class=" " href="{{url('system/deal/talk/detail?id=')}}' + d.id + '">' + d.no + '</a>';

                            return html;
                        }
                    }, {field: 'room_id', title: '房间号', align: 'center', width: 140}, {
                        field: 'type', title: '类型', width: 90, align: 'center', sort: true,
                        templet: function (d) {
                            let html = '';

                            if (d.type === 0) {
                                html = '<span style="color: green">视频</span>';
                            }
                            if (d.type === 1) {
                                html = '<span style="color: darkorange;">语音</span>';
                            }

                            return html;

                        }
                    }

                    , {
                        field: 'dialing_id', title: '主叫', sort: true, minWidth: 100,
                        templet: function (d) {
                            let html = '';
                            if (d.dialing) {
                                html += d.dialing.nick_name + '<br>' + d.dialing.no;
                            }
                            return html;
                        }
                    }
                    , {
                        field: 'called_id', title: '被叫', sort: true, minWidth: 100,
                        templet: function (d) {
                            let html = '';
                            if (d.called) {
                                html += d.called.nick_name + '<br>' + d.called.no;
                            }
                            return html;
                        }
                    }

                    , {
                        field: 'price', title: '单价', width: 80, align: 'center', sort: true
                    }
                    , {
                        field: 'duration', title: '通话时长', width: 110, align: 'center', sort: true
                    }
                    , {
                        field: 'total', title: '消费金额', width: 110, align: 'center', sort: true
                    }
                    , {
                        field: 'begin_time', title: '开始/结束时间', width: 160, align: 'center', sort: true,
                        templet: function (d) {
                            let html = '';
                            if (d.begin_time) {
                                html += d.begin_time;
                            }
                            if (d.end_time) {
                                html += '<br>' + d.end_time;
                            }
                            return html;
                        }
                    }

                    , {
                        field: 'status', title: '通话状态', width: 120, align: 'center', sort: true,
                        templet: function (d) {
                            let html = '';
                            if (d.status === 1) {
                                html = '<span style="color: green;">通话中</span>';
                            }
                            if (d.status === 8) {
                                html = '<span style="color: green;">呼叫中</span>';
                            }
                            if (d.status === 9) {
                                html = '<span style="color: #FFB800;">准备中</span>';
                            }
                            if (d.status === 0) {
                                if (d.way === 0) {
                                    html += '<span style="color: #5FB878;">正常结束</span><br>';
                                }
                                if (d.way === 1) {
                                    html += '<span style="color: #FFB800;"  >无应答</span>';
                                }
                                if (d.way === 2) {
                                    html += '<span style="color: #FFB800;">拒绝接通</span>';
                                }
                                if (d.way === 3) {
                                    html += '<span style="color: #FF5722;">呼叫取消</span>';
                                }
                                if (d.way === 4) {
                                    html += '<span style="color: #FF5722;">后台挂断</span>';
                                }
                                if (d.way === 5) {
                                    html += '<span style="color: #FF5722;">异常挂断</span>';
                                }

                                if (d.way === 9) {
                                    html += '<span style="color: #FFB800;">未结束</span>';
                                }
                            }
                            return html;
                        }
                    }


                    , {field: 'created_at', title: '创建时间', align: 'center', width: 110, hide: true, sort: true}
                    , {
                        title: '操作', align: 'center', width: 110, templet: function (d) {
                            let html = '';
                            if (d.status === 8) {
                                // html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="answer">接听</a>';
                                html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="refuse">拒绝</a>';
                            }
                            if (d.status === 9) {
                                html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="finish">结束</a>';
                            }

                            if (d.status === 1) {
                                html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="finish">挂断</a>';
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
                        , talk: obj.type
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
                            , title: '创建通话'
                            , content: '{{url('system/deal/talk/create')}}'
                            , maxmin: true
                            , area: ['600px', '500px']
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

                if (layEvent === 'answer') {
                    layer.confirm('确定接听吗？', function (index) {
                        data['status'] = 2;
                        axios.post("{{url('system/deal/talk/answer')}}", data)
                            .then(function (response) {
                                    layer.closeAll();
                                    if (response.data.status) {
                                        table.reload('lists');
                                        return parent.layer.msg(response.data.msg);
                                    }
                                    return layer.alert(response.data.msg);
                                }
                            );

                    });
                }
                if (layEvent === 'hangup') {
                    layer.confirm('是否强制挂断？', function (index) {
                        axios.post("{{url('system/deal/talk/hangup')}}", data)
                            .then(function (response) {
                                    layer.closeAll();
                                    if (response.data.status) {
                                        table.reload('lists');
                                        return parent.layer.msg(response.data.msg);
                                    }
                                    return layer.alert(response.data.msg);
                                }
                            );

                    });
                }
                if (layEvent === 'refuse') {
                    layer.confirm('是否拒绝接听？', function (index) {
                        axios.post("{{url('system/deal/talk/refuse')}}", data)
                            .then(function (response) {
                                    layer.closeAll();
                                    if (response.data.status) {
                                        table.reload('lists');
                                        return parent.layer.msg(response.data.msg);
                                    }
                                    return layer.alert(response.data.msg);
                                }
                            );

                    });
                }
                if (layEvent === 'finish') {
                    layer.confirm('是否强制结束？', function (index) {
                        axios.post("{{url('system/deal/talk/finish')}}", data)
                            .then(function (response) {
                                    layer.closeAll();
                                    if (response.data.status) {
                                        table.reload('lists');
                                        return parent.layer.msg(response.data.msg);
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

