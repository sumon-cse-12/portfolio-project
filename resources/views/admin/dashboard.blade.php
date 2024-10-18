@extends('layouts.admin')

@section('title') Dashboard @endsection

@section('extra-css')
    <style>
    .cache_clear_btn{
        display: block !important;
    }
    </style>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content ">
        <div class="container-fluid">

     
            <div class="row">
                <div class="col-sm-12 ">
                    <div id="settingsCard" class="d-flex flex-column order-manage p-3 align-items-center mb-4 card">
                        {{-- <div class="d-flex w-100 d-none">
                            <a id="showSettingList" href="javascript:void(0);"
                               class="btn fs-22 py-1 btn-primary px-4 mr-3">1231231</a>
                            <h4 class="mb-0">{{trans('Pending Configuration')}}<i
                                    class="fa fa-circle text-primary ml-1 fs-13"></i></h4>
                            <a href="{{route('admin.settings.index')}}" class="ml-auto text-primary font-w500">{{trans('Settings')}}
                                <i class="ti-angle-right ml-1"></i></a>
                        </div> --}}
                        <div class="w-100 p-4" id="setting_list">
                            @php
                                $auth = auth()->user();
                            @endphp
                            <ul>
                        <h4>  Hi, {{ucfirst( $auth->name)}} </h4>
                             <br> 
                           <h2> Welcome to  <b>{{ucfirst(get_settings('app_name'))}}</b></h2>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
            {{-- @endif --}}
            <div class="row">
                <!-- ./col -->
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <!-- small box -->
                    <div class="small-box">
                        <div class="inner">
                            <p  class="dashboard_counter mb-2">{{isset($total_blogs)?$total_blogs:'0'}}</p>

                            <p class="counter_text">@lang('admin.total_blogs')</p>
                        </div>
                        <div class="icon today-sent-icon">
                            <i class="fa fa-paper-plane" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <!-- small box -->
                    <div class="small-box">
                        <div class="inner">
                            <p  class="dashboard_counter mb-2">{{isset($total_insturments)?$total_insturments:'0'}}</p>

                            <p class="counter_text">@lang('admin.total_instruments')</p>
                        </div>
                        <div class="icon total-inbox-icon">
                            <i class="fa fa-inbox" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <!-- small box -->
                    <div class="small-box">
                        <div class="inner">
                            <p class="dashboard_counter mb-2">{{isset($total_publications)?$total_publications:'0'}}</p>

                            <p class="counter_text">@lang('admin.total_publications')</p>
                        </div>
                        <div class="icon total-sent-icon">
                            <i class="fa fa-share-square" aria-hidden="true"></i>
                        </div>
                      </div>
                </div>
                <!-- ./col -->
            </div>


            <div class="row">

                <div class="col-sm-6 d-none">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">
                                <i class="fas fa-th mr-1"></i>
                                @lang('admin.inbox')
                            </h3>

                            <div class="card-tools">
                                <button type="button" class="btn bg-primary btn-sm" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn bg-primary btn-sm" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas class="chart admin-dashboard-canvas" id="line-chart" ></canvas>
                        </div>
                        <!-- /.card-body -->

                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                </div>

                <div class="col-sm-6 d-none">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">
                                <i class="fas fa-th mr-1"></i>
                                @lang('admin.sent')
                            </h3>

                            <div class="card-tools">
                                <button type="button" class="btn bg-primary btn-sm" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn bg-primary btn-sm" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas class="chart admin-dashboard-canvas" id="sent-chart"></canvas>
                        </div>
                        <!-- /.card-body -->

                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                </div>

            </div>

            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
@endsection

@section('extra-scripts')
    <script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
    {{-- <script>
        "use strict";
        // Sales graph chart
        var salesGraphChartCanvas = $('#line-chart').get(0).getContext('2d');
        var sentCanvas = $('#sent-chart').get(0).getContext('2d');
        //$('#revenue-chart').get(0).getContext('2d');

        var salesGraphChartData = {
            labels: @json($weekDates),
            datasets: [
                {
                    label: 'SMS',
                    fill: false,
                    borderWidth: 2,
                    lineTension: 0,
                    spanGaps: true,
                    borderColor: '#7367F0',
                    pointRadius: 3,
                    pointHoverRadius: 7,
                    pointColor: '#636363',
                    pointBackgroundColor: '#636363',
                    data: @json($chart_inbox)
                }
            ]
        }

        var sentGraphChartData = {
            labels: @json($weekDates),
            datasets: [
                {
                    label: 'SMS',
                    fill: false,
                    borderWidth: 2,
                    lineTension: 0,
                    spanGaps: true,
                    borderColor: '#7367F0',
                    pointRadius: 3,
                    pointHoverRadius: 7,
                    pointColor: '#636363',
                    pointBackgroundColor: '#636363',
                    data: @json($chart_sent)
                }
            ]
        }

        var salesGraphChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            legend: {
                display: false,
            },
            scales: {
                xAxes: [{
                    ticks: {
                        fontColor: '#636363',
                    },
                    gridLines: {
                        display: false,
                        color: '#636363',
                        drawBorder: false,
                    }
                }],
                yAxes: [{
                    ticks: {
                        stepSize: 5000,
                        fontColor: '#636363',
                    },
                    gridLines: {
                        display: true,
                        color: '#636363',
                        drawBorder: false,
                    }
                }]
            }
        }

        var sentGraphChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            legend: {
                display: false,
            },
            scales: {
                xAxes: [{
                    ticks: {
                        fontColor: '#636363',
                    },
                    gridLines: {
                        display: false,
                        color: '#636363',
                        drawBorder: false,
                    }
                }],
                yAxes: [{
                    ticks: {
                        stepSize: 5000,
                        fontColor: '#636363',
                    },
                    gridLines: {
                        display: true,
                        color: '#636363',
                        drawBorder: false,
                    }
                }]
            }
        };


        var salesGraphChart = new Chart(salesGraphChartCanvas, {
                type: 'line',
                data: salesGraphChartData,
                options: salesGraphChartOptions
            }
        );

        var sentGraphChart = new Chart(sentCanvas, {
                type: 'line',
                data: sentGraphChartData,
                options: sentGraphChartOptions
            }
        );
    </script> --}}
@endsection

