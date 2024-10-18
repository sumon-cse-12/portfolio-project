<!DOCTYPE html>
@php $siteDirection=isset(json_decode(get_settings('local_setting'))->direction)?json_decode(get_settings('local_setting'))->direction:'ltr';
@endphp
<html lang="en" dir="{{$siteDirection}}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">


    <title>@yield('title')</title>
    @if(get_settings('app_favicon'))
        <link rel="icon" href="{{asset('uploads/'.get_settings('app_favicon'))}}" type="image/x-icon">
    @endif
    <link rel="stylesheet" href="{{asset('front/css/maicons.css')}}">

    <link rel="stylesheet" href="{{asset('front/css/animate.css')}}">

    <link rel="stylesheet" href="{{asset('front/css/theme.css')}}">
    <link rel="stylesheet" href="{{asset('css/index.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.css"/>
    @yield('css')


</head>
<style>
    #banner-section-wrapper {
        width: 100%;
        background-image: url("{{asset('front/img/banner.jpg')}}");
        position: relative;
        padding: 120px 0 0 0;
    }

    .accordion-button {
        background-color: #fff !important;
        color: #5F3CF8 !important;
        font-size: 20px !important;
        font-weight: 800 !important;
    }

    .accordion-item {
        margin: 25px 0px !important;
        border: 0px !important;
    }

    .accordion-button:focus {
        z-index: 3;
        border-color: #ffffff !important;
        outline: 0;
        box-shadow: 0 0 0 .25rem rgba(255, 253, 253, 0.25) !important;
    }

    .accordion-button:not(.collapsed) {
        color: #0c63e4;
        background-color: #e7f1ff;
        box-shadow: none !important;
    }

    .accordion-body {
        padding: 0px 20px 20px 20px !important;
        color: #292929e0;
    }

    /*.navbar-toggler {*/
    /*    background-color: white !important;*/
    /*}*/

    .navbar-toggler:focus {
        box-shadow: none !important;
    }

    .header-login-signup {
        color: #fff;
        margin-top: 4px;
    }

    a.header-login {
        color: #fff;
        font-size: 15px;
        font-weight: 500;
        text-decoration: none;
    }
</style>
<style>
    .img {
        height: 100%;
        width: 100%;
    }

    .home-banner img {
        width: 100%;
    }

    .fadeInRight img {
        width: 100% !important;
    }

    .fadeInLeft {
        max-width: 100%;
        overflow: hidden;
    }

    .fadeInRight {
        max-width: 100%;
        overflow: hidden;
    }

    .logo {
        height: 70px;
        width: auto;
    }

    .logo img {
        width: auto !important;
    }
    .fixed {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 999;
        }
</style>


<body>
@php
    $home_slider_sections = json_decode(get_settings('home_slider_section'), true);
@endphp
<div class="pos-f-t main-nav-sec fixed">
    <nav class="navbar nav-menu-bar">
        <a class="navbar-toggler left-side-tg-button top-header-icon" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent"
           aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa fa-bars btn-collapse top-header-collaps-btn" id="btn-collapse"></i>
        </a>

        <a href="{{isset($home_slider_sections['book_an_instruments'])?$home_slider_sections['book_an_instruments']:''}}" target="_blank" class="btn instrument">Book an instrument</a>
    </nav>
    <div class="collapse collapse-bar" id="navbarToggleExternalContent">
        <div class="collapse-menu">
            <div class="menu-content">
                <div class="content popup-menu-h">
                    <div class="meun-bar-one">
                        <ul>
                            <li>
                                <a class="aftherLine" href="/">Home</a>
                            </li>
                            <li>
                                <a class="aftherLine" href="#services">
                                    Services
                                </a>
                            </li>
                            <li>
                                <a class="aftherLine" href="#instruments">
                                    Instruments
                                </a>
                            </li>
                            <li>
                                <a class="aftherLine" href="#feeSection">
                                    Fees
                                </a>
                            </li>
                            <li>
                                <a class="aftherLine" href="#courses">Courses</a>
                            </li>
                            <li>
                                <a class="aftherLine" href="#omug">O-MUG</a>
                            </li>
                            <li>
                                <a class="aftherLine" href="#resources">Resources</a>
                            </li>
                            <li>
                                <a class="aftherLine" href="#team">Team</a>
                            </li>
                            <li>
                                <a class="aftherLine" href="#contact_us">
                                    Contact Us
                                </a>
                            </li>
                            <li>
                                <a class="aftherLine" href="#questions">
                                    Questions
                                </a>
                            </li>
                            <li>
                                <a class="aftherLine" href="{{route('blog')}}">
                                    Blog
                                </a>
                            </li>
                            <li>
                                <a class="aftherLine" href="{{route('publications')}}">
                                    Publications
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="meun-bar-two">
                        <ul>
                            <img class="logo mb-4" src="{{asset('uploads/'.get_settings('app_logo'))}}" style="height: 35px" alt="">
                            <li>
                                <strong><em>Address</em></strong>
                            </li>
                            <li>Advanced Optical Microscopy Facility (AOMF)</li>
                            <li>and Wright Cell Imaging Facility (WCIF)</li>
                            <li>University Health Network</li>
                            <li>MaRS, PMCRT tower, Room 15-305, 101 College St.</li>
                            <li>Toronto, ON, CANADA</li>
                            <li>M5G 1L7</li>
                            <br>
                            <li>
                                <strong><em>Contact</em></strong>
                            </li>
                            <li></li>
                            <li>
                                <a href="#" class="email">james.jonkman@uhn.ca</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@yield('main-section')

<footer class="footer">
    <div class="main-footer">
        <div class="footer-line"></div>
        <div class="footer-content">
            <div class="footer-logo">
                <a href="#">
                    <img src="{{asset('uploads/'.get_settings('app_logo'))}}" alt="">
                </a>
            </div>
            <div class="footer-info">
                <h2>Contact Us</h2>
                <ul>
                    <li><a href="#" class="footer-info-link">
                            {{get_settings('app_name')}}
                        </a></li>
                    <li>
                        {!! isset($home_contact_us_section['contact_us_address'])?$home_contact_us_section['contact_us_address']:'' !!}

                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>
<div class="footer2 container">
    <div class="copy-right">
        <ul>
            <li>
                Copyright © &nbsp;<span>2024 </span> {{get_settings('app_name')}} &nbsp;&nbsp;
            </li>
            <li class="mr-1">
                <a href="#" class="copyright-link">Legal</a>
            </li>
            <li>&nbsp; <a href="# " class="copyright-link"> Provision of Services</a></li>
        </ul>
    </div>

</div>

<script src="{{asset('front/js/jquery-3.5.1.min.js')}}"></script>

<script src="{{asset('front/js/bootstrap.bundle.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>


<script src="{{asset('front/js/google-maps.js')}}"></script>

<script src="{{asset('front/js/wow.min.js')}}"></script>

<script src="{{asset('front/js/theme.js')}}"></script>

<script>
    $(document).ready(function () {
        $(window).scroll(function () {
            var sticky = $('.left-side-tg-button i'),
                scroll = $(window).scrollTop();

            if (scroll >= 40) {
                sticky.addClass('text-dark').removeClass('text-white');
            } else {
                sticky.removeClass('text-dark').addClass('text-white');

            }
        });
    });
</script>
<script>
     $(document).on("click", ".top-header-collaps-btn", function() {
    if ($(this).hasClass('fa-bars')) {
        $(this).removeClass('fa-bars').addClass('fa-times');
    } else {
        $(this).removeClass('fa-times').addClass('fa-bars');
    }
});     

$(document).on("click", ".show-more-btn-instrument", function() {
    $(this).toggleClass('show').text($(this).hasClass('show') ? 'Show More +' : 'Show Less -');
});

$(document).on("click", ".course-show-more-four", function() {
        $(this).hide()
        $(".course-show-less-four").show();
});
$(document).on("click", ".course-show-less-four", function() {
        $(this).hide()
        $(".course-show-more-four").show();
});

$(document).on("click", ".course-show-more", function() {
        $(this).hide()
        $(".course-show-less").show();
});
$(document).on("click", ".course-show-less", function() {
        $(this).hide()
        $(".course-show-more").show();
});


</script>

@yield('js')

</body>
</html>
