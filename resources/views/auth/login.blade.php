<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>HADES - Quản Lý Nhân Sự</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('default/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('default/bower_components/font-awesome/css/font-awesome.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('default/bower_components/Ionicons/css/ionicons.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('default/dist/css/AdminLTE.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('default/plugins/iCheck/square/blue.css')}}">
    <link rel="stylesheet" href="{{ asset('default/dist/css/spx.css')}}">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <!--[if IE]>
    <style>
        .login-box-body{
            border: 1px solid #ddd;
        }
    </style>
    <![endif]-->
    <style>
        body.login-page {
            background: #0d0000 !important;
            background-image: radial-gradient(ellipse at top, #2a0000 0%, #0d0000 70%) !important;
            min-height: 100vh;
        }
        .login-box {
            padding-top: 50px;
        }
        .login-logo {
            background: #1a0000;
            margin-bottom: 0;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            box-shadow: 0 1px 4px 0 rgba(204,0,0,0.3), 0 2px 10px 0 rgba(204,0,0,0.2) !important;
            border-top: 3px solid #cc0000;
            padding: 20px 10px 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }
        .login-logo img {
            width: 70px;
            height: 70px;
            border-radius: 8px;
            border: 2px solid #cc0000;
            box-shadow: 0 0 15px rgba(204,0,0,0.5);
        }
        .login-logo a {
            text-transform: uppercase;
            font-size: 28px;
            color: #fff !important;
            font-family: 'Source Sans Pro', sans-serif;
            font-weight: 700;
            letter-spacing: 4px;
            text-shadow: 0 0 10px rgba(204,0,0,0.8);
        }
        .login-logo .tagline {
            color: #cc0000;
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .login-box-body {
            background: #1a1a1a !important;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            box-shadow: 0 1px 4px 0 rgba(204,0,0,0.2), 0 2px 10px 0 rgba(0,0,0,0.5) !important;
            border: 1px solid #333;
            border-top: none;
        }
        .login-box-msg {
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
            color: #cc0000 !important;
        }
        .login-box-body .form-control {
            background-color: #2b2b2b !important;
            border-color: #444 !important;
            color: #e0e0e0 !important;
        }
        .login-box-body .form-control:focus {
            border-color: #cc0000 !important;
            box-shadow: 0 0 6px rgba(204,0,0,0.4) !important;
        }
        .login-box-body .form-control::placeholder {
            color: #888 !important;
        }
        .login-box-body .glyphicon {
            color: #cc0000 !important;
        }
        .btn-login {
            background: #cc0000 !important;
            color: #fff !important;
            border: none;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: background 0.2s, box-shadow 0.2s;
        }
        .btn-login:hover {
            background: #990000 !important;
            color: #fff !important;
            box-shadow: 0 0 10px rgba(204,0,0,0.5);
        }
    </style>
</head>
<body class="hold-transition login-page">
@if(Auth::user())
    <script>window.location.href='/';</script>
@endif
<div class="login-box">
    <div class="login-logo">
        <img src="/img/hades-logo.png" alt="Hades Logo">
        <a href="/">HADES</a>
        <span class="tagline">Hệ Thống Quản Lý Nhân Sự</span>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
{{--        <div class="logo-login">--}}
{{--            <img width="200" src="http://itplus-academy.edu.vn/public/images/logo.png" alt=""><br><br>--}}
{{--        </div>--}}
        <form action="{{ url('/login') }}" method="post">
            <div class="form-group has-feedback">
                <input style="padding-left: 10px;" type="text" name="email" class="form-control" placeholder="Username or Email">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input style="padding-left: 10px;" type="password" name="password" class="form-control" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
            {{--<div class="col-xs-8">--}}
            {{--<div class="checkbox icheck">--}}
            {{--<label>--}}
            {{--<input type="checkbox" name="chk_remmember"> <span>Remember Me</span>--}}
            {{--</label>--}}
            {{--</div>--}}
            {{--</div>--}}
            <!-- /.col -->
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-block btn-flat text-center btn-login">Sign In</button>
                </div>
                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                <!-- /.col -->
            </div>
        </form>
        <p class="text-danger login-box-msg">Vui lòng đăng nhập để tiếp tục!
        <?php //Hiển thị thông báo thành công?>
        @if ( Session::has('success') )
            <div class="alert alert-success alert-dismissible" role="alert">
                <strong>{{ Session::get('success') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
            </div>
        @endif
        <?php //Hiển thị thông báo lỗi?>
        @if ( Session::has('error') )
            <div class="alert alert-danger alert-dismissible" role="alert">
                <strong>{{ Session::get('error') }}</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
            </div>
       @endif
{{--        @foreach ($errors  as $error)--}}
{{--            <p class="alert alert-danger">{{ $error }}</p>--}}
{{--        @endforeach--}}

{{--        @foreach ($success ?? ''  as $msg)--}}
{{--            <p class="alert alert-success">{{ $msg }}</p>--}}
{{--        @endforeach--}}
{{--        @foreach ($warnings  as $msg)--}}
{{--            <p class="alert alert-warning">{{ $msg }}</p>--}}
{{--            @endforeach--}}
{{--            </p>--}}

            {{--<div class="social-auth-links text-center">--}}
            {{--<p>- OR -</p>--}}
            {{--<a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using--}}
            {{--Facebook</a>--}}
            {{--<a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using--}}
            {{--Google+</a>--}}
            {{--</div>--}}
        <!-- /.social-auth-links -->

            {{--<a href="#">I forgot my password</a><br>--}}
            {{--<a href="register" class="text-center">Register a new membership</a>--}}

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="{{ asset('default/bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('default/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- iCheck -->
<script src="{{ asset('default/plugins/iCheck/icheck.min.js')}}"></script>
<script src="{{ asset('default/dist/js/spx.js')}}?v=1"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' /* optional */
        });
    });
</script>
</body>
</html>
