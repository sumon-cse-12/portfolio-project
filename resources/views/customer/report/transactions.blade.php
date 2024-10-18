@extends('layouts.customer')

@section('title')
    @if(request()->get('type') && request()->get('type')=='plan')
        {{trans('Plan Reports')}}
    @elseif(request()->get('type') && request()->get('type')=='top_up')
        {{trans('Topup Reports')}}
    @elseif(request()->get('type') && request()->get('type')=='message')
        {{trans('Message Reports')}}
    @else
        {{trans('Reports')}}
    @endif

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
                            @if(request()->get('type') && request()->get('type')=='plan')
                                {{trans('Plan Reports')}}
                            @elseif(request()->get('type') && request()->get('type')=='top_up')
                                {{trans('Topup Reports')}}
                            @elseif(request()->get('type') && request()->get('type')=='message')
                                {{trans('Message Reports')}}
                            @else
                                {{trans('Reports')}}
                            @endif
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
                                <th>{{trans('Type')}}</th>
                                <th>{{trans('Status')}}</th>
                                <th>{{trans('Amount')}}</th>
                                <th>{{trans('Transaction ID')}}</th>
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
                    <h5 class="modal-title text-white" id="myModalLabel2">  {{trans('Reports')}}</h5>
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
                                    <label for="">Type</label>
                                    <select  name="main_type" id="allType" class="form-control">
                                        <option value="">--Type--</option>
                                        <option {{isset($request_data['main_type']) && $request_data['main_type']=='top_up'?'selected':''}} value="top_up">Top-Up</option>
                                        <option {{isset($request_data['main_type']) && $request_data['main_type']=='plan'?'selected':''}} value="plan">Plan</option>
                                        <option {{isset($request_data['main_type']) && $request_data['main_type']=='number'?'selected':''}} value="number">Number</option>
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
        const queryString=window.location.search;

        orderDataTable=  $('#contacts').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax:'{{route('customer.get.all.transactions')}}'+queryString,

            columns: [
                { "data": "type" },
                { "data": "status" },
                { "data": "amount" },
                { "data": "transaction_id" },
            ]
        });

        $(document).on('change', '#allType', function(){
            let  type = $(this).val();

            if(type=='plan'){
                $('.sub_type_section').addClass('d-none');
                $('.emptySection').removeClass('d-none');
            }else{
                $('.emptySection').addClass('d-none');
                $('.sub_type_section').removeClass('d-none');
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
        $('select[name=customer_id]').select2({
            multiple:false
        })

    </script>
@endsection

