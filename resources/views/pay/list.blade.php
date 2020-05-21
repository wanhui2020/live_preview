@extends('layouts.h5')
@section('content')
    <div class="page">
        <header class="bar bar-nav">
            <h1 class="title">在线支付</h1>
        </header>
        <div class="content">
            <div class="buttons-tab">
                <a href="#tab1" class="tab-link active button">待支付</a>
                <a href="#tab2" class="tab-link button">支付成功</a>
                <a href="#tab3" class="tab-link button">支付失败</a>
            </div>
            <div class="tabs">
                <div id="tab1" class="tab active">
                    <div class="list-block">
                        <ul>
                            @foreach($list->where('pay_status',9) as $item)
                                <li><a class="item-content item-link" href="{{url('/pay?no='.$item->no)}}">
                                        <div class="item-inner">
                                            <div class="item-title-row">
                                                <div class="item-title">{{$item->no}}</div>
                                                <div class="item-after">{{\Illuminate\Support\Carbon::parse($item->created_at)->toTimeString()}}</div>
                                            </div>

                                            <div class="item-text">{{$item->money}}</div>
                                        </div>

                                    </a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div id="tab2" class="tab">
                    <div class="list-block">
                        <ul>
                            @foreach($list->where('pay_status',0) as $item)
                                <li><a class="item-content item-link" href="{{url('/pay?no='.$item->no)}}">
                                        <div class="item-inner">
                                            <div class="item-title-row">
                                                <div class="item-title">{{$item->no}}</div>
                                                <div class="item-after">{{\Illuminate\Support\Carbon::parse($item->created_at)->toTimeString()}}</div>
                                            </div>
                                            <div class="item-subtitle">
                                                @if($item->pay_status==9)
                                                    待支付
                                                @endif
                                                @if($item->pay_status==0)
                                                    支付成功
                                                @endif
                                                @if($item->pay_status==2)
                                                    支付关闭
                                                @endif
                                            </div>
                                            <div class="item-text">{{$item->money}}</div>
                                        </div>

                                    </a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div id="tab3" class="tab">
                    <div class="list-block">
                        <ul>
                            @foreach($list->whereNotIn('pay_status',[0,9]) as $item)
                                <li><a class="item-content item-link" href="{{url('/pay?no='.$item->no)}}">
                                        <div class="item-inner">
                                            <div class="item-title-row">
                                                <div class="item-title">{{$item->no}}</div>
                                                <div class="item-after">{{\Illuminate\Support\Carbon::parse($item->created_at)->toTimeString()}}</div>
                                            </div>
                                            <div class="item-subtitle">

                                            </div>
                                            <div class="item-text">{{$item->money}}</div>
                                        </div>

                                    </a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

        </div>
        <div class="bar bar-footer" style="background-color: #cc0000;">
            <a href="{{url('/pay/create')}}"
               style="display: block;color: #ffffff;text-align: center;line-height: 50px;">我要支付</a>
        </div>
    </div>
@endsection
@section('script')

    <script type='text/javascript' src='//g.alicdn.com/sj/lib/zepto/zepto.min.js' charset='utf-8'></script>
    <script type='text/javascript' src='//g.alicdn.com/msui/sm/0.6.2/js/sm.min.js' charset='utf-8'></script>
    <script type='text/javascript' src='//g.alicdn.com/msui/sm/0.6.2/js/sm-extend.min.js' charset='utf-8'></script>
    <script>
    </script>
@endsection