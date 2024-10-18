@extends('layouts.admin')

@section('title') {{ trans('admin.blog') }} {{ trans('admin.list') }}  {{ trans('admin.create') }}  @endsection

@section('extra-css')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.css"/>

@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-12 mx-auto col-sm-10 mt-3">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">{{ trans('admin.blog') }} {{ trans('admin.list') }}  {{ trans('admin.create') }} </h2>
                    <a class="btn btn-info float-right" href="{{ route('admin.bloglist.index') }}">{{ trans('admin.back') }}</a>
                </div>
                <form method="post" role="form" id="customerForm" action="{{ route('admin.bloglist.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        @include('admin.blog_management.blog_list.form')
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote.min.js"></script>

    <script>
        $(document).ready(function(){
            $('#description').summernote();
        })
    </script>
@endsection

