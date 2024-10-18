<!DOCTYPE html>
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
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
    <link href="{{asset('plugins/select2/css/select2.min.css')}}" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@500&family=Montserrat:wght@200&display=swap" rel="stylesheet">
    <style>
        .select2-container .select2-selection--single{
            height: 37px !important;
        }
        .select2-container--default{
            width: 100% !important;
        }
        .sms-credit{
            padding: 7px 20px;
            border-radius: 5px;
            font-weight: 800;
        }
        .balance-section{
            border-right: 3px solid ;
            padding-top: 22px;
        }
        .display-none{
            display: none;
        }
        .basic-sidebar{
            position: absolute;
            z-index: 9999;
            right: 0;
            height: -webkit-fill-available;
            top: 0;
        }
        .close-basic-sidebar{
            font-size: 29px;
            font-weight: 900;
            background: var(--primary);
            padding: 0px 10px;
            color: #FFFFFF;
        }
        .responsive-toggle{
            display: none;
        }
        .height-300{
            height: 300px;
        }
        .top-up-sm-btn{
            position: relative;
            left: 77px;
        }
        @media (max-width: 1100px) {
            .responsive-toggle{
                display: block;
            }
            .responsive-profile{
                display: block;
            }
        }
        .notice-card{
            background: #c1afaf2b;
        }
        .notice-modal-body{
            min-height: 300px;
            overflow-y: scroll;
        }
        .c-pointer{
            cursor: pointer;
        }
        .notice-row{
            padding: 5px 20px;
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
    </style>

    @php $themeData=json_decode(get_settings('theme_customize'))?json_decode(get_settings('theme_customize')):''; @endphp
    <style>
        .select2-container .select2-selection--single{
            height: 37px !important;
        }
        .lang-colour{
            background-color: #e6f7ff;
        }
        .lang-colour i{
            color: black;
            font-size: 24px;
        }
        .dark-mode {
            background-color: #454d55!important;
            color: #fff;
        }
        html[theme='dark-mode'] {
            filter: invert(1) hue-rotate(180deg);
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
            top: 0;
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
        .celeste_resources p{
        font-size: 16px;
        color: white;
        font-weight: 700;
        text-transform: uppercase;
        }
        .sidebar{
            padding-left: 22px;
            padding-right: 15px;
        }
        .login_info{
            font-size: 15px;
            color: #a7aee2;
        }
        .overview p{
            font-size: 15px;
            color: #a7aee2;
        }
        .overview-info p{
            font-size: 20px;
            font-weight: 700;
            color: #fff;
        }
        .nav-sidebar .nav-link{
            padding: 3px 0px  !important;
        }
        .nav-sidebar .nav-link p{
            font-size: 20px !important;
            line-height: 50px !important;
            color: #a7aee2;
        }
        .nav-item a.nav-link {
         font-size: 24px !important;
        }
        .celeste_resources{
            font-size: 21px;
            font-weight: 500;
            margin-bottom: 0px;
            text-transform: uppercase;
        }
        .celeste_resource p{
            font-size: 24px;
            color: #fff;
        }
        .agent_code{
            font-size: 13px;
            color: #827b7b;
        }
        .main-header {
        margin-left: 300px !important;
        top: 0px !important;
        }
        .main-sidebar {
        top: 0px !important;
        width: 300px !important;
        }
        .nav-item a.shortcuts {
            padding-left:30px;
            padding-right:30px;
            color: #64666d !important;
            font-size: 18px !important;
        }
        .menu-open .nav-link-active {
            background-color: #406ddd;
            box-shadow: none !important;
        }
        [class*=sidebar-dark-] .nav-treeview>.nav-item>.nav-link.active, [class*=sidebar-dark-] .nav-treeview>.nav-item>.nav-link.active:focus, [class*=sidebar-dark-] .nav-treeview>.nav-item>.nav-link.active:hover {
        background-color: transparent;
        }
        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active, .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active {
            padding-left: 10% !important;
        }
        .nav-sidebar .nav-link>.right, .nav-sidebar .nav-link>p>.right {
        right: 33px;
        top: 17px;
        }
        body:not(.sidebar-mini-md) .content-wrapper, body:not(.sidebar-mini-md) .main-footer, body:not(.sidebar-mini-md) .main-header {
        margin-left: 300px;
        }
        .layout-logo {
        max-height: 43px !important;
        }
    </style>

    @yield('extra-css')
</head>
@php
$auth_id = auth()->user()->id;
 @endphp
<body class="hold-transition sidebar-mini">
<div class="wrapper">


    <!-- Navbar -->
    <nav class="main-header {{session()->get('customer_session_'.auth('customer')->user()->id)?'if_session_main_header':''}} navbar navbar-expand navbar-white navbar-light">

        <!-- Left navbar links -->
        <ul class="navbar-nav">

            <li class="nav-item cache_clear_btn" style="display: none">
                <a class="nav-link" title="{{trans('customer.clear_cache')}}" href="{{route('customer.clear.cache')}}"><i class="fa fa-sync text-danger"></i></a>
            </li>

        </ul>

@php
    $currentPlan= cache('current_plan_'.auth('customer')->user()->id);
     $admin_id = session()->get('admin_id');
@endphp
        <!-- Right navbar links -->
        <ul class="navbar-nav mr-auto">
            @if(isset(get_settings('notice_status')->notice_status) && get_settings('notice_status')->notice_status=='enable')
            <li class="nav-item dropdown user-menu mr-4 view-notice top-up-section pt-2">
                <button class="btn btn-sm btn-warning" type="button">Notice</button>
            </li>
            @endif
            <li class="nav-item">

                <p class="agent_code">
                    <span>
                        {{trans('admin.agent_code')}} :
                    </span>
                    <span>
                        {{auth('customer')->user()->agent_code}}
                    </span>
                </p>
            </li>
        </ul>
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link shortcuts" href="#"> {{trans('admin.shortcuts')}} </a>
            </li>
            <li class="nav-item">
                <a class="nav-link shortcuts" href="#"><i class="fa fa-question-circle"></i> {{trans('admin.help')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link shortcuts" href="{{route('customer.logout')}}"> {{trans('admin.logout')}}</a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar {{session()->get('customer_session_'.auth('customer')->user()->id)?'if_session_main_sidebar':''}} sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a  class="brand-link text-center">
            @if(get_settings('app_logo'))
            <img class="layout-logo" src="{{asset('uploads/'.get_settings('app_logo'))}}" alt="">
            @endif
        </a>

        <!-- Sidebar -->
        <div class="sidebar">


            <!-- Sidebar Menu -->
            <nav class="mt-3">
                @include('layouts.includes.customer_sidebar')
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper {{session()->get('customer_session_'.auth('customer')->user()->id)?'if_session_custom_content-wrapper':''}}">
        @yield('content')
    </div>
    <!-- /.content-wrapper -->

    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="float-right">
            <strong>{{trans('admin.copyright')}} &copy; {{date('Y')}} <a target="_blank"
                                                                            href="#">
                    @if(auth('customer')->user()->type=='normal')
                        {{get_settings('app_name')}}
                    @else
                        {{getCustomerSettings('app_name')}}
                    @endif
                </a>.</strong> {{trans('admin.all_rights_reserved')}}
            .
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
                    <h4 class="modal-title">{{trans('customer.confirmation')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer p-2">
                    <button id="modal-confirm-btn" type="submit"
                            class="btn btn-primary btn-sm">{{trans('customer.confirm')}}</button>
                    <button type="button" class="btn btn-secondary btn-sm"
                            data-dismiss="modal">{{trans('customer.cancel')}}</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </form>
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- Modal -->
<div class="modal fade" id="topUpModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Top-Up</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
</div>

@if(get_settings('notice_status') && get_settings('notice_status')=='enable')
<div class="modal fade" id="noticeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Notice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body notice-modal-body">
                @if(getNotices() != 'null')
                @foreach(getNotices('5') as $notice)
                    <div class="card notice-card ">
                        <div class="row notice-row">
                            <div class="pt-2 col-md-12">
                                <strong >{{$notice->title}}</strong>
                            </div>
                            <div class="col-md-10">
                                {!! isset($notice->description)?clean($notice->description):'' !!}
                            </div>
                            <div class="col-md-2">
                                @if($notice->attached_data)
                                    <strong class="float-right d-block mt-2">
                                        <a href="{{route('customer.download.notice.file', ['id'=>$notice->id])}}"><i class="fa fa-download downloadAttachedData mr-2 c-pointer" data-id="{{$notice->id}}"></i></a>
                                    </strong>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                @else
                        <div class="text-center p-4 mt-4">
                           <strong class="text-danger">No data available</strong>
                        </div>
                    @endif
            </div>

            <div class="modal-footer justify-content-end">
                <a class="btn btn-sm btn-primary float-right" target="_blank" href="{{route('customer.all.notices')}}">View All</a>
            </div>
        </div>
    </div>
</div>
@endif
<!-- REQUIRED SCRIPTS -->
<script>
    "use strict";
</script>
<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('js/adminlte.min.js')}}"></script>
<script src="{{asset('js/readmore.min.js')}}"></script>
<script src="{{asset('js/custom.js')}}"></script>
<script src="{{asset('plugins/select2/js/select2.full.js')}}"></script>
<script>
    jQuery('button[type="submit"]').on('click', function (e) {
        var form = $(this).parents('form:first');
        if (form) {
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



    $(document).on('keyup or paste', "input[name=amount]", function (e){
        let amount = $(this).val();
        let rate = '{{isset($currentPlan->sms_unit_price)?$currentPlan->sms_unit_price:0}}';
        let total=0;

            total = amount / rate;
        if(rate <= 0) {
            total=amount;
        }
        let grandTotal = Math.floor(total);
        $('.totalCredit').text(grandTotal);
    });

    $(document).on('click', '.topUpBtn', function (e){
        const type=$(this).attr('data-type');
        const dataRate= $(this).attr('data-rate');

        $("input[name=topup_type]").val(type);
        $("input[name=rate]").val(dataRate);
        if(type=='masking'){
            $('#maskingType').text('Masking Rate')
        }else{
            $('#maskingType').text('Non Masking Rate')
        }

        $('#topUpModal').modal('show');
        $('.basic-sidebar').addClass('display-none');
    });

    $(document).on('click', '.responsive-toggle', function (e){
        $('.basic-sidebar').removeClass('display-none');
    });

    $(document).on('click', '.content-wrapper',function(e){
        $(".basic-sidebar").addClass('display-none');
    });
    $(document).on('click', '.close-basic-sidebar',function(e){
        $(".basic-sidebar").addClass('display-none');
    });
    $(document).on('click', '.view-notice', function (e){
        $('#noticeModal').modal('show')
        $(".basic-sidebar").addClass('display-none');
    })
</script>

<script>
    const getNotice = localStorage.getItem('noticeCounter');

    if (!getNotice) {
        $(document).ready(function() {
            $('#noticeModal').modal('show')
        });
    }
    localStorage.setItem('noticeCounter', 'true');
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
    <x-alert :type="session()->get('success')?'success':'danger'" :is-errors="$errors"
             :message="session()->get('success')??session()->get('fail')"/>
@endif

<script>
    $.ajax({
        type:'GET',
        url:'{{route('customer.notification.counter')}}',
        success: function (res){
            let data =res[0];
            if(res.status=='success') {

                if(data.plan_request > 0){
                    $('.plan_counter').removeClass('d-none');
                    $('.plan_counter').text(data.plan_request);
                    $('.seller_plan_request').text(data.plan_request);
                }
                if(data.topUpReq > 0){
                    $('.topup_counter').removeClass('d-none');
                    $('.topup_counter').text(data.topUpReq);
                }
                if(data.inboxCount > 0){
                    $('.inbox_counter').removeClass('d-none');
                    $('.inbox_counter').text(data.inboxCount);
                }
            }
        }
    });

    $(document).on('click', '.submit_topup_form', function(e){
        const amount=$('.top_up_amount').val();

        if(!amount || amount==0){
            $('.top_up_amount').addClass('border-1x-red');
            return;
        }

        $('#topup_form').submit();
    });

    $(document).on('click or keyup', '.top_up_amount', function(e){
        $('.top_up_amount').removeClass('border-1x-red');
    })

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
</script>

@yield('extra-scripts')
</body>
</html>
