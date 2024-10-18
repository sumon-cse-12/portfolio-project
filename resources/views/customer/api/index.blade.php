@extends('layouts.customer')

@section('title') {{trans('Developer')}} @endsection

@section('extra-css')
    <style>
        .c-pointer {
            cursor: pointer;
        }
        .btn-link{
            width: 100% !important;
            text-align: left;
        }

        #accordion .card-header{
            background: #d9d7d761 !important;
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
                        <h2 class="card-title">API Token</h2>
                        <div class="float-right">
                            <h4>
                                <a target="_blank" href="https://documenter.getpostman.com/view/15947626/UVyn2eRU"><i class="fas fa-external-link"></i>API Documentation</a>
                            </h4>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mx-auto">
                                <form action="{{route('customer.authorization.token.store')}}" method="post" id="apiForm">
                                    @csrf
                                    <div class="form-group">
                                            <label for="">Access Key</label>
                                            <div class="input-group date" id="reservationdatetime"
                                                 data-target-input="nearest">
                                                <input class="form-control" type="text"
                                                       value="{{isset($authorization_token->access_token)?$authorization_token->access_token:''}}" id="accessKey">
                                                <div class="input-group-append" data-target="#reservationdatetime"
                                                     data-toggle="datetimepicker">
                                                    <div class="input-group-text">
                                                        <i onclick="myFunction()" onmouseout="outFunc()"
                                                           class="fa fa-copy c-pointer"></i>
                                                                <i  class="fas fa-sync-alt ml-3 c-pointer" id="refresh"></i>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>

                                </form>
                            </div>

                            <div class="col-md-12 mt-4">
                                <div id="accordion">

                                    <div class="card">
                                        <div class="card-header" id="headingOne">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                    Check Balance
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                            <div class="card-body">
                                                <b> API URL:</b> <b>YOUR_DOMAIN</b>/api/check/balance?api_key=YOUR_API_KEY
                                                <small class="float-right mt-3 d-block"><b>Note:</b> Authorize user only access</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header" id="headingTwo">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                    Get SenderID
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                            <div class="card-body">
                                                <b>API KEY:</b> <b>YOUR_DOMAIN</b>/api/sender-id?api_key=YOUR_API_KEY
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header" id="headingThree">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                    Get Non SenderID
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                            <div class="card-body">
                                               <b>API URL:</b> <b>YOUR_DOMAIN</b>/api/customer/number?api_key=YOUR_API_KEY&type=number                                            </div>
                                        </div>
                                    </div>


                                    <div class="card">
                                        <div class="card-header" id="headingLast">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseLast" aria-expanded="false" aria-controls="collapseLast">
                                                    Send OTP
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="collapseLast" class="collapse" aria-labelledby="headingLast" data-parent="#accordion">
                                            <div class="card-body">
                                                <b>API KEY:</b> <b>YOUR_DOMAIN</b>/api/otp/message?api_key=YOUR_API_KEY&number=RECEIVER_NUMBER&code=OTP_CODE
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header" id="headingThree1">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree1" aria-expanded="false" aria-controls="collapseThree1">
                                                    Get Contacts
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="collapseThree1" class="collapse" aria-labelledby="headingThree1" data-parent="#accordion">
                                            <div class="card-body">
                                               <b> API URL:</b> <b>YOUR_DOMAIN</b>/api/contacts?api_key=YOUR_API_KEY&page=1&limit=20
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header" id="headingThree2">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree2" aria-expanded="false" aria-controls="collapseThree2">
                                                    Compose
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="collapseThree2" class="collapse" aria-labelledby="headingThree2" data-parent="#accordion">
                                            <div class="card-body">
                                               <div>
                                                   <b>API URL:</b>
                                                   <b>YOUR_DOMAIN</b>/api/sent/compose?api_key=YOUR_&from_type=phone_number&from_number=YOUR_FROM_NUMBER&sender_id=YOUR_MASKING&to_numbers=TO_NUMBER&body=MESSAGE&isSchedule=&schedule=02/24/2022 12:00 AM
                                               </div>

                                                <div class="mt-3">
                                                    <table class="table table-bordered table-striped table-condensed flip-content">
                                                        <thead class="flip-content">
                                                        <tr>
                                                            <th> Parameter Name </th>
                                                            <th> Meaning/Value </th>
                                                            <th> Description </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td> api_key </td>
                                                            <td> API Key </td>
                                                            <td> Your API Key <strong id="key_id_ref">(1|TA8NaJrpJtDQBNQ4HGmLj0c3uHLW1BCO64s3WAVL)</strong> </td>
                                                        </tr>

                                                        <tr>
                                                            <td> from_type </td>
                                                            <td> From Type </td>
                                                            <td> Masking / Non Masking </td>
                                                        </tr>

                                                        <tr>
                                                            <td> phone_number </td>
                                                            <td> Phone Number </td>
                                                            <td> If From Type "number" Use Non Masking </td>
                                                        </tr>

                                                        <tr>
                                                            <td> sender_id </td>
                                                            <td> Masking </td>
                                                            <td> If From Type "sender_id" Use Masking </td>
                                                        </tr>

                                                        <tr>
                                                            <td> to_numbers </td>
                                                            <td> To Number </td>
                                                            <td> Multiple number should be separate by <b>(,)</b> comma </td>
                                                        </tr>


                                                        <tr>
                                                            <td> body </td>
                                                            <td> text/unicode </td>
                                                            <td> text for normal SMS/unicode for Bangla SMS</td>
                                                        </tr>

                                                        <tr>
                                                            <td> isSchedule </td>
                                                            <td> on / '' </td>
                                                            <td> </td>
                                                        </tr>
                                                        <tr>
                                                            <td> schedule </td>
                                                            <td> 02/24/2022 12:00 AM </td>
                                                            <td>  </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header" id="headingThree3">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree3" aria-expanded="false" aria-controls="collapseThree3">
                                                    Campaign Create
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="collapseThree3" class="collapse" aria-labelledby="headingThree3" data-parent="#accordion">
                                            <div class="card-body">
                                            <b>API URL:</b>
                                                <b>YOUR_DOMAIN</b>/api/campaign/store?api_key=YOUR_API_KEY&title=Campaign-1&
                                                from_type=phone_number&from_number=YOUR_FROM_NUMBER&to_number=TO_NUMBER&
                                                start_date=2022/05/24&end_date=2022/05/24&start_time=12:1&end_time=08:10&
                                                template_body={last_name}{last_name}&template_id=1&send_speed=111

                                                <div class="mt-3">
                                                    <table class="table table-bordered table-striped table-condensed flip-content">
                                                        <thead class="flip-content">
                                                        <tr>
                                                            <th> Parameter Name </th>
                                                            <th> Meaning/Value </th>
                                                            <th> Description </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td> api_key </td>
                                                            <td> API Key </td>
                                                            <td> Your API Key <strong id="key_id_ref">(1|TA8NaJrpJtDQBNQ4HGmLj0c3uHLW1BCO64s3WAVL)</strong> </td>
                                                        </tr>

                                                        <tr>
                                                            <td> title </td>
                                                            <td> Campaign Title </td>
                                                            <td> Campaign Title </td>
                                                        </tr>
                                                        <tr>
                                                            <td> from_type </td>
                                                            <td> phone_number </td>
                                                            <td> Phone Number </td>
                                                        </tr>

                                                        <tr>
                                                            <td> phone_number </td>
                                                            <td> Phone Number </td>
                                                            <td> If From Type "number" Use Non Masking </td>
                                                        </tr>


                                                        <tr>
                                                            <td> sender_id </td>
                                                            <td> Masking </td>
                                                            <td> If From Type "sender_id" Use Masking </td>
                                                        </tr>

                                                        <tr>
                                                            <td> start_date </td>
                                                            <td> 2022/05/24 </td>
                                                            <td> Campaign Start Date </td>
                                                        </tr>
                                                        <tr>
                                                            <td> end_date </td>
                                                            <td> 2022/05/24 </td>
                                                            <td> Campaign End Date </td>
                                                        </tr>
                                                        <tr>
                                                            <td> start_time </td>
                                                            <td> 12:01 </td>
                                                            <td> Campaign Start Time </td>
                                                        </tr>
                                                        <tr>
                                                            <td> end_time </td>
                                                            <td> 08:10 </td>
                                                            <td> Campaign End Time </td>
                                                        </tr>

                                                        <tr>
                                                            <td> to_numbers </td>
                                                            <td> To Number </td>
                                                            <td> Multiple number should be separate by <b>(,)</b> comma </td>
                                                        </tr>


                                                        <tr>
                                                            <td> template_body </td>
                                                            <td> {last_name}{last_name}{last_name} </td>
                                                            <td> </td>
                                                        </tr>

                                                        <tr>
                                                            <td> template_id </td>
                                                            <td> 1 </td>
                                                            <td>Enter Template ID </td>
                                                        </tr>

                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>



                             <div class="row mt-4">
                                 <div class="col-md-10 mx-auto">
                                     <div class="card" style=" box-shadow: 0 10px 20px rgb(0 0 0 / 19%), 0 6px 6px rgb(0 0 0 / 23%);background: #dddddd4a;">
                                         <div class="card-body table-body">
                                             <table id="contacts" class="table table-striped table-bordered dt-responsive nowrap">
                                                 <thead>
                                                 <tr>
                                                     <th>{{trans('Response Code')}}</th>
                                                     <th>{{trans('Response Message')}}</th>
                                                 </tr>
                                                 </thead>

                                                 <tbody>

                                                 @foreach(responseCode() as $key=> $response)
                                                     <tr>
                                                         <td>
                                                             {{$key}}
                                                         </td>
                                                         <td>
                                                             {{$response}}
                                                         </td>
                                                     </tr>
                                                 @endforeach
                                                 </tbody>

                                             </table>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                            </div>
                        </div>
                    </div>
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
    <script>
        $(document).on('click', '#refresh', function (e){
            $('#apiForm').submit();
        })
        function myFunction() {
            var copyText = document.getElementById("accessKey");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);

            var tooltip = document.getElementById("keyToolTip");
            tooltip.innerHTML = "Copied: " + copyText.value;
        }

        function outFunc() {
            var tooltip = document.getElementById("keyToolTip");
            tooltip.innerHTML = "Copy to clipboard";
        }
    </script>
@endsection

