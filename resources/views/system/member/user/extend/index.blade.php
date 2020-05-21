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
                                <option value="0">审核通过</option>
                                <option value="1">审核拒绝</option>
                                <option value="2">待审核</option>
                                <option value="9">未审核</option>
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
                {{--                <script type="text/html" id="toolbar">--}}
                {{--                    <div>--}}
                {{--                        <button class="layui-btn  layui-btn-sm" lay-event="add">新增</button>--}}
                {{--                    </div>--}}
                {{--                </script>--}}
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
                , url: '{{url('system/member/user/extend/lists')}}' //数据接口
                , method: 'POST'
                , toolbar: '#toolbar'
                , page: true //开启分页
                , title: '会员管理'
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
                        field: 'weixin', title: '微信', align: 'center',
                        templet: function (d) {
                            if (d.weixin) {
                                return d.weixin;
                            } else {
                                return '无';
                            }
                        }
                    }
                    , {
                        field: 'status', title: '微信号验证', width: 120, align: 'center',
                        templet: function (d) {
                            let html = '未知状态';
                            if (d.weixin_verify === 0) {
                                html = '<a  style="color: green;" lay-event="weixin_verify">审核通过</a>';
                            }
                            if (d.weixin_verify === 1) {
                                html = '<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="weixin_verify">审核不通过</a>';
                            }
                            if (d.weixin_verify === 2) {
                                html = '<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="weixin_verify">待审核</a>';
                            }
                            if (d.weixin_verify === 9) {
                                html = '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="weixin_verify">未审核</a>';
                            }
                            return html;
                        }
                    }
                   , {
                        field: 'weixin', title: 'QQ号', align: 'center',
                        templet: function (d) {
                            if (d.qq) {
                                return d.qq;
                            } else {
                                return '无';
                            }
                        }
                    }
                    , {
                        field: 'status', title: 'QQ号验证', width: 120, align: 'center',
                        templet: function (d) {
                            let html = '未知状态';
                            if (d.qq_verify === 0) {
                                html = '<a  style="color: green;" lay-event="qq_verify">审核通过</a>';
                            }
                            if (d.qq_verify === 1) {
                                html = '<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="qq_verify">审核不通过</a>';
                            }
                            if (d.qq_verify === 2) {
                                html = '<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="qq_verify">待审核</a>';
                            }
                            if (d.qq_verify === 9) {
                                html = '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="qq_verify">未审核</a>';
                            }
                            return html;
                        }
                    }
                    , {
                        field: 'birthday', title: '生日', align: 'center',
                        templet: function (d) {
                            if (d.birthday) {
                                return d.birthday;
                            } else {
                                return '无';
                            }
                        }
                    }
                   , {
                        field: 'weixin', title: '兴趣爱好', align: 'center',
                        templet: function (d) {
                            if (d.hobbies) {
                                return d.hobbies;
                            } else {
                                return '无';
                            }
                        }
                    }
                    , {
                        field: 'birthday', title: '职业', align: 'center',
                        templet: function (d) {
                            if (d.profession) {
                                return d.profession;
                            } else {
                                return '无';
                            }
                        }
                    }
                   , {
                        field: 'weixin', title: '身高(cm)', align: 'center',
                        templet: function (d) {
                            if (d.height) {
                                return d.height;
                            } else {
                                return '无';
                            }
                        }
                    }
                    , {
                        field: 'birthday', title: '体重(KG)', align: 'center',
                        templet: function (d) {
                            if (d.weight) {
                                return d.weight;
                            } else {
                                return '无';
                            }
                        }
                    }
                   // , {
                   //      field: 'weixin', title: '星座', align: 'center',
                   //      templet: function (d) {
                   //          if (d.constellation) {
                   //              return d.constellation;
                   //          } else {
                   //              return '无';
                   //          }
                   //      }
                   //  }
                   // , {
                   //      field: 'weixin', title: '血型', align: 'center',
                   //      templet: function (d) {
                   //          if (d.blood) {
                   //              return d.blood;
                   //          } else {
                   //              return '无';
                   //          }
                   //      }
                   //  }
                   //  , {
                   //      field: 'birthday', title: '情感', align: 'center',
                   //      templet: function (d) {
                   //          if (d.emotion) {
                   //              return d.emotion;
                   //          } else {
                   //              return '无';
                   //          }
                   //      }
                   //  }
                   , {
                        field: 'weixin', title: '收入', align: 'center',
                        templet: function (d) {
                            if (d.income) {
                                return d.income;
                            } else {
                                return '无';
                            }
                        }
                    }
                    , {
                        title: '操作', width: 100, align: 'center', field: 'type',
                        templet: function (d) {
                            let html = '';
                            html += '<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit">编辑</a>';
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
                            , title: '新增实名认证'
                            , content: '{{url('system/member/user/realname/create')}}'
                            , maxmin: true
                            , area: ['1000px', '800px']
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
                if (layEvent === 'edit') { //编辑
                    layer.open({
                        type: 2
                        , title: '编辑'
                        , content: '{{url('system/member/user/extend/edit?id=')}}' + data.id
                        , maxmin: true
                        , area: ['1000px', '800px']
                        , end: function () {
                            tableIns.reload();
                        }
                    });
                }

                if (layEvent === 'weixin_verify') { //微信审核
                    layer.open({
                        type: 2
                        , title: '微信审核'
                        , content: '{{url('system/member/user/extend/edit?type=1&id=')}}' + data.id
                        , maxmin: true
                        , area: ['800px', '400px']
                        , end: function () {
                            tableIns.reload();
                        }
                    });
                }

                if (layEvent === 'qq_verify') { //微信审核
                    layer.open({
                        type: 2
                        , title: 'QQ审核'
                        , content: '{{url('system/member/user/extend/edit?type=2&id=')}}' + data.id
                        , maxmin: true
                        , area: ['800px', '400px']
                        , end: function () {
                            tableIns.reload();
                        }
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

