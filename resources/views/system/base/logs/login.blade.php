@extends('layouts.base')
@section('content')

    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-form layui-card-header layuiadmin-card-header-auto" lay-filter="aa">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input" name="login_time" id="end_time" placeholder="登入时间">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input" name="logout_time" id="end_time" placeholder="登出时间">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <select name="relevance_type">
                                <option value="">用户类型</option>
                                <option value="SystemUser">系统用户</option>
                                <option value="MemberUser">承兑商</option>
                                <option value="MerchantUser">商户</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <select name="status">
                                <option value="">全部状态</option>
                                <option value="0">正常</option>
                                <option value="1">禁用</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <button class="layui-btn " id="Search" lay-submit lay-filter="search">
                            <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>搜索
                        </button>
                        <button lay-submit lay-filter="refresh" class="layui-btn">
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
        layui.use(['table', 'laydate'], function () {
            let table = layui.table, form = layui.form;
            let laydate = layui.laydate;

            let tableIns = table.render({
                elem: '#lists'
                , url: '{{url('system/base/logs/logins')}}' //数据接口
                , method: 'POST'
                , toolbar: '#toolbar'
                , page: true //开启分页
                , title: '业务日志'

                , cols: [[
                    {field: 'id', title: 'ID', align: 'center', width: 80},
                    {
                        field: 'relevance_type', title: '用户类型', align: 'center', width: 120,
                        templet: function (d) {
                            if (d.relevance_type === 'SystemUser') {
                                return '系统用户';
                            }
                            if (d.relevance_type === 'MerchantUser') {
                                return '商户';
                            }
                            if (d.relevance_type === 'MemberUser') {
                                return '承兑商';
                            }
                            return '未知';
                        }
                    }
                    ,
                    {
                        field: 'relevance', title: '系统用户', align: 'center', width: 120,
                        templet: function (d) {
                            if (d.relevance) {
                                return d.relevance.name;
                            }
                            return '未知';
                        }
                    }
                    , {
                        field: 'address', title: 'IP', align: 'center', width: 140,
                    }
                    , {
                        field: 'browser', title: '浏览器', maxWidth: 200,
                    }
                    , {
                        field: 'referer', title: '来源', maxWidth: 200,
                    }
                    , {
                        field: 'login_time', title: '登入时间', align: 'center', width: 120,
                    }
                    , {
                        field: 'logout_time', title: '登出时间', align: 'center', width: 120,
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

            //监听行工具条
            table.on('tool(lists)', function (obj) {
                let data = obj.data;
                let layEvent = obj.event;
                let tr = obj.tr;


            });

        });
    </script>
@endsection

