@extends('layouts.admin')

@section('title')  {{ trans('admin.welcome_section') }}   @endsection

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
                    <h2 class="card-title">{{ trans('admin.welcome_section') }}  </h2>
                </div>
                <form method="post" role="form" id="welcomesectionForm" action="{{route('admin.theme.welcome.section.store')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        @php
                        $welcome_section=json_decode(get_settings('welcome_section'));
                        @endphp
                        <div class="form-group">
                            <label for="title">{{trans('admin.title')}}</label>
                            <input value="{{isset($welcome_section)?$welcome_section->title:''}}" type="text" name="title" class="form-control" id="title"
                                   placeholder="{{trans('admin.title')}}">
                        </div>
                        <div class="form-group">
                            <label for="image">{{trans('admin.image')}}</label>
                            <input type="hidden" name="pre_image" value="{{isset($welcome_section)?$welcome_section->imageone:''}}">
                            <input value="{{isset($welcome_section)?$welcome_section->imageone:''}}" type="file" name="image" class="form-control" id="image"
                                   placeholder="{{trans('admin.image')}}">
                        </div>
                        <div class="form-group">
                            <label for="description">@lang('admin.description')</label>
                            <textarea name="description" id="description" class="form-control"
                                      placeholder="{{trans('admin.description')}}">{{isset($welcome_section)?$welcome_section->description:''}}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-2">
                                <h5>Counter Section</h5>
                            </div>
                        </div>
                        <div class="section">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-4">
                                    <div class="form-group">
                                        <label for="title">{{trans('admin.section_one')}}</label>
                                        <input value="{{isset($welcome_section)?$welcome_section->section_one_founded:''}}" type="text" name="section_one_founded" class="form-control" id="founded"
                                               placeholder="{{trans('admin.founded')}}">
                                        <input value="{{isset($welcome_section)?$welcome_section->section_one_count:''}}" type="text" name="section_one_count" class="form-control mt-2" id="count"
                                                placeholder="{{trans('admin.count')}}">
                                        <input value="{{isset($welcome_section)?$welcome_section->section_one_experience:''}}" type="text" name="section_one_experience" class="form-control mt-2" id="experience"
                                                placeholder="{{trans('admin.experience')}}">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-4">
                                    <div class="form-group">
                                        <label for="title">{{trans('admin.section_two')}}</label>
                                        <input value="{{isset($welcome_section)?$welcome_section->section_two_founded:''}}" type="text" name="section_two_founded" class="form-control" id="founded"
                                               placeholder="{{trans('admin.founded')}}">
                                        <input value="{{isset($welcome_section)?$welcome_section->section_two_count:''}}" type="text" name="section_two_count" class="form-control mt-2" id="count"
                                                placeholder="{{trans('admin.count')}}">
                                        <input value="{{isset($welcome_section)?$welcome_section->section_two_experience:''}}" type="text" name="section_two_experience" class="form-control mt-2" id="experience"
                                                placeholder="{{trans('admin.experience')}}">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-4">
                                    <div class="form-group">
                                        <label for="title">{{trans('admin.section_three')}}</label>
                                        <input value="{{isset($welcome_section)?$welcome_section->section_three_founded:''}}" type="text" name="section_three_founded" class="form-control" id="founded"
                                               placeholder="{{trans('admin.founded')}}">
                                        <input value="{{isset($welcome_section)?$welcome_section->section_three_count:''}}" type="text" name="section_three_count" class="form-control mt-2" id="count"
                                                placeholder="{{trans('admin.count')}}">
                                        <input value="{{isset($welcome_section)?$welcome_section->section_three_experience:''}}" type="text" name="section_three_experience" class="form-control mt-2" id="experience"
                                                placeholder="{{trans('admin.experience')}}">
                                    </div>
                                </div>
                            </div>
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

