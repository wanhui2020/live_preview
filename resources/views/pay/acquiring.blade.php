@extends('layouts.h5')
@section('content')
    <div class="page">
        <header class="bar bar-nav">
            <h1 class="title">在线支付</h1>
        </header>

        <div class="content">
            <div class="list-block">
                <ul>
                    <!-- Text inputs -->
                    <li>
                        <div class="item-content">
                            <div class="item-media"><i class="icon icon-form-name"></i></div>
                            <div class="item-inner">
                                <div class="item-title label">订单编号</div>
                                <div class="item-input">
                                    <span v-text="acquiring.no"></span>
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
                                    <input type="text" name="money" placeholder="支付金额" v-model="acquiring.money">
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="content-block">
                <div class="row">
                    <div class="col-100"><a href="#" class="button button-big button-fill button-danger">确认支付</a></div>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('script')
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    <script>
        new Vue({
            el: '.page',
            data: {
                acquiring:{!! $acquiring !!}
            }
        })
    </script>
@endsection