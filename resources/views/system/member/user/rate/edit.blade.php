@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body" pad15>
                        <div class="layui-form" lay-filter="formData">
                            <input type="hidden" name="id">
                            <div class="layui-form-item">
                                <label class="layui-form-label">费率自定义</label>
                                <div class="layui-input-inline">
                                    <select name="is_custom">
                                        <option value="0">否</option>
                                        <option value="1">是</option>
                                    </select>
                                </div>
                            </div>
                            <fieldset class="layui-elem-field layui-field-title">
                                <legend>收费标准</legend>
                            </fieldset>
                            <div class="layui-form-item">

                                <label class="layui-form-label">普通消息收费</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="text_fee" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">语音消息收费</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="voice_fee" autocomplete="off" class="layui-input">
                                </div>
                                <label class="layui-form-label">视频消息收费</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="video_fee" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            {{--<div class="layui-form-item">--}}
                                {{--<label class="layui-form-label">颜照库查看收费</label>--}}
                                {{--<div class="layui-input-inline">--}}
                                    {{--<input type="text" name="view_picture_fee" autocomplete="off"--}}
                                           {{--class="layui-input">--}}
                                {{--</div>--}}
                                {{--<label class="layui-form-label">视频库查看收费</label>--}}
                                {{--<div class="layui-input-inline">--}}
                                    {{--<input type="text" name="view_video_fee" autocomplete="off" class="layui-input">--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            <fieldset class="layui-elem-field layui-field-title">
                                <legend>主播分成标准</legend>
                            </fieldset>
                            <div class="layui-form-item">
                                <label class="layui-form-label">礼物收入分成</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="gift_rate" autocomplete="off" class="layui-input">
                                </div>
                                <label class="layui-form-label">聊天解锁分成</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="chat_rate" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">语音通话分成</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="voice_rate" autocomplete="off" class="layui-input">
                                </div>
                                <label class="layui-form-label">视频通话分成</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="video_rate" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">图片查看分成</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="view_picture_rate" autocomplete="off"
                                           class="layui-input">
                                </div>
                                <label class="layui-form-label">视频查看分成</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="view_video_rate" autocomplete="off"
                                           class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">奖励自定义</label>
                                <div class="layui-input-inline">
                                    <select name="reward_customization">
                                        <option value="0">否</option>
                                        <option value="1">是</option>
                                    </select>
                                </div>
                            </div>
                            <fieldset class="layui-elem-field layui-field-title">
                                <legend>经济人奖励标准</legend>
                            </fieldset>
                            <div class="layui-form-item">
                                <label class="layui-form-label">收入激励分成</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="middleman_income_rate" autocomplete="off"
                                           class="layui-input">
                                </div>
                                <label class="layui-form-label">充值激励分成</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="middleman_recharge_rate" autocomplete="off"
                                           class="layui-input">
                                </div>
                            </div>
                            <fieldset class="layui-elem-field layui-field-title">
                                <legend>邀请人奖励标准</legend>
                            </fieldset>
                            <div class="layui-form-item">
                                <label class="layui-form-label">收入激励分成</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="recommender_income_rate" autocomplete="off"
                                           class="layui-input">
                                </div>
                                <label class="layui-form-label">充值激励分成</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="recommender_recharge_rate" autocomplete="off"
                                           class="layui-input">
                                </div>
                            </div>
                            <fieldset class="layui-elem-field layui-field-title">
                                <legend>配置</legend>
                            </fieldset>
                            <div class="layui-form-item">
                                <label class="layui-form-label">上级收益天数</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="superior_revenue_time" autocomplete="off"
                                           class="layui-input">
                                </div>
                                <label class="layui-form-label">查看微信花费能量</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="wechat_pay_money" autocomplete="off"
                                           class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">微信查看平台分成</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="wechat_platform_share" autocomplete="off"
                                           class="layui-input">
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
            form.val("formData",{!! $data !!});
            let index = parent.layer.getFrameIndex(window.name);
            $(document).on('click', '#close', function () {
                parent.layer.close(index);
            });
            parent.layer.iframeAuto(index);
            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;
                    let index = parent.layer.getFrameIndex(window.name);
                    axios.post("{{url('system/member/user/rate/update')}}", field)
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
