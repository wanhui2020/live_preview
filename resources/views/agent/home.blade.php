@extends('layouts.base')
@section('content')
    <div class="layui-fluid" >
        <fieldset class="layui-elem-field layui-field-title">
            <legend>服务商</legend>
        </fieldset>
        <div class="layui-row layui-col-space5">
            <div class="layui-col-sm3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <span class="layui-badge layui-bg-blue layuiadmin-badge">推</span>
                        推广链接
                    </div>
                    <div style="text-align: center">
                        <a class="layuiadmin-big-font" href="{{\App\Facades\BaseFacade::agent('invite_code')}}"
                           target="_blank"
                           title="点击打开">{!! \SimpleSoftwareIO\QrCode\Facades\QrCode::margin(0)->size(250)->generate(\App\Facades\BaseFacade::agent('invite_code')); !!}</a>
                        <div style="padding: 10px;">{{\App\Facades\BaseFacade::agent('invite_code')}}</div>


                    </div>
                </div>
            </div>
        </div>
        <fieldset class="layui-elem-field layui-field-title" style="position: relative">

            <legend style="position: relative">概览<button  id="Search" lay-submit lay-filter="search" style="position: absolute; right:-50px; top:-4px; color: #fff; padding: 6px 6px; border: none; background:#1E9FFF; border-radius: 4px; font-size: 16px; cursor: pointer">刷新</button></legend>


        </fieldset>

        <div class="layui-row layui-col-space5">
            <div class="layui-col-sm3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        今日新增客户
                        <span class="layui-badge layui-bg-blue layuiadmin-badge"
                              v-text="plateinfo.total_customer !== '' ?'总: '+plateinfo.total_customer+' ' : '获取中...'">--</span>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                        <span class="layuiadmin-big-font"
                              v-text="plateinfo.today_customer !== '' ? plateinfo.today_customer+' ' : '获取中...'">--</span>

                    </div>
                </div>
            </div>
            <div class="layui-col-sm3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        今日新增提现
                        <span class="layui-badge layui-bg-blue layuiadmin-badge"
                              v-text="plateinfo.total_withdraw !== '' ? '总: '+plateinfo.total_withdraw+' ' : '获取中...'">--</span>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                        <span class="layuiadmin-big-font"
                              v-text="plateinfo.today_withdraws !== '' ? plateinfo.today_withdraws+' ' : '获取中...'">--</span>

                    </div>
                </div>
            </div>
            <div class="layui-col-sm3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        今日新增充值
                        <span class="layui-badge layui-bg-blue layuiadmin-badge"
                              v-text="plateinfo.total_recharge !== '' ? '总: '+plateinfo.total_recharge+' ' : '获取中...'">--</span>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                                <span class="layuiadmin-big-font"
                                      v-text="plateinfo.today_recharges !== '' ? plateinfo.today_recharges+' ' : '获取中...'">--</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="layui-row layui-col-space5">
            <div class="layui-col-sm3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        策略冻结资金

                        <span class="layui-badge layui-bg-blue layuiadmin-badge"
                              v-text="plateinfo.total_assure !== '' ? '总: '+plateinfo.total_assure+' ' : '获取中...'">--</span>
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                                        <span class="layuiadmin-big-font"
                                              v-text="plateinfo.total_ensure !== '' ? plateinfo.total_ensure+' ' : '获取中...'">--</span>
                    </div>
                </div>
            </div>
            <div class="layui-col-sm3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <span class="layui-badge layui-bg-blue layuiadmin-badge">策</span>
                        总策略金
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                                        <span class="layuiadmin-big-font"
                                              v-text="plateinfo.total_contract !== '' ? plateinfo.total_contract+' ' : '获取中...'">--</span>
                    </div>
                </div>
            </div>
            <div class="layui-col-sm3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <span class="layui-badge layui-bg-blue layuiadmin-badge">递</span>
                        总递延费
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                                        <span class="layuiadmin-big-font"
                                              v-text="plateinfo.total_deferred !== '' ? plateinfo.total_deferred+' ' : '获取中...'">--</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space5">
            <div class="layui-col-sm3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <span class="layui-badge layui-bg-blue layuiadmin-badge">资</span>
                        客户总资产
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                       <span class="layuiadmin-big-font"
                             v-text="plateinfo.total_asset !== '' ? plateinfo.total_asset+' ' : '获取中...'">--</span>

                    </div>
                </div>
            </div>
            <div class="layui-col-sm3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <span class="layui-badge layui-bg-blue layuiadmin-badge">余</span>
                        客户资金余额
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                                        <span class="layuiadmin-big-font"
                                              v-text="plateinfo.total_balance !== '' ? plateinfo.total_balance+' ' : '获取中...'">--</span>
                    </div>
                </div>
            </div>
            <div class="layui-col-sm3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <span class="layui-badge layui-bg-blue layuiadmin-badge">提</span>
                        总提现手续费
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                                        <span class="layuiadmin-big-font"
                                              v-text="plateinfo.total_service !== '' ? plateinfo.total_service+' ' : '获取中...'">--</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space5">
            <div class="layui-col-sm3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <span class="layui-badge layui-bg-blue layuiadmin-badge">成</span>
                        总成交金额
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                                        <span class="layuiadmin-big-font"
                                              v-text="plateinfo.total_succeed !== '' ? plateinfo.total_succeed+' ' : '获取中...'">--</span>
                    </div>
                </div>
            </div>
            <div class="layui-col-sm3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <span class="layui-badge layui-bg-blue layuiadmin-badge">成</span>
                        今日成交金额
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                                        <span class="layuiadmin-big-font"
                                              v-text="plateinfo.today_succeed !== '' ? plateinfo.today_succeed+' ' : '获取中...'">--</span>
                    </div>
                </div>
            </div>
        </div>


        <div class="layui-row layui-col-space5">
            <div class="layui-col-sm3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <span class="layui-badge layui-bg-blue layuiadmin-badge">仓</span>
                        总持仓市值
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                                        <span class="layuiadmin-big-font"
                                              v-text="plateinfo.total_market !== '' ? plateinfo.total_market+' ' : '获取中...'">--</span>
                    </div>
                </div>
            </div>
            <div class="layui-col-sm3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <span class="layui-badge layui-bg-blue layuiadmin-badge">盈</span>
                        总策略盈亏
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                                        <span class="layuiadmin-big-font"
                                              v-text="plateinfo.total_contracts !== '' ? plateinfo.total_contracts+' ' : '获取中...'">--</span>
                    </div>
                </div>
            </div>
            <div class="layui-col-sm3">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <span class="layui-badge layui-bg-blue layuiadmin-badge">今</span>
                        今日策略盈亏
                    </div>
                    <div class="layui-card-body layuiadmin-card-list">
                                        <span class="layuiadmin-big-font"
                                              v-text="plateinfo.today_contracts !== '' ? plateinfo.today_contracts+' ' : '获取中...'">--</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="application/javascript">
        let vm = new Vue({
            el: '#app',
            data: {
                listen: {},
                plateinfo: {
                    today_customer: '',
                    today_recharges: '',
                    today_withdraws: '',
                    today_contracts: '',
                    today_succeed: '',
                    total_customer: '',
                    total_technology: '',
                    total_recharge: '',
                    total_withdraw: '',
                    total_contract: '',
                    total_succeed: '',
                    total_asset: '',
                    total_market: '',
                    total_assure: '',
                    total_ensure: '',
                    total_deferred: '',
                    total_balance: '',
                    total_contracts: '',
                    total_service:''
                }
            },
            created: function () {
                let self = this;
                axios.post("/agent/plateinfo")
                    .then(function (response) {
                            if (response.data.status) {
                                return self.plateinfo = response.data.data;
                            }
                        }
                    );

            }
        });
    </script>
    <script type="application/javascript">
        layui.use(['table'], function () {
            let table = layui.table, form = layui.form;
            //监听搜索
            form.on('submit(search)', function (data) {
                layer.confirm('确认要手动刷新数据吗？', {
                    btn: ['确认', '取消']
                }, function () {
                    axios.post("base/total")
                        .then(function (response) {
                                layer.closeAll();
                                if (response.data.status) {
                                    layer.msg(response.data.msg);

                                } else {
                                    layer.alert(JSON.stringify(response.data));
                                }

                            }
                        );
                });
            });
        })
    </script>
@endsection

