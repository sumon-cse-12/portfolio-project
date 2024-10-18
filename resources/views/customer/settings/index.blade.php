@extends('layouts.customer')

@section('title','Settings')

@section('extra-css')
    <style>
        .clearfix{
            margin-bottom: 10px !important;
        }
    </style>
@endsection

@section('content')
    @php $skip_permission=['number_purchase','sender_id_request','manage_sender_id','sender_id_list','staff_list','staff_create','staff_edit','staff_delete','staff_manage','staff_login_as']; @endphp


    <section class="content-header">

    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12 mx-auto col-sm-10">
                <!-- Custom Tabs -->
                <div class="card">
                    <input type="hidden" value="{{request()->get('type')}}" id="url-type">
                    <div class="card-header d-flex p-0">
                        <div class="row">
                            <h2 class="card-title p-3"><a href="{{route('customer.settings.index')}}">{{trans('customer.settings')}}</a></h2>
                            <ul class="nav nav-pills ml-auto p-2 custom-ul-section">
                                <li class="nav-item"><a class="nav-link active" href="#profile_tab" data-toggle="tab">{{trans('admin.profile')}}</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="#password_tab" data-toggle="tab">{{trans('customer.password')}}</a>
                                </li>
                                @if(auth('customer')->user()->type !='users')
                                    @if(auth('customer')->user()->otp_status=='active')
                                        <li class="nav-item"><a class="nav-link" href="#otp_tab" data-toggle="tab">{{trans('OTP')}}</a>
                                        </li>
                                    @endif

                                    @if(auth('customer')->user()->type =='reseller' || auth('customer')->user()->type =='master_reseller')
                                        @if(Module::has('PaymentGateway') && Module::find('PaymentGateway')->isEnabled())
                                            <li class="nav-item"><a class="nav-link" href="#payment_gateway_tab"
                                                                    data-toggle="tab"
                                                                    id="payment_gateway_nav">@lang('paymentgateway::layout.payment_gateway')</a>
                                            </li>
                                        @endif

                                            <li class="nav-item"><a class="nav-link" href="#smtp_tab" data-toggle="tab">{{trans('SMTP')}}</a>
                                            </li>

                                            <li class="nav-item"><a class="nav-link" href="#email_template" data-toggle="tab">{{trans('Email Template')}}</a>
                                            </li>
                                    @endif

                                    <li class="nav-item"><a class="nav-link" href="#notification_tab" data-toggle="tab">{{trans('customer.general')}}</a></li>

                                    <li class="nav-item">
                                        <a class="nav-link" href="#inbound_tab" data-toggle="tab">
                                            {{trans('Inbound Setting')}}
                                        </a>
                                    </li>
                                     @can('staff_manage')
                                     <li class="nav-item">
                                        <a class="nav-link" href="#permission" data-toggle="tab">
                                            {{trans('Permission')}}
                                        </a>
                                     </li>
                                     @endcan
                                @endif
                            </ul>
                        </div>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="profile_tab">
                                <form method="post" role="form" id="profile_form"
                                      action="{{route('customer.settings.profile_update')}}" enctype="multipart/form-data">
                                    @csrf
                                    @include('customer.settings.profile_form')

                                    <button type="submit" class="btn btn-primary">{{trans('customer.submit')}}</button>
                                </form>
                            </div>

                            <div class="tab-pane" id="password_tab">
                                <form method="post" role="form" id="password_form"
                                      action="{{route('customer.settings.password_update')}}">
                                    @csrf
                                    @include('customer.settings.password_form')

                                    <button type="submit" class="btn btn-primary">{{trans('customer.submit')}}</button>
                                </form>
                            </div>


                            <div class="tab-pane" id="smtp_tab">
                                <form method="post" role="form"
                                      action="{{route('customer.smtp.settings')}}">
                                    @csrf
                                    @include('customer.settings.smtp_form')

                                    <button type="submit" class="btn btn-primary">{{trans('customer.submit')}}</button>
                                </form>
                            </div>

                            <div class="tab-pane" id="email_template">
                                @include('customer.settings.email_template')

                            </div>

                            @if(auth('customer')->user()->otp_status=='active')
                            <div class="tab-pane" id="otp_tab">
                                <form method="post" role="form"
                                      action="{{route('customer.opt.settings')}}">
                                    @csrf

                                   <div class="row pb-3">
                                       <div class="col-md-12 text-right">
                                           <button class="btn btn-primary btn-sm" type="button" id="checkOtpSend">
                                               {{trans('Test')}}
                                           </button>
                                       </div>
                                       <div class="form-group col-md-6">
                                           <label for="">From Type</label>
                                           <select name="from_type" class="form-control" id="otpFromType">
                                               <option {{isset($otp_setting->from_type) && $otp_setting->from_type=='number'?'selected':''}} value="number">{{trans('Number')}}</option>
                                               <option {{isset($otp_setting->from_type) && $otp_setting->from_type=='sender_id'?'selected':''}} value="sender_id">{{trans('Sender-ID')}}</option>
                                           </select>
                                       </div>
                                       @php $fromNumbers = auth('customer')->user()->numbers()->where('expire_date','>', now())->get() @endphp

                                       <div class="form-group col-md-6 number_section otp_section" style="display: {{isset($otp_setting->from_type) && $otp_setting->from_type=='number'?'block':'block'}}">
                                           <label for="">From Number</label>
                                           <select name="phone_number" class="form-control" id="">
                                               @foreach($fromNumbers as $key=>$number)
                                                   <option value="{{$number->number_id}}" {{isset($otp_setting->phone_number) && $otp_setting->phone_number==$number->number_id?'selected':''}}>{{$number->number}}</option>
                                               @endforeach
                                           </select>
                                       </div>


                                       @php $senderIds = auth('customer')->user()->sender_ids()->where('expire_date','>', now())->where('is_paid', 'yes')->get() @endphp
                                       <div class="form-group col-md-6 sender_id_section otp_section" style="display:{{isset($otp_setting->from_type) && $otp_setting->from_type=='sender_id'?'block':'none'}}">
                                           <label for="">Sender-ID</label>
                                           <select name="sender_id" class="form-control" id="">
                                               @foreach($senderIds as $key=>$sender_id)
                                                   <option {{isset($otp_setting->sender_id) && $otp_setting->sender_id==$sender_id->id?'selected':''}} value="{{$sender_id->id}}">{{$sender_id->sender_id}}</option>
                                               @endforeach
                                           </select>
                                       </div>

                                       <div class="col-md-12 mt-2">
                                           <label for="">{{trans('Status')}}</label>
                                           <select name="status" class="form-control" id="">
                                               <option {{isset($otp_setting->status) && $otp_setting->status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
                                               <option {{isset($otp_setting->status) && $otp_setting->status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
                                           </select>
                                       </div>

                                   </div>

                                    <button type="submit" class="btn btn-primary">{{trans('customer.submit')}}</button>
                                </form>
                            </div>
                            @endif

                            @if(Module::has('PaymentGateway') && Module::find('PaymentGateway')->isEnabled())
                                <div class="tab-pane" id="payment_gateway_tab">
                                    @if(isset($gateways->value) && json_decode($gateways->value))
                                    <form method="post" role="form" id="resellerGatewaysForm"
                                          action="{{route('paymentgateway::reseller.payment.settings.store')}}"
                                          enctype="multipart/form-data">
                                        @csrf

                                        @include('paymentgateway::settings.reseller_payment_gateway')

                                        <div class="text-right">
                                            <button id="resellerGatewaysBtn" type="button"
                                                    class="btn btn-primary">@lang('admin.submit')</button>
                                        </div>
                                    </form>
                                        @else
                                        <div class="form-group p-4">
                                            <p>{{trans('customer.empty_payment_gateway')}}</p>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <div class="tab-pane" id="notification_tab">
                                <div class="row">
                                    <div class="col-sm-10 ml-2">

                                @include('customer.settings.notification_form')

                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="inbound_tab">
                                <div class="row">
                                    <div class="col-sm-10 ml-2">
                                        <form action="{{route('customer.inbound.settings')}}" method="POST">
                                            @csrf

                                            <div class="form-group">
                                                <label for="">{{trans('Sender Type')}}</label>
                                                <select name="sender_type" class="form-control selectSender">
                                                    <option value="number">{{trans('Number')}}</option>
                                                    <option value="sender_id">{{trans('SenderID')}}</option>
                                                </select>
                                            </div>

                                            <div class="form-group inbound_section section_number">
                                                <label for="">{{trans('Form Numbers')}}</label>
                                                <select name="number" class="form-control select3">
                                                    @foreach($numbers as $number)
                                                        <option {{isset($sender_setting->number) && $sender_setting->number==$number?'selected':''}} value="{{$number->id}}">{{$number->number}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group inbound_section section_sender_id" style="display: none">
                                                <label for="">{{trans('Form Numbers')}}</label>
                                                <select name="sender_id" class="form-control select3">
                                                    @foreach($sender_ids as $senderid)
                                                        <option {{isset($sender_setting->sender_id) && $sender_setting->sender_id==$senderid->id?'selected':''}} value="{{$senderid->id}}">{{$senderid->sender_id}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group mt-3 text-right">
                                                <button type="submit" class="btn btn-sm btn-success">Submit</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="permission">
                                <div class="row">
                                    <div class="col-sm-12 ml-2">
                                        <div class="float-right">
                                            <button type="button" class="btn btn-md btn-primary float-left" id="add_new_role">{{trans('Add
                                                New Role')}}
                                            </button>
                                        </div>
                                        <div class="pt-4">
                                            <div class="col-xl-12">
                                                <div>Permission Name</div>
                                                <hr>
                                                <form action="{{route('customer.permission.update')}}" method="post">
                                                    @csrf
                                                    @method('put')
                                                    @php $counter=0;
                                                    @endphp
                                                    @foreach($roles as $role)
                                                    @if($role->name !='agency_admin')
                                                    @if(auth('customer')->user())
                                                    <div class="row mb-2" id="show_role_{{$role->name}}">
                                                        <div class="col-lg-2">
                                                         <label>{{$role->name}} <span class="float-right ml-3"><a href="#"><i
                                                                            data-role="{{$role->name}}"
                                                                            class="fa fa-trash text-danger"></i></a></span></label>
                                                        </div>
                                                        <div class="col-lg-10">
                                                            <div class="row">
                                                                <input type="hidden" value="{{$role->name}}" name="role_name[]">
                                                                @php
                                                                $pre_role= \Spatie\Permission\Models\Role::where('name',$role->name)->where('customer_id', auth('customer')->user()->id)->first();
                                                                $rolePermissions=$pre_role->getAllPermissions()->pluck('name')->toArray();
                                                                @endphp
                                                                @foreach(get_customer_permission() as $key=>$permission)
                                                                    @if(!in_array($permission, $skip_permission))
                                                                        @php $counter++; @endphp
                                                                        <div class="col-sm-4 display">
                                                                            <div class="form-group clearfix">
                                                                                <div class="icheck-success">
                                                                                    <input name="permission[{{$role->id}}][]" value="{{$permission}}"
                                                                                        {{in_array($permission,$rolePermissions)?'checked':''}}
                                                                                        type="checkbox"
                                                                                        id="checkboxSuccess_{{$permission}}_{{$role->name}}">
                                                                                    <label for="checkboxSuccess_{{$permission}}_{{$role->name}}"
                                                                                        class="text-muted d-inline ml-2">
                                                                                        {{ucfirst(str_replace('_',' ',$permission))}}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @endif
                                                    @endforeach

                                                    @if(isset($counter) && $counter > 0)
                                                    <div class="text-right mt-2">
                                                        <button type="submit" class="btn btn-primary">{{trans('Submit')}}</button>
                                                    </div>
                                                    @endif
                                                </form>


                                            </div>
                                        </div>
                                    </div>
                                </div>
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

    <!-- Modal -->

    <div class="modal fade" id="add_role_new_modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{route('customer.role.create')}}" method="post">
                    @csrf
                    @method('put')
                    <div class="modal-header">
                        <h4 class="modal-title">Role Permission</h4>
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">


                                <div class="form-group">
                                    <label for="">{{trans('Role Name')}}</label>
                                    <input type="text" name="role_name"
                                           class="form-control">
                                </div>
                            </div>
                            @foreach($permissions->whereIn('name',get_customer_permission()) as $permission)
                                @if(in_array($permission->name, get_customer_permission()))
                                    @if(!in_array($permission->name, $skip_permission))
                                        <div class="col-sm-4" style="text-wrap: inherit">
                                    <div class="form-group clearfix display">
                                        <div class="icheck-success">
                                            <input type="checkbox"
                                                   id="checkboxSuccess_{{$permission->name}}"
                                                   value="{{$permission->name}}"
                                                   name="permission[]">
                                            <label class="text-muted d-inline"
                                                   for="checkboxSuccess_{{$permission->name}}">
                                                {{ucfirst(str_replace('_',' ',$permission->name))}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                    @endif
                                @endif
                            @endforeach

                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default"
                                data-dismiss="modal">
                            {{trans('Close')}}
                        </button>
                        <button type="submit"
                                class="btn btn-primary">{{trans('Submit')}}</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="sendOtpModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">OTP Testing</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">{{trans('To Number')}}</label>
                        <input type="text" class="form-control" name="to_number" placeholder="Enter Number With Country Code">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary send_otp_request">Send</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('extra-scripts')
    <script src="{{asset('plugins/jquery-validation/jquery.validate.min.js')}}"></script>

    <script src="{{asset('plugins/bs-custom-file-input/bs-custom-file-input.js')}}"></script>

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
        $('#notification_switch').on('change',function(e){
            const isChecked=$(this).is(':checked');
            $.ajax({
                method:'post',
                url:'{{route('customer.settings.notification_update')}}',
                data:{_token:'{{csrf_token()}}',isChecked},
                success:function(res){
                    notify('success',res.message);
                }
            })
        });

        $(document).ready(function () {
            bsCustomFileInput.init();
        });
        $('#templateForm').validate({
            rules: {
                title: {
                    required: true,
                },
                body: {
                    required: true
                },
                status: {
                    required: true
                },
            },
            messages: {
                title: {
                    required: "Please enter template title",
                },
                body: {
                    required: "Please enter template body",
                },
                status: {required: "Please select template status"},
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


        $('.add_tool').on('click', function (e) {
            var curPos =
                document.getElementById("template_body").selectionStart;

            let x = $("#template_body").val();
            let text_to_insert = $(this).attr('data-name');
            $("#template_body").val(
                x.slice(0, curPos) + text_to_insert + x.slice(curPos));

        });



        $(document).on('keyup or click', '#template_body', function (e){
            const character = $(this).val().length;

            var messageValue = $(this).val();
            var div = parseInt(parseInt(messageValue.length - 1) / 160) + 1;
            if (div <= 1) {
                $("#count").text("Characters left: " + (160 - messageValue.length));
            } else $("#count").text("Characters left: " + (160 * div - messageValue.length) + "/" + div);
        });

        $('#webhookSubmit').on('click',function(e){
            const type = $('#webhook_type').val();
            const url = $('#webhook_url').val();

            $.ajax({
                method:'post',
                url:'{{route('customer.settings.webhook_update')}}',
                data:{_token:'{{csrf_token()}}',type:type, url:url},
                success:function(res){
                    notify('success',res.message);
                }
            })
        })

        $('#dataPostIngSubmit').on('click',function(e){
            const type = $('#data_posting_type').val();
            const url = $('#data_posting_url').val();

            $.ajax({
                method:'post',
                url:'{{route('customer.settings.data_posting')}}',
                data:{_token:'{{csrf_token()}}',type:type, url:url},
                success:function(res){
                    notify('success',res.message);
                }
            })
        })


        @if(Module::has('PaymentGateway'))
        $('#resellerGatewaysBtn').on('click', function (e) {
            e.preventDefault();
            const form = $('#resellerGatewaysForm');
            const formData=form.serialize();
            const url=form.attr('action');
            $.ajax({
                method: 'post',
                url: url,
                data: formData,
                success: function (res) {
                    if (res.status == 'success') {
                        notify('success', res.message);
                    }
                }
            })
        });
        @endif

        $(document).on('click', '#editDomain', function(e){
            $('#editDomainModal').modal('show');
        })
        $(document).on('change', '#otpFromType', function(e){
            const type=$(this).val();

            $('.otp_section').hide();
            $('.'+type+'_section').show();
        })


        let rnd = (Math.random() + 1).toString(36).substring(7);
        console.log('lol='+ rnd)
        @if(isset($authorizationToken->access_token))
        $('.send_otp_request').on('click',function(e){
            const to = $('input[name=to_number]').val();
            $('.send_otp_request').attr('disabled', 'disabled')
            $.ajax({
                method:'GET',
                url:'{{route('otp.message')}}',
                data:{
                    number:to,
                    api_key:'{{$authorizationToken->access_token}}',
                    code:rnd
                },
                success:function(res){
                    notify('success', res.message);
                    $('.send_otp_request').removeAttr('disabled')
                    $('#sendOtpModal').modal('hide');
                    $('input[name=to_number]').val('')
                }
            })
        });
        @endif

        $('#checkOtpSend').on('click',function(e){
            $('#sendOtpModal').modal('show');
        });


        $(document).on('click', '.confirmEdit', function(e){
            $('#editDomain').addClass('d-none');
            $('#saveDomain').removeClass('d-none');
            $('input[name=domain_name]').removeAttr('readonly');
            $('#editDomainModal').modal('hide');
        });

        $('#saveDomain').on('click',function(e){

           const domain_name= $('input[name=domain_name]').val();

            $.ajax({
                method:'post',
                url:'{{route('customer.domain.store')}}',
                data:{_token:'{{csrf_token()}}',domain:domain_name},
                success:function(res){
                    if(res.status=='success') {
                        notify('success', res.message);
                    }else{
                        $('.error_ip').html(res.message);
                        notify('danger',res.message);
                    }
                }
            })
        })

    </script>

    <script>
        $(document).on('change', '.selectSender', function(e){
            const type=$(this).val();
            $('.inbound_section').hide();
            $('.section_'+type).show();
        });

        $('.select3').select2({
            multiple:false
        });
        $(document).on('click', '.fa-trash', function (e) {
            e.preventDefault();

            const role = $(this).attr('data-role');
            $.ajax({
                method: "get",
                url: "{{route('customer.staff.role.delete')}}",
                data: {
                    role: role,
                },

                success: function (res) {
                    let html = '';
                    if (res.status == 'success') {
                        $('#show_role_' + role).remove();
                    } else {
                        toastr.error(res.message, 'failed', {timeOut: 5000});
                    }
                }
            })
        });

        $(document).on('click', '#add_new_role', function(e){
            $('#add_role_new_modal').modal('show');
        })

    </script>
@endsection

