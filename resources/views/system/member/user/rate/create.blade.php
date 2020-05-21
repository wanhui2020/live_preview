@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">

                    <div class="layui-card-body" pad15>

                        <div class="layui-form" lay-filter="">
                            <div class="layui-form-item">
                                <label class="layui-form-label">所属会员</label>
                                <div class="layui-input-inline">
                                    <select name="member_id" id="member_id"  lay-filter="member_id" lay-verify="required" lay-search>
                                        <option value="0"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">礼物平台分成占比</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="gift_rate"  autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">聊天解锁分成占比</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="chat_rate"  autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">语音通话消费分成占比</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="voice_rate"  autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">视频通话消费分成占比</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="video_rate"  autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">邀请充值奖励比</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="invite_recharge_rate"  autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">邀请消费奖励比</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="invite_consumption_rate"  autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">邀请注册奖励元</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="invite_register_award"  autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">普通消息收费</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="text_fee"  autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">颜照库收费</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="view_picture_fee"  autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">视频库收费</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="view_video_fee"  autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">语音消息收费</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="voice_fee"  autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">视频消息收费</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="video_fee"  autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-inline">
                                    <label class="layui-form-label">图片查看分成占比</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="view_picture_rate"  autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-inline">
                                    <label class="layui-form-label">视频查看分成占比</label>
                                    <div class="layui-input-inline">
                                        <input type="text" name="view_video_rate"  autocomplete="off" class="layui-input">
                                    </div>
                                </div>
                            </div>
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
            let index = parent.layer.getFrameIndex(window.name);
            $(document).on('click', '#close', function () {
                parent.layer.close(index);
            });
            parent.layer.iframeAuto(index);
            axios.all([
                axios.post("/system/member/user/lists", {size: 1000}),
            ])
                .then(axios.spread(function (agentResp) {
                    if (agentResp.data.status) {
                        agentResp.data.data.forEach(function (value, index, array) {
                            $('#member_id').append("<option value='" + value.id + "'>" + value.nick_name + "</option>");
                        });
                    }
                    form.render('select');
                }));
            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;
                    axios.post("{{url('system/member/user/rate/store')}}", field)
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
@stop
