@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">env配置</div>

                    <div class="layui-card-body" pad15>

                        <div class="layui-form" lay-filter="formData" wid110>
                            <fieldset class="layui-elem-field layui-field-title">
                                <legend>app配置</legend>
                                <div class="layui-field-box">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">app名称</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="APP_NAME"
                                                   lay-verify="required"
                                                   placeholder="app名称"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">APP_KEY</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="APP_KEY"
                                                   lay-verify="required"
                                                   placeholder="APP_KEY"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                    <div class="layui-inline">
                                        <label class="layui-form-label">app链接</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="APP_URL"
                                                   lay-verify="required"
                                                   placeholder="app链接"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-field-box">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">APP_DEBUG</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="APP_DEBUG"
                                                   lay-verify="required"
                                                   placeholder="APP_DEBUG"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                </div>

                            </fieldset>
                            <fieldset class="layui-elem-field layui-field-title">
                                <legend>数据库</legend>
                                <div class="layui-field-box">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">DB_CONNECTION</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="DB_CONNECTION"
                                                   lay-verify="required"
                                                   placeholder="DB_CONNECTION"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">DB_HOST</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="DB_HOST"
                                                   lay-verify="required"
                                                   placeholder="DB_HOST"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                    <div class="layui-inline">
                                        <label class="layui-form-label">DB_PORT</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="DB_PORT"
                                                   lay-verify="required"
                                                   placeholder="DB_PORT"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-field-box">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">DB_DATABASE</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="DB_DATABASE"
                                                   lay-verify="required"
                                                   placeholder="DB_DATABASE"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">DB_USERNAME</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="DB_USERNAME"
                                                   lay-verify="required"
                                                   placeholder="DB_USERNAME"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                    <div class="layui-inline">
                                        <label class="layui-form-label">DB_PASSWORD</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="DB_PASSWORD"
                                                   lay-verify="required"
                                                   placeholder="DB_PASSWORD"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                </div>

                            </fieldset>
                            <fieldset class="layui-elem-field layui-field-title">
                                <legend>平台设置</legend>
                                <div class="layui-field-box">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">平台兑换汇率</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="PLATFORM_EXCHANGE_RATE"
                                                   lay-verify="required"
                                                   placeholder="平台兑换汇率"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">收益结算方式</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="PLATFORM_WAY"
                                                   lay-verify="required"
                                                   placeholder="收益结算方式"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                </div>

                            </fieldset>
                            <fieldset class="layui-elem-field layui-field-title">
                                <legend>阿里API</legend>
                                <div class="layui-field-box">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">ALI_ACCESS_KEY_ID</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="ALI_ACCESS_KEY_ID"
                                                   lay-verify="required"
                                                   placeholder="ALI_ACCESS_KEY_ID"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">ALI_ACCESS_KEY_SECRET</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="ALI_ACCESS_KEY_SECRET"
                                                   lay-verify="required"
                                                   placeholder="ALI_ACCESS_KEY_SECRET"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                    <div class="layui-inline">
                                        <label class="layui-form-label">ALI_PUSH_ANDROID</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="ALI_PUSH_ANDROID"
                                                   lay-verify="required"
                                                   placeholder="ALI_PUSH_ANDROID"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-field-box">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">ALI_PUSH_IOS</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="ALI_PUSH_IOS"
                                                   lay-verify="required"
                                                   placeholder="ALI_PUSH_IOS"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                </div>

                            </fieldset>
                            <fieldset class="layui-elem-field layui-field-title">
                                <legend>腾讯API</legend>
                                <div class="layui-field-box">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">TENCENT_API_APPID</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="TENCENT_API_APPID"
                                                   lay-verify="required"
                                                   placeholder="TENCENT_API_APPID"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">TENCENT_API_SECRET</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="TENCENT_API_SECRET"
                                                   lay-verify="required"
                                                   placeholder="TENCENT_API_SECRET"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                </div>

                            </fieldset>
                            <fieldset class="layui-elem-field layui-field-title">
                                <legend>腾讯IM</legend>
                                <div class="layui-field-box">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">TENCENT_IM_APPID</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="TENCENT_IM_APPID"
                                                   lay-verify="required"
                                                   placeholder="TENCENT_IM_APPID"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">TENCENT_IM_KEY</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="TENCENT_IM_KEY"
                                                   lay-verify="required"
                                                   placeholder="TENCENT_IM_KEY"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                    <div class="layui-inline">
                                        <label class="layui-form-label">TENCENT_IM_IDENTIFIER</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="TENCENT_IM_IDENTIFIER"
                                                   lay-verify="required"
                                                   placeholder="TENCENT_IM_IDENTIFIER"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-field-box">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">TENCENT_IM_TOKEN</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="TENCENT_IM_TOKEN"
                                                   lay-verify="required"
                                                   placeholder="TENCENT_IM_TOKEN"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                </div>

                            </fieldset>
                            <fieldset class="layui-elem-field layui-field-title">
                                <legend>OSS文件服务</legend>
                                <div class="layui-field-box">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">OSS_ACCESS_KEY_ID</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="OSS_ACCESS_KEY_ID"
                                                   lay-verify="required"
                                                   placeholder="OSS_ACCESS_KEY_ID"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">OSS_ACCESS_KEY_SECRET</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="OSS_ACCESS_KEY_SECRET"
                                                   lay-verify="required"
                                                   placeholder="OSS_ACCESS_KEY_SECRET"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                    <div class="layui-inline">
                                        <label class="layui-form-label">OSS_URL</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="OSS_URL"
                                                   lay-verify="required"
                                                   placeholder="OSS_URL"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-field-box">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">OSS_BUCKET</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="OSS_BUCKET"
                                                   lay-verify="required"
                                                   placeholder="OSS_BUCKET"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">OSS_DIRECTORY</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="OSS_DIRECTORY"
                                                   lay-verify="required"
                                                   placeholder="OSS_DIRECTORY"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                </div>

                            </fieldset>
                            <fieldset class="layui-elem-field layui-field-title">
                                <legend>人脸验证</legend>
                                <div class="layui-field-box">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">ALIYUN_FACE_SCENE</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="ALIYUN_FACE_SCENE"
                                                   lay-verify="required"
                                                   placeholder="ALIYUN_FACE_SCENE"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">ALIYUN_FACE_KEY_ID</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="ALIYUN_FACE_KEY_ID"
                                                   lay-verify="required"
                                                   placeholder="ALIYUN_FACE_KEY_ID"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                    <div class="layui-inline">
                                        <label class="layui-form-label">ALIYUN_FACE_KEY_SECRET</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="ALIYUN_FACE_KEY_SECRET"
                                                   lay-verify="required"
                                                   placeholder="ALIYUN_FACE_KEY_SECRET"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                </div>

                            </fieldset>
                            <fieldset class="layui-elem-field layui-field-title">
                                <legend>短信</legend>
                                <div class="layui-field-box">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">RISK_URL</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="RISK_URL"
                                                   lay-verify="required"
                                                   placeholder="RISK_URL"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">RISK_TOKEN</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="RISK_TOKEN"
                                                   lay-verify="required"
                                                   placeholder="RISK_TOKEN"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                    <div class="layui-inline">
                                        <label class="layui-form-label">RISK_SECRET_KTY</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="RISK_SECRET_KTY"
                                                   lay-verify="required"
                                                   placeholder="RISK_SECRET_KTY"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-field-box">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">RISK_SMS_SIGN_ID</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="RISK_SMS_SIGN_ID"
                                                   lay-verify="required"
                                                   placeholder="RISK_SMS_SIGN_ID"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                </div>

                            </fieldset>
                            <fieldset class="layui-elem-field layui-field-title">
                                <legend>公众号</legend>
                                <div class="layui-field-box">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">WECHAT_OFFICIAL_ACCOUNT_APPID</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="WECHAT_OFFICIAL_ACCOUNT_APPID"
                                                   lay-verify="required"
                                                   placeholder="WECHAT_OFFICIAL_ACCOUNT_APPID"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">WECHAT_OFFICIAL_ACCOUNT_SECRET</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="WECHAT_OFFICIAL_ACCOUNT_SECRET"
                                                   lay-verify="required"
                                                   placeholder="WECHAT_OFFICIAL_ACCOUNT_SECRET"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                    <div class="layui-inline">
                                        <label class="layui-form-label">WECHAT_OFFICIAL_ACCOUNT_TOKEN</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="WECHAT_OFFICIAL_ACCOUNT_TOKEN"
                                                   lay-verify="required"
                                                   placeholder="WECHAT_OFFICIAL_ACCOUNT_TOKEN"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-field-box">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">WECHAT_OFFICIAL_ACCOUNT_AES_KEY</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="WECHAT_OFFICIAL_ACCOUNT_AES_KEY"
                                                   lay-verify="required"
                                                   placeholder="WECHAT_OFFICIAL_ACCOUNT_AES_KEY"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                </div>

                            </fieldset>


                            <div class="layui-form-item ">
                                <div class="layui-input-block">
                                    <input type="button" class="layui-btn" lay-submit lay-filter="formSubmit"
                                           value="确认">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .layui-form-label {
            width: 200px !important;
        }
    </style>
@stop

@section('script')
    <script type="application/javascript">
        layui.use(['form', 'upload', 'laydate'], function () {
            let $ = layui.$
                , form = layui.form
                , laydate = layui.laydate
                , upload = layui.upload;
            form.val("formData",{!! $config !!});

            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;
                    axios.post("{{url('system/platform/env/update')}}", field)
                        .then(function (response) {
                            if (response.data.status) {
                                parent.layer.msg(response.data.msg);
                                return window.location.reload();
                            }
                            return layer.msg(response.data.msg);
                        });
                }
            );

        });

    </script>
@endsection
