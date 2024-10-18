@extends('layouts.customer')

@section('title','Coverage Create')

@section('extra-css')

@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">@lang('New Coverage')</h2>
                        <a class="btn btn-info float-right" href="{{route('customer.coverage.index')}}">@lang('admin.form.button.back')</a>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form method="post" role="form" id="coverage_form" action="{{route('customer.coverage.store')}}">
                        @csrf
                        @include('customer.coverage.form')
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
        $('#coverage_form').validate({
            rules: {
                name: {
                    required: true
                },
                country: {
                    required: true
                },
                status: {
                    required: true
                },
            },
            messages: {
                question: { required:"Please enter coverage name"},
                answer:  { required:"Please select coverage country"},
                status:  { required:"Please select coverage a status"}
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

        $('.country_select').select2({
            multiple: false
        });

    </script>
@endsection

