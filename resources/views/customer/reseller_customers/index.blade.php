@extends('layouts.customer')

@section('title') Customers @endsection

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
                        <h2 class="card-title">@lang('Agencies')</h2>
                        <a class="btn btn-primary float-right" href="{{route('customer.reseller-customers.create')}}">@lang('admin.form.button.new')</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-body">
                        <table id="customers" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>@lang('admin.profile')</th>
                                <th>@lang('admin.plan_details')</th>
                                <th>{{trans('admin.wallet_details')}}</th>
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

    <div class="modal fade" id="addSubstrack" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

            <div class="modal-content">
                <form action="{{route('customer.credit.subtract')}}" method="post">
                    @csrf
                    <input type="hidden" name="customer_id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Add / Subtract</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div> <strong>Credit:</strong> <span class="credit"></span> </div>
                        </div>

                        <div class="form-group mt-2">
                            <label for="">Select Type</label>
                            <select name="select_type" class="form-control" id="">
                                <option value="add">Add</option>
                                <option value="subtract">Subtract</option>
                            </select>
                        </div>

                        <div class="row custom_credit credit_add" id="addSection">
                            <div class="col-md-12 form-group">
                                <label for="">Credit</label>
                                <input type="number" class="form-control" placeholder="Enter Credit" name="credit">
                            </div>
                        </div>

                        <div class="row custom_credit credit_subtract" id="subtractSection" style="display: none">
                            <div class="col-md-12 form-group">
                                <label for="">Credit</label>
                                <input type="number" class="form-control" placeholder="Enter Credit" name="pre_credit">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
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
        $('#customers').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax:'{{route('customer.get.all.reseller.customers')}}',
            columns: [
                { "data": "profile" },
                { "data": "plan_details" },
                { "data": "unit" },
                { "data": "status" },
                { "data": "action" },
            ]
        });

        $(document).on('click', '.addSubstract', function (e){

            const id = $(this).attr('data-id');
            $('input[name=customer_id]').val(id);

            $.ajax({
                type:'GET',
                url:'{{route('customer.get.info')}}',
                data:{
                    id:id
                },
                success:function(res){

                    if(res.data.credit){
                        $('.credit').text(res.data.credit);
                        $('input[name=pre_credit]').val(res.data.credit);
                    }
                }

            });

            $('#addSubstrack').modal('show');
        })



        $(document).on('change', 'select[name=select_type]', function (e){
            const type= $(this).val();
            $('.custom_credit').hide();

            $('.credit_'+type).show();

        })
    </script>
@endsection

