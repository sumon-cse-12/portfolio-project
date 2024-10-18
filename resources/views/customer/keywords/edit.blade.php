@extends('layouts.customer')

@section('title','Edit keyword')

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12 mx-auto col-sm-10 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">@lang('customer.new_keyword')</h2>
                        <a class="btn btn-info float-right" href="{{route('customer.keywords.index')}}">@lang('customer.back')</a>
                    </div>
                    <form method="post" role="form" id="groupForm" action="{{route('customer.keywords.update',[$keyword])}}">
                        @csrf
                        @method('put')
                        <div class="card-body">
                            @include('customer.keywords.form')
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">@lang('customer.update')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('extra-scripts')
    <script src="{{asset('plugins/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>

    <script !src="">
        "use strict";
        $('#groupForm').validate({
            rules: {
                word: {
                    required: true,
                },
                phone_number: {
                    required: true,
                }
            },
            messages: {
                name: {required: "Please provide  name"},
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

        $('.groups').select2({
            multiple:false
        });


        $('#customer_numbers').select2({
            multiple:false
        });

        $(document).on('change', 'select[name=type]', function(e){
            const type=$(this).val();

            if(type=='opt_in'){
                $('#contactGroup').removeClass('d-none');
            }else{
                $('#contactGroup').addClass('d-none');
            }
        });

    </script>
@endsection

