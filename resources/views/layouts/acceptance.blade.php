<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}-承兑商</title>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
{{--    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">--}}

<!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('plugins/layuiadmin/layui/css/layui.css')}}" media="all">
    <link rel="stylesheet" href="{{ asset('plugins/smallpop/spop.min.css')}}">
    <link href="{{ asset('css/common.css') }}" rel="stylesheet">
    <link href="{{ asset('css/member.css') }}" rel="stylesheet">
    @yield('style')
</head>
<body>
<div id="app">

    @include('member.common.header',['page'=>'文章页'])

    <main class="py-3 my-2 container-fluid  " style="max-width: 1400px;">
        @yield('content')
    </main>
</div>
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('plugins/layuiadmin/layui/layui.js')}}"></script>
<script src="{{ asset('plugins/smallpop/spop.min.js')}}"></script>
<script src="{{ asset('js/common.js') }}"></script>

{{--<script src='{{ asset('js/socket.io.js') }}'></script>--}}
@yield('script')

<script type='text/javascript'>


    let user =@json(Auth::guard('MemberUser')->user());
    //
    // const socket = io(':9502', {transports: ['websocket', 'xhr-polling', 'jsonp-polling']});
    // // 连接后登录
    // socket.on('connect', function () {
    //     console.log('连接成功');
    //     socket.emit('login', {'type': 'member', 'id': user.id});
    // });
    // socket.on('connect_error', function (error) {
    //     console.log(error);
    // });
    //
    // // 监听 receiveMsg 事件，用来接收其他客户端推送的消息
    // socket.on("receiveMsg", function (data) {
    //     console.log('收到消息: ' + data);
    //     // spop(data, {
    //     //     template: 'Position top left',
    //     //     position: 'top-left',
    //     //     style: 'success',
    //     //     autoclose: 5000
    //     // });
    // });
    //
    // function WebSocketTest() {
    //     var data = {name: "query", data: '123'};
    //     socket.emit('sendMsg', '123123213');
    // }


    // (function (m, ei, q, i, a, j, s) {
    //     m[i] = m[i] || function () {
    //         (m[i].a = m[i].a || []).push(arguments)
    //     };
    //     j = ei.createElement(q),
    //         s = ei.getElementsByTagName(q)[0];
    //     j.async = true;
    //     j.charset = 'UTF-8';
    //     j.src = 'https://static.meiqia.com/dist/meiqia.js?_=t';
    //     s.parentNode.insertBefore(j, s);
    // })(window, document, 'script', '_MEIQIA');
    // _MEIQIA('entId', 1150);
    // _MEIQIA('metadata', {address: '承兑商'});


</script>
</body>
</html>
