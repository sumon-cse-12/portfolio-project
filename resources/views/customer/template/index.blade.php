@extends('layouts.customer')

@section('title',trans('admin.template'))

@section('extra-css')

@endsection

@section('content')
    @php  $template = isset($templateData)?json_decode($templateData->value):''; @endphp
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form method="post" role="form" id="planForm" action="{{route('customer.template.store')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="card mb-3">
                            <div class="card-header">
                                <h4>Contact Info</h4>
                            </div>
                            <div class="card-body ">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="">Address</label>
                                        <textarea name="address" id="" cols="4" rows="4" class="form-control">{!! isset($contactData->address)?$contactData->address:'' !!}</textarea>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="">Email</label>
                                        <input type="email" value="{{isset($contactData->email_address)?$contactData->email_address:''}}" class="form-control" name="email_address" placeholder="Enter email address">
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="">Phone Number</label>
                                        <input type="number" value="{{isset($contactData->phone_number)?$contactData->phone_number:''}}" class="form-control" name="phone_number" placeholder="Enter phone number">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4>Application</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12 mb-2">
                                        <label for="">{{trans('admin.app_name')}}</label>
                                        <input value="{{isset($app_name->value)?$app_name->value:''}}" type="text" name="app_name" class="form-control" placeholder="Enter application name">
                                    </div>

                                    <div class="form-group col-lg-6 col-md-6">
                                        <label for="">{{trans('admin.form.input.recaptcha_site_key')}}</label>
                                        <input type="text" value="{{isset($recaptcha->value) && isset(json_decode($recaptcha->value)->recaptcha_site_key)?json_decode($recaptcha->value)->recaptcha_site_key:''}}" name="recaptcha_site_key" class="form-control"
                                               placeholder="{{trans('admin.form.input.ex_recaptcha_site_key')}}">
                                    </div>

                                    <div class="form-group col-lg-6 col-md-6">
                                        <label for="">{{trans('Recaptcha Secret Key')}}</label>
                                        <input type="text" value="{{isset($recaptcha->value) && isset(json_decode($recaptcha->value)->recaptcha_secret_key)?json_decode($recaptcha->value)->recaptcha_secret_key:''}}" name="recaptcha_secret_key" class="form-control"
                                               placeholder="{{trans('Enter Recaptcha Secret Key')}}">
                                    </div>

                                    <div class="form-group col-lg-6 col-md-6">
                                        <label>{{trans('admin.favicon')}}</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input name="favicon" type="file" class="custom-file-input" id="profile">
                                                <label class="custom-file-label"
                                                       for="profile">@lang('admin.choose_file')</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6">
                                        <label>{{trans('admin.logo')}}</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input name="logo" type="file" class="custom-file-input" id="profile">
                                                <label class="custom-file-label"
                                                       for="profile">@lang('admin.choose_file')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="custom_body">
                            <div class="card-header">
                                <div class="card-title font-title">@lang('admin.section_banner')</div>
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
                                <div class="card-title font-title">@lang('admin.section_marketing_tool')</div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="title">{{trans('admin.first_title')}}</label>
                                            <input value="{{isset($template->first_title)?$template->first_title:''}}" type="text" name="first_title" class="form-control" id="first_title"
                                                   placeholder="{{trans('admin.first_title')}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="profile">@lang('admin.first_img') <span class="text-danger">(@lang('admin.expecting_image_size'): 100px by 100px)</span></label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input name="first_img" type="file" class="custom-file-input" id="first_img">
                                                    <label class="custom-file-label" for="profile">@lang('admin.choose_file')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="description">@lang('admin.first_description')</label>
                                            <textarea name="first_description" id="first_description" class="form-control"
                                                      placeholder="{{trans('admin.first_description')}}">{{isset($template->first_description)?$template->first_description:''}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="title">{{trans('admin.sec_title')}}</label>
                                            <input value="{{isset($template->sec_title)?$template->sec_title:''}}" type="text" name="sec_title" class="form-control" id="sec_title"
                                                   placeholder="{{trans('admin.sec_title')}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="profile">@lang('admin.sec_img') <span class="text-danger">(@lang('admin.expecting_image_size'): 100px by 100px)</span></label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input name="sec_img" type="file" class="custom-file-input" id="sec_img">
                                                    <label class="custom-file-label" for="profile">@lang('admin.choose_file')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="description">@lang('admin.sec_description')</label>
                                            <textarea name="sec_description" id="sec_description" class="form-control"
                                                      placeholder="{{trans('admin.sec_description')}}">{{isset($template->sec_description)?$template->sec_description:''}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="title">{{trans('admin.thr_title')}}</label>
                                            <input value="{{isset($template->thr_title)?$template->thr_title:''}}" type="text" name="thr_title" class="form-control" id="thr_title"
                                                   placeholder="{{trans('admin.thr_title')}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="profile">@lang('admin.thr_img') <span class="text-danger">(@lang('admin.expecting_image_size'): 100px by 100px)</span></label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input name="thr_img" type="file" class="custom-file-input" id="thr_img">
                                                    <label class="custom-file-label" for="profile">@lang('admin.choose_file')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="description">@lang('admin.thr_description')</label>
                                            <textarea name="thr_description" id="thr_description" class="form-control"
                                                      placeholder="{{trans('admin.thr_description')}}">{{isset($template->thr_description)?$template->thr_description:''}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="custom_body">
                            <div class="card-header">
                                <div class="card-title font-title">@lang('admin.section_about')</div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="sec_thr_title">{{trans('admin.form.title')}}</label>
                                            <input value="{{isset($template->sec_thr_title)?$template->sec_thr_title:''}}" type="text" name="sec_thr_title" class="form-control" id="sec_thr_title"
                                                   placeholder="{{trans('admin.form.title')}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="profile">@lang('admin.background_image') <span class="text-danger">(@lang('admin.expecting_image_size'): 400px by 343px)</span></label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input name="sec_thr_bg_image" type="file" class="custom-file-input" id="profile">
                                                    <label class="custom-file-label" for="profile">@lang('admin.choose_file')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="description">@lang('admin.page.description')</label>
                                            <textarea name="sec_thr_description" id="sec_thr_description" class="form-control"
                                                      placeholder="{{trans('admin.page.description')}}">{{isset($template->sec_thr_description)?$template->sec_thr_description:''}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="custom_body">
                            <div class="card-header">
                                <div class="card-title font-title">@lang('admin.section_features')</div>
                            </div>
                            <div class="card-body" id="add_row">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="sec_thr_title">{{trans('admin.main_title')}}</label>
                                            <input value="{{isset($template->main_title)?$template->main_title:''}}" type="text" name="main_title" class="form-control" id="main_title"
                                                   placeholder="{{trans('admin.main_title')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-11">
                                        <div class="form-group">
                                            <label for="sec_thr_title">{{trans('admin.form.title')}}</label>
                                            <input value="" type="text" name="sec_four_title[]" class="form-control" id="sec_four_title"
                                                   placeholder="{{trans('admin.form.title')}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-1">
                                        <div class="form-group">
                                            <button id="plus" type="button" class="btn btn-primary float-right add-btn"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="description">@lang('admin.page.description')</label>
                                            <textarea name="sec_four_description[]" id="sec_four_description" class="form-control"
                                                      placeholder="{{trans('admin.page.description')}}"></textarea>
                                        </div>
                                    </div>
                                </div>
                                @if(isset($template->sec_four_description) && isset($template->sec_four_title ))
                                    @foreach($template->sec_four_description as $key=>$secFourDescription)
                                        @foreach($template->sec_four_title as $keyOne=>$secFourtitle)
                                            @if($key == $keyOne && $secFourDescription && $secFourtitle)
                                                <div class="row" id="delete_row_{{$key}}">
                                                    <div class="col-lg-11">
                                                        <div class="form-group">
                                                            <label for="sec_thr_title">{{trans('admin.form.title')}}</label>
                                                            <input value="{{$secFourtitle}}" type="text" name="sec_four_title[]"
                                                                   class="form-control" id="sec_four_title"
                                                                   placeholder="{{trans('admin.form.title')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-1 add-btn">
                                                        <div class="form-group">
                                                            <button type="button" data-number="{{$key}}"
                                                                    class="faq_row btn-sm btn-danger mt-1 d-block float-right">
                                                                <i class="fa fa-trash  c-pointer"></i></button>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <label for="description">@lang('admin.page.description')</label>
                                                            <textarea name="sec_four_description[]" id="sec_four_description"
                                                                      class="form-control"
                                                                      placeholder="{{trans('admin.page.description')}}">{{$secFourDescription}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endforeach
                                @endif
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
                                            <input value="{{isset($template->sec_five_title)?$template->sec_five_title:''}}" type="text" name="sec_five_title" class="form-control" id="sec_five_title"
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
                                            <input value="{{isset($template->sec_six_title)?$template->sec_six_title:''}}" type="text" name="sec_six_title" class="form-control" id="sec_six_title"
                                                   placeholder="{{trans('admin.form.title')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="custom_body">
                            <div class="card-header">
                                <div class="card-title font-title">@lang('admin.section_create_banner')</div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="title">{{trans('admin.form.title')}}</label>
                                            <input value="{{isset($template->sec_seven_title)?$template->sec_seven_title:''}}" type="text" name="sec_seven_title" class="form-control" id="sec_seven_title"
                                                   placeholder="{{trans('admin.form.title')}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="profile">@lang('admin.background_image') <span class="text-danger">(@lang('admin.expecting_image_size'): 400px by 343px)</span></label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input name="sec_seven_bg_image" type="file" class="custom-file-input" id="profile">
                                                    <label class="custom-file-label" for="profile">@lang('admin.choose_file')</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="description">@lang('admin.page.description')</label>
                                            <textarea name="sec_seven_description" id="description" class="form-control"
                                                      placeholder="{{trans('admin.page.description')}}">{{isset($template->sec_seven_description)?$template->sec_seven_description:''}}</textarea>
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
        })
    </script>
@endsection

