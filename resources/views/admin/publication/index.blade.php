@extends('layouts.admin')

@section('title') {{ trans('admin.list') }}  @endsection

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
                    <h2 class="card-title">{{ trans('admin.list') }} </h2>
                    <a class="btn btn-primary float-right" href="{{ route('admin.publications.create') }}">{{ trans('admin.new') }}</a>
                </div>
                <div class="card-body table-body">
                    <table id="blogListTable" class="table table-striped table-bordered dt-responsive nowrap">
                        <thead>
                            <tr>
                                <th>{{ trans('admin.title') }}</th>
                                <th>{{ trans('admin.description') }}</th>
                                <th>{{ trans('admin.image') }}</th>
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
        $('#blogListTable').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax:'{{route('admin.publications.get.all')}}',
            columns: [
                { "data": "title" },
                { "data": "description" },
                { "data": "image" },
                { "data": "status" },
                { "data": "action" },
            ]
        });
    </script>
@endsection

