<!DOCTYPE html>
@php $siteDirection=isset(json_decode(get_settings('local_setting'))->direction)?json_decode(get_settings('local_setting'))->direction:'ltr'; @endphp
<html lang="en" dir="{{$siteDirection}}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- Theme style -->
    <link rel="shortcut icon" href="{{asset('uploads/'.get_settings('app_favicon'))}}" type="image/x-icon">

    <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">


    <link rel="stylesheet" href="{{asset('css/adminlte.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/custom.css')}}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@500&family=Montserrat:wght@200&display=swap" rel="stylesheet">
    @yield('extra-css')
    <style>
        .login-body{
            width: 30rem;
        }
        .login-img{
            width: 100%;
            background-image: url('{{asset('images/Tablet login-rafiki.svg')}}');
            background-repeat: no-repeat;
            background-color: #f8f8f8;
            height: 100vh;
        }
        body{
            overflow: hidden;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
        }
        @media (max-width: 768px){
            .login-img{
                display: none;
            }
            .login-alien{
                margin-top: 150px;
            }
        }
    </style>
</head>
<body class="hold-transition">
<div class="row login-alien">
    <div class="col-lg-5 m-auto d-flex justify-content-center">
        <div class="login-body">
            <div class="login-logo">
                Welcome to <a href="{{route('login')}}"><b>{{get_settings('app_name')}}</b></a>
            </div>
            @yield('content')
        </div>

    </div>
    <div class="col-lg-7">
        <div class="login-img"></div>
    </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('js/adminlte.min.js')}}"></script>

@if(session()->has('success') || session()->has('fail') || count($errors)>0)
    <x-alert :type="session()->get('success')?'success':'danger'" :is-errors="$errors" :message="session()->get('success')??session()->get('fail')"/>
@endif

@yield('extra-script')

</body>
</html>
