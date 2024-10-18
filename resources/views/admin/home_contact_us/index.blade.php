@extends('layouts.admin')

@section('title') {{ trans('Contacts') }} @endsection

@section('extra-css')
<link href="https://cdn.jsdelivr.net/npm/summernote/dist/summernote-bs4.min.css" rel="stylesheet">
@endsection
@section('content')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">{{ trans('Contact Us') }} </h2>
                </div>
                @php
                $home_contact_us_section = json_decode(get_settings('home_contact_us'), true);
                @endphp
                <div class="card-body">
                    <form action="{{route('admin.theme.contact.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input value="{{isset($home_contact_us_section['contact_us_title'])?$home_contact_us_section['contact_us_title']:''}}" type="text" name="contact_us_title" class="form-control" id="title"
                                        placeholder="Title">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="description">Short Description</label>
                                    <textarea name="contact_us_short_description" id="description" class="form-control"
                                        placeholder="Enter Short Description">{{isset($home_contact_us_section['contact_us_short_description'])?$home_contact_us_section['contact_us_short_description']:''}}</textarea>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="description">Address</label>
                                    <textarea name="contact_us_address" id="description" class="form-control address-sec"
                                        placeholder="Enter Address">{{isset($home_contact_us_section['contact_us_address'])?$home_contact_us_section['contact_us_address']:''}}</textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="description">Google Map Location <span>You should have to use iframe src link here</span></label>
                                    <textarea name="contact_us_google_map" id="description" class="form-control"
                                        placeholder="Enter your iframe link">{{isset($home_contact_us_section['contact_us_google_map'])?$home_contact_us_section['contact_us_google_map']:''}}</textarea>
                                </div>
                            </div>
                         
                        </div>
                        <button type="submit" class="btn btn-primary mt-5">Submit</button>
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
    $('.address-sec').summernote();
});
</script>
<script>
    let i = 934;
        $(document).on("click", ".add-slider-img", function (e) {
            i++
            let html = `<div class="row align-items-center" id="form_column_${i}">
                <div class="col-lg-10">
                            <div class="form-group">
                                <label for="profile">Image <span class="text-danger">Expecting image size: 1980px by 840px</span> </label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input name="slider_bg_image[]" type="file" class="custom-file-input" id="profile">
                                        <label class="custom-file-label" for="profile">Choose Image</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <button type="button" class="btn btn-danger btn-sm remove-slider-img" data-id="${i}">X</button>
                        </div>

                                            </div>`;
            $("#slider-add-new-section").append(html);
        });
        $(document).on('click', '.remove-slider-img', function (e) {
            const id = $(this).attr('data-id');
            console.log(id);
            $('#form_column_' + id).remove();

        });
</script>
@endsection