@extends('layouts.base')
@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-form layui-card-header layuiadmin-card-header-auto">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" name="key" placeholder="编号/昵称/电话" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <select name="type">
                                <option value="">会员类型</option>
                                <option value="0">普通用户</option>
                                <option value="1">陪聊</option>
                                <option value="2">客服</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <select name="sex">
                                <option value="">性别</option>
                                <option value="0">男</option>
                                <option value="1">女</option>
                                <option value="9">未知</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <select name="is_selfie">
                                <option value="">是否自拍认证</option>
                                <option value="0">已认证</option>
                                <option value="1">认证拒绝</option>
                                <option value="8">认证中</option>
                                <option value="9">待认证</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <select name="is_real">
                                <option value="">是否实名认证</option>
                                <option value="0">已认证</option>
                                <option value="1">认证拒绝</option>
                                <option value="8">认证中</option>
                                <option value="9">待认证</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <select name="is_middleman">
                                <option value="">是否经纪人</option>
                                <option value="0">是</option>
                                <option value="1">否</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" name="agent" placeholder="渠道编号/名称" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" name="balance" placeholder="能量/金币" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" name="grade" placeholder="VIP/魅力" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <select name="im_status">
                                <option value="">在线状态</option>
                                <option value="0">在线</option>
                                <option value="1">离线</option>
                                <option value="2">休眠</option>
                                <option value="9">未知</option>
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
                        <button class="layui-btn  layui-btn-sm" lay-event="addRobot">陪聊</button>
                        <button class="layui-btn  layui-btn-sm" lay-event="multiCheck">IM同步</button>
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
                , url: '{{url('system/member/user/lists')}}' //数据接口
                , method: 'POST'
                , toolbar: '#toolbar'
                , page: true //开启分页
                , title: '会员管理'
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
                            html += '<span title="邀请码：' + d.invite_code + '">' + d.nick_name + '</span> ';
                            html += '<br>' + '<a lay-event="detail">' + d.no + '</a>';

                            return html;
                        }
                    }
                    , {
                        field: 'type', title: '类型', width: 100, align: 'center', sort: true,
                        templet: function (d) {
                            let html = '';
                            if (d.type === 0) {
                                if (d.is_middleman===0) {
                                    html += '<span  style="color: red">[经]</span> ';
                                }
                                html += '普通';
                            }
                            if (d.type === 1) {
                                html = '陪聊';
                            }
                            if (d.type === 2) {
                                html = '客服';
                            }
                            return html;
                        }
                    }
                    , {
                        field: 'parent_id', title: '邀请人', minWidth: 110, sort: true,
                        templet: function (d) {
                            let html = '无邀请';
                            if (d.parent) {
                                html = d.parent.nick_name + '<br>' + d.parent.no;
                            }
                            return html;
                        }
                    }
                    , {
                        field: 'agent_id', title: '所属渠道', minWidth: 110, sort: true,
                        templet: function (d) {
                            let html = '无';
                            if (d.agent) {
                                html = d.agent.nick_name + '<br>' + d.agent.no;
                            }
                            return html;
                        }
                    }
                    , {field: 'childrens_count', title: '邀请数', align: 'center', width: 80, hide: true}
                    , {
                        field: 'mobile', title: '手机号/微信', align: 'center', width: 120,
                        templet: function (d) {
                            let html = '';
                            if (d.mobile) {
                                html = ' <span style="color: green;" >' + d.mobile + '</span>';
                            } else {
                                html = '<span  >手机未绑定</span>';
                            }
                            html += '<br>';
                            if (d.unionid) {
                                html += '<span style="color: green;" title="'+d.unionid+'">微信已绑定</span>';
                            } else {
                                html += '<span   >微信未绑定</span>';
                            }
                            return html;
                        }
                    }

                    , {
                        field: 'sex', title: '性别', width: 80, align: 'center', sort: true,
                        templet: function (d) {
                            let html = '未知';
                            if (d.sex === 0) {
                                html = '男';
                            }
                            if (d.sex === 1) {
                                html = '女';
                            }
                            if (d.age > 0) {
                                html += '(' + d.age + ')';
                            }
                            return html;
                        }
                    }
                    , {
                        field: 'gold', title: '能量/金币', minWidth: 100, align: 'center',
                        templet: function (d) {
                            let html = '';
                            if (d.gold) {
                                html += d.gold.balance;
                            }
                            if (d.cash) {
                                html += '<br>' + d.cash.balance;
                            }
                            return html;
                        }
                    }
                    , {
                        field: 'revenue', title: '平台收益', minWidth: 100, align: 'center',
                        templet: function (d) {
                            if (d.wallet_record) {
                                return d.wallet_record;
                            }else{
                                return 0;
                            }

                        }
                    }
                    , {
                        field: 'is_real', title: '实名', width: 90, align: 'center', sort: true,
                        templet: function (d) {
                            let html = '未知状态';
                            if (d.is_real === 0) {
                                html = '<a  style="color: green;" lay-event="realname">已实名</a>';
                            }
                            if (d.is_real === 1) {
                                html = '<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="realname">实名拒绝</a>';
                            }
                            if (d.is_real === 8) {
                                html = '<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="realname">实名中</a>';
                            }
                            if (d.is_real === 9) {
                                html = '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="realname">待实名</a>';
                            }
                            return html;


                        }
                    }
                    , {
                        field: 'is_selfie', title: '自拍', width: 90, align: 'center', sort: true,
                        templet: function (d) {
                            let html = '未知状态';
                            if (d.is_selfie === 0) {
                                html = '<a style="color: green;" lay-event="selfie">已认证</a>';
                            }
                            if (d.is_selfie === 1) {
                                html = '<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="selfie">认证拒绝</a>';
                            }
                            if (d.is_selfie === 8) {
                                html = '<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="selfie">认证中</a>';
                            }
                            if (d.is_selfie === 9) {
                                html = '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="selfie">待认证</a>';
                            }
                            return html;

                        }
                    }
                    , {
                        field: 'vip_grade', title: 'VIP/魅力', width: 110, align: 'center', sort: true,
                        templet: function (d) {
                            let html = 'V' + d.vip_grade;

                            html += '<br>M' + d.charm_grade;

                            return html;

                        }
                    }
                    , {field: 'vip_integral', title: 'VIP积分', align: 'center', width: 80, hide: true}
                    , {field: 'charm_integral', title: '魅力积分', align: 'center', width: 80, hide: true}
                    , {
                        field: 'online_status', title: '在线', width: 90, align: 'center', sort: true,
                        templet: function (d) {
                            let html = '';
                            if (d.im_status === 0) {
                                html += '<a style="color: green;" lay-event="imStatus">IM在线</a>';
                            }
                            if (d.im_status === 1) {
                                html += '<a style="color: red;" lay-event="imStatus">IM离线</a>';
                            }
                            if (d.im_status === 2) {
                                html += '<a style="color: darkblue;" lay-event="imStatus">IM休眠</a>';
                            }
                            if (d.im_status === 9) {
                                html += '<a style="color: firebrick;" lay-event="imStatus">IM未知</a>';
                            }
                            html += '<br>';
                            if (d.online_status === 0) {
                                html += '<span  style="color: green;">在线</span>';
                            }
                            if (d.online_status === 1) {
                                html += '<span  style="color: red;">离线</span>';

                            }
                            if (d.online_status === 9) {
                                html += '<span style="color: firebrick;" >未知</span>';
                            }
                            return html;

                        }
                    }
                    , {
                        field: 'live_status', title: '忙碌', width: 80, align: 'center', sort: true,
                        templet: function (d) {
                            if (d.dispose_online === 0) {
                                return '<span style="color: green;">空闲</span>';
                            }
                            if (d.dispose_online === 1) {
                                return '<span style="color: red;">离线</span>';
                            }
                            if (d.dispose_online === 2) {
                                return '<span style="color: #ffe041;">忙碌</span>';
                            }
                            if (d.dispose_online === 3) {
                                return '<span style="color: rebeccapurple;">勿扰</span>';
                            }
                        }
                    }
                    , {
                        field: 'is_middleman', title: '是否经纪人', width: 80, align: 'center',
                        templet: function (d) {
                            if (d.is_middleman === 0) {
                                return '<span style="color: green;">是</span>';
                            }
                            if (d.is_middleman === 1) {
                                return '<span style="color: red;">否</span>';
                            }

                        }
                    }
                    , {field: 'hot', title: '邀请指数', align: 'center', width: 110, sort: true, hide: true}
                    , {field: 'app_platform', title: 'APP平台', align: 'center', width: 110, sort: true, hide: true}
                    , {field: 'app_version', title: 'APP版本', align: 'center', width: 110, sort: true, hide: true}
                    , {
                        field: 'status', title: '状态', width: 80, align: 'center', sort: true,
                        templet: function (d) {
                            let html = '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="status">未知</a>' + d.status;
                            if (d.status === 0) {
                                html = '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="status">启用</a>';
                            }
                            if (d.status === 1) {
                                html = '<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="status">禁用</a>';
                            }

                            return html;

                        }
                    }
                    , {field: 'created_at', title: '创建时间', align: 'center', width: 160, hide: true}

                    , {
                        title: '操作', width: 120, align: 'center', field: 'type',
                        templet: function (d) {
                            let html = '';
                            /*html = '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="login" >登入</a>';*/
                            html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="parameter">参数</a>';
                            html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="rate">费率</a><br>';
                            html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">编辑</a>';
                            html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="del">删除</a>';
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
                            , title: '新增会员'
                            , content: '{{url('system/member/user/create')}}'
                            , maxmin: true
                            , area: ['800px', '400px']
                            , end: function () {
                                tableIns.reload();
                            }
                        });
                        break;
                    case 'addRobot':
                        layer.load();
                        axios.post("{{url('system/member/user/robot')}}")
                            .then(function (response) {
                                    layer.closeAll();
                                    if (response.data.status) {
                                        layer.msg(response.data.msg);
                                        return tableIns.reload();
                                    }
                                    return layer.alert(response.data.msg);
                                }
                            );
                        break;
                    case 'multiCheck':
                        layer.confirm('确定要批量更新IM状态吗?', {icon: 3, btn: ['确定', '取消'], title: "信息提示"}, function (index) {
                            layer.load();
                            axios.post("{{url('system/member/user/im/multi')}}")
                                .then(function (response) {
                                        layer.closeAll();
                                        if (response.data.status) {
                                            layer.msg(response.data.msg);
                                            return tableIns.reload();
                                        }
                                        return layer.alert(response.data.msg);
                                    }
                                );
                        });
                        break;
                }
            });
            //监听行工具条
            table.on('tool(lists)', function (obj) {
                let data = obj.data;
                let layEvent = obj.event;
                let tr = obj.tr;
                if (layEvent === 'status') { //改变状态
                    let _c = data.status === 0 ? '禁用' : '启用';
                    layer.confirm('确定要【' + _c + '】么?', {icon: 3, btn: ['确定', '取消'], title: "信息提示"}, function (index) {
                        axios.post("{{url('system/member/user/status')}}", {id: data.id})
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
                if (layEvent === 'imStatus') { //IM状态检查
                    layer.load();
                    axios.post("{{url('system/member/user/im/status')}}", {id: data.id})
                        .then(function (response) {
                                layer.closeAll();
                                if (response.data.status) {
                                    layer.msg(response.data.msg);
                                    return tableIns.reload();
                                }
                                return layer.alert(response.data.msg);
                            }
                        );
                }
                //会员详情
                if (layEvent === 'detail') {
                    parent.layui.index.openTabsPage('system/member/user/detail?id=' + data.id, '会员详情');
                }
                if (layEvent === 'del') { //删除
                    layer.confirm('真的删除用户-' + data.nick_name + '么', function (index) {
                        axios.post("{{url('system/member/user/destroy')}}", {ids: [data.id]})
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
                if (layEvent === 'edit') { //编辑
                    layer.open({
                        type: 2
                        , title: '编辑会员'
                        , content: '{{url('system/member/user/edit?id=')}}' + data.id
                        , maxmin: true
                        , area: ['900px', '600px']
                        , end: function () {
                            tableIns.reload();
                        }
                    });
                }
                if (layEvent === 'rate') { //费率
                    layer.open({
                        type: 2
                        , title: '费率设置'
                        , content: '{{url('system/member/user/rate/edit?id=')}}' + data.id
                        , maxmin: true
                        , area: ['900px', '400px']
                        // , scrollbar: true
                        , end: function () {
                            tableIns.reload();
                        }
                    });
                }
                if (layEvent === 'parameter') { //会员参数
                    layer.open({
                        type: 2
                        , title: '会员参数'
                        , content: '{{url('system/member/user/parameter/edit?id=')}}' + data.id
                        , maxmin: true
                        , area: ['900px', '600px']
                        , end: function () {
                            tableIns.reload();
                        }
                    });
                }
                if (layEvent === 'selfie') { //自拍认证
                    layer.open({
                        type: 2
                        , title: '自拍认证'
                        , content: '{{url('system/member/user/selfie/edit?memberId=')}}' + data.id
                        , maxmin: true
                        , area: ['800px', '400px']
                        , end: function () {
                            tableIns.reload();
                        }
                    });
                }
                if (layEvent === 'realname') { //实名认证
                    layer.open({
                        type: 2
                        , title: '实名认证'
                        , content: '{{url('system/member/user/realname/edit?memberId=')}}' + data.id
                        , maxmin: true
                        , area: ['800px', '400px']
                        , end: function () {
                            tableIns.reload();
                        }
                    });
                }
            });
        });
    </script>
@endsection

