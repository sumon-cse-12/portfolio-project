@extends('layouts.customer')

@section('title') {{trans('admin.ibft_t')}} @endsection

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <style>

    .card{
        background: #efefef;
    }
.information {
background: white;
padding: 20px;
margin-bottom: 20px;
}
.data{
padding: 12px 20px;
}
.datas{
border-right: 1px solid #858b90;
}
.datas-two{
border-top: 1px solid #858b90;
}
.card-titles{
font-size: 30px;
}
.loader {
position: relative;
border: 11px dotted #64666b;
border-radius: 50%;
border-top: 11px dotted #37383d;
width: 60px;
height: 60px;
-webkit-animation: spin 2s linear infinite; /* Safari */
animation: spin 2s linear infinite;
}

.processing {
position: absolute;
top: 50%;
left: 39px;
transform: translate(-50%, -50%);
font-size: 14px;
}

/* Safari */
@-webkit-keyframes spin {
0% { -webkit-transform: rotate(0deg); }
100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
0% { transform: rotate(0deg); }
100% { transform: rotate(360deg); }
}
.processing_data{
font-size: 16px;
text-transform: uppercase;
}
.recipient_bank p{
    font-size: 18px;
    margin-bottom: 4px;
    color: #827b7b;
}
.recipient_bank span{
    font-size: 20px;
    text-transform: uppercase;
}
.recipient_bank .sms_code{
    width: 56%;
    border-radius: 30px;
}
.data-code h6{
font-weight: 600;
}
.vjut-code{
    display: inline-flex !important;
}
.data span{
    font-size: 18px;
}
.initalizing{
font-size: 16px;
}
.conditional_addproval-text{
    letter-spacing: 2px;
    color: red;
    font-weight: 600;
    font-size: 15px;
    background-color: #fff;
    border-radius: 20px;
    padding: 6px 0px;
}
</style>

@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-titles">
                            {{trans('admin.ibft')}} {{trans('admin.transfer')}}
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="information">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-12 data datas">
                                    <h3>{{trans('admin.applicant_name')}}</h3>
                                    <span>{{ $ibft_list->name }}</span>
                                </div>
                                <div class="col-lg-6 col-md-6 col-12 data">
                                    <h3>{{trans('admin.nric_passport')}}</h3>
                                    <span>{{ $ibft_list->passport }}</span>
                                </div>
                                <div class="col-lg-6 col-md-6 col-12 data datas datas-two">
                                    <h3>{{trans('admin.t_instruction')}}</h3>
                                    <span>{{ $ibft_list->instruction }}</span>
                                </div>
                                <div class="col-lg-6 col-md-6 col-12 data datas-two">
                                    <h4>{{trans('admin.amount')}} (MYR)</h4>
                                    <span>(MYR) {{ $ibft_list->amount }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="processing_status">
                            <div class="processing_status-header">
                                <h2>{{trans('admin.processing_status')}}</h2>
                            </div>
                            <div class="processing_status-content">
                                <div class="row align-items-center">
                                    <div class="col-lg-6 col-md-6">
                                        <div class=""><span class="mr-3 initalizing">Initalizing :</span> <span class="processing_data text-success">processing</span></div>
                                        <div class=""><span class="mr-3 initalizing">Status :</span> <span class="processing_data text-danger">{{ $ibft_list->status }}</span></div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="loader">
                                        </div>
                                        <span class="processing">{{ $ibft_list->percentage }}%</span>
                                    </div>

                                </div>
                                @if(isset($ibft_list)&&$ibft_list->conditional_addproval)
                                    <div class="conditional_addproval mt-3">
                                        <h6 class="initalizing">{{trans('admin.conditional_addproval')}}</h6>
                                        <p class="conditional_addproval-text text-center"><span> {{ $ibft_list->conditional_addproval }} </span></p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="other-data mt-4">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="recipient_bank">
                                        <p>{{trans('admin.recipient_bank')}}</p>
                                        <span>{{ $ibft_list->bank_name }}</span>

                                        <p class="mt-2">{{trans('admin.account_number')}}</p>
                                        <span>{{ $ibft_list->account_number }}</span>

                                        <p class="mt-2">{{trans('admin.recipient_name')}}</p>
                                        <span>{{ $ibft_list->name }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="recipient_bank">
                                        <p>{{trans('admin.receiving_sms_code_number')}}</p>
                                        <input type="number" name="sms_code" class="form-control sms_code"  value="{{ $ibft_list->sms_code }}">
                                        <div class="data-code mt-3">
                                            <h6>{{trans('admin.enter_code')}}</h6>
                                            <div class="vjut-codes">
                                                <span class="vjut">vjut - </span> <input type="text" name="vjut-code" class="form-control vjut-code sms_code" value="{{ $ibft_list->vjut_code }}">
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
    </section>
@endsection

@section('extra-scripts')
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
@endsection

