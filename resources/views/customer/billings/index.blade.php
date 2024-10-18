@extends('layouts.customer')

@section('title') Billings @endsection

@section('extra-css')

    <style>
        .b-plan-title{
            font-size: 22px !important;
            color: white !important;
        }
        .price li{
            padding: 12px !important;
            text-align: left;
            font-weight: 500;
        }
        .ribbon-wrapper{
            height: 70px;
            overflow: hidden;
            position: absolute;
            width: 70px;
            z-index: 10;
            left: 24% !important;
            margin-top: 6px !important;
            top: auto !important;
            right: auto !important;
        }
        .ribbon-wrapper .ribbon{
            font-size: 10px !important;
            padding: 8px 4px 8px 4px !important;
        }
        .b-plan-title .current-title{
            font-size: 13px !important;
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
                            <h5 class="m-0">{{trans('customer.billing')}}
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($customer_plan)
                            <table class="w-100">
                                <tr>
                                    <td>
                                        <div class="card-title float-none">{{trans('customer.your_plan')}}</div>
                                        <h2>{{$customer_plan->plan->title}}</h2></td>
                                    <td class="text-right">
                                        <button type="button"
                                                class="btn btn-primary d-none">{{trans('customer.update_plan')}}</button>
                                    </td>
                                </tr>
                            </table>

                            <table class="w-50">
                                <tr>
                                    <td class="plan-des-title">{{trans('customer.cost')}}</td>
                                    <td class="plan-des-value">{{formatNumberWithCurrSymbol($customer_plan->price)}}</td>
                                </tr>
                            </table>
                            @endif
                            <div id="plans" class="plans-wrapper mt-3">
                                @foreach($plans as $plan)
                                    <div class="columns {{$customer_plan && $customer_plan->plan_id==$plan->id?'plan-active':''}}">
                                        @if($plan->set_as_popular=='yes')
                                        <div class="ribbon-wrapper">
                                            <div class="ribbon bg-danger">
                                                Popular
                                            </div>
                                        </div>
                                        @endif
                                        <ul class="price">
                                            <li class="grey">
                                                <h3 class="mb-0 b-plan-title">
                                                    {{$plan->title}}
                                                    <span class="ml-1 current-title">
                                                        {{$customer_plan && $customer_plan->plan_id==$plan->id?'(Current)':''}}
                                                    </span>
                                                </h3>
                                                <h6>
                                                    {{$plan->short_description}}
                                                </h6>
                                            </li>

                                            <li class="price-tag text-muted"><strong>{{trans('Plan Price')}}</strong>:  {{formatNumberWithCurrSymbol($plan->price)}}</li>
                                            <li class="text-muted"><strong>{{trans('Recurring Type')}}</strong>:  {{ucfirst($plan->recurring_type)}}</li>
                                            @if($plan->unlimited_contact=='yes')
                                            <li class="text-muted"><strong>{{trans('Contact Limit')}}</strong>: {{trans('Unlimited')}}</li>
                                            @else
                                                <li class="text-muted"><strong>{{trans('Contact Limit')}}</strong>:  {{$plan->max_contact}}</li>
                                            @endif

                                            @if($plan->unlimited_contact_group=='yes')
                                                <li class="text-muted"><strong>{{trans('Group Limit')}}</strong>: {{trans('Unlimited')}}</li>
                                            @else
                                                <li class="text-muted"><strong>{{trans('Group Limit')}}</strong>:  {{$plan->contact_group_limit}}</li>
                                            @endif
                                            <li class="text-muted"><strong>{{trans('Free SMS Unit')}}</strong>: {{$plan->free_sms_credit}}</li>
                                            <li class="text-muted"><strong>{{trans('SMS Unit Price')}}</strong>: {{$plan->sms_unit_price}}</li>

                                            @if($plan->api_availability=='yes')
                                            <li class="text-muted"><strong>{{trans('API Available')}}</strong>: Yes</li>
                                            @else
                                                <li class="text-muted"><strong>{{trans('API Available')}}</strong>: No</li>
                                            @endif
                                            <li class="text-muted"><strong>{{trans('SMS Send Limit')}}</strong>: {{$plan->sms_sending_limit}}(<small>Per Month</small>)</li>

                                            <li class="text-muted">
                                                <a href="{{route('coverage',[$plan->id])}}" target="_blank">See Coverage</a>
                                            </li>



                                            <li>
                                                @if(!$customer_plan || $customer_plan->plan_id!=$plan->id)
                                                    @if(Module::has('PaymentGateway') && Module::find('PaymentGateway')->isEnabled())
                                                        <button
                                                            data-message="{!! trans('customer.messages.update_plan',['plan'=>$plan->title]) !!} <br/> <span class='text-sm text-muted'>{{trans('customer.messages.update_plan_nb')}}</span>"
                                                            data-action="{{route('paymentgateway::process')}}"
                                                            data-input='{"id":"{{$plan->id}}"}'
                                                            data-toggle="modal" data-target="#modal-confirm"
                                                            type="button"
                                                            class="btn btn-primary btn-sm w-100 d-block">{{trans('customer.choose')}}
                                                        </button>
                                                    @else
                                                        <button
                                                            data-message="{!! trans('customer.messages.update_plan',['plan'=>$plan->title]) !!} <br/> <span class='text-sm text-muted'>{{trans('customer.messages.update_plan_nb')}}</span>"
                                                            data-action="{{route('customer.billing.update')}}"
                                                            data-input='{"id":"{{$plan->id}}"}'
                                                            data-toggle="modal" data-target="#modal-confirm"
                                                            type="button"
                                                            class="btn btn-primary btn-sm w-100 d-block">{{trans('customer.choose')}}
                                                        </button>
                                                    @endif
                                                @endif
                                            </li>

                                        </ul>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>
                <!-- /.col-md-6 -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    @php $contactData = get_settings('contact_info') ? json_decode(get_settings('contact_info')) : ''; @endphp
    <!-- Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Contact With Admin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <h5>
                            <strong>Phone Number: </strong>
                            @if(isset($contactData) && isset($contactData->phone_number))
                                <a class="d-inline-block" href="tel:{!!clean($contactData->phone_number)!!}">
                                    {!!clean($contactData->phone_number)!!}
                                </a>
                            @endif
                        </h5>
                    </div>

                    <div class="form-group">
                        <h5>
                            <strong>Email: </strong>
                            @if(isset($contactData) && isset($contactData->email_address))
                                <a class="d-inline-block" href="mailto:{!!clean($contactData->email_address)!!}">
                                    {!!clean($contactData->email_address)!!}
                                </a>
                            @endif
                        </h5>
                    </div>
                    <div class="form-group">
                        @if(isset($contactData) && isset($contactData->address))
                            {!!clean($contactData->address)!!}

                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
@endsection

