@extends('layouts.frontLayout')

@section('title') {{get_settings('app_name')}} -{{$page->title}} @endsection

@section('header')

    <div class="container">
        <div class="page-banner">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-md-6">
                    <nav aria-label="Breadcrumb">
                        <ul class="breadcrumb justify-content-center py-0 bg-transparent">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item active">{{$page->name}}</li>
                        </ul>
                    </nav>
                    <h1 class="text-center">{{$page->name}}</h1>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('main-section')

    <div class="page-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 py-3">
                    <h2 class="title-section">{{get_settings('app_name')}} - {{$page->title}}</h2>
                    <div class="divider"></div>
                    <p>{{$page->description}}</p>
                </div>
                <div class="col-lg-6 py-3 d-none">
                    <div class="img-fluid py-3 text-center">
                        <img src="../assets/img/about_frame.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
