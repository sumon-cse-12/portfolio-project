@extends('layouts.admin')

@section('title') {{trans('Database Backup List')}} @endsection

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
                        <h2 class="card-title">{{trans('Database Backup List')}}</h2>
                        <div class="float-right">
                            <a href="{{route('db.backup',['redrct'=>'page'])}}" class="btn btn-sm btn-primary">Create</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="plans" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('Date')</th>
                                <th>@lang('File Name')</th>
                                <th>@lang('admin.ticket.action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($files->isNotEmpty())
                                @foreach($files as $key=>$file)
                                    <tr>
                                        <td>
                                            {{++$key}}
                                        </td>
                                        <td>
                                            {{formatDate($file->created_at)}}
                                        </td>
                                        <td>
                                            {{$file->file_name}}
                                        </td>
                                        <td>
                                            <a href="{{route('admin.download.db.backup',['id'=>$file])}}" target="_blank" class="btn btn-primary btn-sm">{{trans('Download')}}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="text-center">
                                    <td colspan="4">{{trans('--No Data Available--')}}</td>
                                </tr>
                            @endif
                            </tbody>

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

@endsection

