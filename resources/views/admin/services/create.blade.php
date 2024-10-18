@extends('layouts.admin')

@section('title') {{ trans('admin.services') }}   @endsection

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/summernote/dist/summernote-bs4.min.css" rel="stylesheet">
    <style>
        .feature-input {
        position: relative;
        }
        .feature-input .delete-feature {
        position: absolute;
        top: 8px;
        right: 5px;
        z-index: 1;
        }
        .add-btn i{
        font-size: 16px !important;
        }
        .add-btn{
            margin-top: 0px !important;
        margin-bottom: 0;
        padding: 0.5rem 1rem !important;
        }
        .add-features{
            padding-top: 8px !important;
        }
    </style>
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-12 mx-auto col-sm-10 mt-3">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title"> {{ trans('admin.services') }}  </h2>
                </div>
                <form method="post" role="form" id="servicesForm" action="{{route('admin.theme.services.store')}}" enctype="multipart/form-data">
                    @csrf
                        <div class="card-body">

                            @php
                            $services =json_decode(get_settings('services'));
                            @endphp
                             <div class="form-group">
                                <label for="title">{{trans('admin.title')}}</label>
                                <input value="{{isset($services)?$services->title:''}}" type="text" name="title" class="form-control" placeholder="{{trans('admin.title')}}">
                            </div>
                            <div class="form-group">
                                <label for="sub_title">{{trans('admin.sub_title')}}</label>
                                <input value="{{isset($services)?$services->sub_title:''}}" type="text" name="sub_title" class="form-control" placeholder="{{trans('admin.sub_title')}}">
                            </div>
                            <div class="add-team text-right">
                                <button type="button" class="btn btn-primary add-input" id="add-input"><i class="fa fa-plus"></i></button>
                            </div>
                        <div class="input-fields" id="input-fields">
                            @if (isset($services)?$services->service_data:'')
                                
                            @foreach ($services->service_data as $key => $service)
                            <div class="row input-content mt-4" >
                                <div class="col-lg-12 text-right">
                                    <button class="delete-fields btn btn-sm btn-danger"><i class="fa fa-times"></i></button>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">{{trans('admin.name')}}</label>
                                        <input value="{{isset($service)?$service->name:''}}" type="text" name="name[]" class="form-control" placeholder="{{trans('admin.name')}}">
                                    </div>
                                </div>                           
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="image">{{trans('admin.image')}}</label>
                                        <input type="hidden" name="pre_image[]" value="{{isset($service)?$service->image:''}}">
                                        <input value="" type="file" name="image[]" class="form-control" placeholder="{{trans('admin.image')}}">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="description">@lang('admin.description')</label>
                                        <textarea name="description[]" id="description" class="form-control description"
                                                  placeholder="{{trans('admin.description')}}">{{isset($service)?$service->description:''}}</textarea>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
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
$('#add-input').click(function() {
    var inputFields = `
    <div class="row input-content mt-4" >
                                <div class="col-lg-12 text-right">
                                    <button class="delete-fields btn btn-sm btn-danger"><i class="fa fa-times"></i></button>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name">{{trans('admin.name')}}</label>
                                        <input value="" type="text" name="name[]" class="form-control" placeholder="{{trans('admin.name')}}">
                                    </div>
                                </div>                           
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="image">{{trans('admin.image')}}</label>
                                        <input value="" type="file" name="image[]" class="form-control" placeholder="{{trans('admin.image')}}">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="description">@lang('admin.description')</label>
                                        <textarea name="description[]" id="description" class="form-control description"
                                                  placeholder="{{trans('admin.description')}}"></textarea>
                                    </div>
                                </div>
                            </div>
    `;
    $('#input-fields').append(inputFields); 
    $('.description').summernote();
});



        $('.description').summernote();



    $(document).on('click', '.delete-fields', function() {
        $(this).closest('.input-content').remove(); 
    });
});

    </script>
@endsection

