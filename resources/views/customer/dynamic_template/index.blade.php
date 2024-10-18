@extends('layouts.customer')

@section('title') Dynamic Template @endsection

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row"> 
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">@lang('customer.list')</h2>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{route('customer.dynamic-template.create')}}">@lang('customer.new')</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-body">
                        <table id="contacts" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>@lang('customer.name')</th>
                                <th>{{trans('customer.status')}}</th>
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


    <!-- Modal -->
    <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="export_form" action="{{route('customer.export.dynamic.template')}}" method="get">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Export Template</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" class="id">
                        <input type="hidden" name="type" class="type">
                        <div class="row">
                            <div class="col-md-12">
                                <h6>Export As :
                                    <button data-type="xls" type="button" class="btn btn-sm btn-primary submit_export ml-3">XLSX</button>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('extra-scripts')
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>

    <script>
        "use strict";
        $('#contacts').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax:'{{route('customer.get.all.dynamic.template')}}',
            columns: [
                { "data": "name" },
                { "data": "status" },
                { "data": "action" },
            ]
        });

        $(document).on('click', '.export', function(e){
            const id=$(this).attr('data-id');
            $('.id').val(id);
            $('#exportModal').modal('show');
        })
        $(document).on('click', '.submit_export', function(e){
            const type=$(this).attr('data-type');
            $('.type').val(type);
            $('#exportModal').modal('show');
            $('#export_form').submit();
        })
    </script>
@endsection

