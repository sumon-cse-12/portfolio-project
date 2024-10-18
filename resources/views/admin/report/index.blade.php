@extends('layouts.admin')

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
                                    <th>{{trans('Profile')}}</th>
                                    <th>{{trans('Type')}}</th>
                                    <th>{{trans('Sub-Type')}}</th>
                                    <th>{{trans('Date')}}</th>
                                    <th>{{trans('Amount')}}</th>
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
                                   <label for=""> Customer Type</label>
                                   <select name="customer_type" id="customerType" class="form-control">
                                       <option value="">--Customer Type--</option>
                                       <option {{request()->get('customer_type') && request()->get('customer_type')=='master_reseller'?'selected':''}} value="master_reseller">Master Reseller</option>
                                       <option {{request()->get('customer_type') && request()->get('customer_type')=='reseller'?'selected':''}} value="reseller">Reseller</option>
                                       <option {{request()->get('customer_type') && request()->get('customer_type')=='customer'?'selected':''}} value="customer">Customer</option>
                                   </select>
                               </div>

                               <div class="col-md-12 mt-3">
                                   <label for=""> Customer</label>
                                   <select name="customer_id" id="customers" class="form-control">
                                       <option value="">--Customer--</option>
                                       @foreach($customers as $customer)
                                           <option {{isset($request_data['customer_id']) && $request_data['customer_id']==$customer->id?'selected':''}}
                                                   value="{{$customer->id}}">{{$customer->full_name}}</option>
                                       @endforeach
                                   </select>
                               </div>

                               <div class="col-md-12 mt-3">
                                   <label for="">Type</label>
                                   <select {{request()->get('type') && request()->get('type')!='all'?'disabled':''}} name="main_type" id="allType" class="form-control">
                                       <option value="">--Type--</option>
                                       <option {{isset($request_data['main_type']) && $request_data['main_type']=='top_up'?'selected':(request()->get('type') && request()->get('type')=='top_up'?'selected':'')}} value="top_up">Top-Up</option>
                                       <option {{isset($request_data['main_type']) && $request_data['main_type']=='plan'?'selected':(request()->get('type') && request()->get('type')=='plan'?'selected':'')}} value="plan">Plan</option>
                                       <option {{isset($request_data['main_type']) && $request_data['main_type']=='message'?'selected':(request()->get('type') && request()->get('type')=='message'?'selected':'')}} value="message">Message</option>
                                   </select>
                               </div>

                               <div class="col-md-12 mt-3">
                                   <div class="emptySection d-none">

                                   </div>
                                   <div class="form-group sub_type_section
                                        {{(request()->get('main_type')=='top_up' || request()->get('main_type')=='message')?'':'d-none' }}">
                                       <label for="">Sub-type</label>
                                       <select name="sub_type" id="" class="form-control">
                                           <option  value="">--Sub-Type--</option>
                                           <option {{isset($request_data['sub_type']) && $request_data['sub_type']=='whatsapp'?'selected':''}} value="whatsapp">WhatsApp</option>
                                           <option {{isset($request_data['sub_type']) && $request_data['sub_type']=='masking'?'selected':''}} value="masking">Masking</option>
                                           <option {{isset($request_data['sub_type']) && $request_data['sub_type']=='non_masking'?'selected':''}} value="non_masking">Non-Masking</option>
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
            ajax:'{{route('admin.getall.reports')}}'+queryString,

            columns: [
                { "data": "customer" },
                { "data": "type" },
                { "data": "sub_type" },
                { "data": "date" },
                { "data": "amount" },
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

        $(document).on('change', '#customerType', function(){
            let  type = $(this).val();

            $.ajax({
                type:'GET',
                url:'{{route('admin.get.customer')}}',
                data:{
                    type:type
                },

                success:function(res){
                    if(res.data){
                        let html='<option>--Select Customer--</option>';
                        $.each(res.data,  function(index, value){
                            html+=`<option value="${value.id}">${value.first_name + value.last_name}</option>`
                        });

                        $('#customers').html(html);
                    }
                }
            })

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

