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
                            <input type="text" name="rate" placeholder="接通率" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" name="talk" placeholder="通话时长" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>

                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input" name="on_time" id="test" placeholder="在线时间周期"
                                   lay-key="17">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input" name="on_line" id="test-laydate-range-time" placeholder="在线时间" lay-key="11">
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
                        {{--<button class="layui-btn  layui-btn-sm" lay-event="add">新增</button>--}}
                        {{--<button class="layui-btn  layui-btn-sm" lay-event="addRobot">陪聊</button>--}}
                        {{--<button class="layui-btn  layui-btn-sm" lay-event="multiCheck">IM同步</button>--}}
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
            laydate.render({
                elem: '#test-laydate-range-time'
                ,type: 'time'
                ,range: true
            });
        });


        layui.use(['table'], function () {
            let table = layui.table, form = layui.form;
            let tableIns = table.render({
                elem: '#lists'
                , url: '{{url('system/member/user/report_form_lists')}}' //数据接口
                , method: 'POST'
                , toolbar: '#toolbar'
                , page: true //开启分页
                , title: '主播统计'
                , cols: [[
                    {field: 'id', title: 'ID', align: 'center', width: 60}
                    , {
                        field: 'head_pic',
                        title: '头像',
                        align: 'center',
                        width: 80,
                        templet: function (d) {
                            if (d.fill_head_pic == null) {
                                return '';
                            }
                            return '<img style="width:40px;height: 40px;" src="' + d.fill_head_pic + '"></img>';
                        }
                    }, {
                        field: 'nick_name', title: '会员信息', minWidth: 110,
                        templet: function (d) {
                            let html = '';
                            html +=  d.nick_name ;
                            html += '<br>' + '<a lay-event="detail">' + d.no + '</a>';

                            return html;
                        }
                    }
                    , {
                        field: 'gold', title: '在线时长', minWidth: 100, align: 'center',
                        templet: function (d) {
                            if (d.duration){
                                return d.duration;
                            } else{
                                return '';
                            }
                        }
                    }
                    , {
                        field: 'gold', title: '接通率', minWidth: 100, align: 'center',
                        templet: function (d) {
                            if (d.talk_rate){
                                return d.talk_rate;
                            } else{
                                return '';
                            }
                        }
                    }
                    , {
                        field: 'gold', title: '成功', minWidth: 100, align: 'center',
                        templet: function (d) {
                            if (d.talk_success){
                                return d.talk_success;
                            } else{
                                return '';
                            }

                        }
                    }
                    , {
                        field: 'gold', title: '失败', minWidth: 100, align: 'center',
                        templet: function (d) {
                            if (d.talk_fail){
                                return d.talk_fail;
                            } else{
                                return '';
                            }
                        }
                    }
                    , {
                        field: 'gold', title: '通话时长', minWidth: 100, align: 'center',
                        templet: function (d) {
                            if (d.talk_duration){
                                return d.talk_duration;
                            } else{
                                return '';
                            }
                        }
                    }
                    , {
                        field: 'gold', title: '通话总收益', minWidth: 100, align: 'center',
                        templet: function (d) {
                            if (d.record_money){
                                return d.record_money;
                            } else{
                                return '';
                            }
                        }
                    }

                    , {
                        field: 'gold', title: '礼物', minWidth: 100, align: 'center',
                        templet: function (d) {
                            if (d.gift_money){
                                return d.gift_money;
                            } else{
                                return '';
                            }
                        }
                    }

                    , {
                        field: 'gold', title: '关注数', minWidth: 100, align: 'center',
                        templet: function (d) {
                            if (d.attention){
                                return d.attention;
                            } else{
                                return '';
                            }
                        }
                    }
                    , {
                        field: 'gold', title: '提现', minWidth: 100, align: 'center',
                        templet: function (d) {
                            if (d.withdraw){
                                return d.withdraw;
                            } else{
                                return '';
                            }
                        }
                    }
                     , {
                        field: 'gold', title: '充值', minWidth: 100, align: 'center',
                        templet: function (d) {
                            if (d.wallet_recharge){
                                return d.wallet_recharge;
                            } else{
                                return '';
                            }
                        }
                    }
                     , {
                        field: 'gold', title: '文本收益', minWidth: 100, align: 'center',
                        templet: function (d) {
                            if (d.deal_chat){
                                return d.deal_chat;
                            } else{
                                return '';
                            }
                        }
                    }
                     , {
                        field: 'gold', title: '资源个数', minWidth: 100, align: 'center',
                        templet: function (d) {
                            if (d.resource){
                                return d.resource;
                            } else{
                                return '';
                            }
                        }
                    }
                     , {
                        field: 'gold', title: '举报数', minWidth: 100, align: 'center',
                        templet: function (d) {
                            if (d.report){
                                return d.report;
                            } else{
                                return '';
                            }
                        }
                    }
                     , {
                        field: 'gold', title: '动态数', minWidth: 100, align: 'center',
                        templet: function (d) {
                            if (d.dynamic){
                                return d.dynamic;
                            } else{
                                return '';
                            }
                        }
                    }
                     , {
                        field: 'gold', title: '点赞', minWidth: 100, align: 'center',
                        templet: function (d) {
                            if (d.deal_like){
                                return d.deal_like;
                            } else{
                                return '';
                            }
                        }
                    }
                     , {
                        field: 'gold', title: '评论', minWidth: 100, align: 'center',
                        templet: function (d) {
                            if (d.deal_comment){
                                return d.deal_comment;
                            } else{
                                return '';
                            }
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
            $(document).keydown(function (e) {
                if (e.keyCode === 13) {
                    $("#Search").trigger("click");
                }
            });
        });
    </script>
@endsection

