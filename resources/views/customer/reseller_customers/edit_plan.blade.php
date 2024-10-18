@extends('layouts.customer')

@section('title','Customers Plan Edit')

@section('extra-css')

@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12 mx-auto col-sm-10 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">{{trans('admin.masking_non_masking_rate')}}</h2>
                        <a class="btn btn-info float-right" href="{{route('customer.reseller-customers.index')}}">@lang('admin.form.button.back')</a>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form method="post" role="form" id="customerForm" action="{{route('customer.current.plan.update', [$current_plan->customer_id])}}">
                        @csrf
                        <div class="card-body">
                            @if(isset($current_plan->plan) && $current_plan->plan->masking=='yes')
                            <div class="form-group">
                                <label for="first_name">{{trans('admin.masking_rate')}}</label>
                                <input value="{{isset($current_plan)?$current_plan->masking_rate:''}}" type="text" name="masking_rate" class="form-control" id="first_name"
                                       placeholder="{{trans('admin.masking_rate')}}">
                            </div>
                            @endif
                            @if(isset($current_plan->plan) && $current_plan->plan->non_masking=='yes')
                            <div class="form-group">
                                <label for="last_name">{{trans('admin.non_masking_rate')}}</label>
                                <input value="{{isset($current_plan)?$current_plan->non_masking_rate:''}}" type="text" name="non_masking_rate" class="form-control" id="last_name"
                                       placeholder="{{trans('admin.non_masking_rate')}}">
                            </div>
                            @endif
                            @if(isset($current_plan->plan) && $current_plan->plan->whatsapp_status=='yes')
                                <div class="form-group">
                                    <label for="last_name">{{trans('WhatsApp Rate')}}</label>
                                    <input value="{{isset($current_plan)?$current_plan->whatsapp_rate:''}}" type="text" name="whatsapp_rate" class="form-control"
                                           placeholder="{{trans('Whatsapp Rate')}}">
                                </div>
                            @endif
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





