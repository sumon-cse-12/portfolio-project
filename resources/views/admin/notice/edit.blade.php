@extends('layouts.admin')

@section('title','Edit Notice')

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12 mx-auto col-sm-10">
                <!-- Custom Tabs -->
                <div class="card">

                    <div class="card-header d-flex p-0">
                        <h2 class="card-title p-3"><a href="{{route('admin.notice.index')}}">@lang('admin.plans.plan')</a></h2>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <form method="post" role="form" id="numberForm"
                              action="{{route('admin.notice.update',[$notice])}}">
                            @csrf
                            @method('put')
                            @include('admin.notice.form')

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">@lang('admin.submit')</button>
                            </div>
                        </form>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- ./card -->


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

