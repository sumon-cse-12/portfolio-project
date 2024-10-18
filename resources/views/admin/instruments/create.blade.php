@extends('layouts.admin')

@section('title') {{ trans('admin.create') }} {{ trans('admin.instruments') }}   @endsection

@section('extra-css')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.css"/>
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-12 mx-auto col-sm-10 mt-3">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">{{ trans('admin.create') }} {{ trans('admin.instruments') }}  </h2>

                    <a class="btn btn-info float-right" href="{{ route('admin.instruments.index') }}">{{ trans('admin.back') }}</a>
                </div>
                <form method="post" role="form" id="instrumentsForm" action="{{ route('admin.instruments.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        @include('admin.instruments.form')
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">{{ trans('admin.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
</section>
@endsection

@section('extra-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote.min.js" ></script>

    <script>
        $(document).ready(function(){
            $('.summernote').summernote();
        })
        let ids=0;
        $(document).ready(function() {
            $("#add-data").click(function() {

                ids++;

                $("#data-container").append(`<div class="card mt-2 delete_section_${ids}">
                                                <div class="card-header">
                                                    <button type="button" data-id="${ids}" class="delete-data btn btn-sm add-btn btn-danger float-right m-0">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </div>
                                                <div class="data-input card-body">
                                                    <div class="row align-items-center">
                                                        <div class="col-lg-12">
                                                            <input type="text" name="table_title[${ids}]"
                                                                   class="form-control mt-2" placeholder="Table Head" required>
                                                        </div>
                                                        <div class="col-lg-12 section_new_ele_${ids}">
                                                            <div class="row">
                                                                <div class="col-lg-5">
                                                                    <input type="text" name="table_head[${ids}][]"
                                                                           class="form-control mt-2" placeholder="Enter Key" required>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <input type="text" name="table_body[${ids}][]"
                                                                           class="form-control mt-2" placeholder="Enter Value" required>
                                                                </div>
                                                                <div class="col-lg-1">
                                                                    <button type="button" data-id="${ids}"
                                                                            class="add_new_element btn btn-sm btn-primary float-right mt-3">
                                                                        <i class="fa fa-plus"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>`);
            });
        });


        $(document).on("click", ".delete-data", function() {
            const id=$(this).attr('data-id');

            $('.delete_section_'+id).remove();
        });


        $(document).on("click", ".del_new_element", function() {
            const id=$(this).attr('data-id');


            $('.delete_rows_'+id).remove();
        });

        let genId=3455;
        $(document).on("click", ".add_new_element", function() {

            const id=$(this).attr('data-id');
            genId++;
            $('.section_new_ele_'+id).append(`<div class="row delete_rows_${genId}">
                                                                <div class="col-lg-5">
                                                                    <input type="text" name="table_head[${id}][]"
                                                                           class="form-control mt-2" placeholder="Enter Key" required>
                                                                </div>
                                                                <div class="col-lg-6">
                                                                    <input type="text" name="table_body[${id}][]"
                                                                           class="form-control mt-2" placeholder="Enter Value" required>
                                                                </div>
                                                                <div class="col-lg-1">
                                                                    <button type="button" data-id="${genId}"
                                                                            class="del_new_element btn btn-sm btn-danger float-right m-0">
                                                                        <i class="fa fa-times"></i>
                                                                    </button>
                                                                </div>
                                                            </div>`);
        });
    </script>
@endsection

