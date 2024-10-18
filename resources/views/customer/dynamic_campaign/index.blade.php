@extends('layouts.customer')

@section('title') Dynamic Campaign @endsection

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
                        <h2 class="card-title">@lang('customer.list')
                            <i  data-toggle="tooltip" data-placement="right" class="fa fa-question-circle alert-tooltip"
                                title="Before creating a campaign you have to create SMS template & make contact group.
                                Otherwise you can not create a campaign."></i>
                        </h2>
                        <div class="float-right">
                            <a href="{{route('customer.message.reports')}}" class="btn btn-info" target="_blank">Reports</a>
                            <a class="btn btn-primary" href="{{route('customer.dynamic.campaign.create')}}">@lang('customer.new')</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="contacts" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>@lang('customer.title')</th>
                                <th>@lang('customer.start_date') & Time</th>
                                <th>@lang('customer.status')</th>
                                <th>@lang('Frequency Status')</th>
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
@endsection

@section('extra-scripts')
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>

    <script>
        "use strict";
        let interval;
        let contactDataTable=$('#contacts').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax:'{{route('customer.get.all.dynamic.campaign')}}',
            columns: [
                { "data": "title","name":"campaigns.title" },
                { "data": "start_date" },
                { "data": "status" },
                { "data": "frequency_status" },
                { "data": "action" },
            ],
            fnInitComplete: function(oSettings, json) {
                interval=setInterval(checkImportingStatus, 5000);
            }
        });


        function checkImportingStatus() {
            let ids = [];
            $('.importing').each(function () {
                let text = $(this).text();
                text = text.replace(/ /g, '');
                const id = $(this).attr('data-id');
                if(text=='importing') {
                    ids.push(id);
                }
            });
            if(ids && ids.length > 0){
                $.ajax({
                    type:'GET',
                    url:'{{route('customer.campaign.check.import.status')}}',
                    data:{
                        ids:JSON.stringify(ids)
                    },
                    success:function(res){
                        if(res.status=='success'){
                            if(res.data>0){
                                contactDataTable.ajax.reload(()=>{
                                    //TODO:: Need ids of completed import so that we can check and pop the ids and send new ids to the server and check
                                    if(interval && ids.length==res.data){
                                        clearInterval(interval);
                                    }
                                });
                            }
                        }
                    }
                })
            }
        }

    </script>
@endsection


