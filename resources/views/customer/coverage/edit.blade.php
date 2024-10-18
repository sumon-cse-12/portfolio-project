@extends('layouts.customer')

@section('title','Edit Coverage')

@section('extra-css')

@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12 mx-auto col-sm-10">
                <!-- Custom Tabs -->
                <div class="card">

                    <div class="card-header d-flex p-0">
                        <h2 class="card-title p-3"><a href="{{route('customer.coverage.index')}}">{{trans('Coverage')}}</a>
                        </h2>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <form method="post" role="form" id="coverage_form" action="{{route('customer.coverage.update',[$coverage])}}">
                            @csrf
                            @method('put')
                            @include('customer.coverage.form')
                            <button type="submit" class="btn btn-primary">@lang('admin.submit')</button>
                        </form>
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.card-body -->
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

