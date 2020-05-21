@extends('layouts.base')

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header layui-bg-blue">
                        <div class="layui-row">
                            <div class="layui-col-md6">
                                订单详情({{$data->type==0?'买入':'卖出'}})
                            </div>
                            <div class="layui-col-md6 text-right">
                                订单状态： @if($data->order_status==0)完成@endif
                                @if($data->order_status==1)<span class="layui-badge">取消</span>@endif
                                @if($data->order_status==2)<span class="layui-badge">支付中</span>@endif
                                @if($data->order_status==3)<span class="layui-badge">已支付</span>@endif
                                @if($data->order_status==4)<span class="layui-badge">未到账</span>@endif
                                @if($data->order_status==5)<span class="layui-badge">已退款</span>@endif
                                @if($data->order_status==9)<span class="layui-badge">待接单</span>@endif
                            </div>
                        </div>
                    </div>
                    <div class="layui-card-body p-3 " style=" line-height: 40px;">
                        <div class="layui-row">
                            <div class="layui-col-md8 border-right pr-3">
                                <div class="layui-row">
                                    <div class="layui-col-md6">
                                        <strong>订单基本信息</strong>
                                    </div>
                                    <div class="layui-col-md6 text-right">
                                        @if(isset($data->out_order))
                                            外部单号：{{$data->out_order}}
                                        @endif
                                    </div>
                                </div>
                                <hr>
                                <div class="layui-row">
                                    <div class="layui-col-md6">
                                        订单编号：{{$data->no}}
                                    </div>
                                    <div class="layui-col-md6">
                                        委托编号：{{$data->entrust->no}}
                                    </div>
                                </div>
                                <div class="layui-row">
                                    <div class="layui-col-md6">
                                        代币名称：{{$data->currency->name}}-{{$data->currency->code}}
                                    </div>
                                    <div class="layui-col-md6">
                                        法币名称：{{$data->legal->name}}-{{$data->legal->code}}
                                    </div>
                                </div>
                                <div class="layui-row">
                                    <div class="layui-col-md6">
                                        数量：{{$data->quantity}}
                                    </div>
                                    <div class="layui-col-md6">
                                        实到：{{$data->received_quantity}}
                                    </div>
                                </div>
                                <strong>客户信息</strong>
                                <hr>
                                <div class="layui-row">
                                    @isset($data->customer_no)
                                        <div class="layui-col-md6">
                                            客户编号：{{$data->customer_no}}
                                        </div>
                                    @endif
                                    @isset($data->pay_bank_mobile)
                                        <div class="layui-col-md6">
                                            付款人手机：{{$data->pay_bank_mobile}}
                                        </div>
                                    @endif
                                </div>
                                <div class="layui-row">
                                    @isset($data->pay_bank_username)
                                        <div class="layui-col-md6">
                                            付款人姓名：{{$data->pay_bank_username}}
                                        </div>
                                    @endif
                                    @isset($data->pay_bank_account)
                                        <div class="layui-col-md6">
                                            付款账号：{{$data->pay_bank_account}}
                                        </div>
                                    @endif
                                </div>

                                <strong>承兑商信息</strong>
                                <hr>
                                <div class="layui-row">
                                    <div class="layui-col-md6">
                                        编号：{{$data->member->no}}
                                    </div>
                                    <div class="layui-col-md6">
                                        名称：{{$data->member->name}}
                                    </div>
                                </div>
                                <div class="layui-row">
                                    @if($data->member->mobile)
                                        <div class="layui-col-md6">
                                            手机号：{{$data->member->mobile}}
                                        </div>
                                    @endif
                                    @if($data->member->email)
                                        <div class="layui-col-md6">
                                            邮箱：{{$data->member->email}}
                                        </div>
                                    @endif
                                </div>
                                <div class="layui-row">
                                    @if($data->member->weixin)
                                        <div class="layui-col-md6">
                                            微信号：{{$data->member->weixin}}
                                        </div> @endif
                                    @if($data->member->alipay)
                                        <div class="layui-col-md6">
                                            支付宝：{{$data->member->alipay}}
                                        </div>
                                    @endif
                                </div>
                                <div class="layui-row">
                                    <div class="layui-col-md6">
                                        承兑商费率： {{$data->otc_rate}}
                                    </div>
                                    <div class="layui-col-md6">
                                        承兑商佣金数量： {{$data->otc_commission}}
                                    </div>
                                </div>
                                <div class="layui-row">
                                    <div class="layui-col-md6">
                                        服务商费率： {{$data->sell_rebate_rate}}
                                    </div>
                                    <div class="layui-col-md6">
                                        服务商佣金数量： {{$data->sell_rebate_commission}}
                                    </div>
                                </div>

                                <strong>商户信息</strong>
                                <hr>
                                <div class="layui-row">
                                    <div class="layui-col-md6">
                                        编号：{{$data->merchant->no}}
                                    </div>
                                    <div class="layui-col-md6">
                                        名称：{{$data->merchant->name}}
                                    </div>
                                </div>

                                <div class="layui-row">
                                    @if($data->merchant->mobile)
                                        <div class="layui-col-md6">
                                            手机号： {{$data->merchant->mobile}}
                                        </div> @endif
                                    @if($data->merchant->email)
                                        <div class="layui-col-md6">
                                            邮箱：{{$data->merchant->email}}
                                        </div>
                                    @endif
                                </div>
                                <div class="layui-row">
                                    <div class="layui-col-md6">
                                        平台费率：{{$data->platform_rate}}
                                    </div>
                                    <div class="layui-col-md6">
                                        平台佣金：{{$data->platform_commission}}
                                    </div>
                                </div>
                                <div class="layui-row">
                                    <div class="layui-col-md6">
                                        服务商费率： {{$data->buy_rebate_rate}}
                                    </div>
                                    <div class="layui-col-md6">
                                        服务商佣金数量： {{$data->buy_rebate_commission}}
                                    </div>
                                </div>

                                <strong>收款账户信息</strong>
                                <hr>
                                @if($data->payee_type==0)
                                    @if(isset($data->payee))


                                        <div class="layui-row">
                                            <div class="layui-col-md6">
                                                账户类型：
                                                @if($data->payee->type==0)银行卡@endif
                                                @if($data->payee->type==1)支付宝@endif
                                                @if($data->payee->type==2)微信@endif
                                            </div>

                                        </div>
                                        <div class="layui-row">

                                            <div class="layui-col-md12">
                                                收款人姓名：
                                                <button class="btn" data-clipboard-action="copy"
                                                        data-clipboard-text="{{$data->payee->name}}" title="点击复制">
                                                    {{$data->payee->name}}
                                                </button>
                                            </div>
                                        </div>
                                        @if($data->payee->type==0)
                                            <div class="layui-row">
                                                <div class="layui-col-md12">
                                                    银行名称：
                                                    <button class="btn" data-clipboard-action="copy"
                                                            data-clipboard-text="{{$data->payee->bank_name}}"
                                                            title="点击复制">
                                                        {{$data->payee->bank_name}}
                                                    </button>
                                                </div>


                                            </div>
                                            <div class="layui-row">

                                                <div class="layui-col-md12">
                                                    银行账号：
                                                    <button class="btn" data-clipboard-action="copy"
                                                            data-clipboard-text="{{$data->payee->bank_account}}"
                                                            title="点击复制">
                                                        {{$data->payee->bank_account}}
                                                    </button>
                                                </div>

                                            </div>
                                            @if(isset($data->payee->bank_branch))
                                                <div class="layui-row">

                                                    <div class="layui-col-md12">
                                                        支行地址：
                                                        <button class="btn" data-clipboard-action="copy"
                                                                data-clipboard-text="{{$data->payee->bank_branch}}"
                                                                title="点击复制">
                                                            {{$data->payee->bank_branch}}
                                                        </button>
                                                    </div>

                                                </div>
                                            @endif
                                        @endif

                                    @endif

                                @else
                                    <div class="layui-row">
                                        <div class="layui-col-md6">
                                            账户类型：银行卡
                                        </div>
                                        <div class="layui-col-md6">
                                            收款人姓名：{{$data->bank_username}}
                                        </div>
                                        @if(isset($data->bank_mobile))
                                            <div class="layui-col-md6">
                                                手机号：{{$data->bank_mobile}}
                                            </div>
                                        @endif
                                        <div class="layui-col-md6">
                                            银行名称：{{$data->bank_name}}
                                        </div>
                                        <div class="layui-col-md6">
                                            银行账号：{{$data->bank_account}}
                                        </div>
                                        @if(isset($data->bank_branch))
                                            <div class="layui-col-md6">
                                                开户支行：{{$data->bank_branch}}
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                @if(isset($data->payPayee))
                                    <strong>付款账户信息</strong>
                                    <hr>
                                    <div class="layui-row">
                                        <div class="layui-col-md6">
                                            账户类型：银行卡
                                        </div>
                                        <div class="layui-col-md6">
                                            付款人姓名：{{$data->payPayee->name}}
                                        </div>
                                        @if(isset($data->payPayee->bank_mobile))
                                            <div class="layui-col-md6">
                                                手机号：{{$data->payPayee->bank_mobile}}
                                            </div>
                                        @endif
                                        <div class="layui-col-md6">
                                            银行名称：{{$data->payPayee->bank_name}}
                                        </div>
                                        <div class="layui-col-md6">
                                            银行账号：{{$data->payPayee->bank_account}}
                                        </div>
                                        @if(isset($data->payPayee->bank_branch))
                                            <div class="layui-col-md6">
                                                开户支行：{{$data->payPayee->bank_branch}}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                @if(isset($data->pay_account))
                                    <strong>付款账户备注</strong>
                                    <hr>
                                    <div class="layui-row">

                                        <div class="layui-col-md6">
                                            银行账号：{{$data->pay_account}}
                                        </div>

                                    </div>
                                @endif
                            </div>
                            <div class="layui-col-md4 pl-3 "><strong>交易情况</strong>
                                <hr>
                                <ul class="layui-timeline">
                                    <li class="layui-timeline-item">
                                        <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                                        <div class="layui-timeline-content layui-text">
                                            <h3 class="layui-timeline-title">{{$data->created_at}}</h3>
                                            <p>
                                                订单创建
                                            </p>
                                        </div>
                                    </li>
                                    <li class="layui-timeline-item">
                                        <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                                        <div class="layui-timeline-content layui-text">
                                            <h3 class="layui-timeline-title"> {{$data->receiving_time}}</h3>
                                            <p> 卖家已接单
                                        </div>
                                    </li>
                                    @if(isset($data->pay_time))
                                        <li class="layui-timeline-item">
                                            <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                                            <div class="layui-timeline-content layui-text">
                                                <h3 class="layui-timeline-title">{{$data->pay_time}}</h3>
                                                <p> 买家已确认支付
                                                </p>
                                            </div>
                                        </li>
                                    @endif
                                    @if(isset($data->cancel_time)&&$data->order_status!=5)
                                        <li class="layui-timeline-item">
                                            <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                                            <div class="layui-timeline-content layui-text">
                                                <h3 class="layui-timeline-title">{{$data->cancel_time}}</h3>
                                                <p> 订单取消
                                                    @if($data->cancel_remark)
                                                        ({{$data->cancel_remark}})
                                                    @endif
                                                </p>
                                            </div>
                                        </li>
                                    @endif
                                    @if(isset($data->pass_time))
                                        <li class="layui-timeline-item">
                                            <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                                            <div class="layui-timeline-content layui-text">
                                                <h3 class="layui-timeline-title">{{$data->pass_time}}</h3>
                                                <p> 卖家确认结算放行-订单完成
                                                </p>
                                            </div>
                                        </li>
                                    @endif
                                    @if($data->order_status==5)
                                        <li class="layui-timeline-item">
                                            <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                                            <div class="layui-timeline-content layui-text">
                                                <h3 class="layui-timeline-title">{{$data->cancel_time}}</h3>
                                                <p> 订单已退款
                                                    @if($data->cancel_remark)
                                                        ({{$data->cancel_remark}})
                                                    @endif
                                                </p>
                                            </div>
                                        </li>
                                    @endif
                                </ul>
                                <hr>
                                @if($data->type==0&&$data->order_status==2)

                                    <div class="layui-form  " lay-filter="">
                                        <input type="hidden" name="id" value="{{$data->id}}">
                                        <div class="layui-form-item">
                                            <input type="text" name="pay_account" lay-verify="required"
                                                   placeholder="请输入付款人账户或姓名"
                                                   autocomplete="off" class="layui-input">
                                        </div>

                                        <div class="layui-form-item">
                                            <input type="button" class="layui-btn layui-btn-danger" lay-submit
                                                   lay-filter="pay"
                                                   value="确认支付">
                                        </div>

                                    </div>
                                @endif
                                @if(isset($data->push_url))
                                    <div class="layui-form  " lay-filter="">
                                        <input type="hidden" name="id" value="{{$data->id}}">
                                        <button class="layui-btn layui-btn-danger text-white" lay-submit
                                                lay-filter="push">重新推送
                                        </button>
                                    </div>
                                @endif
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

            //普通图片上传
            var uploadInst = upload.render({
                elem: '#image'
                , url: '{{url('common/put')}}' //上传接口
                , before: function (obj) {

                }
                , acceptMime: 'image/*'
                , done: function (res) {
                    //如果上传失败
                    if (res.code > 0) {
                        return layer.msg('上传失败');
                    }
                    //上传成功
                    $("[name='image']").val(res.data);
                    layer.closeAll('loading');
                    layer.msg('上传成功');
                }
                , error: function () {
                    //演示失败状态，并实现重传
                    layer.alert('上传失败');
                    layer.closeAll('loading');
                }
            });
            parent.layer.iframeAuto(index);
            form.on('submit(formSubmit)', function (data) {
                    let field = data.field;
                    axios.post("{{url('system/currency/recharge/update')}}", field)
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
