@extends('layouts.admin')

@section('title') {{ trans('admin.teams') }}   @endsection

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-12 mx-auto col-sm-10 mt-3">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">{{ trans('admin.team') }}  </h2>
                </div>
                <form method="post" role="form" id="teamsForm" action="{{route('admin.theme.team.store')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        @php
                        $teams =json_decode(get_settings('team'));
                        @endphp
                        <div class="form-group">
                            <label for="title">{{trans('admin.title')}}</label>
                            <input value="{{isset($teams)?$teams->title:''}}" type="text" name="title" class="form-control" placeholder="{{trans('admin.title')}}">
                        </div>
                        <div class="add-team text-right">
                            <button type="button" class="btn btn-primary" id="add-input"><i class="fa fa-plus"></i></button>
                            </div>
                        <div class="input-fields" id="input-fields">
                            @if(isset($teams->team_data))
                            @foreach ($teams->team_data as $team)
                        <div class="row input-content" >
                            <div class="col-lg-12 text-right">
                                <button class="delete-fields btn btn-sm add-btn btn-danger"><i class="fa fa-times"></i></button>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">{{trans('admin.name')}}</label>
                                    <input value="{{isset($team)?$team->name:''}}" type="text" name="name[]" class="form-control" placeholder="{{trans('admin.name')}}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="work_title">{{trans('admin.work_title')}}</label>
                                    <input value="{{isset($team)?$team->work_title:''}}" type="text" name="work_title[]" class="form-control" placeholder="{{trans('admin.work_title')}}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="email">{{trans('admin.email')}}</label>
                                    <input value="{{isset($team)?$team->email:''}}" type="text" name="email[]" class="form-control" placeholder="{{trans('admin.email')}}">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="image">{{trans('admin.image')}}</label>
                                    <input type="hidden" name="pre_image[]" value="{{isset($team)?$team->name:''}}">
                                    <img src="{{asset('uploads/'.$team->image)}}" alt="" width="50" height="50">
                                    <input value="" type="file" name="image[]" class="form-control" placeholder="{{trans('admin.image')}}">
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
    <script>
$(document).ready(function() {
    $('#add-input').click(function() {
        var fieldsInput = '<div class="row input-content">' +
            '<div class="col-lg-12 text-right">' +
            '<button class="delete-fields btn btn-sm add-btn btn-danger"><i class="fa fa-times"></i></button>' +
            '</div>' +
            '<div class="col-lg-6">' +
            '<div class="form-group">' +
            '<label for="name">{{trans('admin.name')}}</label>' +
            '<input value="" type="text" name="name[]" class="form-control" placeholder="{{trans('admin.name')}}">' +
            '</div>' +
            '</div>' +
            '<div class="col-lg-6">' +
            '<div class="form-group">' +
            '<label for="work_title">{{trans('admin.work_title')}}</label>' +
            '<input value="" type="text" name="work_title[]" class="form-control" placeholder="{{trans('admin.work_title')}}">' +
            '</div>' +
            '</div>' +
            '<div class="col-lg-6">' +
            '<div class="form-group">' +
            '<label for="email">{{trans('admin.email')}}</label>' +
            '<input value="" type="text" name="email[]" class="form-control" placeholder="{{trans('admin.email')}}">' +
            '</div>' +
            '</div>' +
            '<div class="col-lg-6">' +
            '<div class="form-group">' +
            '<label for="image">{{trans('admin.image')}}</label>' +
            '<input value="" type="file" name="image[]" class="form-control" placeholder="{{trans('admin.image')}}">' +
            '</div>' +
            '</div>' +

            '</div>';
                $('#input-fields').append(fieldsInput);
            });

            $(document).on('click', '.delete-fields', function() {
                $(this).closest('.input-content').remove();
            });

});
    </script>
@endsection

