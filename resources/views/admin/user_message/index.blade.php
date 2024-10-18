@extends('layouts.admin')

@section('title') {{trans('admin.subscribers')}} @endsection

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
                        <h2 class="card-title">{{trans('User Messages')}}</h2>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="plans" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>{{trans('Name')}}</th>
                                <th>{{trans('admin.email')}}</th>
                                <th>{{trans('Subject')}}</th>
                                <th>{{trans('Message')}}</th>
                            </tr>
                            </thead>
                            @foreach($user_messages as $user_message)
                            <tr>
                                <td>
                                    {{$user_message->name}}
                                </td>
                                <td>
                                    {{$user_message->email}}
                                </td>
                                <td>
                                    {{$user_message->subject}}
                                </td>
                                <td>
                                    {{$user_message->message}}
                                </td>
                            </tr>
                            @endforeach
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

