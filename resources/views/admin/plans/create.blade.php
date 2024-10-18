@extends('layouts.admin')

@section('title','Plans')

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
    <script src="{{asset('plugins/toastr/toastr.min.css')}}"></script>

    <style>
        /* Basic Rules */
        .switch input {
            display:none;
        }
        .switch {
            display: inline-block;
            width: 48px;
            height: 18px;
            margin: 4px;
            transform: translateY(50%);
            position: relative;
        }
        /* Style Wired */
        .slider {
            position:absolute;
            top:0;
            bottom:0;
            left:0;
            right:0;
            border-radius:30px;
            box-shadow: 0 0 0 2px #e0dddd, 0 0 4px #fffefe;
            cursor:pointer;
            border:2px solid transparent;
            overflow:hidden;
            transition:.4s;
        }
        .slider:before {
            position:absolute;
            content:"";
            width:100%;
            height:100%;
            background: #b6b5b5;
            border-radius:30px;
            transform:translateX(-30px);
            transition:.4s;
        }

        input:checked + .slider:before {
            transform:translateX(30px);
            background: var(--success);
        }
        input:checked + .slider {
            box-shadow:0 0 0 2px var(--success),0 0 2px var(--success);
        }

        /* Style Flat */
        .switch.flat .slider {
            box-shadow:none;
        }
        .switch.flat .slider:before {
            background:#FFF;
        }
        .switch.flat input:checked + .slider:before {
            background:white;
        }
        .switch.flat input:checked + .slider {
            background: var(--success);
        }
        #vert-tabs-tab .active{
            background: var(--primary) !important;
            color: white !important;
        }
        #previous_btn,#next_btn{
            font-weight: 400 !important;
        }
    </style>
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">@lang('admin.plans.new')</h2>
                        <a class="btn btn-info float-right" href="{{route('admin.plans.index')}}">@lang('admin.form.button.back')</a>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form method="post" role="form" id="planForm" action="{{route('admin.plans.store')}}">
                        @csrf
                        <div class="card-body">
                            @include('admin.plans.form')
                        </div>

                    </form>
                </div>


            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
@endsection

@section('extra-scripts')
    <script src="{{asset('plugins/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/moment.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
    <script !src="">
        "use strict";
        $('#planForm').validate({
            rules: {
                title: {
                    required: true
                },
                limit: {
                    required: true
                },
                price: {
                    required: true
                },
                status: {
                    required: true
                },
            },
            messages: {
                title: { required:"Please provide plan title"},
                limit:  { required:"Please provide sms limit"},
                price: { required:"Please provide plan price"},
                status:  { required:"Please select a status"}
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


        $('#reservation').daterangepicker();
        $(document).on('change', "select[name=recurring_type]", function (e){
            const type =$(this).val();
            if(type=='custom'){
                $('#customRecurring').removeClass('d-none')
            }else{
                $('#customRecurring').addClass('d-none')
            }
        });

        $('.select2').select2({
            multiple:true,
            placeholder:'{{trans('admin.select_module')}}',
        });

        $('.country_select').select2({
            multiple:false,
            placeholder:'{{trans('Select Country')}}',
        });
        $('.coverage_select2').select2({
            multiple:true,
            placeholder:'{{trans('Select An Country')}}',
        });
    </script>
    <script>
        $(document).on('change', 'select[name=plan_type]', function (e){
            const type = $(this).val();

            if(type !='normal'){
                $('#landingPageSection').removeClass('d-none');
                $('#modules').trigger('change');
            }else{
                $('#landingPageSection').addClass('d-none');
            }
        });



        $(document).on('click', '.submitBtn', function(e){
            e.preventDefault();

            $('#planForm').submit();
        })

        $(document).on('click', '.left-nav-link-plan', function(e){
            const name=$(this).attr('data-name');

            if(name || name=='permission'){
                $('.submitBtn').removeClass('d-none')
            }else{
                $('.submitBtn').addClass('d-none')
            }
        });

        $(document).on('click','.isUnlimited',function (e) {
            let inputFieldName=$(this).attr('data-name');
            if($(this).is(':checked')){
                $('[name='+inputFieldName+']').hide();
            }else{
                $('[name='+inputFieldName+']').show();
            }
        })
    </script>

    <script>
        let current_page=0;
        $(document).on('click', '#next_btn', function(e){
            current_page++;

            if(current_page < 0){
                current_page=0;
                return;
            }

            $('.tab_panel').removeClass('active').removeClass('show');
            $('.left-nav-link-plan').removeClass('active');

            if(current_page=='1') {
                $('#features').addClass('active').addClass('show');
                $('#features-nav').addClass('active');
            }else if(current_page=='2') {
                $('#pricing').addClass('active').addClass('show');
                $('#pricing-nav').addClass('active');
            }else if(current_page=='3') {
                $('#coverage-area').addClass('active').addClass('show');
                $('#coverage-area-nav').addClass('active');
            }else if(current_page=='4') {
                $('#permission').addClass('active').addClass('show');
                $('#permission-nav').addClass('active');
                $('.submitBtn').removeClass('d-none');
                $('#next_btn').addClass('d-none');
            }else if(current_page=='0') {
                $('#basic-info').addClass('active').addClass('show');
                $('#basic-info-nav').addClass('active');
            }

        });

        $(document).on('click', '#previous_btn', function(e){

            current_page--;
            if(current_page < 0){
                current_page=0;
                return;
            }

            $('.tab_panel').removeClass('active').removeClass('show');
            $('.left-nav-link-plan').removeClass('active');

            if(current_page=='1') {
                $('#features').addClass('active').addClass('show');
                $('#features-nav').addClass('active');
            }else if(current_page=='2') {
                $('#pricing').addClass('active').addClass('show');
                $('#pricing-nav').addClass('active');
            }else if(current_page=='3') {
                $('#coverage-area').addClass('active').addClass('show');
                $('#coverage-area-nav').addClass('active');
                $('#next_btn').removeClass('d-none');
                $('.submitBtn').addClass('d-none');
            }else if(current_page=='4') {
                $('#permission').addClass('active').addClass('show');
                $('#permission-nav').addClass('active');

            }else if(current_page=='0') {
                $('#basic-info').addClass('active').addClass('show');
                $('#basic-info-nav').addClass('active');
            }

        })
    </script>
@endsection

