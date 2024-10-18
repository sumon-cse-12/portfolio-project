@extends('layouts.admin')

@section('title') {{ trans('admin.fees') }}  @endsection

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <style>
        .card {
            width: 100% !important; 
            max-width: 700px !important; 
        }

        .input-sm {
            font-size: 14px; 
            padding: 10px; 
        }
        .content-wrapper{
            padding-left: 25% !important;
            padding-right: 25% !important;
            padding-top: 10% !important;
        }
        .table-body{
            padding: 50px 30px;
        }
        .form-control{
            font-size: 25px !important;
            height: calc(4.25rem + 2px) !important;
        }
        .card-footer{
            padding: 0px 0px !important;
        }
    </style>
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form  method="post" role="form" id="fees_title_Form" action="{{route('admin.settings.header_title',['name'=>'fees'])}}">
                            @csrf
                            
                            <div class="form-group">
                            <input name="header_title" value="{{get_settings('fees')}}" type="text" id="header_title" class="form-control" placeholder="Fees Header Title">
                            </div>
                            <div class="card-footer float-right">
                                <button type="submit" class="btn btn-primary">{{ trans('admin.submit') }}</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('extra-scripts')
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
@endsection

