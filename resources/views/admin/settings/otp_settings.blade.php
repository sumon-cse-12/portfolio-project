@extends('layouts.admin')

@section('title')
        {{trans('OTP Settings')}}
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
                            {{trans('OTP Settings')}}
                        </h2>
                        <div class="float-right">
                        </div>
                    </div>

                    <!-- /.card-header -->
                    <div class="card-body table-body">
                        <div class="card pb-4 p-4">
                            <form method="post" role="form" id="otp_form"
                                  action="{{route('admin.settings.otp')}}" enctype="multipart/form-data">
                                @csrf

                                <div class="row pb-4">
                                    <div class="col-md-6">
                                        <label for="">Select Customer</label>
                                        <select name="customer_id" id="selectCustomer" class="form-control">
                                            @if($customers->isNotEmpty())
                                                <option value="">{{trans('--Select Customer--')}}</option>
                                                @foreach($customers as $customer)
                                                    <option value="{{$customer->id}}">{{$customer->email}}</option>
                                                @endforeach
                                            @else
                                                <option value="">No Data Available</option>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="">Status</label>
                                        <select name="customer_otp_status" class="form-control" id="">
                                            <option value="">{{trans('--Select Customer--')}}</option>
                                            <option value="inactive">{{trans('Inactive')}}</option>
                                            <option value="active">{{trans('Active')}}</option>
                                        </select>
                                    </div>
                                </div>

                                <button type="submit"
                                        class="btn btn-primary">@lang('admin.submit')</button>
                            </form>
                        </div>

                        <table id="contacts" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>{{trans('Name')}}</th>
                                <th>{{trans('Email')}}</th>
                                <th>{{trans('Status')}}</th>
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
        $('#contacts').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax:'{{route('admin.settings.get.all.otp.user')}}',

            columns: [
                { "data": "full_name" },
                { "data": "email" },
                { "data": "status" },
            ]
        });

        $('#selectCustomer').select2({
            multiple:false
        })
        $(document).on('change', '#selectCustomer', function(e){
            const customer_id = $(this).val();

            $.ajax({
                type:'GET',
                url:'{{route('admin.settings.user.otp.status')}}',
                data:{
                    customer_id:customer_id,
                },

                success:function(res){

                    $('select[name=customer_otp_status]').val(res.data).trigger('change')
                    // $('select[name=customer_otp_status]').trigger('change')
                }

            });

        });
    </script>
@endsection

