@extends('layouts.frontLayout')

@section('title') {{get_settings('app_name')}} - Demo Login @endsection

@section('css')
    <style>
        .custom{
            padding-top: 50px;
        }
        .demo-remove{
            display: none;!important;
        }
    </style>

@endsection

@section('header')
    <div class="container">
        <div class="page-banner">
            <div class="custom">
                <h1 class="text-center" style="color: rgba(100, 95, 136, 0.75);">{{get_settings('app_name')}}</h1>
                <div class="divider mx-auto"></div>
            </div>
            <div class="row justify-content-center align-items-center h-75">

                <div class="col-lg-2">
                    <nav aria-label="Breadcrumb">
                        <ul class="breadcrumb justify-content-center py-0 bg-transparent">
                            <li class="breadcrumb-item"><a href="{{route('admin.login')}}" class="btn btn-outline-primary btn-sm pl-4 pr-4"><i class="fa fa-sign-in"></i></a></li>
                        </ul>
                    </nav>
                    <p class="text-center">Admin Login</p>
                </div>
                <div class="col-lg-2">
                    <nav aria-label="Breadcrumb">
                        <ul class="breadcrumb justify-content-center py-0 bg-transparent">
                            <li class="breadcrumb-item"><a href="{{route('login')}}" class="btn btn-outline-primary btn-sm pl-4 pr-4"><i class="fa fa-sign-in"></i></a></li>
                        </ul>
                    </nav>
                    <p class="text-center">Customer Login</p>
                </div>
            </div>
        </div>
    </div>
@endsection
