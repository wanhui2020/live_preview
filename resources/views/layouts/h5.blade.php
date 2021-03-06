<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>手机在线支付</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <link rel="stylesheet" href="{{ asset('/plugins/msui/sm.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/msui/sm-extend.min.css') }}">
    <link href="{{ asset('css/h5.css') }}" rel="stylesheet">

</head>
<body>
<div class="page-group">
    @yield('content')
</div>


<script type='text/javascript' src='{{ asset('/plugins/msui/zepto.min.js') }}' charset='utf-8'></script>
<script type='text/javascript' src='{{ asset('/plugins/msui/sm.min.js') }}' charset='utf-8'></script>
<script type='text/javascript' src='{{ asset('/plugins/msui/sm-extend.min.js') }}' charset='utf-8'></script>
<script>

</script>
@yield('script')
</body>
</html>
