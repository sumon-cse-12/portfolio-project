@extends('layouts.customer')

@section('title') Plans @endsection

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
                        <h2 class="card-title">@lang('admin.plans.plan')</h2>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{route('customer.plans.create')}}">@lang('admin.form.button.new')</a>
                            <a class="btn btn-warning" href="{{route('customer.plans.requests')}}">@lang('admin.form.button.request')
                                <span class="diff-counter plan_counter_2">0</span>
                            </a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-body">
                        <table id="plans" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>@lang('admin.table.title')</th>
                                <th>@lang('admin.table.price')</th>
                                <th>{{trans('admin.form.plan_type')}}</th>
                                <th>{{trans('Free Credit')}}</th>
                                <th>@lang('admin.table.status')</th>
                                <th>@lang('admin.table.created_at')</th>
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
        $('#plans').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax:'{{route('customer.get.all.plans')}}',
            columns: [
                { "data": "title" },
                { "data": "sell_price" },
                { "data": "plan_type" },
                { "data": "free_sms_credit" },
                { "data": "status" },
                { "data": "created_at" },
                { "data": "action" },
            ]
        });
    </script>
@endsection

