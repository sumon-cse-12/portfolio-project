@extends('layouts.customer')

@section('title') {{trans('admin.topup_request')}} @endsection

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
                        <h2 class="card-title">@lang('admin.topup_request')</h2>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="requests" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>@lang('admin.numbers.customer')</th>
                                <th>{{trans('customer.created_at')}}</th>
                                <th>{{trans('admin.payment_status')}}</th>
                                <th>{{trans('Credit')}}</th>
                                <th>{{trans('Credit Type')}}</th>
                                <th>@lang('admin.form.status')</th>
                                <th>@lang('admin.table.action')</th>
                            </tr>
                            </thead>

                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
@endsection

@section('extra-scripts')
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>

    <script>
        "use strict";
        $('#requests').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax:'{{route('customer.get.all.topup.request')}}',
            columns: [
                { "data": "customer" },
                { "data": "created_at" },
                { "data": "payment_status" },
                { "data": "credit" },
                { "data": "credit_type" },
                { "data": "status" },
                { "data": "action" },
            ]
        });
    </script>
@endsection

