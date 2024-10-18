@extends('layouts.admin')

@section('title') {{ trans('admin.publication_create')}} @endsection

@section('extra-css')
    <link href="https://cdn.jsdelivr.net/npm/summernote/dist/summernote-bs4.min.css" rel="stylesheet">
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-12 mx-auto col-sm-10 mt-3">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title"> {{ trans('admin.create_new') }} </h2>
                    <a class="btn btn-info float-right" href="{{ route('admin.publications.index') }}">{{ trans('admin.back') }}</a>
                </div>
                <form method="post" role="form" id="customerForm" action="{{ route('admin.publications.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        @include('admin.publication.form')
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

    <script src="https://cdn.jsdelivr.net/npm/summernote/dist/summernote-bs4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.summernote').summernote();
        });
    </script>
@endsection

