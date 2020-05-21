@extends('layouts.h5')
@section('content')
    <div class="page">
        <header class="bar bar-nav">

            <a class="icon icon-left pull-left" href="{{$order->succeed_url??'javascript:history.go(-1)'}}"></a>

            <h1 class="title">{{$title}}</h1>
        </header>

        <div class="content ">

            <div class="list-block" style="margin-top: 10px;margin-bottom: 10px; ">
                <ul>
                    <!-- Text inputs -->
                    <li>
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-title label">订单编号</div>
                                <div class="item-input">
                                    <span>{{$order->no}}</span>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-title label">金额</div>
                                <div class="item-input">
                                    <span style="color: red">{{$order->total}}</span>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-title label">订单状态</div>
                                <div class="item-input">
                                    @if($order->order_status==0)
                                        <span style="color: green">支付完成</span>
                                    @endif
                                    @if($order->order_status==1)
                                        <span style="color: red">已取消</span>（{{$order->cancel_remark}}）
                                    @endif
                                    @if($order->order_status==2)
                                        <span style="color: yellow">支付中</span>
                                    @endif
                                    @if($order->order_status==3)
                                        <span style="color: red">已支付待确认</span>
                                    @endif
                                    @if($order->order_status==4)
                                        <span style="color: yellow">未到账</span>
                                    @endif
                                    @if($order->order_status==5)
                                        <span style="color: red">已退款</span>
                                    @endif
                                    @if($order->order_status==9)
                                        <span style="color: yellow">待接单</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            @if($order->type==0&&in_array($order->order_status,[2,3]))
                <div class="content-block-title" style="margin : 10px;">收款账户（点击可复制）</div>
                <div class="list-block" style="margin-top: 15px;margin-bottom: 15px;font-size: 14px;">
                    <ul>
                        <li class="item-content">
                            <div class="item-inner" onclick="copy('{{$order->bank_username}}')">
                                <div class="item-title">收款人</div>
                                <div class="item-after">{{$order->bank_username}}</div>
                            </div>
                        </li>
                        <li class="item-content">
                            <div class="item-inner" onclick="copy('{{$order->bank_name}}')">
                                <div class="item-title">开户行</div>
                                <div class="item-after">{{$order->bank_name}}</div>
                            </div>
                        </li>
                        @if(isset($order->bank_branch))
                            <li class="item-content">
                                <div class="item-inner" onclick="copy('{{$order->bank_branch}}')">
                                    <div class="item-title">支行地址</div>
                                    <div class="item-after">{{$order->bank_branch}}</div>
                                </div>
                            </li>
                        @endif
                        <li class="item-content">
                            <div class="item-inner" onclick="copy('{{$order->bank_account}}')">
                                <div class="item-title">卡号</div>
                                <div class="item-after">{{$order->bank_account}}</div>
                            </div>
                        </li>


                    </ul>
                </div>
                {{--                <div class="list-block" style="margin-top: 10px; ">--}}
                {{--                    <ul>--}}
                {{--                        <li class="item-content">--}}
                {{--                            <div class="item-inner">--}}
                {{--                                <div class="item-title"><img src="{{url('images/pay/bank.png')}}" style='width: 30px;'>--}}
                {{--                                </div>--}}
                {{--                                <div class="item-after">转账支付</div>--}}
                {{--                            </div>--}}
                {{--                        </li>--}}
                {{--                        <li class="item-content">--}}
                {{--                            <div class="item-inner">--}}
                {{--                                <div class="item-title">开户行</div>--}}
                {{--                                <div class="item-after">{{$order->payee->bank_name}}</div>--}}
                {{--                            </div>--}}
                {{--                        </li>--}}
                {{--                        @if(isset($order->bank_branch))--}}
                {{--                            <li class="item-content">--}}
                {{--                                <div class="item-inner">--}}
                {{--                                    <div class="item-title">银行地址</div>--}}
                {{--                                    <div class="item-after">{{$order->payee->bank_branch}}</div>--}}
                {{--                                </div>--}}
                {{--                            </li>--}}
                {{--                        @endif--}}
                {{--                        <li class="item-content">--}}
                {{--                            <div class="item-inner" onclick="copy('{{$order->payee->bank_account}}')">--}}
                {{--                                <div class="item-title">卡号(点击复制)</div>--}}
                {{--                                <div class="item-after">{{$order->payee->bank_account}}</div>--}}
                {{--                            </div>--}}
                {{--                        </li>--}}
                {{--                        <li class="item-content">--}}
                {{--                            <div class="item-inner">--}}
                {{--                                <div class="item-title">收款人</div>--}}
                {{--                                <div class="item-after">{{$order->payee->name}}</div>--}}
                {{--                            </div>--}}
                {{--                        </li>--}}
                {{--                        <li class="item-content">--}}
                {{--                            <div class="item-inner">--}}
                {{--                                <div class="item-title">付款账户</div>--}}
                {{--                                <div class="item-after">{{$order->pay_account}}</div>--}}
                {{--                            </div>--}}
                {{--                        </li>--}}
                {{--                    </ul>--}}
                {{--                </div>--}}

            @endif
            <div class="content-block-title" style="margin-top: 15px;">注意事项</div>
            <div class="content-block "
                 style="background-color: white;padding: 10px;font-size: 14px;line-height: 25px;margin : 10px 0 10px 0;">
                <div>
                    <div>1、已支付10分钟未到账可申诉</div>
                    <div>2、未确认支付或支付超时可申诉</div>
                    <div>3、上传支付凭证有利于快速到账</div>
                    <div>4、未按金额转账可申诉退款</div>
                </div>
            </div>
{{--            <div class="content-block-title" style="margin-top: 15px;">支付凭证上传</div>--}}
{{--            <div class="content-block "--}}
{{--                 style="background-color: white;padding: 10px;font-size: 14px;line-height: 25px;margin : 10px 0 10px 0; ">--}}
{{--                <input accept="image/*" type="file" id="uploadIMG" οnchange="btnUploadFile(event)"/>--}}
{{--            </div>--}}
        </div>
        @if($order->settle_status==9)
            @if($order->order_status==1)
                <nav class="bar bar-footer"
                     style="margin: 0;padding: 0;line-height:50px;text-align: center ;display: flex; justify-content:center">

{{--                    <a class="  button-big button-fill button-red"--}}
{{--                       href="{{$order->succeed_url??'javascript:history.go(-1)'}}" style=" flex: 1;">申诉</a>--}}
                    <a class="  button-big button-fill button-success"
                       href="{{$order->succeed_url??'javascript:history.go(-1)'}}" style=" flex: 2;">返回</a>

                </nav>

            @endif
                @if($order->order_status==2)
                    <nav class="bar bar-footer"
                         style="margin: 0;padding: 0;line-height:50px;text-align: center ;display: flex; justify-content:center">

                        {{--                    <a class="  button-big button-fill button-red"--}}
                        {{--                       href="{{$order->succeed_url??'javascript:history.go(-1)'}}" style=" flex: 1;">申诉</a>--}}
                        <a class="  button-big button-fill button-success"
                           href="{{$order->succeed_url??'javascript:history.go(-1)'}}" style=" flex: 2;">返回</a>

                    </nav>

                @endif
            @if($order->order_status==3)
                <nav class="bar  bar-footer"
                     style="margin: 0;padding: 0;line-height:50px;text-align: center ;display: flex; justify-content:center">
                    <span class="  button-big button-fill button-danger" style=" flex: 1; " onclick="cancel()">取消</span>
                    <a class="  button-big button-fill button-success"
                       href="{{$order->succeed_url??'javascript:history.go(-1)'}}" style=" flex: 2;">返回</a>
                </nav>
            @endif
        @endif


    </div>
@endsection
@section('script')
    <script src="{{ asset('plugins/layuiadmin/layui/layui.js')}}"></script>
    <script>
        function copy(message) {
            var input = document.createElement("input");
            input.value = message;
            document.body.appendChild(input);
            input.select();
            input.setSelectionRange(0, input.value.length);
            document.execCommand('Copy');
            document.body.removeChild(input);
            $.toast("复制成功");
        }
        function cancel() {

            $.confirm('确认取消订单?',
                function () {
                    let token = document.head.querySelector('meta[name="csrf-token"]');
                    $.ajax({
                        type: "POST",
                        url: "/pay/cancel",
                        headers: {
                            "X-CSRF-TOKEN": token.content,
                        },
                        data: {
                            "no": '{{$order->no}}'
                        },
                        success: function (resp) {
                            if (resp.status) {
                                $.toast("取消成功", 2000);
                                return window.location.reload();
                            }
                            return $.toast("确认失败：" + resp.msg);
                        }, error: function (jqXHR) {
                            $.toast("发生错误：" + jqXHR.status);
                        }
                    });
                },
                function () {
                });
        }

    </script>
@endsection