<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<p>Xin chào nhân viên, <b>{{$email->name}}</b></p>
<p>Công ty TNHH PH, xin chúc mừng bạn đã trở thành nhân viên chính thức của công ty</p>
<p>Đây là tài khoản và mật khẩu của bản để thể đăng nhập hệ thống chấm công</p>
<p>Tài khoản: <i>{{$email->email}}</i></p>
<p>Mật khẩu: 123456</p>
</body>
</html>