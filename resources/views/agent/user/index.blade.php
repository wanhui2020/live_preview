@extends('layouts.agent')
@section('content')
    <div class="row ">
        <div class="col-sm-2">
            @include('agent.common.side',['page'=>'代币账户','sub'=>'agent'])
        </div>
        <div class="col-sm-10">
            <div class="layui-card">
                <div class="layui-card-header">服务商管理</div>
                <div class="layui-card-body"><script type="text/html" id="toolbar">
                        <div>
                            <a class="layui-btn  layui-btn-sm" href="{{url('agent/base/user/create')}}" >添加</a>
                        </div>
                    </script>
                    <table id="lists" lay-filter="lists"></table></div>

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
                , url: '{{url('agent/base/user/lists')}}' //数据接口
                , method: 'POST'
                , toolbar: '#toolbar'
                , page: true //开启分页
                , title: '服务商管理'
                , cols: [[
                    {field: 'id', title: 'ID', align: 'center',width:80},
                    {field: 'no', title: '编号', width: 110, align: 'center'}

                    , {field: 'name', title: '服务商名称', minWidth:100,align: 'center'}

                    , {field: 'email', title: '登录邮箱', width: 200,   sort: true}
                    , {field: 'mobile', title: '手机',width: 120,  align: 'center'}

                    , {field: 'childrens_count', title: '下级服务商', width: 90, align: 'center'}
                    , {field: 'merchants_count', title: '商户数', width: 90, align: 'center'}
                    , {field: 'members_count', title: '承兑商数', width: 90, align: 'center'}
                    , {field: 'level', title: '层级', width: 90, align: 'center'}
                    , {field: 'invite_code', title: '邀请码', width: 90, align: 'center'}

                    , {field: 'created_at', title: '创建时间', sort: true, width: 120, align: 'center'}
                    , {
                        title: '操作', width: 125, align: 'center',
                        templet: function (d) {
                            let html = '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit"  >编辑</a>';
                            if (d.status === 9) {
                                html += '<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="delete"  >删除</a>';
                            }

                            html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="login"  >登入</a>';

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

            //监听工具栏事件
            table.on('toolbar(lists)', function (obj) {
                let checkStatus = table.checkStatus(obj.config.id);

                switch (obj.event) {
                    case 'add':
                        layer.open({
                            type: 2
                            , title: '商户充值转入'
                            , content: '{{url('agent/base/agent/create')}}'
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
                if (layEvent === 'login') {
                    layer.confirm('确定登陆吗？', function (index) {
                        layer.load();
                        axios.post("{{url('agent/base/user/login')}}", {id: data.id})
                            .then(function (response) {
                                    layer.closeAll();
                                    if (response.data.status) {
                                        layer.msg(response.data.msg);
                                        return window.open('{{url('agent')}}');

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

