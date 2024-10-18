@extends('layouts.admin')

@section('title') {{ trans('admin.slider') }} @endsection


@section('content')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">{{ trans('admin.slider') }} </h2>
                </div>
                @php
                $home_slider_section = json_decode(get_settings('home_slider_section'), true);
                $slider_images = isset($home_slider_section['home_slider_images'])?$home_slider_section['home_slider_images']:[];
                @endphp
                <div class="card-body">
                    <form action="{{route('admin.theme.slider.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="title">Slider Title</label>
                                    <input value="{{isset($home_slider_section['slider_title'])?$home_slider_section['slider_title']:''}}" type="text" name="slider_title" class="form-control" id="title"
                                        placeholder="Title">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="title">Slider Sub Title</label>
                                    <input value="{{isset($home_slider_section['slider_sub_title'])?$home_slider_section['slider_sub_title']:''}}" type="text" name="slider_sub_title" class="form-control" id="title"
                                        placeholder="Sub Title">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="description">Short Description</label>
                                    <textarea name="slider_short_description" id="description" class="form-control"
                                        placeholder="Enter Short Description">{{isset($home_slider_section['slider_short_description'])?$home_slider_section['slider_short_description']:''}}</textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="title">Book an Instruments</label>
                                    <input value="{{isset($home_slider_section['book_an_instruments'])?$home_slider_section['book_an_instruments']:''}}" type="text" name="book_an_instruments" class="form-control" id="title"
                                        placeholder="Book an Instruments">
                                </div>
                            </div>
                         
                        </div>
                         @if (isset($slider_images) && $slider_images)
                         @php 
                          $counter = 999;
                         @endphp
                       <div class="row">
                        <div class="col-lg-12">
                            <button type="button" class="btn btn-info btn-sm add-slider-img mt-4 float-right">+</button>
                        </div> 
                       </div>
                         @foreach ($slider_images as $key => $slider_image)
                         @php 
                          $counter++;
                         @endphp
                        <div class="row align-items-center" id="form_column_{{$counter}}">
                           
                            <div class="col-lg-10">
                                <div class="form-group">
                                    <label for="profile">Image <span class="text-danger">Expecting image size: 1980px by
                                            840px</span> </label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input name="slider_bg_image[]" type="file" class="custom-file-input"
                                                id="profile">
                                            <label class="custom-file-label" for="profile">Choose Image</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <button type="button" class="btn btn-danger btn-sm remove-slider-img mt-4" data-id="{{$counter}}">X</button>
                            </div>
                        </div>
                         @endforeach
                             @else
                             <div class="row align-items-center">
                                <div class="col-lg-10">
                                    <div class="form-group">
                                        <label for="profile">Image <span class="text-danger">Expecting image size: 1980px by
                                                840px</span> </label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input name="slider_bg_image[]" type="file" class="custom-file-input"
                                                    id="profile">
                                                <label class="custom-file-label" for="profile">Choose Image</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <button type="button" class="btn btn-info btn-sm add-slider-img mt-4">+</button>
                                </div>
                             </div>
                       
                     
                        @endif
                        <div id="slider-add-new-section">

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