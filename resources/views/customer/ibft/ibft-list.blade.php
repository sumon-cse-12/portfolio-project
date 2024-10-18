@extends('layouts.customer')

@section('title') {{trans('admin.ibft')}} @endsection

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <style>

    </style>
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">{{trans('admin.ibft')}}
                            <i  data-toggle="tooltip" data-placement="right" class="fa fa-question-circle alert-tooltip"
                                title="Admin can create notice for users according to user type or for all system users.
                                 So that whenever any user login their account a notice board will popup."></i>
                        </h2>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-body">
                        <table id="idft" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>@lang('admin.name')</th>
                                <th>@lang('admin.nric_passport')</th>
                                <th>@lang('admin.t_instruction')</th>
                                <th>@lang('admin.bank_name')</th>
                                <th>@lang('admin.account_number')</th>
                                <th>@lang('admin.amount')</th>
                                <th>@lang('admin.status')</th>
                                <th>@lang('admin.action')</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($ibft_lists as $key => $ibft_list)
                                <tr>
                                    <td>{{ $ibft_list->name }}</td>
                                    <td>{{ $ibft_list->passport }}</td>
                                    <td>{{ $ibft_list->instruction }}</td>
                                    <td>{{ $ibft_list->bank_name }}</td>
                                    <td>{{ $ibft_list->account_number }}</td>
                                    <td>{{ $ibft_list->amount }}</td>
                                    <td class="text-uppercase">{{ $ibft_list->status }}</td>
                                    <td>
                                     <a class="btn btn-sm btn-primary" href="{{ route('admin.customer.ibft.transfer',[$ibft_list->id]) }}" >@lang('admin.see_details')</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
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

     {{-- <script>
        "use strict";
        $('#idft').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax:'{{route('admin.get.all.ibft')}}',
            columns: [
                { "data": "name" },
                { "data": "passport" },
                { "data": "instruction" },
                { "data": "bank_name" },
                { "data": "account_number" },
                { "data": "amount" },
                { "data": "status" },
                { "data": "action" },
            ]
        });
    </script> --}}
@endsection


