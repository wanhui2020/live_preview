@extends('layouts.h5')
@section('content')
    <div class="page">
        <header class="bar bar-nav">
            <a class="icon icon-left pull-left external" href="{{$order->succeed_url}}">返回</a>
            <a class="icon  pull-right">取消</a>
            <h1 class="title">在线支付</h1>
        </header>

        <div class="content " style="margin-bottom: 50px;">

            <div class="list-block" style="margin-top: 15px;margin-bottom: 15px;">
                <ul>
                    <!-- Text inputs -->
                    <li>
                        <div class="item-content">
                            <div class="item-media"><i class="icon icon-form-name"></i></div>
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
                            <div class="item-media"><i class="icon icon-form-name"></i></div>
                            <div class="item-inner">
                                <div class="item-title label">金额</div>
                                <div class="item-input">
                                    <span style="color: red">{{$order->money}}</span>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>

                        <div class="item-content">
                            <div style="text-align: center;color: #d0211c;font-size: 16px;"><span id="miao">-</span>秒内未确认已支付，订单将会自动取消！
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="buttons-tab">
                @if($order->resp->bankcard)
                    <a href="#tab1" class="tab-link active button">银行转账</a>
                @endif
                @if($order->resp->alipaycode)
                    <a href="#tab2" class="tab-link button">支付宝</a>
                @endif
                @if($order->resp->wechatcode)
                    <a href="#tab3" class="tab-link button">微信</a> @endif
            </div>
            <div class="tabs">
                @if($order->resp->bankcard)
                    <div id="tab1" class="tab active">

                        <div class="card  ">
                            <div class="card-header">
                                <img src="{{url('images/pay/bank.png')}}" style='width: 30px;'>转账支付
                            </div>
                            <div class="card-content">
                                <div class="list-block">
                                    <ul>
                                        <li class="item-content">
                                            <div class="item-inner">
                                                <div class="item-title">开户行</div>
                                                <div class="item-after">{{$order->resp->bankname}}</div>
                                            </div>
                                        </li>
                                        <li class="item-content">
                                            <div class="item-inner">
                                                <div class="item-title">银行地址</div>
                                                <div class="item-after">{{$order->resp->bankaddr}}</div>
                                            </div>
                                        </li>
                                        <li class="item-content">
                                            <div class="item-inner" onclick="copy('{{$order->resp->bankcard}}')">
                                                <div class="item-title">卡号(点击复制)</div>
                                                <div class="item-after">{{$order->resp->bankcard}}</div>
                                            </div>
                                        </li>
                                        <li class="item-content">
                                            <div class="item-inner">
                                                <div class="item-title">收款人</div>
                                                <div class="item-after">{{$order->resp->username}}</div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-footer">转账备注:{{$order->resp->bankmemo}}
                            </div>
                        </div>
                        <div class="card  ">
                            <div class="card-header">
                                <img src="{{url('images/pay/bank.png')}}" style='width: 30px;'>转账支付
                            </div>
                            <div class="card-content">
                                <div class="list-block">
                                    <ul>
                                        <li class="item-content">
                                            <div class="item-inner">
                                                <div class="item-title">开户行</div>
                                                <div class="item-after">{{$order->resp->bankname}}</div>
                                            </div>
                                        </li>
                                        <li class="item-content">
                                            <div class="item-inner">
                                                <div class="item-title">银行地址</div>
                                                <div class="item-after">{{$order->resp->bankaddr}}</div>
                                            </div>
                                        </li>
                                        <li class="item-content">
                                            <div class="item-inner" onclick="copy('{{$order->resp->bankcard}}')">
                                                <div class="item-title">卡号(点击复制)</div>
                                                <div class="item-after">{{$order->resp->bankcard}}</div>
                                            </div>
                                        </li>
                                        <li class="item-content">
                                            <div class="item-inner">
                                                <div class="item-title">收款人</div>
                                                <div class="item-after">{{$order->resp->username}}</div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-footer">转账备注:{{$order->resp->bankmemo}}
                            </div>
                        </div>
                    </div>
                @endif
                @if($order->resp->alipaycode)
                    <div id="tab2" class="tab">

                        <div class="card">
                            <div class="card-header">
                                <div><img src="{{url('images/pay/alipay.png')}}" style='width: 30px;'>
                                </div>
                                <div onclick="copy('{{$order->resp->alipaycode}}')">{{$order->resp->alipaycode}}
                                    (点击复制)
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="card-content-inner"><img src="{{$order->resp->alipayqrcode}} "
                                                                     style="width: 100%;"></div>
                            </div>
                            <div class="card-footer">打开支付宝扫一扫
                            </div>
                        </div>

                    </div>
                @endif
                @if($order->resp->wechatcode)
                    <div id="tab3" class="tab">

                        <div class="card">
                            <div class="card-header">
                                <div><img src="{{url('images/pay/weixin.png')}}" style='width: 30px;'>
                                </div>
                                <div onclick="copy('{{$order->resp->wechatcode}}')">{{$order->resp->wechatcode}}
                                    (点击复制)
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="card-content-inner "><img src="{{$order->resp->wechatqrcode}} "
                                                                      style="width: 100%;"></div>
                            </div>
                            <div class="card-footer">打开支付宝扫一扫
                            </div>
                        </div>

                    </div>
                @endif
            </div>
        </div>
        <div class="bar bar-footer" style="background-color: red;" onclick="confirmPay()">

            <div style="text-align: center;color: #ffffff;line-height: 50px;font-size: 16px;"> 确认已支付</div>

        </div>
        {{--        {!! $order !!}--}}
    </div>
@endsection
@section('script')

    <script>
        var i = 600 -{{  strtotime('now')-strtotime($order->created_at) }};
        window.setInterval(function () {
            if (i>0){
                $('#miao').text(i--);
            }else{
                @if(!empty($order->succeed_url))
                    return window.location.href = '{{$order->succeed_url??'/pay/lists'}}';
                @endif

            }
        }, 1000);


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

        function confirmPay() {
            $.confirm('确认已付款?',
                function () {
                    let token = document.head.querySelector('meta[name="csrf-token"]');
                    $.ajax({
                        type: "POST",
                        url: "/pay/order/confirm",
                        headers: {
                            "X-CSRF-TOKEN": token.content,
                        },
                        data: {
                            "no": '{{$order->no}}',
                        },
                        success: function (resp) {
                            if (resp.status) {
                                $.toast("确认成功，稍后刷新查看", 2000);
                                return window.location.href = '{{$order->succeed_url??'/pay/lists'}}';
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