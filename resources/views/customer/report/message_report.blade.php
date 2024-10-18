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
        .total-accumulated-sms {
            background: #5b73e8;
            padding: 4px 8px;
            color: #fff;
            border-radius: 3px;
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
                            <span id="download-message-report-sec"></span>
                            <button type="button" class="btn btn-primary show_filter_modal">
                                <i class="fa fa-filter mr-2" aria-hidden="true"></i>  Filter
                            </button>
                        </div>
                        <div class="float-right w-100 text-right" id="show_total_sms"></div>
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
                                <th>{{trans('Status')}}</th>
                                <th>{{trans('SMS Count')}}</th>
                                <th>{{trans('Title SMS')}}</th>
                                <th>{{trans('SMS Content')}}</th>
                                <th>{{trans('Characters Count.')}}</th>
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
                                        <input placeholder="--Select Date Range--" type="text" value="{{isset($request_data['date'])?$request_data['date']:''}}" name="date"
                                               class="form-control pull-right" id="reservation">
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3 {{$staff_role_id?'d-none':''}}">
                                    <label for="role_id"> Roles </label>
                                    <select id="role_id" class="form-control select2-single">
                                        <option value="">--Select Role--</option>
                                        @foreach($roles as $role)
                                            <option {{isset($request_data['role_id']) && $request_data['role_id']==$role->id?'selected':''}}
                                                    value="{{$role->id}}">{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label for="staff_id"> Profile </label>
                                    <select name="staff_id" id="staff_id" class="form-control select2-single">
                                        <option value="">--Select Profile--</option>

                                    </select>
                                </div>
                                <div class="form-group col-md-12 mt-2">
                                    <label for="">Status</label>
                                    <select name="status" id="" class="form-control">
                                        <option  value="">--Select Status--</option>
                                         <option {{isset($request_data['status']) && $request_data['status']=='sent'?'selected':''}} value="sent">Sent</option>
                                        <option {{isset($request_data['status']) && $request_data['status']=='failed'?'selected':''}} value="failed">Failed</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="">Type</label>
                                    <select name="type" id="" class="form-control">
                                        <option  value="">--Select Type--</option>
                                        <option {{isset($request_data['type']) && $request_data['type']=='plain_sms'?'selected':''}} value="plain_sms">Plain SMS</option>
                                        <option {{isset($request_data['type']) && $request_data['type']=='whatsapp_sms'?'selected':''}} value="whatsapp_sms">Whatsapp SMS</option>
                                    </select>
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
        const staffs=@json($staffs);
        const queryString=window.location.search;

        orderDataTable=  $('#contacts').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax:'{{route('customer.get.all.messages.report')}}'+queryString,

            columns: [
                { "data": "profile" },
                { "data": "details" },
                { "data": "from" },
                { "data": "to" },
                { "data": "status" },
                { "data": "total" },
                { "data": "title_sms" },
                { "data": "body" },
                { "data": "characters_count" },
            ],
            drawCallback: function(oSettings) {
                const json=oSettings.json;
                const data = json.data;
                let filteredId = [];
                let totalSMS = 0;
                $.each( data, function( key, value ) {
                    filteredId.push(value.id);
                    totalSMS=value.total_sms;
                });

                const url = '{{route('customer.message.report.download')}}'+'?reports='+filteredId;


                $("#download-message-report-sec").html(`<a href="${url}" class="btn btn-primary download-message-report">Download</a>`);
                $("#show_total_sms").html(`<div class="mb-2 mt-2"><span class="total-accumulated-sms">Total Accumulated SMS : ${totalSMS}</span></div>`)
            }
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

        $(document).on('change','#role_id', function (e){
            let role_id=$(this).val();
            const staffDetails=staffs[role_id];
            const staffId="{{$request_data['staff_id']??''}}";
            let select="<option>--Select Profile--</option>";
            $.each(staffDetails,function(index,value){
                select+=`<option ${staffId && staffId==value.id?'selected':''} value="${value.id}">${value.first_name} ${value.last_name}</option>`;
            });

            $('#staff_id').html(select);

        });

        @if($staff_role_id)
            $('#role_id').val({{$staff_role_id}}).trigger('change');
        @endif


    </script>
@endsection

