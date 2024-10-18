@extends('layouts.admin')

@section('title') Customers @endsection

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">@lang('admin.list')</h2>
                        <a class="btn btn-primary float-right" href="{{route('admin.customers.create')}}">@lang('admin.new')</a>
                    </div>
                    <div class="card-body table-body">
                        <table id="customers" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>@lang('admin.image')</th>
                                <th>@lang('admin.profile')</th>
                                <th>@lang('admin.created') @lang('admin.at')</th>
                                <th>@lang('admin.status')</th>
                                <th>@lang('admin.action')</th>
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
        $('#customers').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax:'{{route('admin.customer.get.all')}}',
            columns: [
                { "data": "image" },
                { "data": "profile" },
                { "data": "created_at" },
                { "data": "status" },
                { "data": "action" },
            ]
        });
    </script>
@endsection

