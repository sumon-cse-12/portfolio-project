@extends('layouts.customer')

@section('title','Dynamic Template Edit')

@section('extra-css')

@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12 mx-auto col-sm-10">
                <!-- Custom Tabs -->
                <div class="card mt-3">

                    <div class="card-header d-flex p-0">
                        <h2 class="card-title p-3"><a href="{{route('customer.dynamic-template.index')}}">@lang('customer.dynamic_template')</a></h2>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <form method="post" role="form" id="contactForm"
                              action="{{route('customer.dynamic-template.update',[$template])}}">
                            @csrf
                            @method('put')
                            @include('customer.dynamic_template.form')

                            <button type="submit" class="btn btn-primary mt-4">@lang('customer.submit')</button>
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

