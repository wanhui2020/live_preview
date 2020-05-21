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
                            <select name="type">
                                <option value="">类型</option>
                                <option value="0">文字</option>
                                <option value="1">图片</option>
                                <option value="2">视频</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <select name="status">
                                <option value="">审核状态</option>
                                <option value="9">未审核</option>
                                <option value="0">审核通过</option>
                                <option value="1">审核拒绝</option>
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
                , url: '{{url('system/member/social/lists')}}' //数据接口
                , method: 'POST'
                , toolbar: '#toolbar'
                , page: true //开启分页
                , title: '会员资源'
                , cols: [[
                    {field: 'id', title: 'ID', align: 'center', width: 80}

                    , {
                        field: 'member_id', title: '所属会员',
                        templet: function (d) {
                            if (d.member) {
                                return d.member.nick_name + '<br>' + d.member.no;
                            } else {
                                return '未知';
                            }
                        }
                    }
                    , {
                        field: 'type', title: '内容', align: 'center',
                        templet: function (d) {
                            if (d.content) {
                                return d.content;
                            }else{
                                return '';
                            }
                        }
                    }
                    , {
                        field: 'url', title: '资源地址', align: 'center',
                        templet: function (d) {
                            if (d.file != null) {
                                if (d.type === 1) {
                                    var html = '';
                                    for (var i  in d.file) {
                                        html +='<a href="' + d.file[i].url + '" target="_blank" title="点击查看"><img src="' + d.file[i].thumb + '?x-oss-process=video/snapshot,t_1000,f_jpg,w_0,h_0,m_fast" alt="" width="500px" height="50px"></a>';
                                    }
                                    return html;
                                } else {
                                    var html = '';
                                    for (var i  in d.file) {
                                        html +='<div onclick="show_img(this)" ><img src="' + d.file[i].thumb + '" alt="" width="500px" height="50px"></div>';
                                    }
                                    return html;
                                }
                            } else {
                                return '';
                            }
                        }
                    }
                    // , {field: 'thumb', title: '缩略地址', align: 'center',
                    //     templet: function (d) {
                    //         if (d.file) {
                    //             return d.file.thumb;
                    //         }
                    //         return '无记录';
                    //
                    //     }}
                    , {
                        field: 'audit_id', title: '审核人', align: 'center',
                        templet: function (d) {
                            if (d.audit) {
                                return d.audit.name;
                            } else if (d.status === 0) {
                                return '<span style="color: green;">自动审核</span>';
                            }
                            return '<span style="color: orange;">待审核</span>';
                        }
                    }
                    , {
                        field: 'audit_time', title: '审核时间', align: 'center',
                        templet: function (d) {
                            if (d.audit_time) {
                                return d.audit_time;
                            } else if (d.status === 0) {
                                return '<span style="color: green;">自动审核</span>';
                            }
                            return '<span style="color: orange;">待审核</span>';
                        }
                    }
                    , {
                        field: 'status', title: '审核状态', width: 120, align: 'center',
                        templet: function (d) {
                            if (d.status === 0) {
                                return '<span style="color: green;">审核通过</span>';
                            }
                            if (d.status === 1) {
                                return '<span style="color: red;">审核拒绝</span>';
                            }
                            if (d.status === 8) {
                                return '<span style="color: orange;">审核中</span>';
                            }
                            if (d.status === 9) {
                                return '<span style="color: orange;">待审核</span>';
                            }

                        }
                    }
                    , {
                        title: '操作', width: 150, align: 'center', field: 'type',
                        templet: function (d) {
                            let html = '';
                            if (d.status === 9) {
                                html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="audit" >审核</a>';
                            }
                            html += '<a class="layui-btn layui-btn-normal layui-btn-xs" onclick="like('+d.id+')">点赞人</a>';
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
                            , title: '新增会员资源'
                            , content: '{{url('system/member/social/create')}}'
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
                        layer.closeAll();
                        axios.post("{{url('system/member/social/audit')}}", {
                            id: data.id,
                            status: 0
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
                    }, function () {
                        axios.post("{{url('system/member/social/audit')}}", {
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
                    });
                }
                if (layEvent === 'detail') { //查看
                    //do somehing
                } else if (layEvent === 'del') { //删除
                    layer.confirm('确认删除？', function (index) {
                        axios.post("{{url('system/member/social/destroy')}}", {ids: [data.id]})
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
                        , title: '编辑会员资源'
                        , content: '{{url('system/member/social/edit?id=')}}' + data.id
                        , maxmin: true
                        , area: ['600px', '800px']
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
                area: ['60%', '80%'], //宽高
                shadeClose: true, //开启遮罩关闭
                end: function (index, layero) {
                    return false;
                },
                content: '<div style="text-align:center; "><img src="' + $(t).attr('src') + ' "  style="max-width: 800px;"/></div>'
            });
        }

        function like(id) {
            window.location.href="{{url('system/deal/like?id=')}}"+id
        }
    </script>
@endsection

