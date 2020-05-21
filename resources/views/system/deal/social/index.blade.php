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
                            <select name="status">
                                <option value="">审核状态</option>
                                <option value="1">已审核</option>
                                <option value="0">未审核</option>
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
                , url: '{{url('system/deal/social/lists')}}' //数据接口
                , method: 'POST'
                , toolbar: '#toolbar'
                , page: true //开启分页
                , title: '社交动态'
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
                    , {field: 'content', title: '内容', align: 'center'}
                    , {field: 'pic', title: '图片', align: 'center',
                        templet:function (d) {
                            if (d.pic){
                                return '<div onclick="show_img(this)" ><img src="' + d.pic + '" alt="" width="500px" height="50px"></a></div>';
                            }
                            return  '无';
                        }
                    }
                    , {field: 'vido', title: '视频', align: 'center',
                        templet:function (d) {
                            if (d.vido){
                                let html = '';
                                html = '<video style="cursor:pointer" width="100%" height="80px"  src="' + d.vido + '"></video>';
                                return '<a href="' + d.vido + '" target="_blank" title="点击查看">' + html + '</a>';
                            }
                            return  '无';
                        }
                    }
                    , {
                        field: 'likes_count', title: '点赞数', width: 100, align: 'center'
                    }
                    , {
                        field: 'comments_count', title: '评论数', width: 100, align: 'center'
                    }
                    , {field: 'audit', title: '审核人', align: 'center',
                        templet: function (d) {
                            if (d.audit) {
                                return d.audit.name;
                            } else{
                                return '无';
                            }
                        }
                    }
                    , {field: 'audit_time', title: '审核时间', align: 'center',
                        templet: function (d) {
                            if (d.audit_time) {
                                return d.audit_time;
                            } else{
                                return '无';
                            }
                        }
                    }
                    , {
                        field: 'status', title: '审核状态', width: 120, align: 'center',
                        templet: function (d) {
                            if (d.status === 0) {
                                return '未审核';
                            }else if(d.status === 1){
                                return '审核通过';
                            }else if(d.status === 2){
                                return '<p style="color: red;">' +'审核拒绝' + '</p>';
                            }else{
                                return '无';
                            }

                        }
                    }
                    , {
                        title: '操作', width: 120, align: 'center', templet: function (d) {
                            let html = '';
                            if (d.status === 0){
                                html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="audit">审核</a>';
                            }
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
                            , title: '新增社交动态'
                            , content: '{{url('system/deal/social/create')}}'
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
                    layer.confirm('确认审核？', {
                        btn: ['通过', '不通过']
                    }, function () {
                        layer.prompt({
                            formType: 1,
                            title: '安全码效验',
                        }, function (value, index, elem) {
                            axios.post("{{url('system/verifysafecode')}}", {safecode: value})
                                .then(function (response) {
                                    if (response.data.status) { //通过
                                        layer.closeAll();
                                        axios.post("{{url('system/deal/social/audit')}}", {
                                            id: data.id,
                                            status: 1
                                        })
                                            .then(function (response) {
                                                    if (response.data.status) {
                                                        layer.msg(response.data.msg);
                                                        table.reload('lists');
                                                        return
                                                    }
                                                    return layer.alert(response.data.msg);
                                                }
                                            );
                                    } else {
                                        return layer.alert(response.data.msg);
                                    }
                                })
                        });
                    }, function () {  //拒绝
                        axios.post("{{url('system/deal/social/audit')}}", {
                            id: data.id,
                            status: 2
                        })
                            .then(function (response) {
                                    if (response.data.status) {
                                        layer.msg(response.data.msg);
                                        table.reload('lists');
                                        return
                                    }
                                    return layer.alert(response.data.msg);
                                }
                            );
                    });
                }
                if (layEvent === 'detail') { //查看
                    //do somehing
                } else if (layEvent === 'del') { //删除
                    layer.confirm('真的删除么', function (index) {
                        axios.post("{{url('system/deal/social/destroy')}}", {ids: [data.id]})
                            .then(function (response) {
                                    if (response.data.status) {
                                        layer.msg(response.data.msg);
                                        return tableIns.reload();
                                    }
                                    return layer.alert(response.data.msg);
                                }
                            );
                    });
                } else if (layEvent === 'login') {//登录
                    axios.post("{{url('')}}", {id: data.id})
                        .then(function (response) {
                                if (response.data.code === 0) {
                                    layer.msg(response.data.data);
                                    tableIns.reload();
                                    return
                                }
                                return layer.msg(response.data.data);
                            }
                        );
                    window.open('/system/member/user/login?id=' + data.id);
                } else if (layEvent === 'edit') { //编辑
                    layer.open({
                        type: 2
                        , title: '编辑系统用户'
                        , content: '{{url('system/member/user/edit?id=')}}' + data.id
                        , maxmin: true
                        , area: ['800px', '400px']
                        , end: function () {
                            tableIns.reload();
                        }
                    });
                } else if (layEvent === 'status') { //禁用启用
                    let str = data.status == 1 ? '启用' : '禁用';
                    layer.confirm('确定' + str + '用户-' + data.email + '么？', function (index) {
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
            });

            //监听单元格编辑
            table.on('edit(lists)', function (obj) {
                let value = obj.value //得到修改后的值
                    , data = obj.data //得到所在行所有键值
                    , field = obj.field; //得到字段
            });
        });
        //显示图片
        function show_img(t) {
            var t = $(t).find("img");
            //页面层
            layer.open({
                type: 1,
                skin: 'layui-layer-rim', //加上边框
                area: ['60%', '50%'], //宽高
                shadeClose: true, //开启遮罩关闭
                end: function (index, layero) {
                    return false;
                },
                content: '<div style="text-align:center;height: 100%;"><img src="' + $(t).attr('src') + ' " height="100%" width="auto"/></div>'
            });
        }
    </script>
@endsection

