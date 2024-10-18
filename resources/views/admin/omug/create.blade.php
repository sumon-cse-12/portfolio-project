@extends('layouts.admin')

@section('title') {{ trans('admin.create') }} {{ trans('admin.omug') }}   @endsection

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
                    <h2 class="card-title">{{ trans('admin.create') }} {{ trans('admin.omug') }}  </h2>
                </div>
                <form method="post" role="form" id="omugForm" action="{{route('admin.theme.omug.store')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        @php
                        $omug =json_decode(get_settings('omug'));
                        @endphp
                       
                                <div class="form-group">
                                    <label for="title">{{trans('admin.title')}}</label>
                                    <input value="{{isset($omug)?$omug->title:''}}" type="text" name="title" class="form-control" id="title"
                                           placeholder="{{trans('admin.title')}}">
                                </div>
                      
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="title">{{trans('YouTube Video Link')}}</label>
                                        <input value="{{isset($omug) && isset($omug->omug_youtube_link) ? $omug->omug_youtube_link : ''}}" type="input" name="omug_youtube_link" class="form-control" id="omug_youtube_link">
                                    </div>
                                </div>
                        
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="title">{{trans('Image')}}</label>
                                    <input type="file" name="omug_image" class="form-control" id="omug_image">
                                    <input type="hidden" name="pre_image" value="{{isset($omug)?$omug->image:''}}" class="form-control" id="omug_image">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="short_description">@lang('admin.short_description')</label>
                            <textarea name="short_description" id="short_description" class="form-control"
                                      placeholder="{{trans('admin.short_description')}}">{{isset($omug)?$omug->short_description:''}}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="description">@lang('admin.description')</label>
                            <textarea name="description" id="description" class="form-control description"
                                      placeholder="{{trans('admin.description')}}">{{isset($omug)?$omug->description:''}}</textarea>
                        </div>
                    </div>
                    <div class="card-footer text-right">
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
        $('.description').summernote();
    });
    </script>
@endsection

