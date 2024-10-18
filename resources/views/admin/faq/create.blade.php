@extends('layouts.admin')

@section('title','FAQ Create')

@section('extra-css')

@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">@lang('admin.new_faq')</h2>
                        <a class="btn btn-info float-right" href="{{route('admin.faq.index')}}">@lang('admin.back')</a>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form method="post" role="form" id="planForm" action="{{route('admin.faq.store')}}">
                        @csrf
                        <div class="card-body" id="add_row">
                            <div class="form-group">
                                <button id="plus" type="button" class="btn btn-primary float-right"><i class="fa fa-plus"></i></button>
                            </div>
                            <div class="form-group">
                                <label for="title">{{trans('admin.question')}}</label>
                                <input value="" type="text" name="question[]" class="form-control" id="title"
                                       placeholder="{{trans('admin.enter_question')}}">
                            </div>

                            <div class="form-group">
                                <label for="limit">{{trans('admin.answer')}}</label>
                                <input value="" required type="text" name="answer[]" class="form-control" id="limit"
                                       placeholder="{{trans('admin.enter_answer')}}">
                            </div>
                            <div class="form-group">
                                <label for="status">@lang('Status')</label>
                                <select class="form-control" name="status[]" id="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
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
        $('#planForm').validate({
            rules: {
                question: {
                    required: true
                },
                answer: {
                    required: true
                },
                status: {
                    required: true
                },
            },
            messages: {
                question: { required:"Please provide plan title"},
                answer:  { required:"Please provide sms limit"},
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

        let rowNumber = 1;

        $(document).on('click', '#plus', function (e){
            rowNumber++
            $('#add_row').append(`
                           <div class="row mt-4" id="delete_row_${rowNumber}">
                                <div class="col-sm-12">
                                <button type="button" data-number="${rowNumber}" class="faq_row btn-sm btn-danger mb-2 d-block float-right"><i class="fa fa-trash  c-pointer" ></i></button>

                                       <label for="title">{{trans('admin.new_question')}}</label>
                                        <input type="text" name="question[]" class="form-control"
                                       placeholder="{{trans('admin.enter_question')}}">
                                </div>

                                <div class="col-sm-12">
                                     <label for="limit">{{trans('admin.new_answer')}}</label>
                                     <input value="" type="text" name="answer[]" class="form-control"
                                       placeholder="{{trans('admin.enter_answer')}}">
                                </div>
                                <div class="col-sm-12">
                                    <label for="status">{{trans('admin.new_status')}}</label>
                                    <select class="form-control" name="status[]">
                                        <option  value="active">Active</option>
                                        <option  value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            `);
        });

        $(document).on('click', '.faq_row', function (e){
            const number =$(this).attr('data-number');

            $('#delete_row_'+ number).remove();
        })
    </script>
@endsection

