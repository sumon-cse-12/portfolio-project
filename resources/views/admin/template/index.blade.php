@extends('layouts.admin')

@section('title',trans('admin.template'))

@section('extra-css')

@endsection

@section('content')
    @php $template = json_decode(get_settings('template')); @endphp
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form method="post" role="form" id="planForm" action="{{route('admin.template.store')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="custom_body">
                            <div class="card-header">
                                <div class="card-title font-title">@lang('admin.section_banner')
                                    <i  data-toggle="tooltip" data-placement="right" class="fa fa-question-circle alert-tooltip"
                                        title="Admin can customize frontend template according to his needs."></i>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="title">{{trans('admin.form.title')}}</label>
                                            <input value="{{isset($template->title)?$template->title:''}}" type="text" name="title" class="form-control" id="title"
                                                   placeholder="{{trans('admin.form.title')}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="profile">@lang('admin.background_image') <span class="text-danger">(@lang('admin.expecting_image_size'): 400px by 343px)</span> </label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input name="bg_image" type="file" class="custom-file-input" id="profile">
                                                    <label class="custom-file-label" for="profile">@lang('admin.choose_file')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="description">@lang('admin.page.description')</label>
                                            <textarea name="description" id="description" class="form-control"
                                                      placeholder="{{trans('admin.page.description')}}">{{isset($template->description)?$template->description:''}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="custom_body">
                            <div class="card-header">
                                <div class="card-title font-title">@lang('Section Two')</div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="title">{{trans('Title')}}</label>
                                            <input value="{{isset($template->sec_two_first_title)?$template->sec_two_first_title:''}}" type="text" name="sec_two_first_title" class="form-control" id="sec_two_first_title"
                                                   placeholder="{{trans('Enter Title')}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="profile">@lang('admin.first_img') <span class="text-danger">(@lang('admin.expecting_image_size'): 100px by 100px)</span></label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input name="section_two_bg_image" type="file" class="custom-file-input" id="section_one_bg_image">
                                                    <label class="custom-file-label" for="profile">@lang('admin.choose_file')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="description">@lang('Description')</label>
                                            <textarea name="section_two_description" id="section_two_description" class="form-control"
                                                      placeholder="{{trans('Description')}}">{{isset($template->section_two_description)?$template->section_two_description:''}}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <h5>Features</h5>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-info btn-sm mt-2 add-new-item float-right">Add More +</button>
                                        </div>
                                    </div>

                                    @if (isset($template->section_two_feature_items) && $template->section_two_feature_items != '[]')
                                    @php
                                        $sec_two_feature_items = json_decode($template->section_two_feature_items);

                                       $counter = 909;
                                    @endphp
                                    @foreach ($sec_two_feature_items as $sec_two_item)
                                   @php
                                        $counter ++;
                                   @endphp
                                    <div class="col-lg-12">
                                        <div class="row align-items-center" id="form_column_{{$counter}}">
                                            <div class="col-lg-5">
                                                <div class="form-group">
                                                    <label for="title">{{trans('Title')}}</label>
                                                    <input type="text" name="section_two_feature_title[]" value="{{isset($sec_two_item->sec_two_feature_title)?$sec_two_item->sec_two_feature_title:''}}" class="form-control" id="first_title"
                                                        placeholder="{{trans('Enter Title')}}">
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <div class="form-group">
                                                    <label for="profile">@lang('Icon')</label>
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input name="section_two_feature_icon[]" type="file" class="custom-file-input"
                                                                id="section_one_bg_image">
                                                                <input type="hidden" name="pre_sec_two_feature_icon[]" value="{{isset($sec_two_item->sec_two_feature_icon)?$sec_two_item->sec_two_feature_icon:''}}">
                                                            <label class="custom-file-label" for="profile">@lang('admin.choose_file')</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-danger btn-sm mt-4 remove_btn" data-id="{{$counter}}">X</button>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label for="description">@lang('Description')</label>
                                                    <textarea name="section_two_feature_description[]" id="section_two_feature_description" class="form-control"
                                                        placeholder="{{trans('Description')}}">{{isset($sec_two_item->sec_two_feature_des)?$sec_two_item->sec_two_feature_des:''}}</textarea>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    @endforeach
                                    @else
                                    <div class="col-lg-5">
                                        <div class="form-group">
                                            <label for="title">{{trans('Title')}}</label>
                                            <input type="text" name="section_two_feature_title[]" class="form-control" id="first_title"
                                                   placeholder="{{trans('Enter Title')}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-5">
                                        <div class="form-group">
                                            <label for="profile">@lang('Icon')</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input name="section_two_feature_icon[]" type="file" class="custom-file-input" id="section_one_bg_image">
                                                    <input type="hidden" name="pre_section_two_feature_icon[]" value="">
                                                    <label class="custom-file-label" for="profile">@lang('admin.choose_file')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="description">@lang('Description')</label>
                                            <textarea name="section_two_feature_description[]" id="section_two_feature_description" class="form-control"
                                                      placeholder="{{trans('Description')}}"></textarea>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-lg-12" id="add-new-section">

                                    </div>



                                </div>
                            </div>
                        </div>
                        <div class="custom_body">
                            <div class="card-header">
                                <div class="card-title font-title">@lang('Section Three')</div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="title">{{trans('Title')}}</label>
                                            <input value="{{isset($template->section_three_title)?$template->section_three_title:''}}" type="text" name="section_three_title" class="form-control" id="section_three_title"
                                                   placeholder="{{trans('Enter Title')}}">
                                        </div>
                                    </div>


                                    <div class="col-lg-12">
                                        <h5>Gateway Features</h5>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-info btn-sm mt-2 sec-three-add-new-item float-right">Add More +</button>
                                        </div>
                                    </div>

                                    @if (isset($template->section_three_feature_items) && $template->section_three_feature_items != '[]')
                                    @php
                                        $section_three_feature_items = json_decode($template->section_three_feature_items);
                                       $counter = 9099;
                                    @endphp
                                    @foreach ($section_three_feature_items as $sec_three_item)
                                   @php
                                        $counter ++;
                                   @endphp
                                    <div class="col-lg-12">
                                        <div class="row align-items-center" id="form_column_{{$counter}}">
                                            <div class="col-lg-5">
                                                <div class="form-group">
                                                    <label for="title">{{trans('Title')}}</label>
                                                    <input type="text" name="section_three_feature_title[]" value="{{isset($sec_three_item->sec_three_feature_title)?$sec_three_item->sec_three_feature_title:''}}" class="form-control" id="first_title"
                                                        placeholder="{{trans('Enter Title')}}">
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <div class="form-group">
                                                    <label for="profile">@lang('Icon')</label>
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input name="section_three_feature_icon[]" type="file" class="custom-file-input"
                                                                id="section_one_bg_image">
                                                                <input type="hidden" name="pre_section_three_feature_icon[]" value="{{isset($sec_three_item->sec_three_feature_icon)?$sec_three_item->sec_three_feature_icon:''}}">
                                                            <label class="custom-file-label" for="profile">@lang('admin.choose_file')</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-danger btn-sm mt-4 sec_three_remove_btn" data-id="{{$counter}}">X</button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    @endforeach
                                    @else
                                    <div class="col-lg-5">
                                        <div class="form-group">
                                            <label for="title">{{trans('Title')}}</label>
                                            <input value="{{isset($template->section_three_feature_title)?$template->section_three_feature_title:''}}" type="text" name="section_three_feature_title[]" class="form-control" id="first_title"
                                                   placeholder="{{trans('Enter Title')}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-5">
                                        <div class="form-group">
                                            <label for="profile">@lang('Icon')</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input name="section_three_feature_icon[]" type="file" class="custom-file-input" id="section_one_bg_image">
                                                    <input type="hidden" name="pre_section_three_feature_icon[]" value="{{isset($sec_three_item->sec_three_feature_icon)?$sec_three_item->sec_three_feature_icon:''}}">
                                                    <label class="custom-file-label" for="profile">@lang('admin.choose_file')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @endif
                                    <div class="col-lg-12" id="sec-three-add-new-section">

                                    </div>



                                </div>
                            </div>
                        </div>
                        <div class="custom_body">
                            <div class="card-header">
                                <div class="card-title font-title">@lang('Section Four')</div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="title">{{trans('Title')}}</label>
                                            <input value="{{isset($template->section_four_title)?$template->section_four_title:''}}" type="text" name="section_four_title" class="form-control" id="section_four_title"
                                                   placeholder="{{trans('Enter Title')}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="profile">@lang('admin.background_image') <span class="text-danger">(@lang('admin.expecting_image_size'): 400px by 343px)</span></label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input name="section_four_bg_image" type="file" class="custom-file-input" id="profile">
                                                    <label class="custom-file-label" for="profile">@lang('admin.choose_file')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="description">@lang('Description')</label>
                                            <textarea name="section_four_description" id="section_four_description" class="form-control"
                                                      placeholder="{{trans('Description')}}">{{isset($template->section_four_description)?$template->section_four_description:''}}</textarea>
                                        </div>
                                    </div>


                                    <div class="col-lg-12">
                                        <h5>Add Features</h5>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-info btn-sm mt-2 sec-four-add-new-item float-right">Add More +</button>
                                        </div>
                                    </div>

                                    @if (isset($template->section_four_features) && $template->section_four_features != '[]')
                                    @php
                                        $section_four_feature_items = json_decode($template->section_four_features);
                                       $counter = 29099;
                                    @endphp
                                    @foreach ($section_four_feature_items as $sec_four_item)
                                   @php
                                        $counter ++;
                                   @endphp
                                    <div class="col-lg-12">
                                        <div class="row align-items-center" id="form_column_{{$counter}}">

                                            <div class="col-lg-10">
                                                <div class="form-group">
                                                    <label for="description">@lang('Description')</label>
                                                    <textarea name="section_four_feature_des[]" id="section_four_feature" class="form-control"
                                                              placeholder="{{trans('Description')}}">{{isset($sec_four_item->section_four_feature_des)?$sec_four_item->section_four_feature_des:''}}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-danger btn-sm mt-4 sec_four_remove_btn" data-id="{{$counter}}">X</button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    @endforeach
                                    @else
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="description">@lang('Description')</label>
                                            <textarea name="section_four_feature_des[]" id="section_four_feature" class="form-control"
                                                      placeholder="{{trans('Description')}}">{{isset($sec_four_item->section_four_feature_des)?$sec_four_item->section_four_feature_des:''}}</textarea>
                                        </div>
                                    </div>

                                    @endif
                                    <div class="col-lg-12" id="sec-four-add-new-section">

                                    </div>



                                </div>
                            </div>
                        </div>
                        <div class="custom_body">
                            <div class="card-header">
                                <div class="card-title font-title">@lang('Section Five')</div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="section_five_title">{{trans('admin.form.title')}}</label>
                                            <input value="{{isset($template->section_five_title)?$template->section_five_title:''}}" type="text" name="section_five_title" class="form-control" id="section_five_title"
                                                   placeholder="{{trans('admin.form.title')}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="profile">@lang('admin.background_image') <span class="text-danger">(@lang('admin.expecting_image_size'): 400px by 343px)</span></label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input name="section_five_bg_image" type="file" class="custom-file-input" id="profile">
                                                    <label class="custom-file-label" for="profile">@lang('admin.choose_file')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="section_five_title">{{trans('Sub Title')}}</label>
                                            <input value="{{isset($template->section_five_sub_title)?$template->section_five_sub_title:''}}" type="text" name="section_five_sub_title" class="form-control" id="section_five_sub_title"
                                                   placeholder="{{trans('Sub Title')}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="description">@lang('admin.page.description')</label>
                                            <textarea name="section_five_description" id="section_five_description" class="form-control"
                                                      placeholder="{{trans('admin.page.description')}}">{{isset($template->section_five_description)?$template->section_five_description:''}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="custom_body">
                            <div class="card-header">
                                <div class="card-title font-title">@lang('admin.section_about')</div>
                            </div>
                            {{-- {{dd($template)}} --}}
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="about_us_title">{{trans('admin.form.title')}}</label>
                                            <input value="{{isset($template->about_us_title)?$template->about_us_title:''}}" type="text" name="about_us_title" class="form-control" id="about_us_title"
                                                   placeholder="{{trans('admin.form.title')}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="profile">@lang('admin.background_image') <span class="text-danger">(@lang('admin.expecting_image_size'): 400px by 343px)</span></label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input name="about_us_bg_image" type="file" class="custom-file-input" id="profile">
                                                    <label class="custom-file-label" for="profile">@lang('admin.choose_file')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="description">@lang('admin.page.description')</label>
                                            <textarea name="about_us_description" id="about_us_description" class="form-control"
                                                      placeholder="{{trans('admin.page.description')}}">{{isset($template->about_us_description)?$template->about_us_description:''}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="custom_body">
                            <div class="card-header">
                                <div class="card-title font-title">@lang('admin.section_subscribe')</div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="title">{{trans('admin.form.title')}}</label>
                                            <input value="{{isset($template->subscribe_title)?$template->subscribe_title:''}}" type="text" name="subscribe_title" class="form-control" id="subscribe_title"
                                                   placeholder="{{trans('admin.form.title')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="custom_body">
                            <div class="card-header">
                                <div class="card-title font-title">@lang('admin.section_plan')</div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="title">{{trans('admin.form.title')}}</label>
                                            <input value="{{isset($template->plan_title)?$template->plan_title:''}}" type="text" name="plan_title" class="form-control" id="plan_title"
                                                   placeholder="{{trans('admin.form.title')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="custom_body">
                            <div class="card-header">
                                <div class="card-title font-title">@lang('Contact Us')</div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="title">{{trans('admin.form.title')}}</label>
                                            <input value="{{isset($template->contact_us_title)?$template->contact_us_title:''}}" type="text" name="contact_us_title" class="form-control" id="contact_us_title"
                                                   placeholder="{{trans('admin.form.title')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="custom_body">
                            <div class="card-header">
                                <div class="card-title font-title">@lang('Section FAQ')</div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="title">{{trans('admin.form.title')}}</label>
                                            <input value="{{isset($template->faq_title)?$template->faq_title:''}}" type="text" name="faq_title" class="form-control" id="faq_title"
                                                   placeholder="{{trans('admin.form.title')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">@lang('admin.submit')</button>
                        </div>
                    </form>
                </div>


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
        $('#planForm').validate({
            rules: {
                question: {
                    required: true
                },
                answer: {
                    required: true
                },
                status: {
                    required: true
                },
            },
            messages: {
                question: { required:"Please provide plan title"},
                answer:  { required:"Please provide sms limit"},
                status:  { required:"Please select a status"}
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        let rowNumber = 1;

        $(document).on('click', '#plus', function (e){
            rowNumber++
            $('#add_row').prepend(`<div class="row" id="delete_row_${rowNumber}">
                                <div class="col-lg-11">
                                    <div class="form-group">
                                        <label for="sec_thr_title">{{trans('admin.form.title')}}</label>
                                        <input value="" type="text" name="sec_four_title[]" class="form-control" id="sec_four_title"
                                               placeholder="{{trans('admin.form.title')}}">
                                    </div>
                                </div>
                                <div class="col-lg-1 add-btn">
                                    <div class="form-group">
                                        <button type="button" data-number="${rowNumber}" class="faq_row btn-sm btn-danger mb-2 d-block float-right"><i class="fa fa-trash  c-pointer" ></i></button>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="description">@lang('admin.page.description')</label>
                                        <textarea name="sec_four_description[]" id="sec_four_description" class="form-control"
                                                  placeholder="{{trans('admin.page.description')}}">{{isset($page) && $page->description?$page->description:old('description')}}</textarea>
                                    </div>
                                </div>
                            </div>`);
        });

        $(document).on('click', '.faq_row', function (e){
            const number =$(this).attr('data-number');

            $('#delete_row_'+ number).remove();
        });

        $(document).on('click', '.delete_image', function(e){
            const key = $(this).attr('data-key');
            const image = $(this).attr('data-image');


            $.ajax({
               type:'GET',
              
                data:{
                   image:image, key:key
                },

                success: function (res){

                   if(res.status=='success'){
                       $('#deleteImage_'+ key).remove();
                       $(document).Toasts('create', {
                           autohide: true,
                           delay: 10000,
                           class: 'bg-success',
                           title: 'Notification',
                           body: res.message,
                       });
                   }
                }
            });
        })
    </script>
    <script>
            let i = 1;
        $(document).on("click", ".add-new-item", function (e) {
            i++
            let html = `<div class="row align-items-center" id="form_column_${i}">
                <div class="col-lg-5">
                                        <div class="form-group">
                                            <label for="title">{{trans('Title')}}</label>
                                            <input type="text" name="section_two_feature_title[]" class="form-control" id="first_title"
                                                   placeholder="{{trans('Enter Title')}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-5">
                                        <div class="form-group">
                                            <label for="profile">@lang('Icon')</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input name="section_two_feature_icon[]" type="file" class="custom-file-input" id="section_one_bg_image">
                                                    <label class="custom-file-label" for="profile">@lang('admin.choose_file')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-danger btn-sm mt-4 remove_btn" data-id="${i}">X</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="description">@lang('Description')</label>
                                            <textarea name="section_two_feature_description[]" id="section_two_feature_description" class="form-control"
                                                      placeholder="{{trans('Description')}}"></textarea>
                                        </div>
                                    </div>

                                            </div>`;
            $("#add-new-section").append(html);
        });
        $(document).on('click', '.remove_btn', function (e) {
            const id = $(this).attr('data-id');
            $('#form_column_' + id).remove();

        });
    </script>
       <script>
        let j = 10;
    $(document).on("click", ".sec-three-add-new-item", function (e) {
        j++
        let html = `<div class="row align-items-center" id="form_column_${j}">
            <div class="col-lg-5">
                                    <div class="form-group">
                                        <label for="title">{{trans('Title')}}</label>
                                        <input type="text" name="section_three_feature_title[]" class="form-control" id="first_title"
                                               placeholder="{{trans('Enter Title')}}">
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <label for="profile">@lang('Icon')</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input name="section_three_feature_icon[]" type="file" class="custom-file-input" id="section_one_bg_image">
                                                <label class="custom-file-label" for="profile">@lang('admin.choose_file')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-danger btn-sm mt-4 remove_btn" data-id="${j}">X</button>
                                    </div>
                                </div>


                                        </div>`;
        $("#sec-three-add-new-section").append(html);
    });
    $(document).on('click', '.sec_three_remove_btn', function (e) {
        const id = $(this).attr('data-id');
        $('#form_column_' + id).remove();

    });
</script>
<script>
    let k = 101;
$(document).on("click", ".sec-four-add-new-item", function (e) {
    k++
    let html = `<div class="row align-items-center" id="form_column_${k}">
                            <div class="col-lg-10">
                                <div class="form-group">
                                            <label for="description">@lang('Description')</label>
                                            <textarea name="section_four_feature_des[]" id="section_four_feature_des" class="form-control"
                                                      placeholder="{{trans('Description')}}"></textarea>
                                        </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group">
                                    <button type="button" class="btn btn-danger btn-sm mt-4 sec_four_remove_btn" data-id="${k}">X</button>
                                </div>
                            </div>


                                    </div>`;
    $("#sec-four-add-new-section").append(html);
});
$(document).on('click', '.sec_four_remove_btn', function (e) {
    const id = $(this).attr('data-id');
    $('#form_column_' + id).remove();

});
</script>
@endsection

