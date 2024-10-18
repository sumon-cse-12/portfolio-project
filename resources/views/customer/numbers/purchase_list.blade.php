@extends('layouts.customer')

@section('title') Numbers @endsection

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <style>
        .capability-badge{
            font-size: 11px !important;
            font-weight: 400;
            padding: 3px 6px !important;
        }
    </style>

@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">{{trans('customer.choose_a_number')}}</h2>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="numbers" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>{{trans('customer.number')}}</th>
                                <th>{{trans('Capability')}}</th>
                                <th>{{trans('customer.cost')}}</th>
                                <th>{{trans('customer.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($numbers as $number)
{{--                                {{dd($number)}}--}}
                                @php
                                    $admin_number =\App\Models\Number::where('id', $number->id)->first();
                                    $capability='';
                                @endphp

                                <tr>
                                    <td>{{$number->number}}</td>
                                    <td>
                                        @if($admin_number)
                                            @if($admin_number->sms_capability=='yes')
                                                <span class="badge badge-success capability-badge">SMS</span>
                                            @endif
                                            {{-- @if($admin_number->sms_capability=='yes')
                                                <span class="badge badge-success capability-badge ml-2">MMS</span>
                                            @endif
                                            @if($admin_number->voice_capability=='yes')
                                                 <span class="badge badge-success capability-badge ml-2">Voice SMS</span>
                                            @endif --}}
                                            @if($admin_number->whatsapp_capability=='yes')
                                                 <span class="badge badge-success capability-badge ml-2">Whatsapp</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{formatNumberWithCurrSymbol($number->sell_price)}}</td>
                                    <td>
                                        @if(Module::has('PaymentGateway') && Module::find('PaymentGateway')->isEnabled())
                                            <button
                                                data-message="Are you sure you want to buy <b> {{$number->number}} </b> ?"
                                                data-action="{{route('paymentgateway::number.process')}}"
                                                data-input='{"id":"{{$number->id}}"}'
                                                data-toggle="modal" data-target="#modal-confirm"
                                                type="button"
                                                class="btn btn-info btn-sm">{{trans('Buy')}}
                                            </button>
                                        @else
                                            <button
                                                data-message="Are you sure you want to buy <b> {{$number->number}} </b> ?"
                                                data-action="{{route('customer.buy.number')}}"
                                                data-input='{"id":"{{$number->id}}"}'
                                                data-toggle="modal" data-target="#modal-confirm"
                                                type="button"
                                                class="btn btn-info btn-sm">{{trans('Buy')}}
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->


@endsection

@section('extra-scripts')
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>

    <script>
        "use strict";
        $('#numberssss').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax:'{{route('customer.numbers.purchase.list_get')}}',
            columns: [
                { "data": "number" ,"name":"number"},
                { "data": "cost" },
                { "data": "action" },
            ]
        });
    </script>
@endsection

