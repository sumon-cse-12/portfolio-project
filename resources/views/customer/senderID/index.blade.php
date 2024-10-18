@extends('layouts.customer')

@section('title') Sender | ID @endsection

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <style>
        .select2-container--default.select2-dropdown.select2-dropdown--below {
            margin-right: 151px;
        }
        select2-container.select2-container--default.select2-container--open{
            left: 0;
        }

    </style>
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">@lang('customer.list')</h2>
                        <div class="float-right">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">@lang('customer.sender_id_request')</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="sender_ids" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>@lang('customer.sender_id')</th>
                                <th>{{trans('customer.expire_date')}}</th>
                                <th>@lang('customer.status')</th>
                                <th>@lang('customer.action')</th>
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

    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h4 class="modal-title ml-1">@lang('customer.new_sender_id')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('customer.senderID.create')
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editSenderId" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h4 class="modal-title ml-1">@lang('customer.edit_sender_id')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('customer.senderID.edit')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-scripts')
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('js/readmore.min.js')}}"></script>

    <script>
        "use strict";
        $('#sender_ids').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax:'{{route('customer.sender.get.all')}}',
            columns: [
                { "data": "sender_id" },
                { "data": "expire_date" },
                { "data": "status" },
                { "data": "action" },
            ],
        });

$(document).on('click', '.edit', function(e){
    e.preventDefault();

    const data_sender_id = $(this).attr('data-senderid');
    const data_url=$(this).attr('data-url');
    $('#updateForm').attr('action', data_url);
    $('#sender_id_edit').val(data_sender_id);
    $('#editSenderId').modal('show');
})

        $('#create_gateway').select2();
        $('#edit_gateway').select2();

    </script>
@endsection

