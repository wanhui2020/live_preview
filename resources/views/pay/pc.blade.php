@extends('layouts.h5')
@section('content')
    <div class="page">
        <header class="bar bar-nav">
            @if(isset($order->succeed_url))
                <a class="icon icon-left pull-left" href="{{$order->succeed_url??'javascript:history.go(-1)'}}"></a>
            @endif
            {{--<a class="icon  pull-right">取消</a>--}}
            <h1 class="title">在线支付</h1>
        </header>

        <div class="content ">

            <div class="list-block" style="margin-top: 10px;margin-bottom: 10px;">
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
                            <div style="text-align: center;color: #d0211c;font-size: 14px;"><span id="miao">-</span>秒后未确认已支付，订单将会自动取消！
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
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
            <div class="list-block" style="margin-top: 15px;margin-bottom: 0;">
                <ul>
                    <li class="item-content  " style="border:1px solid  red">
                        <div class="item-inner">
                            <div class="item-input">
                                <input type="text" id="pay_account" placeholder="请输入付款人姓名或账号">
                            </div>
                        </div>
                    </li>

                </ul>
            </div>
            <div class="content-block-title" style="margin-top: 10px;">注意事项</div>
            <div class="content-block "
                 style="background-color: white;padding: 10px;font-size: 14px;line-height: 25px;">
                <div>
                    <div>1、请按页面显示金额支付</div>
                    <div>2、请按照以上付款信息进行打款，<span style="color: red;">请勿直接打款到之前账户</span>，否则可能无法到账，造成损失平台概不负责！</div>
                    <div>3、转账成功后请确认已支付</div>
                    <div>4、支付宝或微信转银行卡，请上传支付凭证</div>
                </div>
            </div>


            {{--            <div class="buttons-tab">--}}

            {{--                <a href="#tab1" class="tab-link active button">银行转账</a>--}}

            {{--                @if($order->alipaycode)--}}
            {{--                    <a href="#tab2" class="tab-link button">支付宝</a>--}}
            {{--                @endif--}}
            {{--                @if($order->wechatcode)--}}
            {{--                    <a href="#tab3" class="tab-link button">微信</a> @endif--}}
            {{--            </div>--}}
            {{--            <div class="tabs">--}}

            {{--                <div id="tab1" class="tab active">--}}

            {{--                    <div class="card  ">--}}
            {{--                        --}}{{--<div class="card-header">--}}
            {{--                            --}}{{--<img src="{{url('images/pay/bank.png')}}" style='width: 30px;'>转账支付--}}
            {{--                        --}}{{--</div>--}}
            {{--                        <div class="card-content">--}}
            {{--                            <div class="list-block">--}}
            {{--                                <ul>--}}
            {{--                                    <li class="item-content">--}}
            {{--                                        <div class="item-inner">--}}
            {{--                                            <div class="item-title">开户行</div>--}}
            {{--                                            <div class="item-after">{{$order->bank_name}}</div>--}}
            {{--                                        </div>--}}
            {{--                                    </li>--}}
            {{--                                    @if(isset($order->bank_branch))--}}
            {{--                                        <li class="item-content">--}}
            {{--                                            <div class="item-inner">--}}
            {{--                                                <div class="item-title">银行地址</div>--}}
            {{--                                                <div class="item-after">{{$order->bank_branch}}</div>--}}
            {{--                                            </div>--}}
            {{--                                        </li>--}}
            {{--                                    @endif--}}
            {{--                                    <li class="item-content">--}}
            {{--                                        <div class="item-inner" onclick="copy('{{$order->bank_account}}')">--}}
            {{--                                            <div class="item-title">卡号</div>--}}
            {{--                                            <div class="item-after">{{$order->bank_account}}</div>--}}
            {{--                                        </div>--}}
            {{--                                    </li>--}}
            {{--                                    <li class="item-content">--}}
            {{--                                        <div class="item-inner">--}}
            {{--                                            <div class="item-title">收款人</div>--}}
            {{--                                            <div class="item-after">{{$order->bank_username}}</div>--}}
            {{--                                        </div>--}}
            {{--                                    </li>--}}
            {{--                                    <div class="item-content">--}}
            {{--                                        <div class="item-inner">--}}
            {{--                                            <div class="item-input">--}}
            {{--                                                <input type="text" id="pay_account" placeholder="付款人账号">--}}
            {{--                                            </div>--}}
            {{--                                        </div>--}}
            {{--                                    </div>--}}
            {{--                                </ul>--}}
            {{--                            </div>--}}
            {{--                        </div>--}}
            {{--                        <div class="card-footer">转账备注:{{$order->bank_memo??'转账成功后请点击确认已支付'}}--}}
            {{--                        </div>--}}
            {{--                    </div>--}}
            {{--                </div>--}}

            {{--                @if($order->alipaycode)--}}
            {{--                    <div id="tab2" class="tab">--}}

            {{--                        <div class="card">--}}
            {{--                            <div class="card-header">--}}
            {{--                                <div><img src="{{url('images/pay/alipay.png')}}" style='width: 30px;'>--}}
            {{--                                </div>--}}
            {{--                                <div onclick="copy('{{$order->alipaycode}}')">{{$order->alipaycode}}--}}
            {{--                                    (点击复制)--}}
            {{--                                </div>--}}
            {{--                            </div>--}}
            {{--                            <div class="card-content">--}}
            {{--                                <div class="card-content-inner"><img src="{{$order->alipayqrcode}} "--}}
            {{--                                                                     style="width: 100%;"></div>--}}
            {{--                            </div>--}}
            {{--                            <div class="card-footer">打开支付宝扫一扫--}}
            {{--                            </div>--}}
            {{--                        </div>--}}

            {{--                    </div>--}}
            {{--                @endif--}}
            {{--                @if($order->wechatcode)--}}
            {{--                    <div id="tab3" class="tab">--}}

            {{--                        <div class="card">--}}
            {{--                            <div class="card-header">--}}
            {{--                                <div><img src="{{url('images/pay/weixin.png')}}" style='width: 30px;'>--}}
            {{--                                </div>--}}
            {{--                                <div onclick="copy('{{$order->wechatcode}}')">{{$order->wechatcode}}--}}
            {{--                                    (点击复制)--}}
            {{--                                </div>--}}
            {{--                            </div>--}}
            {{--                            <div class="card-content">--}}
            {{--                                <div class="card-content-inner "><img src="{{$order->wechatqrcode}} "--}}
            {{--                                                                      style="width: 100%;"></div>--}}
            {{--                            </div>--}}
            {{--                            <div class="card-footer">打开支付宝扫一扫--}}
            {{--                            </div>--}}
            {{--                        </div>--}}

            {{--                    </div>--}}
            {{--                @endif--}}
            {{--            </div>--}}
        </div>

        <nav class="bar  bar-footer"
             style="margin: 0;padding: 0;line-height:50px;text-align: center ;display: flex; justify-content:center">
            <span class="  button-big button-fill button-danger" style=" flex: 1; " onclick="cancel()">取消</span>

            <span class="  button-big button-fill button-success" style=" flex: 2;" onclick="pay()">确认已支付</span>

            {{--            <span href="#" class="tab-item   active"  >取消</span>--}}
            {{--            <span href="#" class="tab-item    ">确认已支付</span>--}}
            {{--            <div style="text-align: center;color: #ffffff;line-height: 50px;font-size: 16px;"> 确认已支付</div>--}}
            {{--            <div style="text-align: center;color: #ffffff;line-height: 50px;font-size: 16px;"> 确认已支付</div>--}}

        </nav>
    </div>
@endsection
@section('script')

    <script>
        var i = 600 -{{  strtotime('now')-strtotime($order->created_at) }};
        window.setInterval(function () {
            if (i > 0) {
                $('#miao').text(i--);
            } else {
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

        function pay() {
            let pay_account = $('#pay_account').val();
            if (pay_account.length == 0) {
                $('#pay_account').focus();
                return $.toast("请填写付款人账号或姓名");
            }
            $.confirm('确认已付款?',
                function () {
                    let token = document.head.querySelector('meta[name="csrf-token"]');
                    $.ajax({
                        type: "POST",
                        url: "/pay/confirm",
                        headers: {
                            "X-CSRF-TOKEN": token.content,
                        },
                        data: {
                            "no": '{{$order->no}}', "pay_account": pay_account
                        },
                        success: function (resp) {
                            if (resp.status) {
                                $.toast("确认成功", 2000);
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