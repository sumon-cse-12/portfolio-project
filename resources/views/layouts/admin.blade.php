<!DOCTYPE html theme="auto">
@php $siteDirection=isset(json_decode(get_settings('local_setting'))->direction)?json_decode(get_settings('local_setting'))->direction:'ltr'; @endphp
<html lang="en" dir="{{$siteDirection}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>@yield('title')</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('css/adminlte.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/custom.css')}}">
    @if(get_settings('app_favicon'))
    <link rel="shortcut icon" href="{{asset('uploads/'.get_settings('app_favicon'))}}" type="image/x-icon">
    @endif
    <link href="{{asset('plugins/select2/css/select2.min.css')}}" rel="stylesheet" />

    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @php $themeData=json_decode(get_settings('theme_customize'))?json_decode(get_settings('theme_customize')):''; @endphp
    <style>
        .select2-container .select2-selection--single{
            height: 37px !important;
        }

        .lang-colour i{
            color: black;
            font-size: 30px;
        }
        .dark-mode {
            background-color: #454d55!important;
            color: #fff;
        }
        html[theme='dark-mode'] {
            filter: invert(1) hue-rotate(180deg);
        }
        .counter{
            float: right;
            position: absolute;
            background: #dc4745;
            padding: 3px 8px 1px 7px;
            font-size: 11px;
            color: white;
            border-radius: 50%;
            right: 0px;
            z-index: 9999;
            margin-top: 7px;
            font-weight: 600;
        }
        @if(isset($themeData->navbar_color))
            .main-header {
            background: {{$themeData->navbar_color}};
        }
        @endif
        @if(isset($themeData->left_sidebar))
            .main-sidebar {
            background: {{$themeData->left_sidebar}};
        }
        @endif

        @if(isset($themeData->active_sidebar))
            .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active, .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active {
            background-color: {{$themeData->active_sidebar}};
        }
        @endif


        .main-sidebar{
            bottom: 0;
            float: none !important;
            left: 0;
            position: fixed !important;
            top: 0px !important;
        }
        .main-header {
            left: 0;
            position: fixed !important;
            right: 0;
            top: 0px !important;
            z-index: 1037 !important;
        }
        .main-sidebar::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            background-color: #F5F5F5;
        }

        .main-sidebar ::-webkit-scrollbar {
            width: 7px;
            background-color: #ffffff40;
        }

        .main-sidebar ::-webkit-scrollbar-thumb {
            border: 2px solid #A2ADB7;
            background: #A2ADB7;
            border-radius: 7px;
        }
        .sidebar{
            background-color: #192e72 !important;
        }
        .nav-sidebar .nav-link p {
            color: #fff;
        }
        .nav-icon{
            color: #fff;
        }
        .nav-sidebar .nav-link:hover p {
        color:#fff !important;
        }
        [class*=sidebar-dark-] .nav-treeview>.nav-item>.nav-link.active, [class*=sidebar-dark-] .nav-treeview>.nav-item>.nav-link.active:focus, [class*=sidebar-dark-] .nav-treeview>.nav-item>.nav-link.active:hover {
        background-color: transparent !important;
        }
        .layout-logo {
        max-height: 43px !important;
        }
        .custom-menu-li{
            color: white;
            padding: 0px 15px;
            font-size: 15px;
            border-bottom: 1px solid #ffffff5e;
            margin-bottom: 20px;
        }
    </style>
    @yield('extra-css')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown d-none">
                <a class="nav-link lang-colour" data-toggle="dropdown" href="#" aria-expanded="false">
                    <i class="fa fa-language text-primary"></i>
                </a>
                <div class="dropdown-menu" style="left: inherit; right: 0px; text-align: center">
                    <span class="dropdown-item dropdown-header">Language</span>
                    @if(get_available_languages())
                    @foreach(get_available_languages() as $lang)
                        <a href="{{route('set.locale',['type'=>$lang])}}" class="dropdown-item">
                            <i class="fa fa-language mr-2"></i> {{$lang}}
                        </a>
                    @endforeach
                    @endif
                </div>
            </li>
            <li class="nav-item ml-3 mr-3">
                <div class="panel-text text-primary">{{trans('admin.admin_panel')}}</div>
                <div class="panel-text panel-date">{{date('d M Y')}}</div>
            </li>
            <li class="nav-item dropdown user-menu">
                <a href="{{route('admin.settings.index')}}" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                    <img src="{{asset('uploads/'.auth()->user()->profile_picture)}}" class="user-image img-circle elevation-2" alt="img">
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right dropdown-profile">
                    <!-- User image -->
                    <li class="user-header bg-primary">
                        <img  src="{{asset('uploads/'.auth()->user()->profile_picture)}}"  class="img-circle elevation-2" alt="img">

                        <p>
                            {{auth()->user()->name}}
                            <small>{{trans('admin.member_since')}} {{date('M. Y')}}</small>
                        </p>
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <a href="{{route('admin.settings.index')}}" class="btn btn-default btn-flat">{{trans('admin.profile')}}</a>
                        <a href="{{route('admin.logout')}}" class="btn btn-default btn-flat float-right">{{trans('admin.sign_out')}}</a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a class="brand-link text-center">
            @if(get_settings('app_logo'))
                <img class="layout-logo" src="{{asset('uploads/'.get_settings('app_logo'))}}" alt="">
            @endif
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
{{--            <div class="user-panel mt-3 pb-3 mb-3 d-flex">--}}
{{--                <div class="image">--}}
{{--                    <img  src="{{asset('uploads/'.auth()->user()->profile_picture)}}"  class="img-circle elevation-2" alt="User Image">--}}
{{--                </div>--}}
{{--                <div class="info">--}}
{{--                    <a href="{{route('admin.settings.index')}}" class="d-block">{{auth()->user()->name}}</a>--}}
{{--                </div>--}}
{{--            </div>--}}

            <!-- Sidebar Menu -->
            <nav class="mt-3">
                @include('layouts.includes.admin_sidebar')
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @yield('content')
    </div>
    <!-- /.content-wrapper -->

    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="float-right">
            <strong>{{trans('admin.copyright')}} &copy; {{date('Y')}} <a target="_blank" href="#">{{get_settings('app_name')}}</a>.</strong> {{trans('admin.all_rights_reserved')}}.
        </div>

    </footer>
</div>
<!-- ./wrapper -->

<!-- Confirmation modal -->
<div class="modal fade" id="modal-confirm">
    <div class="modal-dialog">
        <form id="modal-form">
            @csrf
            <div id="customInput"></div>
        <div class="modal-content">
            <div class="modal-header p-2">
                <h4 class="modal-title">{{trans('admin.confirmation')}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer p-2">
                <button id="modal-confirm-btn" type="button" class="btn btn-primary btn-sm">{{trans('admin.confirm')}}</button>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">{{trans('admin.cancel')}}</button>
            </div>
        </div>
        <!-- /.modal-content -->
        </form>
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('js/adminlte.min.js')}}"></script>
<script src="{{asset('js/custom.js')}}"></script>
<script src="{{asset('plugins/select2/js/select2.full.js')}}"></script>
<script>
    jQuery('button[type="submit"]').on('click', function (e) {
        var form = $(this).parents('form:first');
        if (form.valid()) {
            $(this).attr('disabled', 'disabled').addClass('disabled')
            $(this).html(' <i class="fa fa-spinner fa-spin"></i> Loading');
            form.submit();
        }
    });
    jQuery('#modal-confirm-btn').on('click', function (e) {
        var form = $(this).parents('form:first');
        if (form.valid()) {
            $(this).attr('disabled', 'disabled').addClass('disabled')
            $(this).html(' <i class="fa fa-spinner fa-spin"></i> Loading');
            form.submit();
        }
    });
</script>
<script>
    $(document).on('click','.gateway-bb', function (e){
        const type = $(this).attr('data-type');
        localStorage.setItem("gateway_type", type);
    });
    $(document).on('click','.sending-setting', function (e){
        const type = $(this).attr('data-type');
        localStorage.setItem("sending_setting", type);
    });

    $(document).on('click','.nav-link', function (e){
        const sendingClass = $(this).hasClass('sending-setting');
        const gatewayClass = $(this).hasClass('gateway-bb');
        if(!sendingClass){
            localStorage.removeItem("sending_setting");
        }
        if(!gatewayClass){
            localStorage.removeItem("gateway_type");
        }
    });

</script>
<script>
    if ('{{request()->segment(2)== 'settings'}}') {
        const gateway = localStorage.getItem("gateway_type");
        const sending_setting_nav = localStorage.getItem("sending_setting");

        if (gateway) {
            $("#" + gateway).trigger('click');
            $('.gateway-bb').addClass('active');
        }
        if (sending_setting_nav) {
            $("#" + sending_setting_nav).trigger('click').addClass('active');
            $('.sending-setting').addClass('active');
        }
    }else {
        localStorage.clear();
    }
</script>
@if(isset($themeData->type))
<script>
    const type ='{{$themeData->type}}';
    if(type=='dark'){
        $('html').attr('theme', 'dark-mode')
    }else{
        $('html').attr('theme', 'light-mode')
    }
</script>
@endif
@if(isset($themeData->collapse_sidebar) && $themeData->collapse_sidebar=='true')
<script>
    $('.fa-bars').trigger('click');
</script>
@endif

@if(session()->has('success') || session()->has('fail') || count($errors)>0)
<x-alert :type="session()->get('success')?'success':'danger'" :is-errors="$errors" :message="session()->get('success')??session()->get('fail')"/>
@endif
<script>
    $.ajax({
        type:'GET',
        url:'{{route('admin.notification.counter')}}',
        success: function (res){
            let data =res[0];
            if(res.status=='success') {

                if(data.plan_request > 0){
                    $('.plan_counter').removeClass('d-none');
                    $('.plan_counter').text(data.plan_request);
                    $('.plan_counter_2').text(data.plan_request);
                }

                if(data.tickets > 0){
                    $('.ticket_counter').removeClass('d-none');
                    $('.ticket_counter').text(data.tickets);
                }

                if(data.verifications > 0){
                    $('.verification_counter').removeClass('d-none');
                    $('.verification_counter').text(data.verifications);
                }

                if(data.topUpReq > 0){
                    $('.topup_counter').removeClass('d-none');
                    $('.topup_counter').text(data.topUpReq);
                }

                if(data.domain > 0){
                    $('.domain_counter').removeClass('d-none');
                    $('.domain_counter').text(data.domain);
                }

                if(data.senderId > 0){
                    $('.masking_counter').removeClass('d-none');
                    $('.masking_counter').text(data.senderId);
                    $('.masking_counter_2').text(data.senderId);
                }

                if(data.numberReq > 0){
                    $('.non_masking_counter').removeClass('d-none');
                    $('.non_masking_counter').text(data.numberReq);
                    $('.numberreq_counter_2').text(data.numberReq);
                }
                if(data.whatsappReq > 0){
                    $('.whatsapp_counter').removeClass('d-none');
                    $('.whatsapp_counter').text(data.whatsappReq);
                    $('.whatsappnum_req_2').text(data.whatsappReq);
                }


            }
        }
    })
    // $(function () {
    //     $('[data-toggle="tooltip"]').tooltip()
    // })
</script>
<script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
</script>
@if(request()->get('tab') && request()->segment(2)=='settings')
    <script>
        $(document).ready(function() {
            $('.apiTab').trigger('click');
        });
    </script>
@endif
@yield('extra-scripts')
</body>
</html>
