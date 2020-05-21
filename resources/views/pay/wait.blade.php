@extends('layouts.h5')
@section('content')
    <div class="page">
        <header class="bar bar-nav">
            <a class="icon icon-left pull-left" href="{{url('/pay/create')}}"></a>
            <h1 class="title">支付中</h1>
        </header>

        <div class="content">
            <div class="content-padded">
            支付中，请稍后查询
        </div>
        </div>

    </div>
@endsection
