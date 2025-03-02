@extends('layouts.admin')

@section('title','Customers')

@section('extra-css')

@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12 mx-auto col-sm-10 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">@lang('admin.title')</h2>
                        <a class="btn btn-info float-right" href="{{route('admin.customers.index')}}">@lang('admin.back')</a>
                    </div>
                    <form method="post" role="form" id="customerForm" action="{{route('admin.customers.store')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            @include('admin.customers.form')
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">@lang('admin.submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection

@section('extra-scripts')
    <script src="{{asset('plugins/jquery-validation/jquery.validate.min.js')}}"></script>
    <script !src="">
        "use strict";
        $('#customerForm').validate({
            rules: {
                email: {
                    required: true,
                    email: true,
                },
                password: {
                    required: true,
                    minlength: 5
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
                first_name: { required:"Please provide first name"},
                last_name:  { required:"Please provide last name"}
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
        $(document).on('change', "select[name=type]", function(e){
            const type = $(this).val();
            if(type == 'normal'){
                $('.customer_gateway').addClass('d-none');
            }else{
                $('.customer_gateway').removeClass('d-none');
            }
        });

        $(document).ready(function() {
            $('#gateway').select2();
            placeholder:'Select an gateway'
        });

    </script>
@endsection

