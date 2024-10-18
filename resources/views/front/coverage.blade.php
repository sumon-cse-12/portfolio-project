@extends('layouts.frontLayout')

@section('title') {{get_settings('app_name')}} - {{trans('Coverage')}} @endsection

@section('css')
    <link href="{{asset('plugins/select2/css/select2.min.css')}}" rel="stylesheet" />

    <style>
        .select2-search--dropdown .select2-search__field{
            outline-offset: 0px !important;
            outline: azure !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected]{
            background-color: #6c55f9 !important;
        }
        .select2-container--default .select2-results__option[aria-selected=true]{
            background-color: #efefef !important;
            color: rgba(0, 0, 0, 0.93) !important;
        }
        .select2-container--default .select2-selection--single{
            height: 35px;
            padding: 4px 15px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow{
            height: 35px;
            padding: 4px 15px !important;
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
                            <li class="breadcrumb-item active">{{trans('Coverage')}}</li>
                        </ul>
                    </nav>
                    <h1 class="text-center">{{trans('Coverage')}}</h1>
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
                    <h2 class="title-section">
                        Coverage For @if(isset($plan_title)) {{$plan_title}} <small class="font-11">(Plan)</small>@endif
                    </h2>
                    <div class="divider"></div>
                    <div class="row">
                        <div class="col-md-8 mx-auto">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row p-3 pb-0">
                                        <div class="col-md-6 mx-auto">
                                            <div class="form-group">
                                                <label for="">{{trans('Choose Country')}}</label>
                                                <select name="country" id="select_country" class="form-control country_select">
                                                    @foreach($coverages as $key=>$coverage)
                                                        <option value="{{$coverage->id}}">{{ucfirst($coverage->country)}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row p-3 pt-0">
                                        <div class="col-md-6 mt-2">
                                            <div class="form-group">
                                                <label for="">{{trans('admin.send_sms')}}: <strong class="send-sms">{{isset($coverage->plain_sms)?$coverage->plain_sms:0}}</strong></label>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-2">
                                            <div class="form-group">
                                                <label for="">{{trans('admin.receive_sms')}}: <strong class="receive-sms">{{isset($coverage->receive_sms)?$coverage->receive_sms:0}}</strong></label>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-2">
                                            <div class="form-group">
                                                <label for="">{{trans('Send MMS')}}: <strong class="send-mms">{{isset($coverage->send_mms)?$coverage->send_mms:0}}</strong></label>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-2">
                                            <div class="form-group">
                                                <label for="">{{trans('Receive MMS')}}: <strong class="receive-mms">{{isset($coverage->receive_mms)?$coverage->receive_mms:0}}</strong></label>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-2">
                                            <div class="form-group">
                                                <label for="">{{trans('Send Voice SMS')}}: <strong class="send-voice">{{isset($coverage->send_voice_sms)?$coverage->send_voice_sms:0}}</strong></label>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-2">
                                            <div class="form-group">
                                                <label for="">{{trans('Receive Voice SMS')}}: <strong class="receive-voice">{{isset($coverage->receive_voice_sms)?$coverage->receive_voice_sms:0}}</strong></label>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-2">
                                            <div class="form-group">
                                                <label for="">{{trans('Send Whatsapp Message')}}: <strong class="send-whatsapp">{{isset($coverage->send_whatsapp_sms)?$coverage->send_whatsapp_sms:0}}</strong></label>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mt-2">
                                            <div class="form-group">
                                                <label for="">{{trans('Receive Whatsapp Message')}}: <strong class="receive-whatsapp">{{isset($coverage->receive_whatsapp_sms)?$coverage->receive_whatsapp_sms:0}}</strong></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="{{asset('plugins/select2/js/select2.full.js')}}"></script>

    <script>
        $(document).ready(function(){
            $('#select_country').select2({
                multiple:false
            });
        });

        $(document).on('change', '#select_country', function(e){
            const country=$(this).val();
            const plan_id='{{$plan_id}}';

            $.ajax({
                type:'GET',
                url:'{{route('get.coverage')}}',
                data:{
                    country:country
                },

                success:function(res){
                    if(res.status=='success'){
                        $('.send-sms').text(res.data.plain_sms);
                        $('.receive-sms').text(res.data.receive_sms);
                        $('.send-mms').text(res.data.send_mms);
                        $('.receive-mms').text(res.data.receive_mms);
                        $('.send-voice').text(res.data.send_voice_sms);
                        $('.receive-voice').text(res.data.receive_voice_sms);
                        $('.send-whatsapp').text(res.data.send_whatsapp_sms);
                        $('.receive-whatsapp').text(res.data.receive_whatsapp_sms);
                    }
                }
            })
        })

    </script>

@endsection
