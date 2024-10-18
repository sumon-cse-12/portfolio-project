@extends('layouts.admin')

@section('title') {{ trans('admin.fees') }}  @endsection

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
                    <h2 class="card-title">{{ trans('admin.fees') }} </h2>
                    <a class="btn btn-primary float-right" href="{{ route('admin.fees.create') }}">{{ trans('admin.new') }}</a>
                </div>
                <div class="card-body table-body">
                    <table id="feesTable" class="table table-striped table-bordered dt-responsive nowrap">
                        <thead>
                            <tr>
                                <th>{{ trans('admin.service') }}</th>
                                <th>{{ trans('admin.type_of_instrument') }}</th>
                                <th>{{ trans('admin.uhn_rate') }}</th>
                                <th>{{ trans('admin.ea_rate') }}</th>
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
        $('#feesTable').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax:'{{route('admin.fees.get.all')}}',
            columns: [
                { "data": "service_name" },
                { "data": "type_of_instrument" },
                { "data": "uhn_rate" },
                { "data": "ea_rate" },
                { "data": "status" },
                { "data": "action" },
            ]
        });
    </script>
@endsection

