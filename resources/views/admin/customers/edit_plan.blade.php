@extends('layouts.admin')

@section('title','Customers Plan Edit')

@section('extra-css')

@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12 mx-auto col-sm-10 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">{{trans('SMS Unit Price')}}</h2>
                        <a class="btn btn-info float-right" href="{{route('admin.customers.index')}}">@lang('admin.back')</a>
                    </div>
                    <form method="post" role="form" id="customerForm" action="{{route('admin.customer.current.plan.update', [$current_plan->customer_id])}}">
                        @csrf
                        <div class="card-body">
                            @if(isset($current_plan->plan) && $current_plan->plan->sms_unit_price)
                            <div class="form-group">
                                <label for="first_name">{{trans('SMS Unit Price')}}</label>
                                <input value="{{isset($current_plan)?$current_plan->sms_unit_price:''}}" type="text" name="sms_unit_price" class="form-control"
                                    placeholder="{{trans('Enter SMS Unit Price')}}">
                            </div>
                            @endif
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">@lang('admin.submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection

@section('extra-scripts')

@endsection





