<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}-服务商</title>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
{{--    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">--}}

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('plugins/layuiadmin/layui/css/layui.css')}}" media="all">
    <link rel="stylesheet" href="{{ asset('plugins/smallpop/spop.min.css')}}">
    <link href="{{ asset('css/common.css') }}" rel="stylesheet">
    <link href="{{ asset('css/merchant.css') }}" rel="stylesheet">
    @yield('style')
</head>
<body>
<div id="app">

    @include('agent.common.header',['page'=>'文章页'])

    <main class="py-3 my-2 container-fluid  " style="max-width: 1400px;">
        @yield('content')
    </main>
</div>
<!-- Scripts -->
<script src="{{ asset('plugins/layuiadmin/layui/layui.js')}}"></script>
<script src="{{ asset('plugins/smallpop/spop.min.js')}}"></script>
<script src="{{ asset('js/app.js') }}"  ></script>
<script src="{{ asset('js/common.js') }}" ></script>

@yield('script')
</body>
</html>
