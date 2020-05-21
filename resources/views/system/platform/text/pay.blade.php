<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Content-Type" content="text/html; charset=">
    <meta http-equiv="Content-Language" content="zh-cn">
    <meta name="apple-mobile-web-app-capable" content="no">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="format-detection" content="telephone=no,email=no">
    <meta name="apple-mobile-web-app-status-bar-style" content="white">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>未知</title>
    <link rel="stylesheet" href="{{ asset('css/css/ui-box.css')}}">
    <link rel="stylesheet" href="{{ asset('css/css/ui-color.css')}}">
    <link rel="stylesheet" href="{{ asset('css/css/ui-base.css')}}">

</head>

<body>
<div class="body">
    <div class="ub ub-ac ub-pc pay_type" style="margin: 1.5em 0;padding-bottom: 1em">
        <div class="ub ub-pc ub-ver ub-f1 " data-name="{{$data[0]['name'] ?? ''}}" data-src="{{$data[0]['icon'] ?? ''}}" data-text="{{$data[0]['payee'] ?? ''}}">
            <div class="ub ub-ac ub-pc" >
                <div class=" s-icon" style="height: 3em;width: 3em">
                    <img src="{{$data[0]['icon'] ?? ''}}"/>
                </div>
            </div>
            <div class="ub ub-ac ub-pc">
                {{$data[0]['name'] ?? ''}}
            </div>
        </div>
        <div class="ub ub-pc ub-ver ub-f1 border-b-1-g" data-name="{{$data[1]['name'] ?? ''}}"  data-src="{{$data[1]['icon'] ?? ''}}" data-text="{{$data[1]['payee'] ?? ''}}">
            <div class="ub ub-ac ub-pc" >
                <div class=" s-icon" style="height: 3em;width: 3em">
                    <img src="{{$data[1]['icon'] ?? ''}}"/>
                </div>
            </div>
            <div class="ub ub-ac ub-pc">
                {{$data[1]['name'] ?? ''}}
            </div>
        </div>
    </div>
    <div class="ub ub-ac ub-pc pay" style="margin: 1em;height: 30em;box-shadow:#cccccc 0px 0px 20px">
        <div class="ub ub-ac ub-ver">
            <div class="ub ub-ac ub-pc s-fs-12 s-mt-8 name">
                {{$data[0]['name'] ?? ''}}（推荐）
            </div>
            <div class="ub ub-ac ub-pc s-fs-9 s-mt-7 s-mb-7 payee" >
                收款方：{{$data[0]['payee'] ?? ''}}
            </div>
            <div class="ub ub-ac ub-pc">
                <div class=" s-icon" style="height: 15em;width: 15em">
                    <img src="{{$data[0]['payee_icon'] ?? ''}}"/>
                </div>
            </div>
            <br>
            {!! $data[0]['remark'] ?? '' !!}
        </div>
    </div>
</div>

　<script src="https://code.jquery.com/jquery-3.0.0.min.js"></script>
<script>
    $('.pay_type').on('click','.ub-f1',function(){
        $('.pay').find('.name').text($(this).data('name')+'(推荐)')
        $('.pay').find('.s-icon').html(" <img src='"+$(this).data('src')+"'/>")
        $('.pay').find('.payee').html('收款方：'+$(this).data('text'))
    })
</script>

</body>

</html>