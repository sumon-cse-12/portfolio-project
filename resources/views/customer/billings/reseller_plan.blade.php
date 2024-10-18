@extends('layouts.customer')

@section('title')
    Reseller PLan
@endsection

@section('extra-css')
    <style>
        .nav-item .active{
            background: #047afb !important;
            color: white !important;
            border-radius: 5px;
        }
    </style>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">

    </section>
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- /.col-md-6 -->
                <div class="col-lg-10 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="m-0">{{trans('customer.billing')}}</h5>
                        </div>
                        <div class="card-body">
                            <div class="card-tabs">
                                <div class="card-header p-0 pt-1">
                                    <ul class="nav nav-tabs justify-content-center" id="custom-tabs-one-tab"
                                        role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill"
                                               href="#custom-tabs-one-home" role="tab"
                                               aria-controls="custom-tabs-one-home" aria-selected="true">For Reseller</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill"
                                               href="#custom-tabs-one-profile" role="tab"
                                               aria-controls="custom-tabs-one-profile" aria-selected="false">For Master Reseller</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-one-tabContent">
                                        <div class="tab-pane fade active show" id="custom-tabs-one-home" role="tabpanel"
                                             aria-labelledby="custom-tabs-one-home-tab">
                                            <div id="plans" class="plans-wrapper mt-3">
                                                @foreach($plans as $plan)
                                                    @if($plan->plan_type=='reseller')
                                                    <div
                                                        class="columns {{$customer_plan->plan_id==$plan->id?'plan-active':''}}">
                                                        <ul class="price">
                                                            <li class="grey">{{$plan->title}} <span
                                                                    class="plan-title-current">{{$customer_plan->plan_id==$plan->id?'(Current)':''}}</span>
                                                            </li>
                                                            <li class="price-tag">$ {{$plan->price}}</li>
                                                            <li>{{$plan->sms_limit}} {{trans('customer.sms')}}</li>
                                                            <li>{{trans('customer.unlimited_support')}}</li>
                                                            <li>{{trans('customer.cancel_anytime')}}</li>
                                                            <li>
                                                                @if($customer_plan->plan_id!=$plan->id)
                                                                    @if(Module::has('PaymentGateway') && Module::find('PaymentGateway')->isEnabled())
                                                                        <button
                                                                            data-message="{!! trans('customer.messages.update_plan',['plan'=>$plan->title]) !!} <br/> <span class='text-sm text-muted'>{{trans('customer.messages.update_plan_nb')}}</span>"
                                                                            data-action="{{route('paymentgateway::process')}}"
                                                                            data-input='{"id":"{{$plan->id}}"}'
                                                                            data-toggle="modal"
                                                                            data-target="#modal-confirm"
                                                                            type="button"
                                                                            class="btn btn-primary btn-sm">{{trans('customer.choose')}}
                                                                        </button>
                                                                    @else
                                                                        <button
                                                                            data-message="{!! trans('customer.messages.update_plan',['plan'=>$plan->title]) !!} <br/> <span class='text-sm text-muted'>{{trans('customer.messages.update_plan_nb')}}</span>"
                                                                            data-action="{{route('customer.billing.update')}}"
                                                                            data-input='{"id":"{{$plan->id}}"}'
                                                                            data-toggle="modal"
                                                                            data-target="#modal-confirm"
                                                                            type="button"
                                                                            class="btn btn-primary btn-sm">{{trans('customer.choose')}}
                                                                        </button>
                                                                    @endif
                                                                @endif
                                                            </li>

                                                        </ul>
                                                    </div>
                                                    @endif
                                                @endforeach
                                            </div>

                                        </div>
                                        <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel"
                                             aria-labelledby="custom-tabs-one-profile-tab">
                                            <div id="plans" class="plans-wrapper mt-3">
                                                @foreach($plans as $plan)
                                                    @if($plan->plan_type=='master_reseller')
                                                        <div
                                                            class="columns {{$customer_plan->plan_id==$plan->id?'plan-active':''}}">
                                                            <ul class="price">
                                                                <li class="grey">{{$plan->title}} <span
                                                                        class="plan-title-current">{{$customer_plan->plan_id==$plan->id?'(Current)':''}}</span>
                                                                </li>
                                                                <li class="price-tag">$ {{$plan->price}}</li>
                                                                <li>{{$plan->sms_limit}} {{trans('customer.sms')}}</li>
                                                                <li>{{trans('customer.unlimited_support')}}</li>
                                                                <li>{{trans('customer.cancel_anytime')}}</li>
                                                                <li>
                                                                    @if($customer_plan->plan_id!=$plan->id)
                                                                        @if(Module::has('PaymentGateway') && Module::find('PaymentGateway')->isEnabled())
                                                                            <button
                                                                                data-message="{!! trans('customer.messages.update_plan',['plan'=>$plan->title]) !!} <br/> <span class='text-sm text-muted'>{{trans('customer.messages.update_plan_nb')}}</span>"
                                                                                data-action="{{route('paymentgateway::process')}}"
                                                                                data-input='{"id":"{{$plan->id}}"}'
                                                                                data-toggle="modal"
                                                                                data-target="#modal-confirm"
                                                                                type="button"
                                                                                class="btn btn-primary btn-sm">{{trans('customer.choose')}}
                                                                            </button>
                                                                        @else
                                                                            <button
                                                                                data-message="{!! trans('customer.messages.update_plan',['plan'=>$plan->title]) !!} <br/> <span class='text-sm text-muted'>{{trans('customer.messages.update_plan_nb')}}</span>"
                                                                                data-action="{{route('customer.billing.update')}}"
                                                                                data-input='{"id":"{{$plan->id}}"}'
                                                                                data-toggle="modal"
                                                                                data-target="#modal-confirm"
                                                                                type="button"
                                                                                class="btn btn-primary btn-sm">{{trans('customer.choose')}}
                                                                            </button>
                                                                        @endif
                                                                    @endif
                                                                </li>

                                                            </ul>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        {{--                        <div class="card-body">--}}
                        {{--                            <div id="plans" class="plans-wrapper mt-3">--}}
                        {{--                                @foreach($plans as $plan)--}}
                        {{--                                    <div--}}
                        {{--                                        class="columns {{$customer_plan->plan_id==$plan->id?'plan-active':''}}">--}}
                        {{--                                        <ul class="price">--}}
                        {{--                                            <li class="grey">{{$plan->title}} <span--}}
                        {{--                                                    class="plan-title-current">{{$customer_plan->plan_id==$plan->id?'(Current)':''}}</span>--}}
                        {{--                                            </li>--}}
                        {{--                                            <li class="price-tag">$ {{$plan->price}}</li>--}}
                        {{--                                            <li>{{$plan->sms_limit}} {{trans('customer.sms')}}</li>--}}
                        {{--                                            <li>{{trans('customer.unlimited_support')}}</li>--}}
                        {{--                                            <li>{{trans('customer.cancel_anytime')}}</li>--}}
                        {{--                                            <li>--}}
                        {{--                                                @if($customer_plan->plan_id!=$plan->id)--}}
                        {{--                                                    @if(Module::has('PaymentGateway') && Module::find('PaymentGateway')->isEnabled())--}}
                        {{--                                                        <button--}}
                        {{--                                                            data-message="{!! trans('customer.messages.update_plan',['plan'=>$plan->title]) !!} <br/> <span class='text-sm text-muted'>{{trans('customer.messages.update_plan_nb')}}</span>"--}}
                        {{--                                                            data-action="{{route('paymentgateway::process')}}"--}}
                        {{--                                                            data-input='{"id":"{{$plan->id}}"}'--}}
                        {{--                                                            data-toggle="modal" data-target="#modal-confirm"--}}
                        {{--                                                            type="button"--}}
                        {{--                                                            class="btn btn-primary btn-sm">{{trans('customer.choose')}}--}}
                        {{--                                                        </button>--}}
                        {{--                                                    @else--}}
                        {{--                                                        <button--}}
                        {{--                                                            data-message="{!! trans('customer.messages.update_plan',['plan'=>$plan->title]) !!} <br/> <span class='text-sm text-muted'>{{trans('customer.messages.update_plan_nb')}}</span>"--}}
                        {{--                                                            data-action="{{route('customer.billing.update')}}"--}}
                        {{--                                                            data-input='{"id":"{{$plan->id}}"}'--}}
                        {{--                                                            data-toggle="modal" data-target="#modal-confirm"--}}
                        {{--                                                            type="button"--}}
                        {{--                                                            class="btn btn-primary btn-sm">{{trans('customer.choose')}}--}}
                        {{--                                                        </button>--}}
                        {{--                                                    @endif--}}
                        {{--                                                @endif--}}
                        {{--                                            </li>--}}

                        {{--                                        </ul>--}}
                        {{--                                    </div>--}}
                        {{--                                @endforeach--}}
                        {{--                            </div>--}}

                        {{--                        </div>--}}
                    </div>
                </div>
                <!-- /.col-md-6 -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
@endsection

