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
                            <input type="text" name="keys" placeholder="好友编号/昵称" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input" name="dateTime" id="test" placeholder="关注时间"
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
                , url: '{{url('system/member/friend/lists')}}' //数据接口
                , method: 'POST'
                , toolbar: '#toolbar'
                , page: true //开启分页
                , title: '会员好友'
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
                    , {
                        field: 'to_member_id', title: '好友',
                        templet: function (d) {
                            if (d.tomember) {
                                return d.tomember.nick_name+'<br>'+d.tomember.no;
                            } else {
                                return '未知';
                            }
                        }
                    }

                    , {field: 'created_at', title: '关注时间', align: 'center'}
                    , {
                        title: '操作', width: 120, align: 'center', templet: function (d) {
                            let html = '';
                            html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="del">取消好友</a>';
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
                            , title: '新增好友'
                            , content: '{{url('system/member/friend/create')}}'
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
                }
                if (layEvent === 'del') { //删除
                    layer.confirm('真的取消么', function (index) {
                        axios.post("{{url('system/member/friend/destroy')}}", {ids: [data.id]})
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

