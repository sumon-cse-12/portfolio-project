@extends('layouts.admin')

@section('title') {{trans('admin.notices')}} @endsection

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
                        <h2 class="card-title">{{trans('admin.notices')}}
                            <i  data-toggle="tooltip" data-placement="right" class="fa fa-question-circle alert-tooltip"
                                title="Admin can create notice for users according to user type or for all system users.
                                 So that whenever any user login their account a notice board will popup."></i>
                        </h2>
                        <div class="float-right">
                        <a class="btn btn-primary" href="{{route('admin.notice.create')}}">@lang('admin.form.button.new')</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-body">
                        <table id="plans" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>@lang('admin.table.title')</th>
                                <th>{{trans('admin.table.description')}}</th>
                                <th>{{trans('customer.type')}}</th>
                                <th>@lang('admin.table.status')</th>
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
            ajax:'{{route('admin.get.all.notice')}}',
            columns: [
                { "data": "title" },
                { "data": "description" },
                { "data": "for" },
                { "data": "status" },
                { "data": "action" },
            ]
        });
    </script>
@endsection

