@extends('layouts.admin')

@section('title','Edit FAQ')

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
                        <h2 class="card-title p-3"><a href="{{route('admin.faq.index')}}">{{trans('admin.faq')}}</a>
                        </h2>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <form method="post" role="form" id="numberForm"
                              action="{{route('admin.faq.update',[$faq])}}">
                            @csrf
                            @method('put')
                            <div class="card-body" id="add_row">
                                <div class="form-group">
                                    <button id="plus" type="button" class="btn btn-primary float-right"><i
                                            class="fa fa-plus"></i></button>
                                </div>
                                <div class="form-group">
                                    <label for="title">{{trans('admin.question')}}</label>
                                    <input value="{{isset($faq)?$faq->question:''}}" type="text" name="question"
                                           class="form-control" id="title"
                                           placeholder="{{trans('admin.enter_question')}}">
                                </div>

                                <div class="form-group">
                                    <label for="limit">{{trans('admin.answer')}}</label>
                                    <input value="{{isset($faq)?$faq->answer:''}}" type="text" name="answer"
                                           class="form-control" id="limit"
                                           placeholder="{{trans('admin.enter_answer')}}">
                                </div>
                                <div class="form-group">
                                    <label for="status">@lang('Status')</label>
                                    <select class="form-control" name="status" id="status">
                                        <option {{isset($faq) && $faq->status=='active'?'selected':''}} value="active">
                                            Active
                                        </option>
                                        <option
                                            {{isset($faq) && $faq->status=='inactive'?'selected':''}} value="inactive">
                                            Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">@lang('admin.submit')</button>
                        </form>
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
@endsection

@section('extra-scripts')
    <script src="{{asset('plugins/jquery-validation/jquery.validate.min.js')}}"></script>
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
                title: {required: "Please provide plan title"},
                limit: {required: "Please provide sms limit"},
                price: {required: "Please provide plan price"},
                status: {required: "Please select a status"}
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

        $(document).on('click', '#plus', function (e) {
            rowNumber++
            $('#add_row').append(`
                           <div class="row mt-4" id="delete_row_${rowNumber}">
                                <div class="col-sm-12">
                                <button type="button" data-number="${rowNumber}" class="faq_row btn-sm btn-danger mb-2 d-block float-right"><i class="fa fa-trash  c-pointer" ></i></button>

                                   <label for="title">{{trans('admin.new_question')}}</label>
                                        <input value="{{old('question')?old('question'):''}}" type="text" name="new_question[]" class="form-control" id="title"
                                       placeholder="{{trans('admin.enter_question')}}">
                                </div>

                                <div class="col-sm-12">
                                     <label for="limit">{{trans('admin.new_answer')}}</label>
                                     <input value="{{old('answer')?old('answer'):''}}" type="text" name="new_answer[]" class="form-control" id="limit"
                                       placeholder="{{trans('admin.enter_answer')}}">
                                </div>
                                <div class="col-sm-12">
                                    <label for="status">{{trans('admin.new_status')}}</label>
                                    <select class="form-control" name="new_status[]" id="status">
                                        <option {{old('status') && old('status')=='Active'?'selected':''}} value="active">Active</option>
                                        <option {{old('status') && old('status')=='Inactive'?'selected':''}} value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            `);
        });

        $(document).on('click', '.faq_row', function (e) {
            const number = $(this).attr('data-number');

            $('#delete_row_' + number).remove();
        });

    </script>
@endsection

