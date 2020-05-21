@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">

                    <div class="layui-card-body" pad15>

                        <div class="layui-form" lay-filter="formData">
                            <div class="layui-form-item">
                                <label class="layui-form-label">会员类型</label>
                                <div class="layui-input-inline">
                                    <select name="type">
                                        <option value="0" selected="">普通</option>
                                        <option value="1">陪聊</option>
                                        <option value="2">客服</option>
                                    </select>
                                </div>

                                <label class="layui-form-label">昵称</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="nick_name" lay-verify="required" placeholder="请输入昵称"
                                           autocomplete="off"
                                           class="layui-input">
                                </div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">头像</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="head_pic" id="head_pic">
                                    @if($user->head_pic)
                                        <img src="{{url($user->head_pic)}}" style="width: 50px;height: 50px;"
                                             id="headView">
                                    @endif
                                    <button type="button" class="layui-btn" id="upHead">
                                        <i class="layui-icon">&#xe67c;</i>上传
                                    </button>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">封面</label>
                                <div class="layui-input-block">
                                    <input class="layui-input" name="cover" id="cover">
                                    @if($user->cover)
                                        <img src="{{url($user->cover)}}" style="width: 50px;height: 50px;"
                                             id="coverView">
                                    @endif
                                    <button type="button" class="layui-btn" id="upCover">
                                        <i class="layui-icon">&#xe67c;</i>上传
                                    </button>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">性别</label>
                                <div class="layui-input-inline">
                                    <select name="sex">
                                        <option value="0" selected="">男</option>
                                        <option value="1">女</option>
                                        <option value="9">未知</option>
                                    </select>
                                </div>

                                <label class="layui-form-label">推荐指数</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="hot" placeholder="请输入热门推荐指数"
                                           autocomplete="off"
                                           class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">当前魅力积分</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="charm_integral" placeholder=""
                                           autocomplete="off"
                                           class="layui-input" readonly>
                                </div>
                                <label class="layui-form-label">改变魅力积分</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="change_charm_integral" placeholder="10代表为加，-10为减"
                                           autocomplete="off"
                                           class="layui-input">
                                </div>

                            </div>
                            <div class="layui-form-item">

                                <label class="layui-form-label">当前VIP积分</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="vip_integral" placeholder=""
                                           autocomplete="off"
                                           class="layui-input" readonly>
                                </div>
                                <label class="layui-form-label">改变VIP积分</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="change_vip_integral" placeholder="10代表为加，-10为减"
                                           autocomplete="off"
                                           class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">所属经纪人</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="agent_id" placeholder="请输入所属经纪人"
                                           autocomplete="off"
                                           class="layui-input">
                                </div>

                                <label class="layui-form-label">是否经纪人</label>
                                <div class="layui-input-inline">
                                    <select name="is_middleman">
                                        <option value="0">是</option>
                                        <option value="1">否</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-form-item">
                                    <label class="layui-form-label">所属邀请人</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="parent_no" placeholder="请输入推荐人所属邀请人"
                                               class="layui-input">
                                    </div>
                                </div>
                            </div>

                            @if(count($type) > 0)
                                <div class="layui-form-item">
                                    <label class="layui-form-label">所属标签</label>
                                    <div class="layui-input-block">
                                        @foreach($type as $item)
                                            <input type="checkbox" name="userstype" title="{{$item['name']}}" value="{{$item['id']}}" @if(isset($usertype) && in_array($item['id'], $usertype)) checked="checked" @endif><div class="layui-unselect layui-form-checkbox layui-form-checked"><span>{{$item['name']}}</span><i class="layui-icon layui-icon-ok"></i></div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="layui-form-item">
                                <label class="layui-form-label">在线状态</label>
                                <div class="layui-input-inline">
                                    <select name="online_status">
                                        <option value="">在线状态</option>
                                        <option value="0">在线</option>
                                        <option value="1">离线</option>
                                        <option value="2">休眠</option>
                                        <option value="9">未知</option>
                                    </select>
                                </div>
                            </div>

                            {{--<div class="layui-form-item">--}}
                                {{--<label class="layui-form-label">IM状态</label>--}}
                                {{--<div class="layui-input-inline">--}}
                                    {{--<select name="im_status">--}}
                                        {{--<option value="">IM状态</option>--}}
                                        {{--<option value="0">IM在线</option>--}}
                                        {{--<option value="1">IM离线</option>--}}
                                        {{--<option value="2">IM休眠</option>--}}
                                        {{--<option value="9">IM未知</option>--}}
                                    {{--</select>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            {{--<div class="layui-form-item">--}}
                                {{--<label class="layui-form-label">活跃状态</label>--}}
                                {{--<div class="layui-input-inline">--}}
                                    {{--<select name="live_status">--}}
                                        {{--<option value="">IM状态</option>--}}
                                        {{--<option value="0">空闲</option>--}}
                                        {{--<option value="1">离线</option>--}}
                                        {{--<option value="2">忙碌</option>--}}
                                        {{--<option value="9">勿扰</option>--}}
                                    {{--</select>--}}
                                {{--</div>--}}
                            {{--</div>--}}


                            <input type="hidden" name="id">
                            <div class="layui-form-item ">
                                <div class="layui-input-block">
                                    <input type="button" class="layui-btn" lay-submit lay-filter="formSubmit"
                                           value="确认">
                                    <input type="button" class="layui-btn layui-btn-primary " id="close" value="关闭">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script type="application/javascript">
        layui.use(['form', 'upload'], function () {
            let $ = layui.$
                , form = layui.form
                , upload = layui.upload;
            let user ={!! $user !!};
            form.val("formData", user);
            // $('#head_pic').val(user.head_pic);
            // $('#headView').attr('src', user.fill_head_pic);
            let index = parent.layer.getFrameIndex(window.name);
            $(document).on('click', '#close', function () {
                parent.layer.close(index);
            });
            parent.layer.iframeAuto(index);
            //普通图片上传
            upload.render({
                elem: '#upHead'
                , url: '{{url('common/put')}}' //上传接口
                , accept: 'file' //普通文件
                , before: function (obj) {
                    layui.layer.load();
                }
                , done: function (res) {
                    $('#head_pic').val(res.data);
                    $('#headView').attr('src', res.src);
                    layer.closeAll('loading');
                    layer.msg('上传成功')
                }
                , error: function () {
                    layer.alert('上传失败')
                    layer.closeAll('loading');
                }
            });
            //普通图片上传
            upload.render({
                elem: '#upCover'
                , url: '{{url('common/put')}}' //上传接口
                , accept: 'file' //普通文件
                , before: function (obj) {
                    layui.layer.load();
                }
                , done: function (res) {
                    $('#cover').val(res.data);
                    $('#coverView').attr('src', res.src);
                    layer.closeAll('loading');
                    layer.msg('上传成功')
                }
                , error: function () {
                    layer.alert('上传失败');
                    layer.closeAll('loading');
                }
            });
            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;
                    var arr = new Array();
                    $("input:checkbox[name='userstype']:checked").each(function(i){
                        arr[i] = $(this).val();
                    });
                    data.field.type_id = arr.join(",");//将数组合并成字符串
                    delete field.userstype;
                    let index = parent.layer.getFrameIndex(window.name);
                    axios.post("{{url('system/member/user/update')}}", field)
                        .then(function (response) {
                            if (response.data.status) {
                                parent.layer.msg(response.data.msg);
                                return parent.layer.close(index);
                            }
                            return layer.msg(response.data.msg);
                        });
                }
            );
        });

    </script>
@endsection
