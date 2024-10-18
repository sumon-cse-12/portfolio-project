@extends('layouts.admin')

@section('title','Notice')

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">{{trans('admin.notice_create')}}</h2>
                        <a class="btn btn-info float-right" href="{{route('admin.notice.index')}}">@lang('admin.form.button.back')</a>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form method="post" role="form" id="planForm" action="{{route('admin.notice.store')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            @include('admin.notice.form')
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">@lang('admin.submit')</button>
                        </div>
                    </form>
                </div>


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

@endsection

