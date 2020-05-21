@extends('layouts.base')
@section('content')

    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-form layui-card-header layuiadmin-card-header-auto">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" name="key" placeholder="赠送人编号/昵称" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" name="keys" placeholder="被赠送人编号/昵称" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <select name="relevance_type">
                                <option value="">赠送场景</option>
                                <option value="MemberUser">会员</option>
                                <option value="DealTalk">通话</option>
                                <option value="DealSocial">评论</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" name="received" placeholder="能量" autocomplete="off"
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
                , url: '{{url('system/deal/gift/lists')}}' //数据接口
                , method: 'POST'
                , toolbar: '#toolbar'
                , page: true //开启分页
                , title: '礼物赠送'
                , cols: [[
                    {field: 'id', title: 'ID', align: 'center', width: 80},
                    {
                        field: 'member_id', title: '赠送人',
                        templet: function (d) {
                            if (d.member) {
                                return d.member.nick_name + '<br>' + d.member.no;
                            } else {
                                return '未知';
                            }
                        }
                    }
                    , {
                        field: 'to_member_id', title: '被赠送人', width: 160,
                        templet: function (d) {
                            if (d.tomember) {
                                return d.tomember.nick_name + '<br>' + d.tomember.no;
                            }
                            return '';
                        }
                    }
                    , {
                        field: 'relevance_type', title: '赠送场景', width: 160,
                        templet: function (d) {
                            if (d.relevance_type === 'MemberUser') {
                                return '会员';
                            }
                            if (d.relevance_type === 'DealTalk') {
                                return '通话';
                            }
                            if (d.relevance_type === 'DealSocial') {
                                return '评论';
                            }
                            return '';
                        }
                    }
                    , {
                        field: 'ico', title: '礼物图标', align: 'center',
                        templet: function (d) {
                            if (d.gift) {
                                if (d.gift.ico) {
                                    return '<img src="' + d.gift.ico + '" style="width: 60px;">';
                                }
                            }
                            return '未配置';

                        }
                    }

                    , {
                        field: 'gift_id', title: '礼物名称', align: 'center',
                        templet: function (d) {
                            if (d.gift) {
                                return d.gift.name;
                            } else {
                                return d.name;
                            }
                        }
                    }

                    , {field: 'price', title: '单价', align: 'center'}
                    , {field: 'quantity', title: '数量', align: 'center'}
                    , {field: 'total', title: '能量价值', align: 'center'}
                    , {field: 'received', title: '实到', align: 'center'}
                    , {field: 'created_at', title: '赠送时间', align: 'center'}
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
                            , title: '礼物赠送'
                            , content: '{{url('system/deal/gift/create')}}'
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

