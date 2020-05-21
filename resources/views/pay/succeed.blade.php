@extends('layouts.h5')
@section('content')
    <div class="page">
        <header class="bar bar-nav">
            <a class="icon icon-left pull-left external" href="{{$order->succeed_url}}">返回</a>
            <h1 class="title">支付结果</h1>
        </header>

        <div class="content">
            支付成功
        </div>

    </div>
@endsection
