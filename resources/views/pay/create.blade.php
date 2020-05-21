@extends('layouts.h5')
@section('content')
    <div class="page">
        <header class="bar bar-nav">
            <a class="icon icon-left pull-left" href="{{$order->succeed_url??'javascript:history.go(-1)'}}"></a>
            <h1 class="title">在线支付</h1>
        </header>

        <div class="content">
            <div class="list-block">
                <ul>
                    <li>
                        <div class="item-content">
                            <div class="item-inner">
                                <div class="item-input">
                                    <input type="text" placeholder="支付金额" id="money">
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="content-block">
                <a href="#" class="button button-big button-fill button-success"  onclick="save();">确认支付</a>
            </div>
        </div>

    </div>
@endsection
@section('script')
    <script>
        function save() {
            var money = $('#money').val();
            if (!money) {
                return $.toast('金额不能为空');
            }
            if (money < 0) {
                return $.toast('金额不能小于0');
            }
            $.confirm('确认已付款?',
                function () {
                    let token = document.head.querySelector('meta[name="csrf-token"]');
                    $.ajax({
                        type: "POST",
                        url: "/pay/create",
                        headers: {
                            "X-CSRF-TOKEN": token.content,
                        },
                        data: {
                            "money": money,
                        },
                        success: function (resp) {
                            if (resp.status) {
                                $.toast("生成支付成功");
                                return window.location.href = '/pay?no=' + resp.data.no;
                            }
                            return $.toast("生成支付失败：" + resp.msg);
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