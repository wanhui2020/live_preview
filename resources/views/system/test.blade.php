<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<div>
    <a
            href="alipays://platformapi/startapp?t={{time()}}&saId=10000007&clientVersion=3.7.0.0718&qrcode=HTTPS%3a%2f%2fQR.ALIPAY.COM%2fFKX09099VQZDCJ1QFGXA9F"
            target='_blank'
    >打开支付宝</a>

    <button onclick="notifyMe('这是通知的标题')">点我查看</button>
</div>
<script>

    // notifyMe('这是通知的标题', options);
    function notify(title) {
        var options = {
            dir: "auto", // 文字方向
            body: "通知：OBKoro1评论了你的朋友圈", // 通知主体
            requireInteraction: true, // 不自动关闭通知
            // 通知图标
            // icon: "https://upload-images.jianshu.io/upload_images/5245297-818e624b75271127.jpg?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240"
        };
        // 先检查浏览器是否支持
        if (!window.Notification) {
            console.log('浏览器不支持通知');
        } else {
            // 检查用户曾经是否同意接受通知
            if (Notification.permission === 'granted') {
                var notification = new Notification(title, options); // 显示通知
            } else if (Notification.permission === 'default') {
                // 用户还未选择，可以询问用户是否同意发送通知
                Notification.requestPermission().then(permission => {
                    if (permission === 'granted') {
                        console.log('用户同意授权');
                        var notification = new Notification(title, options); // 显示通知
                    } else if (permission === 'default') {
                        console.warn('用户关闭授权 未刷新页面之前 可以再次请求授权');
                    } else {
                        // denied
                        console.log('用户拒绝授权 不能显示通知');
                    }
                });
            } else {
                // denied 用户拒绝
                console.log('用户曾经拒绝显示通知');
            }
        }
    }
</script>
</body>
</html>