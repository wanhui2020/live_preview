@extends('layouts.base')
@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">{{$member->nick_name}}-会员详情</div>
                    <div class="layui-card-body layui-form " lay-filter="formData" pad15>
                        <div class="layui-row">
                            <div class="layui-col-md4" style="border-right: #f1f1f1 solid 1px">
                                <div class="layui-form-item">
                                    <div class="layui-inline">

                                        <div class=" layui-input-block">
                                            <img src="{{$member->fill_head_pic}}" style="height: 80px;">
                                            <p>{{$member->no}}</p>
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">昵称</label>
                                        <div class=" layui-input-block ">
                                            <span class="layui-form-mid">   {{$member->nick_name}}</span>
                                        </div>
                                        <label class="layui-form-label">性别</label>
                                        <div class=" layui-input-block ">
                                            <span class="layui-form-mid">  {{$member->sex==0?'男':'女'}}</span>
                                        </div>
                                        <label class="layui-form-label">年龄</label>
                                        <div class=" layui-input-block ">
                                            <span class="layui-form-mid">   {{$member->sex}}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="layui-form-item">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">邀请码</label>
                                        <div class=" layui-input-block">
                                            <span class="layui-form-mid"> {{$member->invite_code}}</span>
                                        </div>
                                    </div>

                                    <div class="layui-inline">
                                        <label class="layui-form-label">邀请人</label>
                                        <div class=" layui-input-block">
                                        <span class="layui-form-mid">
                                            {{$member->parent?$member->parent->nick_name:'无邀请'}}
                                        </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">手机号</label>
                                        <div class=" layui-input-block">
                                        <span class="layui-form-mid">
                                            {{$member->mobile??'未绑定'}}
                                        </span>
                                        </div>
                                    </div>
                                    @if($member->mobile)
                                        <div class="layui-inline">
                                            <label class="layui-form-label">手机验证</label>
                                            <div class="layui-input-block">
                                            <span
                                                    class="layui-form-mid">
                                                {{$member->mobile_verify==0?'已验证':'未验证'}}
                                            </span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="layui-form-item">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">实名状态</label>
                                        <div class="layui-input-block">
                                         <span class="layui-form-mid">
                                        @if($member->is_real==0)
                                                 已实名
                                             @endif
                                             @if($member->is_real==1)
                                                 实名失败
                                             @endif
                                             @if($member->is_real==8)
                                                 实名中
                                             @endif
                                             @if($member->is_real==9)
                                                 待实名
                                             @endif
                                         </span>
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">自拍认证</label>
                                        <div class="layui-input-block">
                                         <span class="layui-form-mid">
                                        @if($member->is_selfie==0)
                                                 已认证
                                             @endif
                                             @if($member->is_selfie==1)
                                                 认证失败
                                             @endif
                                             @if($member->is_selfie==8)
                                                 认证中
                                             @endif
                                             @if($member->is_selfie==9)
                                                 待认证
                                             @endif
                                         </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <div class="layui-inline"><label class="layui-form-label">格言</label>
                                        <div class="layui-input-block">
                                            <span class="layui-form-mid">    {{$member->aphorism??'未设置'}}</span>
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">签名</label>
                                        <div class="layui-input-block">
                                            <span class="layui-form-mid">     {{$member->signature??'未设置'}}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="layui-form-item">
                                    <label class="layui-form-label">当前地址</label>
                                    <div class="layui-input-block">
                                        <span class="layui-form-mid">    {{$member->address}}</span>
                                    </div>
                                </div>


                                <div class="layui-form-item">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">金币余额</label>
                                        <div class="layui-input-block">
                                            <span
                                                    class="layui-form-mid">     {{$member->cash->balance}}</span>
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">现金可用</label>
                                        <div class="layui-input-block">
                                            <span
                                                    class="layui-form-mid"> {{$member->cash->usable}}</span>
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">金币余额</label>
                                        <div class="layui-input-block">
                                            <span class="layui-form-mid">     {{$member->gold->balance}}</span>
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">金币可用</label>
                                        <div class="layui-input-block">
                                            <span class="layui-form-mid">  {{$member->gold->usable}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="layui-col-md8  " style="padding-left: 10px;">

                                <div class="layui-tab">
                                    <ul class="layui-tab-title">
                                        <li class=" layui-this" lay-id="11" onclick="getData('{{$member->id}}',1)">
                                            我的好友
                                        </li>
                                        <li lay-id="22" onclick="getData('{{$member->id}}',2)">我的关注</li>
                                        <li lay-id="22" onclick="getData('{{$member->id}}',3)">我的礼物</li>
                                        <li lay-id="33" onclick="getData('{{$member->id}}',4)">充值记录</li>
                                        <li lay-id="44" onclick="getData('{{$member->id}}',5)">提现记录</li>
                                        <li lay-id="55" onclick="getData('{{$member->id}}',6)">订单管理</li>
                                    </ul>
                                    <div class="layui-tab-content">
                                        <table id="lists" lay-filter="lists"></table>
                                        <div class="layui-tab-item layui-show" id="one">

                                        </div>
                                        <div class="layui-tab-item">

                                        </div>

                                        <div class="layui-tab-item">

                                        </div>
                                        <div class="layui-tab-item">
                                        </div>
                                        <div class="layui-tab-item">
                                        </div>
                                        <div class="layui-tab-item">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
    <script type="application/javascript">
        $(function () {
            var id = "{{$member->id}}";
            getData(id, 1)
        })

        function getData(id, type) {
            if (type == 1 || type == 2) {
                layui.use(['table'], function () {
                    let table = layui.table, form = layui.form;
                    let tableIns = table.render({
                        elem: '#lists'
                        , url: '{{url('/system/member/user/getData?id=')}}' + id + '&type=' + type //数据接口
                        , method: 'POST'
                        // , toolbar: '#toolbar'
                        , page: true //开启分页
                        , title: '我的好友'
                        , cols: [[
                            {
                                field: 'no', title: '编号',
                                templet: function (d) {
                                    if (d) {
                                        return d.no;
                                    } else {
                                        return '未知';
                                    }
                                }
                            }
                            , {
                                field: 'head_pic', title: '头像',
                                templet: function (d) {
                                    if (d) {
                                        return d.head_pic;
                                    } else {
                                        return '未知';
                                    }
                                }
                            }
                            , {
                                field: 'nick_name', title: '昵称',
                                templet: function (d) {
                                    if (d) {
                                        return d.nick_name;
                                    } else {
                                        return '未知';
                                    }
                                }
                            }
                            , {
                                field: 'created_at', title: '时间',
                                templet: function (d) {
                                    if (d) {
                                        return d.created_at;
                                    } else {
                                        return '未知';
                                    }
                                }
                            }

                        ]]
                    });

                });
            }
            if (type == 3) {
                layui.use(['table'], function () {
                    let table = layui.table, form = layui.form;
                    let tableIns = table.render({
                        elem: '#lists'
                        , url: '{{url('/system/member/user/getData?id=')}}' + id + '&type=' + type //数据接口
                        , method: 'POST'
                        // , toolbar: '#toolbar'
                        , page: true //开启分页
                        , title: '我的好友'
                        , cols: [[
                            {
                                field: 'no', title: '订单编号',
                                templet: function (d) {
                                    if (d) {
                                        return d.no;
                                    } else {
                                        return '未知';
                                    }
                                }
                            }
                            , {
                                field: 'head_pic', title: '赠送人',
                                templet: function (d) {
                                    if (d.tomember) {
                                        return d.tomember.nick_name;
                                    } else {
                                        return '未知';
                                    }
                                }
                            }
                            , {
                                field: 'nick_name', title: '礼物名称',
                                templet: function (d) {
                                    if (d) {
                                        return d.name;
                                    } else {
                                        return '未知';
                                    }
                                }
                            }
                            , {
                                field: 'nick_name', title: '礼物单价',
                                templet: function (d) {
                                    if (d) {
                                        return d.price;
                                    } else {
                                        return '未知';
                                    }
                                }
                            }
                            , {
                                field: 'nick_name', title: '数量',
                                templet: function (d) {
                                    if (d) {
                                        return d.quantity;
                                    } else {
                                        return '未知';
                                    }
                                }
                            }
                            , {
                                field: 'nick_name', title: '消费合计',
                                templet: function (d) {
                                    if (d) {
                                        return d.total;
                                    } else {
                                        return '未知';
                                    }
                                }
                            }
                            , {
                                field: 'created_at', title: '时间',
                                templet: function (d) {
                                    if (d) {
                                        return d.created_at;
                                    } else {
                                        return '未知';
                                    }
                                }
                            }

                        ]]
                    });

                });
            }
            if (type == 4) {
                layui.use(['table'], function () {
                    let table = layui.table, form = layui.form;
                    let tableIns = table.render({
                        elem: '#lists'
                        , url: '{{url('/system/member/user/getData?id=')}}' + id + '&type=' + type //数据接口
                        , method: 'POST'
                        // , toolbar: '#toolbar'
                        , page: true //开启分页
                        , title: '我的好友'
                        , cols: [[
                            {
                                field: 'no', title: '编号',
                                templet: function (d) {
                                    if (d) {
                                        return d.no;
                                    } else {
                                        return '未知';
                                    }
                                }
                            }
                            , {
                                field: 'money', title: '充值金额',
                                templet: function (d) {
                                    if (d) {
                                        return d.money;
                                    } else {
                                        return '未知';
                                    }
                                }
                            }
                            , {
                                field: 'pay_time', title: '支付时间',
                                templet: function (d) {
                                    if (d) {
                                        return d.pay_time;
                                    } else {
                                        return '未知';
                                    }
                                }
                            }
                            , {
                                field: 'created_at', title: '支付状态',
                                templet: function (d) {
                                    if (d) {
                                        if (d.pay_status == 0) {
                                            return '成功';
                                        }
                                        if (d.pay_status == 1) {
                                            return '失败';
                                        }
                                        if (d.pay_status == 2) {
                                            return '取消';
                                        }
                                        if (d.pay_status == 9) {
                                            return '支付中';
                                        }

                                    } else {
                                        return '未知';
                                    }
                                }
                            }
                            , {
                                field: 'pay_time', title: '经办人',
                                templet: function (d) {
                                    if (d) {
                                        return d.audit_name;
                                    } else {
                                        return '未知';
                                    }
                                }
                            }
                            , {
                                field: 'pay_status', title: '状态',
                                templet: function (d) {
                                    if (d) {
                                        if (d.status == 0) {
                                            return '审核通过';
                                        }
                                        if (d.status == 1) {
                                            return '审核拒绝';
                                        }
                                        if (d.status == 9) {
                                            return '支付中';
                                        }

                                    } else {
                                        return '未知';
                                    }
                                }
                            }

                        ]]
                    });

                });
            }
            if (type == 5) {
                layui.use(['table'], function () {
                    let table = layui.table, form = layui.form;
                    let tableIns = table.render({
                        elem: '#lists'
                        , url: '{{url('/system/member/user/getData?id=')}}' + id + '&type=' + type //数据接口
                        , method: 'POST'
                        // , toolbar: '#toolbar'
                        , page: true //开启分页
                        , title: '我的好友'
                        , cols: [[
                            {
                                field: 'no', title: '编号',
                                templet: function (d) {
                                    if (d) {
                                        return d.no;
                                    } else {
                                        return '未知';
                                    }
                                }
                            }
                            , {
                                field: 'money', title: '提现金额',
                                templet: function (d) {
                                    if (d) {
                                        return d.money;
                                    } else {
                                        return '未知';
                                    }
                                }
                            }
                            , {
                                field: 'username', title: '用户姓名',
                                templet: function (d) {
                                    if (d) {
                                        return d.username;
                                    } else {
                                        return '未知';
                                    }
                                }
                            }
                            , {
                                field: 'no', title: '银行账号',
                                templet: function (d) {
                                    if (d) {
                                        return d.bank_account;
                                    } else {
                                        return '未知';
                                    }
                                }
                            }
                            , {
                                field: 'money', title: '银行名称',
                                templet: function (d) {
                                    if (d) {
                                        return d.bank_name;
                                    } else {
                                        return '未知';
                                    }
                                }
                            }
                            , {
                                field: 'username', title: '支付时间',
                                templet: function (d) {
                                    if (d) {
                                        return d.pay_time;
                                    } else {
                                        return '未知';
                                    }
                                }
                            }
                            , {
                                field: 'created_at', title: '支付状态',
                                templet: function (d) {
                                    if (d) {
                                        if (d.pay_status == 0) {
                                            return '成功';
                                        }
                                        if (d.pay_status == 1) {
                                            return '失败';
                                        }
                                        if (d.pay_status == 9) {
                                            return '支付中';
                                        }

                                    } else {
                                        return '未知';
                                    }
                                }
                            }
                            , {
                                field: 'pay_time', title: '经办人',
                                templet: function (d) {
                                    if (d) {
                                        return d.audit_name;
                                    } else {
                                        return '未知';
                                    }
                                }
                            }
                            , {
                                field: 'pay_status', title: '状态',
                                templet: function (d) {
                                    if (d) {
                                        if (d.status == 0) {
                                            return '审核通过';
                                        }
                                        if (d.status == 1) {
                                            return '审核拒绝';
                                        }
                                        if (d.status == 9) {
                                            return '支付中';
                                        }

                                    } else {
                                        return '未知';
                                    }
                                }
                            }

                        ]]
                    });

                });
            }
            if (type == 6) {
                layui.use(['table'], function () {
                    let table = layui.table, form = layui.form;
                    let tableIns = table.render({
                        elem: '#lists'
                        , url: '{{url('/system/member/user/getData?id=')}}' + id + '&type=' + type //数据接口
                        , method: 'POST'
                        // , toolbar: '#toolbar'
                        , page: true //开启分页
                        , title: '我的好友'
                        , cols: [[
                            {
                                field: 'no', title: '时间',
                                templet: function (d) {
                                    if (d) {
                                        return d.created_at;
                                    } else {
                                        return '未知';
                                    }
                                }
                            }
                            , {
                                field: 'head_pic', title: '金额',
                                templet: function (d) {
                                    if (d) {
                                        return d.money;
                                    } else {
                                        return '未知';
                                    }
                                }
                            }
                            , {
                                field: 'nick_name', title: '摘要',
                                templet: function (d) {
                                    if (d.type === 11) {
                                        return '充值收入金币';
                                    }
                                    if (d.type === 12) {
                                        return '兑换收入金币';
                                    }
                                    if (d.type === 13) {
                                        return '打赏收入金币';
                                    }
                                    if (d.type === 14) {
                                        return '邀请充值奖励收入金币';
                                    }
                                    if (d.type === 15) {
                                        return '语音视频收入金币';
                                    }
                                    if (d.type === 16) {
                                        return '文本聊天收入金币';
                                    }
                                    if (d.type === 17) {
                                        return '资源查看收入金币';
                                    }
                                    if (d.type === 18) {
                                        return '礼物接收收入金币';
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
                                        return '用户充值';
                                    }

                                    if (d.type === 21) {
                                        return '金币提现支出';
                                    }
                                    if (d.type === 22) {
                                        return '金币打赏支出';
                                    }
                                    if (d.type === 23) {
                                        return '能量充值支出';
                                    }
                                    if (d.type === 24) {
                                        return 'VIP购买支出';
                                    }
                                    if (d.type === 31) {
                                        return '能量充值收入';
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
                                        return '邀请注册金币奖励';
                                    }
                                    if (d.type === 37) {
                                        return '邀请消费金币奖励';
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
                                        return '金币兑换支出';
                                    }
                                    if (d.type === 47) {
                                        return '聊天解锁支出';
                                    }
                                    return '未知';
                                }
                            }

                        ]]
                    });

                });
            }
        }

        layui.use(['table', 'element'], function () {

            let table = layui.table
                , form = layui.form;
            form.val("formData",{!! Auth::user('SystemUser') !!});

            //监听搜索
            form.on('submit(save)', function (data) {
                let field = data.field;
                axios.post("/system/base/user/update", field)
                    .then(function (response) {
                            if (response.data.status) {
                                return layer.msg(response.data.msg);

                            }
                            return layer.alert(response.data.msg);
                        }
                    );
            });


        });
    </script>
@endsection
