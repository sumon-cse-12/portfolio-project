@extends('layouts.admin')

@section('title') {{ trans('admin.omug') }}  @endsection

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
                    <h2 class="card-title">{{ trans('admin.omug') }} </h2>
                    <a class="btn btn-primary float-right" href="{{ route('admin.omug.create') }}">{{ trans('admin.new') }}</a>
                </div>
                <div class="card-body table-body">
                    <table id="omugTable" class="table table-striped table-bordered dt-responsive nowrap">
                        <thead>
                            <tr>
                                <th>{{ trans('admin.title') }}</th>
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
        $('#omugTable').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax:'{{route('admin.omug.get.all')}}',
            columns: [
                { "data": "title" },
                { "data": "image" },
                { "data": "status" },
                { "data": "action" },
            ]
        });
    </script>
@endsection

