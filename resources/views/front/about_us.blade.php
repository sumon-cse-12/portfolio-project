@extends('layouts.frontLayout')

@section('title') {{get_settings('app_name')}} - {{trans('admin.about_us')}} @endsection


@section('header')

    <div class="container">
        <div class="page-banner">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-md-6">
                    <nav aria-label="Breadcrumb">
                        <ul class="breadcrumb justify-content-center py-0 bg-transparent">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">{{trans('admin.home')}}</a></li>
                            <li class="breadcrumb-item active">{{trans('admin.about_us')}}</li>
                        </ul>
                    </nav>
                    <h1 class="text-center">{{trans('admin.about_us')}}</h1>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('main-section')
    @php $template = json_decode(get_settings('template')); @endphp
    <div class="page-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 py-3">
                    <h2 class="title-section">
                        {{get_settings('app_name')}} -  @if(isset($template) && isset($template->about_us_title))    {!!clean($template->about_us_title)!!}   @endif
                    </h2>
                    <div class="divider"></div>

                    <p>
                        @if(isset($template) && isset($template->about_us_description))
                            {!!clean($template->about_us_description)!!}
                        @endif
                    </p>
                </div>
                <div class="col-lg-6 py-3 d-none">
                    <div class="img-fluid py-3 text-center">
                        <img src="../assets/img/about_frame.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section d-none">
        <div class="container">
            <div class="text-center">
                <div class="subhead">Pricing Plan</div>
                <h2 class="title-section">Choose plan the right for you</h2>
                <div class="divider mx-auto"></div>
            </div>
            <div class="row mt-5">
                <div class="col-lg-4 py-3">
                    <div class="card-pricing">
                        <div class="header">
                            <div class="pricing-type">Basic</div>
                            <div class="price">
                                <span class="dollar">$</span>
                                <h1>39<span class="suffix">.99</span></h1>
                            </div>
                            <h5>Per Month</h5>
                        </div>
                        <div class="body">
                            <p>25 Analytics <span class="suffix">Campaign</span></p>
                            <p>1,300 Change <span class="suffix">Keywords</span></p>
                            <p>Social Media <span class="suffix">Reviews</span></p>
                            <p>1 Free <span class="suffix">Optimization</span></p>
                            <p>24/7 <span class="suffix">Support</span></p>
                        </div>
                        <div class="footer">
                            <a href="#" class="btn btn-pricing btn-block">Subscribe</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 py-3">
                    <div class="card-pricing marked">
                        <div class="header">
                            <div class="pricing-type">Standar</div>
                            <div class="price">
                                <span class="dollar">$</span>
                                <h1>59<span class="suffix">.99</span></h1>
                            </div>
                            <h5>Per Month</h5>
                        </div>
                        <div class="body">
                            <p>25 Analytics <span class="suffix">Campaign</span></p>
                            <p>1,300 Change <span class="suffix">Keywords</span></p>
                            <p>Social Media <span class="suffix">Reviews</span></p>
                            <p>1 Free <span class="suffix">Optimization</span></p>
                            <p>24/7 <span class="suffix">Support</span></p>
                        </div>
                        <div class="footer">
                            <a href="#" class="btn btn-pricing btn-block">Subscribe</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 py-3">
                    <div class="card-pricing">
                        <div class="header">
                            <div class="pricing-type">Professional</div>
                            <div class="price">
                                <span class="dollar">$</span>
                                <h1>99<span class="suffix">.99</span></h1>
                            </div>
                            <h5>Per Month</h5>
                        </div>
                        <div class="body">
                            <p>25 Analytics <span class="suffix">Campaign</span></p>
                            <p>1,300 Change <span class="suffix">Keywords</span></p>
                            <p>Social Media <span class="suffix">Reviews</span></p>
                            <p>1 Free <span class="suffix">Optimization</span></p>
                            <p>24/7 <span class="suffix">Support</span></p>
                        </div>
                        <div class="footer">
                            <a href="#" class="btn btn-pricing btn-block">Subscribe</a>
                        </div>
                    </div>
                </div>

            </div>
        </div> <!-- .container -->
    </div> <!-- .page-section -->

@endsection
