@extends('layouts.customer')

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
                        <h2 class="card-title">@lang('New Agency')</h2>
                        <a class="btn btn-info float-right" href="{{route('customer.reseller-customers.index')}}">@lang('admin.form.button.back')</a>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form method="post" role="form" id="customerForm" action="{{route('customer.reseller-customers.store')}}">
                        @csrf
                        <div class="card-body">
                            @include('customer.reseller_customers.form')
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">@lang('admin.submit')</button>
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

        $(document).ready(function () {
            $('#gateway').select2();
        });

        $(document).on('change', 'select[name=type]', function (e) {
            const type = $(this).val();
            console.log(type);

            if(type=='master_reseller_customer'){
                $('#forCustomer').addClass('d-none');
                $('#paymentGateway').addClass('d-none');
                $('#forReseller').removeClass('d-none');
            }else if (type == 'reseller') {
                $('#forReseller').addClass('d-none');
                $('#forCustomer').removeClass('d-none');
                $('#paymentGateway').removeClass('d-none');
            }
        })

    </script>
@endsection

