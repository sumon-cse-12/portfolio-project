@extends('layouts.admin')

@section('title') {{ trans('admin.sign_up_info') }}  @endsection

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">{{ trans('admin.sign_up_info') }} </h2>
                    <a class="btn btn-primary float-right" href="{{ route('admin.signupinfo.create') }}">{{ trans('admin.new') }}</a>
                </div>
                <div class="card-body table-body">
                    <table id="sign_up_info_Table" class="table table-striped table-bordered dt-responsive nowrap">
                        <thead>
                            <tr>
                                <th>{{ trans('admin.title') }}</th>
                                <th>{{ trans('admin.status') }}</th>
                                <th>{{ trans('admin.action') }}</th>
                            </tr>
                        </thead>
                    </table>
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
        "use strict";
        $('#sign_up_info_Table').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax:'{{route('admin.sign.up.info.get.all')}}',
            columns: [
                { "data": "title" },
                { "data": "status" },
                { "data": "action" },
            ]
        });
    </script>
@endsection

