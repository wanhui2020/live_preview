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
                            <input type="text" class="layui-input" name="dateTime" id="test" placeholder="创建时间"
                                   lay-key="17">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <select name="type">
                                <option value="">类型</option>
                                <option value="11">充值收入{{$cashName}}</option>
                                <option value="12">兑换收入{{$cashName}}</option>
                                <option value="13">打赏收入{{$cashName}}</option>
                                <option value="14">邀请充值奖励收入{{$cashName}}</option>
                                <option value="15">语音视频收入{{$cashName}}</option>
                                <option value="16">文本聊天收入{{$cashName}}</option>
                                <option value="17">资源查看收入{{$cashName}}</option>
                                <option value="18">礼物接收收入{{$cashName}}</option>
                                <option value="19">经济人收入奖励</option>
                                <option value="20">经济人充值奖励</option>
                                <option value="50">邀请人收入奖励</option>
                                <option value="51">邀请人充值奖励</option>

                                <option value="21">{{$cashName}}提现支出</option>
                                <option value="22">{{$cashName}}打赏支出</option>
                                <option value="23">{{$goldName}}充值支出</option>
                                {{--<option value="24">VIP购买支出</option>--}}
                                {{--<option value="25">语音视频收入金币</option>--}}
                                {{--<option value="26">文本聊天收入金币</option>--}}
                                {{--<option value="27">资源查看收入金币</option>--}}
                                {{--<option value="28">礼物接收收入金币</option>--}}
                                {{--<option value="29">经济人收入奖励</option>--}}
                                {{--<option value="30">经济人充值奖励</option>--}}

                                <option value="31">{{$goldName}}充值收入</option>
                                <option value="32">语音视频收入</option>
                                <option value="33">文本聊天收入</option>
                                <option value="34">资源查看收入</option>
                                <option value="35">礼物接收收入</option>
                                <option value="36">邀请注册{{$goldName}}奖励</option>
                                <option value="37">邀请消费{{$cashName}}奖励</option>
                                <option value="38">聊天解锁收入</option>
                                <option value="39">注册获得{{$goldName}}</option>
                                <option value="55">邀请注册获得{{$cashName}}</option>
                                <option value="56">注册获得{{$cashName}}</option>
                                {{--<option value="40">经济人充值奖励</option>--}}

                                <option value="41">语音视频支出</option>
                                {{--<option value="42">语音视频收入</option>--}}
                                <option value="43">文本聊天支出</option>
                                <option value="44">资源查看支出</option>
                                <option value="45">礼物赠送支出</option>
                                <option value="46">{{$cashName}}兑换支出</option>
                                <option value="47">聊天解锁支出</option>
                                <option value="52">微信查看收入</option>
                                <option value="53">微信查看支出</option>
                                {{--<option value="48">聊天解锁收入</option>--}}
                                {{--<option value="39">经济人收入奖励</option>--}}
                                {{--<option value="40">经济人充值奖励</option>--}}

                            </select>
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
        var cashName= "{{$cashName}}"
        var goldName= "{{$goldName}}"
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
        var typeData = "{{$type}}";
        layui.use(['table'], function () {
            let table = layui.table, form = layui.form;
            let tableIns = table.render({
                elem: '#lists'
                , url: '{{url('system/member/wallet/record/lists?type_id=')}}'+typeData //数据接口
                , method: 'POST'
                , toolbar: '#toolbar'
                , page: true //开启分页
                , title: '资金流水'
                , totalRow: true
                , cols: [[
                    {field: 'id', title: 'ID', align: 'center', width: 80},
                    {
                        field: 'member_id', title: '所属会员', maxWidth: 200,
                        templet: function (d) {
                            if (d.member) {
                                return d.member.nick_name + '<br>' + d.member.no;
                            } else {
                                return '平台收入';
                            }
                        }
                    }
                    , {
                        field: 'relevance_type', title: '账户', width: 100,
                        templet: function (d) {
                            if (d.relevance_type === 'MemberWalletCash') {
                                return cashName+'账户';
                            }
                            if (d.relevance_type === 'MemberWalletGold') {
                                return goldName+'账户';
                            }
                        }
                    }
                    , {
                        field: 'type', title: '摘要',
                        templet: function (d) {
                            if (d.type === 11) {
                                return '充值收入'+cashName;
                            }
                            if (d.type === 12) {
                                return '兑换收入'+cashName;
                            }
                            if (d.type === 13) {
                                return '打赏收入'+cashName;
                            }
                            if (d.type === 14) {
                                return '邀请充值奖励收入'+cashName;
                            }
                            if (d.type === 15) {
                                return '语音视频收入'+cashName;
                            }
                            if (d.type === 16) {
                                return '文本聊天收入'+cashName;
                            }
                            if (d.type === 17) {
                                return '资源查看收入'+cashName;
                            }
                            if (d.type === 18) {
                                return '礼物接收收入'+cashName;
                            }
                            if (d.type === 19) {
                                return '经济人收入奖励';
                            }
                            if (d.type === 20) {
                                return '经济人充值奖励';
                            }
                            if (d.type === 50) {
                                return '邀请人收入奖励';
                            }
                            if (d.type === 51) {
                                return '邀请人充值奖励';
                            }
                            if (d.type === 52) {
                                return '微信查看收入';
                            }
                            if (d.type === 53) {
                                return '微信查看支出';
                            }
                            if (d.type === 39) {
                                return '注册获得'+goldName;
                            }

                            if (d.type === 21) {
                                return cashName+'提现支出';
                            }
                            if (d.type === 22) {
                                return cashName+'打赏支出';
                            }
                            if (d.type === 23) {
                                return goldName+'充值支出';
                            }
                            if (d.type === 24) {
                                return 'VIP购买支出';
                            }
                            if (d.type === 31) {
                                return goldName+'充值收入';
                            }
                            if (d.type === 32) {
                                return '语音视频收入';
                            }
                            if (d.type === 33) {
                                return '文本聊天收入';
                            }
                            if (d.type === 34) {
                                return '资源查看收入';
                            }
                            if (d.type === 35) {
                                return '礼物接收收入';
                            }
                            if (d.type === 36) {
                                return '邀请注册'+goldName+'奖励';
                            }
                            if (d.type === 37) {
                                return '邀请消费'+cashName+'奖励';
                            }
                            if (d.type === 56) {
                                return '注册获得'+cashName;
                            }
                            if (d.type === 55) {
                                return '邀请注册'+cashName+'奖励';
                            }
                            if (d.type === 38) {
                                return '聊天解锁收入';
                            }
                            if (d.type === 41) {
                                return '语音视频支出';
                            }
                            if (d.type === 43) {
                                return '文本聊天支出';
                            }
                            if (d.type === 44) {
                                return '资源查看支出';
                            }
                            if (d.type === 45) {
                                return '礼物赠送支出';
                            }
                            if (d.type === 46) {
                                return cashName+'兑换支出';
                            }
                            if (d.type === 47) {
                                return '聊天解锁支出';
                            }
                            return '未知';
                        }
                    }
                    ,
                    {
                        field: 'money',
                        title: '金额',
                        width: 120,
                        align: 'center',
                        totalRow: true,
                        templet: function (d) {
                            if (d.money < 0) {
                                return '<span style="color: firebrick">' + d.money + '</span>';
                            }
                            return '<span style="color:green">' + d.money + '</span>';
                        }
                    }
                    ,
                    {
                        field: 'surplus', title:
                            '结余', width:
                            120, align:
                            'center', totalRow:
                            true
                    }
                    ,
                    {
                        field: 'remark', title:
                            '备注',
                    }
                    ,
                    {
                        field: 'created_at', title:
                            '发生时间', width:
                            160, align:
                            'center'
                    }

                    ,
                    {
                        title: '操作', width:
                            120, align:
                            'center',
                        templet:

                            function (d) {
                                let html = '';
                                if (d.pay_status === 0) {
                                }
                                if (d.pay_status === 1) {
                                    html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">删除</a>';
                                }
                                if (d.pay_status === 9) {
                                    html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">审核</a>';
                                    html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">删除</a>';
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
                            , content: '{{url('system/member/wallet/record/create')}}'
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
        })
        ;

    </script>
@endsection

