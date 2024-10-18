@extends('layouts.customer')

@section('title') {{trans('Coverage Area')}} @endsection

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
                        <h2 class="card-title">{{trans('Coverage')}}</h2>
                        <div class="float-right">
                        <a class="btn btn-primary" href="{{route('customer.coverage.create')}}">{{trans('Create')}}</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body  table-body">
                        <table id="coverage" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>{{trans('Country')}}</th>
                                <th>{{trans('Plain SMS')}}</th>
                                <th>{{trans('Receive SMS')}}</th>
                                <th>{{trans('Send MMS')}}</th>
                                <th>{{trans('Receive MMS')}}</th>
                                <th>{{trans('Send Voice SMS')}}</th>
                                <th>{{trans('Receive Voice SMS')}}</th>
                                <th>{{trans('Send Whatsapp SMS')}}</th>
                                <th>{{trans('Receive Whatsapp SMS')}}</th>
                                <th>Action</th>
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
        $('#coverage').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax:'{{route('customer.get.all.coverage')}}',
            columns: [
                { "data": "country" },
                { "data": "plain_sms" },
                { "data": "receive_sms" },
                { "data": "send_mms" },
                { "data": "receive_mms" },
                { "data": "send_voice_sms" },
                { "data": "receive_voice_sms" },
                { "data": "send_whatsapp_sms" },
                { "data": "receive_whatsapp_sms" },
                { "data": "action" },
            ]
        });
    </script>
@endsection

