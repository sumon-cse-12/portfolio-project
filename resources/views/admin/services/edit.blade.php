@extends('layouts.admin')

@section('title') {{ trans('admin.create') }} {{ trans('admin.services') }}   @endsection

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <style>
        .feature-input {
        position: relative;
        }
        .feature-input .delete-feature {
        position: absolute;
        top: 3px;
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
    </style>
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-12 mx-auto col-sm-10 mt-3">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">{{ trans('admin.create') }} {{ trans('admin.services') }}  </h2>

                    <a class="btn btn-info float-right" href="{{ route('admin.services.index') }}">{{ trans('admin.back') }}</a>
                </div>
                <form method="post" role="form" id="servicesForm" action="{{ route('admin.services.update',[$services]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        @include('admin.services.form')
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
    <script>
        $(document).ready(function() {
            $('#add-features').click(function() {
                var featureInput = '<div class="feature-input">' +
                                        '<input type="text" name="features[]" class="form-control mt-2" placeholder="Features">' +
                                        '<button class="delete-feature btn btn-sm add-btn btn-danger"><i class="fa fa-times"></i></button>' +
                                    '</div>';
                $('#features-container').append(featureInput);
            });
    
            $(document).on('click', '.delete-feature', function() {
                $(this).parent('.feature-input').remove();
            });
        });
    </script>
@endsection

