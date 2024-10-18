@extends('layouts.customer')

@section('title') Groups @endsection

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
                        <h2 class="card-title">@lang('customer.list')
                            <i  data-toggle="tooltip" data-placement="right" class="fa fa-question-circle alert-tooltip"
                                title="Make sure before importing contact groups, you run cron jobs"></i>
                        </h2>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{route('customer.groups.create')}}">@lang('customer.new_group')

                            </a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="groups" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th data-orderable="false">
                                    <button class="btn btn-xs btn-sm btn-default bulk_delete_all" data-checked="false">
                                        <i class="far fa-square "></i>
                                    </button>

                                    <i id="deleteAll" class="fa fa-trash c-pointer btn-sm ml-3 text-danger"></i>

                                </th>
                                <th>@lang('customer.group_name')</th>
                                <th style="max-width: 500px">@lang('customer.contacts')</th>
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
    <!-- Button trigger modal -->
    <!-- Modal -->
    <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{route('customer.export.group.contact')}}" id="exportForm" method="get">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Export Group Contacts</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="groupId">
                        <div class="form-group">
                            <label for="">Select Label</label>
                            <select name="label[]" class="form-control" id="label">
                                @foreach($labels as $label)
                                    <option value="{{$label->id}}">{{$label->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary confirmExport">Confirm</button>
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
    <script src="{{asset('js/readmore.min.js')}}"></script>

    <script>
        "use strict";
        let interval;
        let groupDataTable=$('#groups').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax:'{{route('customer.group.get.all')}}',
            columns: [
                { "data": "bulk_delete", "ordering": false,},
                { "data": "name" },
                { "data": "contacts" },
                { "data": "status" },
                { "data": "action" },
            ],
            fnInitComplete: function(oSettings, json) {
                $(".show-more").css('overflow', 'hidden').readmore({collapsedHeight: 20,moreLink: '<a href="#">More</a>',lessLink: '<a href="#">Less</a>'});
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
                    url:'{{route('customer.group.check.import.status')}}',
                    data:{
                        ids:JSON.stringify(ids)
                    },
                    success:function(res){
                        if(res.status=='success'){
                            if(res.data>0){
                                groupDataTable.ajax.reload(()=>{
                                    //TODO:: Need ids of completed import so that we can check and pop the ids and send new ids to the server and check
                                    if(interval && ids.length==res.data){
                                        clearInterval(interval);
                                    }
                                    $(".show-more").css('overflow', 'hidden').readmore({collapsedHeight: 20,moreLink: '<a href="#">More</a>',lessLink: '<a href="#">Less</a>'});
                                });
                            }
                        }
                    }
                })
            }
        }

        $(document).on('click', '.export_group_contact', function (e) {
            $('.confirmExport').html('Confirm');
            const id = $(this).attr('data-id');
            $('#groupId').val(id);
            $('#exportModal').modal('show');
        });
        $('#exportForm').submit(function (e) {
            setTimeout(function () {
                $('#exportModal').modal('hide');
            }, 700);
        });
        $('#label').select2({
            multiple:true
        });


        let values =[];
        $(document).on('click', '.groups', function (e){
            if ($(this).attr('data-checked') == 'false') {
                $(this).attr('data-checked', 'true');
            }else {
                $(this).attr('data-checked', 'false');
            }
            $(this).removeAttr('checked');
            values.push($(this).val());
        });

        $(document).on('click', '#deleteAll', function (e){

            $('.groups:checked').each(function() {
                if ($(this).attr('data-checked') == 'true') {
                    values.push($(this).val());
                }
            });

            $.ajax({
                method:'POST',
                url:'{{route('customer.group.bulk.delete')}}',
                data:{
                    "_token":"{{csrf_token()}}",
                    ids:values
                },
                success:function (res){
                    if(res.status=='success'){
                        $(document).Toasts('create', {
                            autohide: true,
                            delay: 10000,
                            class: 'bg-success',
                            title: 'Notification',
                            body: res.message,
                        });
                        location.reload();
                    }else{
                        $(document).Toasts('create', {
                            autohide: true,
                            delay: 10000,
                            class: 'bg-danger',
                            title: 'Notification',
                            body: res.message,
                        });
                    }
                },
                error: function (res){
                    $(document).Toasts('create', {
                        autohide: true,
                        delay: 10000,
                        class: 'bg-danger',
                        title: 'Notification',
                        body: res.message,
                    });
                }
            })
        });

        $(document).on('click', '.bulk_delete_all', function (e){
            if ($(this).attr('data-checked') == 'false') {
                $(this).attr('data-checked', 'true');
                $(this).find('i').removeClass('fa-square').addClass('fa-check-square');
            } else {
                $(this).attr('data-checked', 'false');
                $(this).find('i').addClass('fa-square').removeClass('fa-check-square');
            }
            $('.groups').each(function() {
                $(this).trigger('click');
                $(this).attr('data-checked', 'true');
            });
        })


    </script>
@endsection
