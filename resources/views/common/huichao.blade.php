<!DOCTYPE html>
<html class="full-height" lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>汇潮支付</title>
    <meta name="description" content="Material design app landing page template built"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="/home/css/bootstrap.min.css" rel="stylesheet">
    <link href="/home/css/mdb.min.css" rel="stylesheet">
    <link href="/home/styles/main.css" rel="stylesheet">
</head>
<body >
<form action="https://gwapi.yemadai.com/pay/sslpayment" method="post" id="form">
    @foreach ($parameter as $key=>$value)
        <input   type="hidden"  name="{{ $key }}" value="{{ $value }}"/>
    @endforeach
{{--        <input type="submit" name="submit" value="提交" />--}}
</form>
<script type="text/javascript" src="/home/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/home/js/popper.min.js"></script>
<script type="text/javascript" src="/home/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/home/js/mdb.min.js"></script>
</body>
<script>
    document.getElementById("form").submit();
    jquery: $("#form").submit();
</script>
</html>
