@extends('layouts.customer')

@section('title','Dynamic Template Create')

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <style>
        .select2-container--default .select2-selection--single {
            min-height: 38px;
            border-radius: 4px 0 0 4px;
        }
    </style>
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12 mx-auto col-sm-10 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">@lang('customer.new_template')</h2>
                        <a class="btn btn-info float-right" href="{{route('customer.dynamic-template.index')}}">@lang('customer.back')</a>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form method="post" role="form" id="templateForm" action="{{route('customer.dynamic-template.store')}}">
                        @csrf
                        <div class="card-body">
                            @include('customer.dynamic_template.form')
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">@lang('customer.submit')</button>
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
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
    <script !src="">
        "use strict";
        $.validator.addMethod("phone_number", function(value, element) {
            return new RegExp(/^[0-9\-\+]{9,15}$/).test(value);
        }, 'Invalid phone number');

        $('#templateForm').validate({
            rules: {
                number: {
                    required: true,
                    phone_number:true
                }
            },
            messages: {

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
        let counter=0;
        $(document).on('click', '#add_more', function(e){
            counter++;
            let html='';

            html=`<div class="row mt-2" id="field_row_${counter}">
                    <div class="col-md-11 col-11">
                        <label for="">Field</label>
                        <input type="text" name="inputes[]" class="form-control" placeholder="Enter Field Name">
                    </div>
                    <div class="col-md-1 col-1 pt-3">
                        <button class="btn btn-sm btn-danger mt-3 delete_field" data-id="${counter}" type="button">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>`;

            $('#append_fields').append(html);
        });

        $(document).on('click', '.delete_field', function(e){
            const id =$(this).attr('data-id');
            $('#field_row_'+id).remove();
        })

    </script>
@endsection

