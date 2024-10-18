@extends('layouts.frontLayout')

@section('title') {{get_settings('app_name')}} - {{trans('admin.pricing')}} @endsection

@section('css')
    <style>
        .ribbon-wrapper .ribbon {
            font-size: 10px !important;
            padding: 8px 4px 8px 4px !important;
        }
        .ribbon-wrapper {
            height: 70px;
            overflow: hidden;
            position: absolute;
            width: 70px;
            z-index: 10;
            margin-top: 6px !important;
            top: 10px;
            right: 35px;
        }
        .ribbon-wrapper .ribbon {
            box-shadow: 0 0 3px rgba(0,0,0,.3);
            font-size: .8rem;
            line-height: 100%;
            padding: 0.375rem 0;
            position: relative;
            right: -2px;
            text-align: center;
            text-shadow: 0 -1px 0 rgba(0,0,0,.4);
            text-transform: uppercase;
            top: 10px;
            -webkit-transform: rotate(45deg);
            transform: rotate(45deg);
            width: 90px;
        }
        .bg-danger{
            color: #fff!important;
            background-color: #f0696c !important;
        }
        .card-pricing.marked .body p a{
            color:white !important;
        }
        .card-pricing.marked .body p{
            color:white !important;
        }
        .footer .btn-pricing{
            color: black !important;
        }
    </style>
    @endsection

@section('header')

    <div class="container">
        <div class="page-banner">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-md-6">
                    <nav aria-label="Breadcrumb">
                        <ul class="breadcrumb justify-content-center py-0 bg-transparent">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{trans('admin.home')}}</a></li>
                            <li class="breadcrumb-item active">{{trans('admin.pricing')}}</li>
                        </ul>
                    </nav>
                    <h1 class="text-center">{{trans('admin.pricing')}}</h1>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('main-section')
    @php $template = json_decode(get_settings('template')); @endphp
    <div class="container">
        <div class="row">
          @if($plans->isNotempty())
                @foreach ($plans as $key => $plan )
                <div class="col-lg-4 mt-5 wow fadeInUp">
                  <div class="price-card">
                    @if ($plan->set_as_popular=='yes')
                    <div class="price-card-header">
                      {{$plan->title}}
                      <span class="advanced-text">Advanced</span>
                     </div>
                    @else
                    <div class="price-card-header">
                      {{$plan->title}}
                     </div>
                     @endif
                    <div class="price-card-body">
                      <span class="price-title-sec">
                        <sub class="price-symbol">$</sub>
                        <span class="price-value"> {{formatNumberWithCurrSymbol($plan->price)}}</span> <small class="per-sms-cost">${{$plan->sms_unit_price}} per SMS</small>
                      </span>
                      <div class="items-section">
                        <div class="single-feature">
                          {{ucfirst(str_replace('_','-',$plan->recurring_type))}}
                        </div>
                        <div class="single-feature">
                          {{$plan->short_description}}
                        </div>
                        <div class="single-feature">
                          <strong>{{trans('Recurring Type')}}</strong>:  {{ucfirst($plan->recurring_type)}}
                        </div>
                        <div class="single-feature">
                          @if($plan->unlimited_contact=='yes')
                          <p class="text-muted"><strong>{{trans('Contact Limit')}}</strong>: {{trans('Unlimited')}}</p>
                      @else
                          <p class="text-muted"><strong>{{trans('Contact Limit')}}</strong>:  {{$plan->max_contact}}</p>
                      @endif
                      <p class="text-muted"><strong>{{trans('Free SMS Unit')}}</strong>: {{$plan->free_sms_credit}}</p>
                      <p class="text-muted"><strong>{{trans('SMS Unit Price')}}</strong>: {{$plan->sms_unit_price}}</p>
                        </div>
                        <div class="single-feature">
                          @if($plan->api_availability=='yes')
                          <p class="text-muted"><strong>{{trans('API Available')}}</strong>: Yes</p>
                          @else
                          <p class="text-muted"><strong>{{trans('API Available')}}</strong>: No</p>
                      @endif
                      <p class="text-muted"><strong>{{trans('SMS Send Limit')}}</strong>: {{$plan->sms_sending_limit}}(<small>Per Month</small>)</p>
    
                      <p>{{trans('customer.unlimited_support')}}</p>
                      <p>{{trans('customer.cancel_anytime')}}</p>
                      <p class="text-muted">
                          <a href="{{route('coverage',[$plan->id])}}" target="_blank">See Coverage</a>
                      </p>
                        </div>
                      </div>
                    </div>
                    <div class="price-card-footer">
                      @if(auth('customer')->check())
                      @if(Module::has('PaymentGateway') && Module::find('PaymentGateway')->isEnabled())
                          <form action="{{route('paymentgateway::process')}}" method="post">
                              @csrf
                              <input type="hidden" name="id" value="{{$plan->id}}">
                              <button type="submit" class="btn btn-pricing btn-block text-white main-banner-btn">{{trans('Subscribe')}}
                              </button>
                          </form>
                      @endif
                  @elseif(get_settings('registration_status')=='enable')
                      <a href="{{route('signup',['plan'=>$plan->id])}}"
                         class="btn btn-pricing btn-block text-white main-banner-btn">{{trans('Subscribe')}}</a>
                  @endif
                  <a href="#" class="main-banner-btn">Subscribe</a>
                    </div>
                  </div>
              </div>
                @endforeach
          @endif
        
        </div>
      </div>
        <!-- .page-section -->
@endsection
