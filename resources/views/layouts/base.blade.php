<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{env('APP_NAME')}}-平台</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="{{ asset('plugins/layuiadmin/layui/css/layui.css') }}" media="all">
    <link rel="stylesheet" href="{{ asset('plugins/layuiadmin/style/admin.css') }} " media="all">
    <link rel="stylesheet" href="{{ asset('plugins/smallpop/spop.min.css')}}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }} " media="all">
    @yield('style')
</head>
<body>
<div id="app">
    @yield('content')
</div>
<script src="{{ asset('js/app.js')}}"></script>
<script src="{{ asset('plugins/layuiadmin/layui/layui.js')}}"></script>
<script src="{{ asset('plugins/smallpop/spop.min.js')}}"></script>
<script src="{{ asset('js/common.js')}}"></script>
@yield('script')
</body>
</html>
