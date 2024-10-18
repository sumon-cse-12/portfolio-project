@extends('layouts.customer')

@section('title') Dashboard @endsection


@section('extra-css')
    <style>
    .cache_clear_btn{
        display: block !important;
    }

    .notic-section-c{
        height: 300px;
        overflow-y: scroll;
    }
    .notic-section-c::-webkit-scrollbar-track
    {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
        background-color: #F5F5F5;
    }

    .notic-section-c::-webkit-scrollbar
    {
        width: 3px;
        background-color: #F5F5F5;
    }

    .notic-section-c::-webkit-scrollbar-thumb
    {
        background-color: #878787;
        border: 2px solid #555555;
    }
    .c-pointer{
        cursor: pointer;
    }

    #accordion .card-header .normal_collapsed::after {
        float: right !important;
        font-family: FontAwesome;
        content:"\2212";
        padding-right: 5px;
        font-weight: 800;
        color: blue !important;
    }
    #accordion .card-header .collapsed::after {
        /* symbol for "collapsed" panels */
        float: right !important;
        content:"\2b";
        font-weight: 800;
        color: blue !important;
    }
    .normal_collapsed{
        color: #f0696c !important;
    }
    .w-100{
        width:100%;
    }
    </style>
@endsection

@section('content')
    {{-- <!-- Content Header (Page header) -->
    <div class="content-header custom-content-header">
        <h2 class="card-title">{{trans('customer.dashboard')}}</h2>
    </div>
    <!-- /.content-header -->

    <!-- /.content-header -->
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content --> --}}
@endsection

@section('extra-scripts')

@endsection


