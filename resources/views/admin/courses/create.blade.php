@extends('layouts.admin')

@section('title') {{ trans('admin.courses') }}   @endsection

@section('extra-css')

    <link href="https://cdn.jsdelivr.net/npm/summernote/dist/summernote-bs4.min.css" rel="stylesheet">
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-12 mx-auto col-sm-10 mt-3">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">{{ trans('admin.courses') }}  </h2>
                </div>
                <form method="post" role="form" id="coursesForm" action="{{route('admin.theme.courses.store')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        @php
                        $courses =json_decode(get_settings('courses'));
                        // dd($courses)
                        @endphp
                        <div class="custom_body">
                            <div class="card-header">
                                <div class="card-title font-title">@lang('admin.section_one')
                                    <i  data-toggle="tooltip" data-placement="right" class="fa fa-question-circle alert-tooltip"
                                        title="Admin can customize frontend template according to his needs."></i>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="title">{{trans('admin.title')}}</label>
                                            <input value="{{isset($courses)?$courses->title:''}}" type="text" name="title" class="form-control" id="title"
                                                   placeholder="{{trans('admin.title')}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="image">@lang('admin.image')</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                <input type="hidden" name="pre_image" value="{{isset($courses)?$courses->imageone:''}}">
                                                    <input name="image" value="" type="file" class="custom-file-input" id="image">
                                                    <label class="custom-file-label" for="image">@lang('admin.choose_file')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="description">@lang('admin.description')</label>
                                            <textarea name="description" id="description" class="form-control summernote"
                                                      placeholder="{{trans('admin.description')}}">{{isset($courses)?$courses->description:''}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="custom_body">
                            <div class="card-header">
                                <div class="card-title font-title">@lang('admin.section_two')
                                    <i  data-toggle="tooltip" data-placement="right" class="fa fa-question-circle alert-tooltip"
                                        title="Admin can customize frontend template according to his needs."></i>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="title">{{trans('admin.title')}}</label>
                                            <input value="{{isset($courses)?$courses->title_two:''}}" type="text" name="title_two" class="form-control" id="title"
                                                   placeholder="{{trans('admin.title')}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="image">@lang('admin.image')</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="hidden" name="pre_image_two" value="{{isset($courses)?$courses->imagetwo:''}}">
                                                    <input name="image_two" value="" type="file" class="custom-file-input" id="image">
                                                    <label class="custom-file-label" for="image">@lang('admin.choose_file')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="description">@lang('admin.description')</label>
                                            <textarea name="description_two" id="description" class="form-control summernote"
                                                      placeholder="{{trans('admin.description')}}">{{isset($courses)?$courses->description_two:''}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="custom_body">
                            <div class="card-header">
                                <div class="card-title font-title">@lang('admin.section_three')
                                    <i  data-toggle="tooltip" data-placement="right" class="fa fa-question-circle alert-tooltip"
                                        title="Admin can customize frontend template according to his needs."></i>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="title">{{trans('admin.title')}}</label>
                                            <input value="{{isset($courses)?$courses->title_three:''}}" type="text" name="title_three" class="form-control" id="title"
                                                   placeholder="{{trans('admin.title')}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="image">@lang('admin.image')</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="hidden" name="pre_image_three" value="{{isset($courses)?$courses->imagethree:''}}">
                                                    <input name="image_three" value="" type="file" class="custom-file-input" id="image">
                                                    <label class="custom-file-label" for="image">@lang('admin.choose_file')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="description">@lang('admin.description')</label>
                                            <textarea name="description_three" id="description" class="form-control summernote"
                                                      placeholder="{{trans('admin.description')}}">{{isset($courses)?$courses->description_three:''}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="custom_body">
                            <div class="card-header">
                                <div class="card-title font-title">@lang('admin.section_four')
                                    <i  data-toggle="tooltip" data-placement="right" class="fa fa-question-circle alert-tooltip"
                                        title="Admin can customize frontend template according to his needs."></i>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="title">{{trans('admin.title')}}</label>
                                            <input value="{{isset($courses)?$courses->title_four:''}}" type="text" name="title_four" class="form-control" id="title"
                                                   placeholder="{{trans('admin.title')}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="image">@lang('admin.image')</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="hidden" name="pre_image_four" value="{{isset($courses)?$courses->imagefour:''}}">
                                                    <input name="image_four" value="" type="file" class="custom-file-input" id="image">
                                                    <label class="custom-file-label" for="image">@lang('admin.choose_file')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="description">@lang('admin.description')</label>
                                            <textarea name="description_four" id="description" class="form-control summernote"
                                                      placeholder="{{trans('admin.description')}}">{{isset($courses)?$courses->description_four:''}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
    <script src="https://cdn.jsdelivr.net/npm/summernote/dist/summernote-bs4.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.summernote').summernote();
    });
    </script>
@endsection

