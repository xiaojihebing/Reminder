<!doctype html>
<html lang="zh-CN">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
  </head>
<body>
  <p>{{ $subject }}</p>
  <p></p>
  <p>剩余天数： {{ $days }}天</p>
  <p></p>
  <p>剩余金额：{{ $money }}元</p>
  <p></p>
  <p><a href="{{ $buy_url }}" target="_blank">点击查看</a></p>
  <p></p>
</body>
</html>