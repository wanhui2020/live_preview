@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">平台参数</div>

                    <div class="layui-card-body" pad15>

                        <div class="layui-form" lay-filter="formData" wid110>
                            <fieldset class="layui-elem-field layui-field-title">
                                <legend>平台参数</legend>
                                <div class="layui-field-box">
                                    <div class="layui-form-item">

                                        <div class="layui-inline">
                                            <label class="layui-form-label">能量兑换比例</label>
                                            <div class="layui-form-mid">
                                                {{env('PLATFORM_EXCHANGE_RATE')}}
                                            </div>
                                        </div>
                                        <div class="layui-inline">
                                            <label class="layui-form-label">收益结算方式</label>
                                            <div class="layui-form-mid">
                                                {{env('PLATFORM_WAY')==0?'能量':'金币'}}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="layui-elem-field layui-field-title">
                                <legend>系统参数</legend>
                                <div class="layui-field-box">
                                    <div class="layui-form-item">

                                        <div class="layui-inline">
                                            <label class="layui-form-label">现金名称</label>
                                            <div class="layui-input-inline">
                                                <input type="text" name="cash_name"
                                                       lay-verify="required"
                                                       placeholder="现金名称"
                                                       autocomplete="off" class="layui-input">
                                            </div>
                                        </div>
                                        <div class="layui-inline">
                                            <label class="layui-form-label">代币名称</label>
                                            <div class="layui-input-inline">
                                                <input type="text" name="gold_name"
                                                       lay-verify="required"
                                                       placeholder="代币名称"
                                                       autocomplete="off" class="layui-input">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="layui-field-box">
                                    <div class="layui-form-item">

                                        <div class="layui-inline">
                                            <label class="layui-form-label">用户的最低年龄</label>
                                            <div class="layui-input-inline">
                                                <input type="number" name="user_age"
                                                       lay-verify="required"
                                                       placeholder="用户的最低年龄"
                                                       autocomplete="off" class="layui-input">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="layui-elem-field layui-field-title">
                                <legend>审核权限</legend>
                                <div class="layui-field-box">
                                    <div class="layui-form-item">
                                        <div class="layui-inline">
                                            <label class="layui-form-label">文本审核</label>
                                            <div class="layui-input-inline">
                                                <select name="platform_text_audit">
                                                    <option value="0">人工</option>
                                                    <option value="1">系统</option>
                                                    <option value="1">关闭</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="layui-inline">
                                            <label class="layui-form-label">图片审核方式</label>
                                            <div class="layui-input-inline">
                                                <select name="platform_image_audit">
                                                    <option value="0">人工</option>
                                                    <option value="1">系统</option>
                                                    <option value="1">关闭</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="layui-inline">
                                            <label class="layui-form-label">视频审核方式</label>
                                            <div class="layui-input-inline">
                                                <select name="platform_video_audit">
                                                    <option value="0">人工</option>
                                                    <option value="1">系统</option>
                                                    <option value="1">关闭</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <div class="layui-inline">
                                            <label class="layui-form-label">头像审核</label>
                                            <div class="layui-input-inline">
                                                <select name="headpic_audit">
                                                    <option value="0">打开</option>
                                                    <option value="1">关闭</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="layui-inline">
                                            <label class="layui-form-label">图片审核</label>
                                            <div class="layui-input-inline">
                                                <select name="picture_audit">
                                                    <option value="0">打开</option>
                                                    <option value="1">关闭</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="layui-inline">
                                            <label class="layui-form-label">视频审核</label>
                                            <div class="layui-input-inline">
                                                <select name="video_audit">
                                                    <option value="0">打开</option>
                                                    <option value="1">关闭</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <div class="layui-inline">
                                            <label class="layui-form-label">昵称审核</label>
                                            <div class="layui-input-inline">
                                                <select name="nickname_audit">
                                                    <option value="0">打开</option>
                                                    <option value="1">关闭</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="layui-inline">
                                            <label class="layui-form-label">签名审核</label>
                                            <div class="layui-input-inline">
                                                <select name="signature_audit">
                                                    <option value="0">打开</option>
                                                    <option value="1">关闭</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="layui-inline">
                                            <label class="layui-form-label">格言审核</label>
                                            <div class="layui-input-inline">
                                                <select name="aphorism_audit">
                                                    <option value="0">打开</option>
                                                    <option value="1">关闭</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <div class="layui-inline">
                                            <label class="layui-form-label">聊天审核</label>
                                            <div class="layui-input-inline">
                                                <select name="chat_audit">
                                                    <option value="0">打开</option>
                                                    <option value="1">关闭</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="layui-form-item">
                                        <div class="layui-inline">
                                            <label class="layui-form-label">实名审核</label>
                                            <div class="layui-input-inline">
                                                <select name="realname_audit">
                                                    <option value="0">打开</option>
                                                    <option value="1">关闭</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="layui-inline">
                                            <label class="layui-form-label">自拍认证</label>
                                            <div class="layui-input-inline">
                                                <select name="self_sex">
                                                    <option value="0">不限</option>
                                                    <option value="1">男</option>
                                                    <option value="2">女</option>
                                                </select>
                                            </div>
                                        </div>
                                        {{--<div class="layui-inline">--}}
                                            {{--<label class="layui-form-label">自拍审核</label>--}}
                                            {{--<div class="layui-input-inline">--}}
                                                {{--<select name="selfie_audit">--}}
                                                    {{--<option value="0">打开</option>--}}
                                                    {{--<option value="1">关闭</option>--}}
                                                {{--</select>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="layui-inline">--}}
                                            {{--<label class="layui-form-label">自拍前实名</label>--}}
                                            {{--<div class="layui-input-inline">--}}
                                                {{--<select name="selfie_realname">--}}
                                                    {{--<option value="0">需要</option>--}}
                                                    {{--<option value="1">不需要</option>--}}
                                                {{--</select>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="layui-elem-field layui-field-title">
                                <legend>解锁时长</legend>
                                <div class="layui-field-box">
                                    <div class="layui-form-item">

                                        {{--<div class="layui-inline">--}}
                                            {{--<label class="layui-form-label">聊天解锁时长</label>--}}
                                            {{--<div class="layui-input-inline">--}}
                                                {{--<input type="number" name="chat_unlock_duration"--}}
                                                       {{--lay-verify="required|number|minus|moneyMinus"--}}
                                                       {{--placeholder="聊天解锁时长(天)"--}}
                                                       {{--autocomplete="off" class="layui-input">--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        <div class="layui-inline">
                                            <label class="layui-form-label">资源查看解锁时长</label>
                                            <div class="layui-input-inline">
                                                <input type="number" name="view_unlock_duration"
                                                       lay-verify="required|number|moneyMinus"
                                                       placeholder="资源查看解锁时长(天)"
                                                       autocomplete="off" class="layui-input">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </fieldset>
                            <fieldset class="layui-elem-field layui-field-title">
                                <legend>运营推广</legend>
                                <div class="layui-field-box">
                                    <div class="layui-form-item">
                                        <div class="layui-inline">
                                            <label class="layui-form-label">在线机器人</label>
                                            <div class="layui-input-inline">
                                                <input type="number" name="online_robot"
                                                       lay-verify="required|number"
                                                       placeholder="在线机器人数量"
                                                       autocomplete="off" class="layui-input">
                                            </div>
                                        </div>

                                        <div class="layui-inline">
                                            <label class="layui-form-label">签到奖励代币</label>
                                            <div class="layui-input-inline">
                                                <input type="number" name="signin_award"
                                                       lay-verify="required|number|moneyMinus"
                                                       placeholder="签到奖励代币"
                                                       autocomplete="off" class="layui-input">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <div class="layui-inline">
                                            <label class="layui-form-label">(邀请人)邀请充值奖励分成</label>
                                            <div class="layui-input-inline">
                                                <input type="number" name="recommender_recharge_rate"
                                                       lay-verify="required|number|moneyMinus"
                                                       placeholder="邀请充值奖励分成"
                                                       autocomplete="off" class="layui-input">
                                            </div>
                                        </div>
                                        <div class="layui-inline">
                                            <label class="layui-form-label">(邀请人)邀请主播收益分成</label>
                                            <div class="layui-input-inline">
                                                <input type="number" name="recommender_income_rate"
                                                       lay-verify="required|number|moneyMinus"
                                                       placeholder="邀请消费收入分成"
                                                       autocomplete="off" class="layui-input">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="layui-form-item">
                                        <div class="layui-inline">
                                            <label class="layui-form-label">(经纪人)邀请充值奖励分成</label>
                                            <div class="layui-input-inline">
                                                <input type="number" name="invite_recharge_rate"
                                                       lay-verify="required|number|moneyMinus"
                                                       placeholder="邀请充值奖励比"
                                                       autocomplete="off" class="layui-input">
                                            </div>
                                        </div>
                                        <div class="layui-inline">
                                            <label class="layui-form-label">（经纪人）邀请主播收益分成</label>
                                            <div class="layui-input-inline">
                                                <input type="number" name="invite_consumption_rate"
                                                       lay-verify="required|number|moneyMinus"
                                                       placeholder="邀请消费收入分成"
                                                       autocomplete="off" class="layui-input">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="layui-form-item">
                                        <div class="layui-inline">
                                            <label class="layui-form-label">邀请注册奖励代币</label>
                                            <div class="layui-input-inline">
                                                <input type="number" name="invite_register_award"
                                                       lay-verify="required|number|moneyMinus"
                                                       placeholder="邀请注册奖励代币"
                                                       autocomplete="off" class="layui-input">
                                            </div>
                                        </div>
                                        <div class="layui-inline">
                                            <label class="layui-form-label">注册获得奖励代币</label>
                                            <div class="layui-input-inline">
                                                <input type="number" name="register_energy"
                                                       lay-verify="required|number|moneyMinus"
                                                       placeholder="注册获得奖励代币"
                                                       autocomplete="off" class="layui-input">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="layui-form-item">
                                        <div class="layui-inline">
                                            <label class="layui-form-label">邀请注册奖励现金</label>
                                            <div class="layui-input-inline">
                                                <input type="number" name="invite_cash"
                                                       lay-verify="required|number|moneyMinus"
                                                       placeholder="邀请注册奖励现金"
                                                       autocomplete="off" class="layui-input">
                                            </div>
                                        </div>
                                        <div class="layui-inline">
                                            <label class="layui-form-label">注册获得奖励现金</label>
                                            <div class="layui-input-inline">
                                                <input type="number" name="register_cash"
                                                       lay-verify="required|number|moneyMinus"
                                                       placeholder="注册获得奖励现金"
                                                       autocomplete="off" class="layui-input">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="layui-form-item">
                                        <div class="layui-inline">
                                            <label class="layui-form-label">上级收益时间(天)</label>
                                            <div class="layui-input-inline">
                                                <input type="number" name="superior_revenue_time"
                                                       lay-verify="required|number|moneyMinus"
                                                       placeholder="上级收益时间"
                                                       autocomplete="off" class="layui-input">
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </fieldset>
                            <fieldset class="layui-elem-field layui-field-title">
                                <legend>等级评选</legend>
                                <div class="layui-form-item">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">魅力统计周期</label>
                                        <div class="layui-input-inline">
                                            <input type="number" name="charm_period"
                                                   lay-verify="required|number"
                                                   placeholder="魅力统计周期"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">电话未接/挂断魅力减少</label>
                                        <div class="layui-input-inline">
                                            <input type="number" name="unanswered"
                                                   lay-verify="required|number"
                                                   placeholder="电话未接/挂断"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                </div>
                                <div class="layui-form-item">

                                    <div class="layui-inline">
                                        <label class="layui-form-label">魅力在线时长权重</label>
                                        <div class="layui-input-inline">
                                            <input type="number" name="charm_online_duration_weight"
                                                   lay-verify="required|number"
                                                   placeholder="魅力在线时长权重"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">魅力被叫通话时长权重</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="charm_totalk_duration_weight"
                                                   lay-verify="required|number"
                                                   placeholder="日最大提现次数"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                </div>

                                <div class="layui-form-item">

                                    <div class="layui-inline">
                                        <label class="layui-form-label">魅力接收礼物能量数权重</label>
                                        <div class="layui-input-inline">
                                            <input type="number" name="charm_togift_gold_weight"
                                                   lay-verify="required|number"
                                                   placeholder="魅力在线时长权重"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">魅力接收点赞数权重</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="charm_tolike_count_weight"
                                                   lay-verify="required|number"
                                                   placeholder="日最大提现次数"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                </div>
                                <br>
                                <div class="layui-form-item">

                                    <div class="layui-inline">
                                        <label class="layui-form-label">VIP统计周期</label>
                                        <div class="layui-input-inline">
                                            <input type="number" name="vip_period"
                                                   lay-verify="required|number"
                                                   placeholder="VIP统计周期0不限"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>


                                </div>
                                <div class="layui-form-item">

                                    <div class="layui-inline">
                                        <label class="layui-form-label">VIP在线时长权重</label>
                                        <div class="layui-input-inline">
                                            <input type="number" name="vip_online_duration_weight"
                                                   lay-verify="required|number"
                                                   placeholder="VIP在线时长权重"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">VIP主叫通话时长权重</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="vip_fromtalk_duration_weight"
                                                   lay-verify="required|number"
                                                   placeholder="日最大提现次数"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                </div>
                                <div class="layui-form-item">

                                    <div class="layui-inline">
                                        <label class="layui-form-label">VIP赠送礼物能量数权重</label>
                                        <div class="layui-input-inline">
                                            <input type="number" name="vip_fromgift_gold_weight"
                                                   lay-verify="required|number"
                                                   placeholder="VIP赠送礼物能量数权重"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">VIP点赞数权重</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="vip_fromlike_count_weight"
                                                   lay-verify="required|number"
                                                   placeholder="日最大提现次数"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                </div>
                                <div class="layui-form-item">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">VIP充值合计权重</label>
                                        <div class="layui-input-inline">
                                            <input type="number" name="vip_recharge_total_weight"
                                                   lay-verify="required|number"
                                                   placeholder="VIP充值合计权重"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>


                                </div>
                            </fieldset>
                            <fieldset class="layui-elem-field layui-field-title">
                                <legend>支付参数</legend>
                                <div class="layui-form-item">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">最小提现金额</label>
                                        <div class="layui-input-inline">
                                            <input type="number" name="withdraw_min"
                                                   lay-verify="required|number|moneyMinus"
                                                   placeholder="最小提现金额"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">最大提现金额</label>
                                        <div class="layui-input-inline">
                                            <input type="number" name="withdraw_max"
                                                   lay-verify="required|number|moneyMinus"
                                                   placeholder="最大提现金额"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                </div>
                                <div class="layui-form-item">

                                    <div class="layui-inline">
                                        <label class="layui-form-label">日最大提现金额</label>
                                        <div class="layui-input-inline">
                                            <input type="number" name="withdraw_day_max"
                                                   lay-verify="required|number|moneyMinus"
                                                   placeholder="日最大提现金额"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">日最大提现次数</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="withdraw_day_count"
                                                   lay-verify="required|number|moneyMinus"
                                                   placeholder="日最大提现次数"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                </div>
                                <div class="layui-form-item">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">提现手续费费率</label>
                                        <div class="layui-input-inline">
                                            <input type="number" name="withdraw_rate"
                                                   lay-verify="required|number|moneyMinus"
                                                   placeholder="提现手续费费率"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">充值手续费费率</label>
                                        <div class="layui-input-inline">
                                            <input type="number" name="recharge_rate"
                                                   lay-verify="required|number|moneyMinus"
                                                   placeholder="充值手续费费率"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                </div>
                                <div class="layui-form-item">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">最小充值金额</label>
                                        <div class="layui-input-inline">
                                            <input type="number" name="recharge_min"
                                                   lay-verify="required|number|moneyMinus"
                                                   placeholder="最小充值金额"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">最大充值金额</label>
                                        <div class="layui-input-inline">
                                            <input type="number" name="recharge_max"
                                                   lay-verify="required|number|moneyMinus"
                                                   placeholder="最大充值金额"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                </div>
                            </fieldset>
                            <fieldset class="layui-elem-field layui-field-title">
                                <legend>设置参数</legend>
                                <div class="layui-form-item">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">是否开启微信查看支付</label>
                                        <div class="layui-input-inline">
                                            <select name="is_wechat_pay">
                                                <option value="0">是</option>
                                                <option value="1">否</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="layui-inline">
                                        <label class="layui-form-label">微信查看花费能量</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="wechat_pay_money"
                                                   lay-verify="required|number"
                                                   placeholder="微信查看花费能量"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                    <div class="layui-inline">
                                        <label class="layui-form-label">微信查看平台分成</label>
                                        <div class="layui-input-inline">
                                            <input type="text" name="wechat_platform_share"
                                                   lay-verify="required|number"
                                                   placeholder="微信查看平台分成"
                                                   autocomplete="off" class="layui-input">
                                        </div>
                                    </div>

                                </div>
                                <div class="layui-form-item">

                                    <div class="layui-inline">
                                        <label class="layui-form-label">登录方式</label>
                                        <div class="layui-input-inline">
                                            <select name="login_mode">
                                                <option value="0">全部</option>
                                                <option value="1">手机号</option>
                                                <option value="2">微信号</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="layui-inline">
                                        <label class="layui-form-label">是否开启扣主叫费用</label>
                                        <div class="layui-input-inline">
                                            <select name="is_deduction_calling_fee">
                                                <option value="0">是</option>
                                                <option value="1">否</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="layui-inline">
                                        <label class="layui-form-label">认证是否绑定手机</label>
                                        <div class="layui-input-inline">
                                            <select name="authentication_binding_phone">
                                                <option value="0">是</option>
                                                <option value="1">否</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="layui-form-item">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">主播不能给主播发消息/用户不能给用户发消息</label>
                                        <div class="layui-input-inline">
                                            <select name="private_chat">
                                                <option value="0">允许</option>
                                                <option value="1">不允许</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="layui-inline">
                                        <label class="layui-form-label">是否允许录屏</label>
                                        <div class="layui-input-inline">
                                            <select name="is_screencap">
                                                <option value="0">是</option>
                                                <option value="1">否</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">公告显示</label>
                                        <div class="layui-input-inline">
                                            <select name="notice_display">
                                                <option value="0">是</option>
                                                <option value="1">否</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-form-item">

                                    <div class="layui-inline">
                                        <label class="layui-form-label">同意协议</label>
                                        <div class="layui-input-inline">
                                            <select name="notice_agreement">
                                                <option value="0">是</option>
                                                <option value="1">否</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </fieldset>

                            <input type="hidden" name="id">
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
            console.log({!! $config !!});
            //时间选择器
            laydate.render({
                elem: '#begin_time'
                , type: 'time'
            });
            laydate.render({
                elem: '#end_time'
                , type: 'time'
            });

            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;
                    axios.post("{{url('system/platform/config/update')}}", field)
                        .then(function (response) {
                            if (response.data.status) {
                                parent.layer.msg(response.data.msg);
                                return window.location.reload();
                            }
                            return layer.msg(response.data.msg);
                        });
                }
            );

            form.verify({ // 验证金额不能为负
                moneyMinus: function (value, item) {
                    if (value < 0) {
                        return '金额不能为负';
                    }
                },
                minus: function (value, item) {
                    if (value < 0) {
                        return '不能为负';
                    }
                },
                Numbers: function (value, item) {
                    let role = /^(?:(?:(?:[1-9]\d{0,2}(?:,\d{3})*)|[1-9]\d*|0))?$/;
                    if (!role.test(value)) {
                        return '请输入正确的数字，不能为负或小数。';
                    }
                }
            });
        });

    </script>
@endsection
