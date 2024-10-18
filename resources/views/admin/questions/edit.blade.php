@extends('layouts.admin')

@section('title') {{ trans('admin.edit') }} {{ trans('admin.questions') }}   @endsection

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/summernote/dist/summernote-bs4.min.css" rel="stylesheet">
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-12 mx-auto col-sm-10 mt-3">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">{{ trans('admin.edit') }} {{ trans('admin.questions') }}  </h2>

                    <a class="btn btn-info float-right" href="{{ route('admin.questions.index') }}">{{ trans('admin.back') }}</a>
                </div>
                <form method="post" role="form" id="questionsForm" action="{{ route('admin.questions.update',[$questions]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        @include('admin.questions.form')
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
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote/dist/summernote-bs4.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#description').summernote();
    });
    </script>
@endsection

