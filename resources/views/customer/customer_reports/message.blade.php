@extends('layouts.customer')

@section('title')
    {{trans('Message Reports')}}
@endsection

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" href="{{asset('/css/custom_modal.css')}}">

    <style>
        .daterangepicker.show-calendar{
            top: 226.391px !important;
        }
        .custom-box-shadow{
            background: #f5f1f1 !important;
        }
    </style>
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">
                            {{trans('Message Reports')}}
                        </h2>
                        <div class="float-right">
                            <button type="button" class="btn btn-primary show_filter_modal">
                                <i class="fa fa-filter mr-2" aria-hidden="true"></i>  Filter
                            </button>
                        </div>
                    </div>

                    <!-- /.card-header -->
                    <div class="card-body table-body">
                        <table id="contacts" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>{{trans('Profile')}}</th>
                                <th>{{trans('Details')}}</th>
                                <th>{{trans('From')}}</th>
                                <th>{{trans('To')}}</th>
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
    <div class="modal right fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header justify-content-center bg-primary">
                    <h4 class="modal-title text-white" id="myModalLabel2">     {{trans('Message Reports')}}  </h4>
                </div>

                <div class="modal-body">
                    <div class="container">
                        <form action="">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="">Date</label>
                                    <div class="input-group">
                                        <input type="text" value="{{isset($request_data['date'])?$request_data['date']:''}}" name="date"
                                               class="form-control pull-right" id="reservation">
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label for=""> Customer</label>
                                    <select name="customer_id" id="customers" class="form-control select2-single">
                                        <option value="">--Customer--</option>
                                        @foreach($customers as $customer)
                                            <option {{isset($request_data['customer_id']) && $request_data['customer_id']==$customer->id?'selected':''}}
                                                    value="{{$customer->id}}">{{$customer->full_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <div class="form-group">
                                        <label for="">Destination</label>
                                        <select name="destination" id="" class="form-control">
                                            <option  value="">--Select Destination--</option>
                                            <option {{isset($request_data['destination']) && $request_data['destination']=='inbound'?'selected':''}} value="inbound">Inbound</option>
                                            <option {{isset($request_data['destination']) && $request_data['destination']=='outbound'?'selected':''}} value="outbound">Outbound</option>
                                        </select>
                                    </div>
                                </div>
                                    <div class="col-md-12 mt-3">
                                    <div class="form-group">
                                        <label for="">Type</label>
                                        <select name="type" id="" class="form-control">
                                            <option  value="">--Select Type--</option>
                                            <option {{isset($request_data['type']) && $request_data['type']=='plain_sms'?'selected':''}} value="plain_sms">Plain SMS</option>
                                            <option {{isset($request_data['type']) && $request_data['type']=='mms'?'selected':''}} value="mms">MMS</option>
                                            <option {{isset($request_data['type']) && $request_data['type']=='whatsapp_sms'?'selected':''}} value="whatsapp_sms">Whatsapp SMS</option>
                                            <option {{isset($request_data['type']) && $request_data['type']=='voice_sms'?'selected':''}} value="voice_sms">Voice SMS</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6 pt-2">
                                    <button class="btn btn-danger btn-sm mt-4 d-block w-100 closeFilterModal" type="button">Close</button>
                                </div>
                                <div class="col-md-6 pt-2">
                                    <button class="btn btn-primary btn-sm mt-4 d-block w-100 float-right" type="submit">Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div><!-- modal-content -->
        </div><!-- modal-dialog -->
    </div>
    <!-- modal -->
@endsection

@section('extra-scripts')
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/moment.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script>
        "use strict";
        let orderDataTable='';
        const queryString=window.location.search;

        orderDataTable=  $('#contacts').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax:'{{route('customer.message.getall.reports')}}'+queryString,

            columns: [
                { "data": "profile" },
                { "data": "details" },
                { "data": "from" },
                { "data": "to" },
            ]
        });



        //Date range picker
        $('#reservation').daterangepicker();

        $(document).ready(function(){
            $('#reservation').val('');
        });
        $(document).on('click','.cancelBtn', function (e){
            $('#reservation').val('');
        });
        $(document).on('click','.show_filter_modal', function (e){
            $('#filterModal').modal('show');
        });
        $(document).on('click','.closeFilterModal', function (e){
            $('#filterModal').modal('hide');
        });
        $('.select2-single').select2({
            multiple:false
        });
    </script>
@endsection

