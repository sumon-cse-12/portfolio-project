@extends('layouts.admin')

@section('title','Settings')

@section('extra-css')


    <style>
        #email_temp .nav-link{
            width: 100% !important;
        }
    </style>
@endsection

@section('content')
    <section class="content-header">

    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12 mx-auto col-sm-10">
                <!-- Custom Tabs -->
                <div class="card">

                    <div class="card-header d-flex p-0">
                        <div class="row">
                            <h2 class="card-title p-3"><a href="{{route('admin.settings.index')}}">@lang('admin.settings')</a></h2>
                            <ul class="nav nav-pills ml-auto p-2 custom-ul-section">
                                <li class="nav-item"><a class="nav-link active" href="#profile_tab"
                                                        data-toggle="tab">@lang('admin.profile')</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="#application_tab"
                                                        data-toggle="tab">@lang('admin.application')</a></li>
                                <li class="nav-item"><a class="nav-link d-none" href="#smtp_tab"
                                                        data-toggle="tab">@lang('admin.settings.smtp')</a></li>

                                <li class="nav-item d-none"><a class="nav-link d-none apiTab" href="#api_tab"
                                                        data-toggle="tab">@lang('admin.settings.api')</a></li>
                                <li class="nav-item d-none"><a class="nav-link d-none" href="#whatsapp_api"
                                                        data-toggle="tab">@lang('admin.settings.whatsapp_api')</a></li>

 <li class="nav-item"><a class="nav-link d-none" href="#cacheSettings"
                                                        data-toggle="tab">@lang('admin.settings.cache_settings')</a></li>



                                <li class="nav-item d-none"><a class="nav-link" href="#emailTemplate"
                                                        data-toggle="tab">{{trans('admin.settings.email_template')}}</a>

                                <li class="nav-item d-none"><a class="nav-link" href="#local_setting_tab"
                                                        data-toggle="tab">{{trans('admin.settings.local_setting')}}</a>
                                </li>
                                <li class="nav-item d-none"><a class="nav-link" href="#sending_setting" id="sending_setting_nav"
                                                        data-toggle="tab">{{trans('admin.settings.sending_setting')}}</a>
                                </li>
                                <li class="nav-item d-none"><a class="nav-link" href="{{url('translations')}}"
                                                        target="_blank">{{trans('admin.settings.translations')}}</a>
                                </li>
                            </ul>
                        </div>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="profile_tab">
                                <form method="post" role="form" id="profile_form"
                                      action="{{route('admin.settings.profile_update')}}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="alert alert-primary" role="alert">
                                        This email & password is an admin login credentials. Try to set strong password to save you system from HACKER
                                    </div>

                                    @include('admin.settings.form')

                                    <button type="submit"
                                            class="btn btn-primary">@lang('admin.submit')</button>
                                </form>
                            </div>

                            <div class="tab-pane" id="application_tab">
                                <form method="post" role="form" id="application_form"
                                      action="{{route('admin.settings.app_update')}}" enctype="multipart/form-data">
                                    @csrf
                                    @include('admin.settings.app_update_form')

                                    <button type="submit"
                                            class="btn btn-primary">@lang('admin.submit')</button>
                                </form>
                            </div>


                            <div class="tab-pane" id="smtp_tab">
                                <form method="post" role="form" id="smtp_form"
                                      action="{{route('admin.settings.smtp_update')}}" enctype="multipart/form-data">
                                    @csrf

                                    <div class="alert alert-primary" role="alert">
                                        SMTP is a very important part of system. Without configuration of SMTP settings your customer could not register to your site.
                                    </div>

                                    @include('admin.settings.smtp_form')

                                    <button type="submit"
                                            class="btn btn-primary">@lang('admin.submit')</button>
                                </form>
                            </div>


                            <div class="tab-pane" id="api_tab">
                                <form method="post" role="form" id="api_form"
                                      action="{{route('admin.settings.app_update')}}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="alert alert-primary" role="alert">
                                        30+ SMS gateway available. Choose your best list & configure the settings.
                                    </div>
                                    @include('admin.settings.api_form')

                                    <button id="submit_api" type="button"
                                            class="btn btn-primary">@lang('admin.submit')</button>
                                </form>
                            </div>

                            {{-- <div class="tab-pane" id="whatsapp_api">
                                <form method="post"
                                      action="{{route('admin.settings.whatsapp.api')}}" enctype="multipart/form-data">
                                    @csrf

                                    @include('admin.settings.whatsapp_api')

                                    <button  type="submit"
                                            class="btn btn-primary">@lang('admin.submit')</button>
                                </form>
                            </div> --}}


                            <div class="tab-pane" id="cacheSettings">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-primary" role="alert">
                                                {{trans('customer.message.msg_log_del_alert')}}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{trans('admin.from_date')}}</label>
                                                <input  type="date" name="from_date" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">{{trans('admin.to_date')}}</label>
                                                <input  type="date" name="to_date" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mt-2">
                                            <button  type="button" class="btn btn-primary logDeleteConfirm">@lang('admin.submit')</button>
                                        </div>
                                    </div>

                            </div>

                            <div class="tab-pane" id="local_setting_tab">
                                <form method="post" role="form" id="smtp_form"
                                      action="{{route('admin.settings.local.setting')}}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="alert alert-primary" role="alert">
                                        Configure local settings by setup language, timezone, currency symbol, currency code .
                                    </div>

                                    @include('admin.settings.local_setting_form')

                                    <button type="submit"
                                            class="btn btn-primary">@lang('admin.submit')</button>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="emailTemplate">
                                <div class="alert alert-primary" role="alert">
                                    Without configuration of email template, users/customers do not get email.
                                </div>

                                @include('admin.settings.email_template')
                            </div>

                            <div class="tab-pane" id="sending_setting">
                                <form method="post" role="form" id="application_form"
                                      action="{{route('admin.settings.sending.setting')}}" enctype="multipart/form-data">
                                    @csrf

                                    @include('admin.settings.sending_settings')

                                    <button type="submit"
                                            class="btn btn-primary">@lang('admin.submit')</button>
                                </form>
                            </div>

                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- ./card -->


            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->

    <div class="modal fade" id="cacheLogModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="m-0" action="{{route('admin.settings.cache')}}" method="post">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">{{trans('layout.confirmation')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <input type="hidden" name="to" class="to_date">
                    <input type="hidden" name="from" class="from_date">
                    <div class="modal-body">
                        <p>{{trans('customer.message.msg_log_del_warn')}}</p>
                        <small class="mt-2 text-danger">{{trans('customer.message.log_undone_alert')}}</small>
                    </div>
                    <div class="modal-footer p-2">
                        <button type="submit" class="btn btn-primary">{{trans('admin.confirm')}}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('extra-scripts')
    <script src="{{asset('plugins/jquery-validation/jquery.validate.min.js')}}"></script>

    <script src="{{asset('plugins/bs-custom-file-input/bs-custom-file-input.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script !src="">
        "use strict";
        let $validate;
        $validate = $('#profile_form').validate({
            rules: {
                email: {
                    required: true,
                    email: true,
                },
                first_name: {
                    required: true
                },
                last_name: {
                    required: true
                },
            },
            messages: {
                email: {
                    required: "Please enter a email address",
                    email: "Please enter a vaild email address"
                },
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long"
                },
                first_name: {required: "Please provide first name"},
                last_name: {required: "Please provide last name"}
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
        $(document).ready(function () {
            bsCustomFileInput.init();
        });

        $('#gateway').select2({
            multiple:false
        }).on('change', function (e) {
            e.preventDefault();
            const type = $(this).val();
            $('.api-section').hide();
            $('#' + type + "_section").show();
        });

        $('#voice_call_gateway').select2({
            multiple:false
        }).on('change', function (e) {
            e.preventDefault();
            const type = $(this).val();
            $('.voice-call-api-section').hide();
            $('#' + type + "_section").show();
        });
        $('#whatsapp_gateway').select2({
            multiple:false
        }).on('change', function (e) {
            e.preventDefault();
            const type = $(this).val();
            $('.whatsapp-api-section').hide();
            $('#' + type + "_section").show();
        });
        $('#timezone').select2();

        $('#submit_api').on('click', function (e) {
            e.preventDefault();
            const form = $('#api_form').serialize();
            $.ajax({
                method: 'post',
                url: '{{route('admin.settings.api_update')}}',
                data: form,
                success: function (res) {
                    if (res.status == 'success') {
                        notify('success', res.message);
                    }
                }
            })
        });


        $('#offDay').select2({
            placeholder:'Select an offday',
            multiple:true
        }).val(@json($offdays)).change();

        $('.message_limit').on('keyup or paste', function (e){
          let  message_limit = $(this).val();
            $('#message_limit').text(message_limit?message_limit:'0');
        });
        $('.minutes').on('keyup or paste', function (e){
            let minute_limit = $(this).val()
            $('#minutes').text(minute_limit?minute_limit:'0');
        });


        $(document).on('change', '#otpGateway', function(e){
            const from = $(this).val();


            $.ajax({
                type:'GET',
                url:'{{route('admin.settings.gateway.numbers')}}',
                data:{
                    from:from
                },

                success:function(res){
                    let html='';
                    $.each(res.data, function (index, value){
                        html+=`<option value="${value.number}">${value.number}</option>`
                    });

                    $('#otpFromNumber').html(html);
                }
            })
        });

        $('#otpFromNumber').select2({
            multiple:false
        });


    </script>

    <script>
        $(document).on('click','.logDeleteConfirm', function(e){

            const to_date=$('input[name=to_date]').val();
            const from_date=$('input[name=from_date]').val();

            if(!from_date){
                toastr.error('Please select from date');
                return;
            }

            if(!to_date){
                toastr.error('Please select to date');
                return;
            }


            $('.to_date').val(to_date);
            $('.from_date').val(from_date);

            $('#cacheLogModal').modal('show');

            // data-input={"id":'.$q->id.'}

        })
    </script>

@endsection

