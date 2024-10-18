@extends('layouts.frontLayout')

@section('title')
    {{get_settings('app_name')}}
@endsection

@php
    $home_slider_sections = json_decode(get_settings('home_slider_section'), true);
    $slider_images = isset($home_slider_sections['home_slider_images'])?$home_slider_sections['home_slider_images']:[];
@endphp

@section('css')

    <style>
        /* .accordion-button {
            width: 100% !important;
            text-align: left;
        } */
        .payment-partner {
            height: 100px;
            width: 100%;
        }

        .payment-partner img {
            height: 100%;
        }

        .text-white {
            color: white !important;
        }

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
            box-shadow: 0 0 3px rgba(0, 0, 0, .3);
            font-size: .8rem;
            line-height: 100%;
            padding: 0.375rem 0;
            position: relative;
            right: -2px;
            text-align: center;
            text-shadow: 0 -1px 0 rgba(0, 0, 0, .4);
            text-transform: uppercase;
            top: 10px;
            -webkit-transform: rotate(45deg);
            transform: rotate(45deg);
            width: 90px;
        }

        .bg-danger {
            color: #fff !important;
            background-color: #f0696c !important;
        }

        .card-pricing.marked .body p a {
            color: white !important;
        }

        .card-pricing.marked .body p {
            color: white !important;
        }

        .footer .btn-pricing {
            color: black !important;
        }

        img.feature-icon {
            width: auto;
            height: 100%;
        }

        .service-icon-sec {
            width: auto;
            height: 50px;
        }

        img.sec_three_icon {
            width: 50px;
            height: 50px;
        }

        .subscribe-btn {
            font-family: "Montserrat", sans-serif;
            font-weight: 500;
            font-size: 16px;
            letter-spacing: 1px;
            display: inline-block;
            padding: 10px 30px;
            border-radius: 50px;
            transition: 0.5s;
            color: #fff;
            background: #5F3CF8;
            text-decoration: none;
            border: none;
        }


        .carousel {
            width: 100%;
            margin: 0px auto;
        }

        .slick-slide {
            margin: 10px;
        }

        .slick-slide img {
            width: 100%;
            border: 2px solid #fff;
        }

        .wrapper .slick-dots li button:before {
            font-size: 20px;
            color: white;
        }

        .slick-prev:before, .slick-next:before {
            color: #3270ec !important;
        }
    </style>

    <style>
        .menu-content::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('./img/icons8-logo-50.png');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 50%;
            z-index: -1;
            opacity: 0.5;
        }

        @if(isset($slider_images) && $slider_images)

        @keyframes imageSlider {

                @foreach ($slider_images as $key => $sliderImage)
                    0% {
                            background-image: url({{ asset('uploads/' . $sliderImage) }});
                        }
                    @if($key==0)
                        0% {
                            background-image: url({{ asset('uploads/' . $sliderImage) }});
                        }
                    @endif
                    @if($key==1)
                        35% {
                            background-image: url({{ asset('uploads/' . $sliderImage) }});
                        }
                    @endif
                    @if($key==2)
                        55% {
                            background-image: url({{ asset('uploads/' . $sliderImage) }});
                        }
                    @endif
                    @if($key==3)
                        80% {
                            background-image: url({{ asset('uploads/' . $sliderImage) }});
                        }
                    @endif
                    @if($key==4)
                        100% {
                            background-image: url({{ asset('uploads/' . $sliderImage) }});
                        }
                    @endif
                    100% {
                            background-image: url({{ asset('uploads/' . $sliderImage) }});
                        }
                @endforeach


                }

        .herder-bg {
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            height: 100vh;
            animation: imageSlider 35s infinite;
        }

        @endif


.right {
            background-image: url('./img/right-bg.png');
            background-size: contain;
            height: 700px;
            background-repeat: no-repeat;
            background-position: center center;
            cursor: default;
            transition: .3s ease;
        }

        .contact-data-tab-body {
            background-image: url('./img/tab-img.svg');
            height: 30%;
            background-color: #e5edfd;
            width: 100%;
            transition: 0.3s ease;
            background-repeat: no-repeat;
            background-position: 98% 50%;
            background-size: calc(100% / 3);
        }

        .back {
            width: 100%;
        }

        .main-nav-section {
            padding: 20px 40px;
            background: gray;
            width: 100%;
            -webkit-transition: position 10s;
            -moz-transition: position 10s;
            -ms-transition: position 10s;
            -o-transition: position 10s;
            transition: position 10s;
        }

      

        .left-side-tg-button {
            background-color: #ffffff00 !important;
            font-size: 26px !important;
        }
    </style>

@endsection


@php
    $template = json_decode(get_settings('template'));
    $home_contact_us_section = json_decode(get_settings('home_contact_us'), true);
    $home_slider_sections = json_decode(get_settings('home_slider_section'), true);
@endphp
@section('main-section')

    <section class="top-header">
        <div class="herder-bg">
            <div class="header-logo">
                <a href="#">
                    <img src="{{asset('uploads/'.get_settings('app_logo'))}}" alt="">
                </a>
            </div>
            <div class="container">
                <div class="header-main-content">
                    <h1 class="main-content-title mt-4 mb-4">
                        {{isset($home_slider_sections['slider_title'])?$home_slider_sections['slider_title']:''}}
                    </h1>
                    <div class="main-content-sub-title">{{isset($home_slider_sections['slider_sub_title'])?$home_slider_sections['slider_sub_title']:''}}</div>
                    <div class="slider-short-description mb-4">{{isset($home_slider_sections['slider_short_description'])?$home_slider_sections['slider_short_description']:''}}</div>
                    <div class="btn-sign-in">
                        <a href="#services" class="sign-in custom-btn btn-sign-up arrow-icon-btn">Get Started</a> <span>      </span>
                        <a href="#sign_up" class="sign-up custom-btn btn-sign-up ml-5 arrow-icon-btn">Sign Up</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="main-header" id="about">
        <div class="main-header-content">
            @php
                $welcome_section=json_decode(get_settings('welcome_section'));
            @endphp
            <div class="row align-items-center">
                <div class="col-lg-5">
                    <div class="left">
                        <h1>{{isset($welcome_section->title)?$welcome_section->title:''}}</h1>
                        <br>
                        <p>
                            {!! isset($welcome_section->description)?$welcome_section->description:'' !!}
                        </p>
                    </div>
                </div>
                <div class="col-lg-7">
                    @if (isset($welcome_section->imageone))
                        <div class="right" style="background-image: url('{{asset('uploads/'.$welcome_section->imageone)}}')">
                        </div>
                    @endif
                </div>
            </div>
            <div class="row main-header-b-content text-center">
                <div class="col-lg-4 col-md-4">
                    <div class="one-box">
                        <p>{{isset($welcome_section->section_one_founded)?$welcome_section->section_one_founded:''}}</p>
                        <h1>{{isset($welcome_section->section_one_count)?$welcome_section->section_one_count:''}}</h1>
                        <p>{{isset($welcome_section->section_one_experience)?$welcome_section->section_one_experience:''}}</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="one-box">
                        <p>{{isset($welcome_section->section_two_founded)?$welcome_section->section_two_founded:''}}</p>
                        <h1>{{isset($welcome_section->section_two_count)?$welcome_section->section_two_count:''}}</h1>
                        <p>{{isset($welcome_section->section_two_experience)?$welcome_section->section_two_experience:''}}</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="one-box">
                        <p>{{isset($welcome_section->section_three_founded)?$welcome_section->section_three_founded:''}}</p>
                        <h1>{{isset($welcome_section->section_three_count)?$welcome_section->section_three_count:''}}</h1>
                        <p>{{isset($welcome_section->section_three_experience)?$welcome_section->section_three_experience:''}}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section id="services" class="main-services">
        <div class="services-content">
            @php
                $services =json_decode(get_settings('services'));
            @endphp
            <div class="services-content-header text-center">
                <h1>{{isset($services)?$services->title:''}}</h1>
                <div class="under-line"></div>
                <a href="#">
                    {{isset($services)?$services->sub_title:''}}
                    <img src="./img/rocket.svg" alt="" class="rocket">
                </a>
            </div>
            <div class="flip-services-details">
                @if (isset($services) && isset($services->service_data))

                    @foreach ($services->service_data as $key => $service)
                        <div class="flip-services-details-one">
                            <div class="flipper-card">
                                <div class="front">
                                    <div class="flip-front-content" style="background-image: url('{{asset('uploads/'.$service->image)}}');">
                                        <div class="flip-front-content-heading">
                                            <h2>{{isset($service->name)?$service->name:''}}
                                                <br>
                                                <br>
                                                +
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="back">
                                    <div class="flip-front-content flip-back-content">
                                        <div class="flip-back-content-heading">
                                            <h2 class="text-center mb-4"> {{isset($service->name)?$service->name:''}}</h2>
                                            <div>
                                                {!! isset($service->description)?$service->description:'' !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>


    <section id="instruments" class="main-instruments">
        <div class="container">
            <div class="instruments-content">
                <div class="mb-4">
                    <h1 class="text-center">Instruments</h1>
                    <div class="under-line"></div>
                </div>
                <div class="instruments-content-info">

                    @foreach($instruments as $a_key=>$instrument)
                        <div class="row align-items-center instruments-content-sm-info">
                            <div class="col-lg-4 col-md-4">
                                <div class="instruments-content-info-img">
                                    <img src="{{asset('uploads/'.$instrument->image)}}" alt="">
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 mt-4 mb-3">
                                <h2 class="content-info-text">{{$instrument->title}}</h2>
                                {!! $instrument->description !!}
                                <div class="show-more">
                                    <div class="collapse" id="collapseExamplee{{$a_key}}">
                                        <div class="more-details">

                                            @if($instrument->instrumentDetails)
                                                @foreach($instrument->instrumentDetails as $key=>$instrumentDetail)
                                                    <div class="more-details-link">
                                                        <a data-toggle="collapse" href="#LSM7{{$key}}" role="button"
                                                           aria-expanded="false" aria-controls="collapseExample">
                                                            ▸ {{$instrumentDetail->title}}
                                                        </a>
                                                    </div>
                                                    <div class="collapse" id="LSM7{{$key}}">
                                                        @php
                                                            $keyTitles=json_decode($instrumentDetail->key, true);
                                                            $keyValues=json_decode($instrumentDetail->value, true);
                                                        @endphp
                                                        <div class="link-table">
                                                            <table class="table">
                                                                <tbody>
                                                                @if($keyTitles)
                                                                    @foreach($keyTitles as $key=>$keyTitle)
                                                                        <tr>
                                                                            <td class="link-table-td">{{$keyTitle}}</td>
                                                                            <td>
                                                                                {{isset($keyValues[$key])?$keyValues[$key]:''}}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endif

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif

                                        </div>
                                    </div>
                                    <button class="btn show-more-btn show-more-btn-instrument show" type="button" data-toggle="collapse"
                                            data-target="#collapseExamplee{{$a_key}}" aria-expanded="false"
                                            aria-controls="collapseExample">
                                        Show More +
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </section>
    <section id="feeSection" class="main-feeSection">
        <div class="services-content-header text-center">
            @php
                $fees =json_decode(get_settings('fees'));
            @endphp
            <h1>{{isset($fees->title)?$fees->title:''}}</h1>
            <div class="under-line"></div>
        </div>
        <div class="container">
            <div class="mt-5 table-responsive-sm">
                <table class="main-feeSection-table">
                    <thead>
                    <tr>
                        <th>Service</th>
                        <th>Type of Instrument</th>
                        <th>UHN rate</th>
                        <th>External Academic rate *</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($fees) && isset($fees->fees_data))
                        @foreach ($fees->fees_data as $fee)

                            <tr>
                                <td>{{isset($fee->service)?$fee->service:''}}</td>
                                <td>{{isset($fee->type_of_instrument)?$fee->type_of_instrument:''}}</td>
                                <td>{{isset($fee->uhn_rate)?$fee->uhn_rate:''}}</td>
                                <td>{{isset($fee->ea_rate)?$fee->ea_rate:''}}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
            <div class="main-feeSection-table-des">
                <p>
                    * {{isset($fees->sub_title)?$fees->sub_title:''}}
                </p>
            </div>
        </div>
    </section>
    <section id="courses" class="main-instruments">
        <div class="container">
            <div class="instruments-content">
                <div class="mb-4">
                    <h1 class="text-center">Courses</h1>
                    <div class="under-line"></div>
                </div>
                <div class="instruments-content-info">
                    @php
                        $courses =json_decode(get_settings('courses'));
                    @endphp
                    <div class="row align-items-center  instruments-content-sm-info">
                        <div class="col-lg-4 col-md-4">
                            <div class="instruments-content-info-img">
                                @if(isset($courses->imageone) && isset($courses->imageone))
                                    <img src="{{asset('uploads/'.$courses->imageone)}}" alt="">
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-8 mt-4 mb-3">
                            <h2 class="content-info-text">{{isset($courses->title)?$courses->title:''}}</h2>
                            <p>
                                {{isset($courses->description)?$courses->description:''}}
                            </p>
                            <a href="#sign_up">
                                <strong>Sign Up</strong>
                                <img src="{{asset('img/rocket.svg')}}" alt="" class="rocket">
                            </a>
                        </div>
                    </div>

                    <div class="row align-items-center instruments-content-sm-info">
                        <div class="col-lg-4 col-md-4">
                            <div class="instruments-content-info-img">
                                @if(isset($courses->imagetwo) && $courses->imagetwo)
                                    <img src="{{asset('uploads/'.$courses->imagetwo)}}" alt="">
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-8 mt-4 mb-3">
                            <h2 class="content-info-text">{{isset($courses->title_two)?$courses->title_two:''}}</h2>
                            <p>
                                {{isset($courses->description_two)?$courses->description_two:''}}
                            </p>
                            <a href="#sign_up">
                                <strong>Sign Up</strong>
                                <img src="{{asset('img/rocket.svg')}}" alt="" class="rocket">
                            </a>
                        </div>
                    </div>

                    <div class="row align-items-center instruments-content-sm-info">
                        <div class="col-lg-4 col-md-4">
                            <div class="instruments-content-info-img">
                                @if(isset($courses->imagethree) && $courses->imagethree)
                                    <img src="{{asset('uploads/'.$courses->imagethree)}}" alt="">
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-8 mt-4 mb-3">
                            @if(isset($courses->title_three) && $courses->title_three)
                            <h2 class="content-info-text">
                                {{$courses->title_three}}
                            </h2>
                            @endif
                            @if(isset($courses->description_three) && $courses->description_three)
                            @php
                                $description = $courses->description_three;
                                $show_more_needed = strlen(strip_tags($description)) > 200;
                                $initial_description = substr(strip_tags($description), 0, 200);
                                @endphp
                                
                                <div class="description-container">
                                    @if($description)
                                    <div class="description-text">
                                        <span class="short-description">{{ $initial_description }}@if($show_more_needed)...@endif</span>
                                        <span class="full-description" style="display: none;">{!! $description !!}</span>
                                    </div>
                                    @endif
                                    @if ($show_more_needed)
                                    <button class="show-more-btn course-show-more" onclick="toggleDescription()">Show More +</button>
                                    <button class="show-more-btn course-show-less" style="display: none;" onclick="toggleDescription()">Show Less -</button>
                                    @endif
                                </div>
                                @endif
                        </div>
                    </div>

                    <div class="row align-items-center instruments-content-sm-info">
                        <div class="col-lg-4 col-md-4">
                            <div class="instruments-content-info-img">
                                @if(isset($courses->imagefour) && $courses->imagefour)
                                    <img src="{{asset('uploads/'.$courses->imagefour)}}" alt="">
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-8 col-md-8 mt-4 mb-3">
                            <h2 class="content-info-text">
                                {{isset($courses->title_four)?$courses->title_four:''}}
                            </h2>
                       @if(isset($courses->description_four) && $courses->description_four)
                                @php
                                $description = $courses->description_four;
                                $show_more_needed = strlen(strip_tags($description)) > 200;
                                $initial_description = substr(strip_tags($description), 0, 200);
                                @endphp
                                
                                <div class="description-container">
                                    @if($description)
                                    <div class="description-text">
                                        <span class="short-description-four">{{ $initial_description }}@if($show_more_needed)...@endif</span>
                                        <span class="full-description-four" style="display: none;">{!! $description !!}</span>
                                    </div>
                                    @endif
                                    @if ($show_more_needed)
                                    <button class="show-more-btn course-show-more-four" onclick="toggleDescriptionFour()">Show More +</button>
                                    <button class="show-more-btn course-show-less-four" style="display: none;" onclick="toggleDescriptionFour()">Show Less
                                        -</button>
                                    @endif
                                </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="omug" class="main-feeSection">
        <div class="container">
            @php
                $omug =json_decode(get_settings('omug'));
            @endphp
            <div class="mb-4">
                <h1 class="text-center">{{isset($omug)?$omug->title:''}}</h1>
                <div class="under-line"></div>
                <p class="omug-des">
                    {{isset($omug)?$omug->short_description:''}}
                </p>
            </div>
            <div class="row">
                <div class="col-lg-5 col-12">
                  <div class="omgu-image-sec">
                    @if (isset($omug->image) && $omug->image)
                    <img src="{{asset('uploads/'.$omug->image)}}" class="omug-image" alt="">
                    @endif

                  </div>
                </div>
                <div class="col-lg-7 col-12">
                    {!! isset($omug)?$omug->description:'' !!}
                 @if (isset($omug->omug_youtube_link) && $omug->omug_youtube_link)
                 <div class="youtube-video-link-sec">
                        <a href="{{$omug->omug_youtube_link}}" target="_blank" class="omug-play-icon">
                            <span>
                                <i class="fa fa-play-circle omug-play-icon" aria-hidden="true"></i>
                            </span>
                           <span> Click here to watch the recording.</span>
                         </a>
                    </div>
                 @endif
                </div>
            </div>
        </div>
    </section>

    <section id="resources" class="main-instruments">
        <div class="container">
            @php
                $resources =json_decode(get_settings('resources'));
            @endphp
            @if (isset($resources) && $resources)
                <div class="instruments-content">
                    <div class="mb-4">
                        <h1 class="text-center">{{isset($resources->title)?$resources->title:''}}</h1>
                        <div class="under-line"></div>
                    </div>
                </div>
            <div class="row">
                <div class="col-md-10 m-auto">
                    <div class="resources-data">
                    <div class="resources-first-data">
                        @if(isset($resources->description))
                            {!! $resources->description !!}
                        @endif
                    </div>
                </div>
                </div>
            </div>
            @endif
        </div>
    </section>

    <section id="team" class="main-feeSection">
        <div class=" team-container">

            <div class="mb-4">
                <h1 class="text-center">Team</h1>
                <div class="under-line"></div>
            </div>
            <div class="team-members">
                @if(isset($teams->team_data) && $teams->team_data)
                    <div class="wrapper">
                        <div class="carousel">
                            @foreach($teams->team_data as $team)
                                <div>
                                    <div class="member">
                                        <div class="member-img">
                                            @if(isset($team->image) && $team->image)
                                                <img src="{{asset('uploads/'.$team->image)}}" alt="">
                                            @endif
                                        </div>
                                        <div class="member-info">
                                            <h2>{{isset($team->name)?$team->name:''}}</h2>
                                            <p>{{isset($team->work_title)?$team->work_title:''}}</p>
                                            <a href="mailto:{{isset($team->email)?$team->email:'#'}}">{{isset($team->email)?$team->email:''}}</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>


    <section id="contact_us" class="main-instruments">
        <div class="container">
            <div class="instruments-content">
                <div class="mb-4">
                    <h1 class="text-center">Contact Us</h1>
                    <div class="under-line"></div>
                    <p class="sub-title d-none">
                        If you are based at one of our 4 sites, feel free to email a staff
                        member at that site directly. Otherwise please contact
                        <a href="#">aomf@uhnresearch.ca</a>
                        and we'll direct your inquiry appropriately.
                    </p>
                </div>
            </div>
        </div>

        <div class="contact-data">
            <div class="tab-content " id="pills-tabContent">
                <div class="tab-pane contact-data-tab-body fade show active" id="pills-pmcrt" role="tabpanel"
                     aria-labelledby="pills-pmcrt-tab">
                    <div class="contact-data-tab-body-contaent">
                        <div class="tab-body-contaent-information">
                            <div class="tab-body-contaent-information-title">
                                <strong>
                                    {{isset($home_contact_us_section['contact_us_title'])?$home_contact_us_section['contact_us_title']:''}}
                                </strong>
                            </div>
                            <div class="row">
                                <div class="col-lg-5 col-md-5 col-sm-5 tab-body-contaent-information-map">
                                    @php
                                        $iframe_link = isset($home_contact_us_section['contact_us_google_map'])?$home_contact_us_section['contact_us_google_map']:'';
                                    @endphp
                                    <iframe src="{{$iframe_link}}" width="600" height="450" frameborder="0"
                                            style="border:0" allowfullscreen></iframe>
                                </div>
                                <div class="col-lg-7 col-md-7 col-sm-7 tab-body-contaent-information-data">
                                    <p>
                                    {{isset($home_contact_us_section['contact_us_short_description'])?$home_contact_us_section['contact_us_short_description']:''}}
                                    <p><strong>Address:</strong> <br>
                                        {!! isset($home_contact_us_section['contact_us_address'])?$home_contact_us_section['contact_us_address']:'' !!}

                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="sign_up" class="main-feeSection">
        <div class="container">
            <div class="instruments-content">
                @php
                    $sign_up_info =json_decode(get_settings('sign_up_info'));
                @endphp
                <div class="mb-4">
                    <h1 class="text-center">{{isset($sign_up_info->title)?$sign_up_info->title:''}}</h1>
                    <div class="under-line"></div>
                    <p class="sub-title">
                        {{isset($sign_up_info->short_description)?$sign_up_info->short_description:''}}
                    </p>
                </div>
            </div>
            <div class="sign_up-data">
                <div class="row">
                    <div class="clo-lg-12">
                        {!! isset($sign_up_info->description)?$sign_up_info->description:'' !!}
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section id="questions" class="main-instruments">
        <div class="container">
            <div class="instruments-content">
                <div class="mb-4">
                    <h1 class="text-center">Questions?</h1>
                    <div class="under-line"></div>
                </div>
            </div>
            <div class="questions-data">
                <div id="accordion">
                    @php $cnt_key=0; @endphp
                    @foreach($faqs as $key=>$faq)
                        <div class="card">
                            <div class="card-header card-header2" id="headingOne{{$key}}">
                                <h5 class="mb-0">
                                    <button class="" data-toggle="collapse" data-target="#collapseOne{{$key}}"
                                            aria-expanded="true"
                                            aria-controls="collapseOne">
                                        <p class="collapsed-title">
                                            <span>{{++$cnt_key}}</span>
                                            {{$faq->question}}
                                        </p>
                                    </button>
                                </h5>
                            </div>

                            <div id="collapseOne{{$key}}" class="collapse" aria-labelledby="headingOne{{$key}}"
                                 data-parent="#accordion">
                                <div class="card-body collapsed-body">
                                    <p class="faq-answer-section">
                                        {{$faq->answer}}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.carousel').slick({
                infinite: true,
                slidesToShow: 2,
                slidesToScroll: 1,
                arrows: true,
                dots: false
            });
        });
    </script>
    @if(get_settings('crisp_token'))
        <script type="text/javascript">window.$crisp = [];
            window.CRISP_WEBSITE_ID = "{{get_settings('crisp_token')}}";
            (function () {
                d = document;
                s = d.createElement("script");
                s.src = "https://client.crisp.chat/l.js";
                s.async = 1;
                d.getElementsByTagName("head")[0].appendChild(s);
            })();</script>
    @endif

    <script>

function toggleDescription() {
        $('.short-description, .full-description, .course-show-more, .course-show-less').toggle();
    }

    $(document).ready(function() {
        if ($('.full-description').text().length <= 200) {
            $('.course-show-more').hide();
        }
    });

    function toggleDescriptionFour() {
        $('.short-description-four, .full-description-four, .course-show-more-four, .course-show-less-four').toggle();
    }

    $(document).ready(function() {
        if ($('.full-description-four').text().length <= 200) {
            $('.course-show-more-four').hide();
        }
    });
</script>

@endsection
