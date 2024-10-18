@extends('layouts.admin')

@section('title') {{ trans('admin.create') }} {{ trans('admin.sign_up_info') }}   @endsection

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
                    <h2 class="card-title">{{ trans('admin.create') }} {{ trans('admin.sign_up_info') }}  </h2>
                </div>
                <form method="post" role="form" id="sign_up_info_Form" action="{{route('admin.theme.sign.up.info.store')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                            @php
                                $sign_up_info =json_decode(get_settings('sign_up_info'));
                            @endphp

                                <div class="form-group">
                                    <label for="title">{{trans('admin.title')}}</label>
                                    <input value="{{isset($sign_up_info)?$sign_up_info->title:''}}" type="text" name="title" class="form-control" id="title"
                                           placeholder="{{trans('admin.title')}}">
                                </div>
                                <div class="form-group">
                                    <label for="short_description">@lang('admin.short_description')</label>
                                    <textarea name="short_description" id="short_description" class="form-control"
                                              placeholder="{{trans('admin.short_description')}}">{{isset($sign_up_info)?$sign_up_info->short_description:''}}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="description">@lang('admin.description')</label>
                                    <textarea name="description" id="description" class="form-control description"
                                              placeholder="{{trans('admin.description')}}">{{isset($sign_up_info)?$sign_up_info->description:''}}</textarea>
                                </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">{{ trans('admin.submit') }}</button>
                        </div>
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

