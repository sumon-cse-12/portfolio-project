@extends('layouts.admin')

@section('title') {{ trans('admin.create') }} {{ trans('admin.resources') }}   @endsection

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
                    <h2 class="card-title">{{ trans('admin.create') }} {{ trans('admin.resources') }}  </h2>
                </div>
                <form method="post" role="form" id="resourcesForm" action="{{route('admin.theme.resources.store')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        @php
                        $resources =json_decode(get_settings('resources'));
                        @endphp
                        <div class="form-group">
                            <label for="title">{{trans('admin.title')}}</label>
                            <input value="{{isset($resources)?$resources->title:''}}" type="text" name="title" class="form-control" id="title"
                                   placeholder="{{trans('admin.title')}}">
                        </div>
                        <div class="form-group">
                            <label for="description">@lang('admin.description')</label>
                            <textarea name="description" id="description" class="form-control description"
                                      placeholder="{{trans('admin.description')}}">{{isset($resources)?$resources->description:''}}</textarea>
                        </div>
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

