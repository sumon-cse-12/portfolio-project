@extends('layouts.customer')

@section('title','Compose | SmsBox')

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <style>
        #select2-toNumbers-results, #select2-fromNumber-results{
            overflow-y: auto;
            max-height: 200px;
        }
        .from_type_btn.active{
            background-color: rgb(5 187 201) !important;
            border-color: rgb(5 187 201) !important;
        }
    </style>
@endsection

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{trans('customer.compose')}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a
                                href="{{route('customer.smsbox.inbox')}}">{{trans('customer.smsbox')}}</a></li>
                        <li class="breadcrumb-item active">{{trans('customer.compose')}}</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- /.col -->
            <div class="col-lg-8 col-md-7 mx-auto">
                <div class="card card-primary card-outline">
                    <form id="compose_form" action="{{route('customer.smsbox.compose.sent')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card-header">
                            <h3 class="card-title">{{trans('customer.compose_new_message')}}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group pb-3">
                                <button type="button" class="btn btn-default from_type_btn active" data-type="sms">SMS</button>
                                <button type="button" class="btn btn-default from_type_btn" data-type="mms">MMS</button>
                                <button type="button" class="btn btn-default from_type_btn" data-type="whatsapp">Whatsapp SMS</button>
                                <button type="button" class="btn btn-default from_type_btn" data-type="voicecall">Voicecall SMS</button>
                            </div>

                            <div class="form-group">
                                <label for="">Select From Type</label>
                                <select name="from_type" class="form-control select2" id="fromType">
                                    <option {{isset($from_type) && $from_type=='phone_number'?'selected':''}} value="phone_number">Phone Number</option>
                                    <option {{isset($from_type) && $from_type=='whatsapp_number'?'selected':''}} value="whatsapp_number">WhatsApp Number</option>
                                      <option {{isset($from_type) && $from_type=='sender_id'?'selected':''}} value="sender_id">Sender ID</option>
                                </select>
                            </div>
                            <div id="pre_draft">
                                @isset($draft)
                                    <input type='hidden' id='draft_id' name='draft_id' value='{{$draft->id}}'/>
                                @endisset
                            </div>
                            <div class="form-group from-number-section pb-3" id="phone_number_section">
                                <label for="">Phone Number  :</label>
                                @php $fromNumbers = auth('customer')->user()->numbers()->where('expire_date','>', now())->get() @endphp
                                <select name="from_number" class="form-control from_number" id="from_number">
                                    @if($fromNumbers->isNotEmpty())
                                        @foreach($fromNumbers as $key=>$number)
                                            <option {{isset($draft) && $draft->formatted_number_from==$number->number?'selected':($key==0?'selected':'')}}>{{$number->number}}</option>
                                        @endforeach
                                    @else
                                        <option value="">No Data Available</option>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group from-number-section pb-3" id="whatsapp_number_section" style="display: none">
                                <label for="">WhatsApp From Number:</label>
                                @php $whatsAppNumbers = auth('customer')->user()->whatsapp_numbers()->where('expire_date','>', now())->get() @endphp
                                <select name="whatsapp_from_number" class="form-control from_number" id="whatsAppNumber">
                                    @foreach($whatsAppNumbers as $key=>$number)
                                        <option {{isset($draft) && $draft->formatted_number_from==$number->number?'selected':($key==0?'selected':'')}}>{{$number->number}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group from-number-section pb-3" id="sender_id_section" style="display: none">
                                <label for="">From Sender ID :</label>
                                @php $senderIds = auth('customer')->user()->sender_ids()->where('expire_date','>', now())->where('is_paid', 'yes')->get() @endphp
                                <select name="sender_id" class="form-control" id="senderId">

                                    @if($senderIds->isNotEmpty())
                                    @foreach($senderIds as $key=>$sender_id)
                                        <option value="{{$sender_id->id}}" senderID="{{$sender_id->sender_id}}">{{$sender_id->sender_id}}</option>
                                    @endforeach
                                    @else
                                        <option value="">No Data Available</option>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group from-number-section pb-3" id="voicecall_section" style="display: none">
                                <label for="voicecall_file">{{trans('customer.voicecall_file')}}</label>
                                <input type="file" accept="audio/mp3" id="voicecall_file" class="form-control" name="file_mp3">
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-12">

                                        <select name="to_numbers[]" id="toNumbers" class="select2 compose-select"
                                                multiple="multiple"
                                                data-placeholder="{{trans('customer.recipient')}}:">

                                            @if(isset($draft) && $draft->formatted_number_to)
                                                @foreach($draft->formatted_number_to_array as $to)
                                                    <option selected value="{{$to}}">{{$to}}</option>
                                                @endforeach
                                            @endif
                                            @isset($users_to_contacts)
                                                <optgroup label="Contacts">
                                                    @foreach($users_to_contacts as $to)
                                                        <option value="{{json_encode($to)}}">{{$to['value']}}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endisset

                                            @isset($users_to_groups)
                                                <optgroup label="Groups">
                                                    @foreach($users_to_groups as $to)
                                                        <option value="{{json_encode($to)}}">{{$to['value']}}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endisset
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                            <textarea name="body" id="compose-textarea" class="form-control compose-body"
                                      placeholder="{{trans('customer.enter_message')}}">{{isset($draft)?$draft->body:''}}</textarea>
                                <div class="text-right">
                                    <b id="smsCount"></b> SMS (<b id="smsLength"></b>) Characters left
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="d-block">
                                    <button type="button" class="btn btn-danger" id="smsCalculator">Calculate</button>
                                    <span id="smsCalculateData" class="d-none">Total SMS will send (<b id="smsCountTotal">0</b>) || Total Contacts (<b id="contectCount">0</b>) || Total amount ({{isset(json_decode(get_settings('local_setting'))->currency_symbol)?json_decode(get_settings('local_setting'))->currency_symbol:''}}<b id="amountCount">0</b>)</span>
                                    <span class="text-danger ml-3 d-none" id="composeErrorMessage">No data available</span>
                                </div>
                            </div>
                            <div class="form-group d-none" id="mms_files_input">
                                <label for="mms_files">{{trans('customer.choose_file')}}:</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" accept=" application/pdf, image/*,video/*" id="mms_files" class="custom-file-input form-group" name="mms_files[]" multiple>
                                        <label class="custom-file-label" for="profile">{{trans('customer.choose_file')}}</label>
                                    </div>
                                </div>
                                <ul class="img-jpg" id="img-jpg"></ul>
                            </div>

                            <div class="form-group">

                                <div class="icheck-success d-inline">
                                    <input {{isset($draft) && $draft->schedule_datetime?'checked':''}} name="isSchedule"
                                           type="checkbox" id="isScheduled">
                                    <label for="isScheduled">
                                        {{trans('customer.schedule')}}
                                    </label>
                                </div>

                                <input style="display: {{isset($draft) && $draft->schedule_datetime?'block':'none'}}"
                                       name="schedule"
                                       value="{{isset($draft) && $draft->schedule_datetime?$draft->schedule_datetime->format('m/d/Y h:i A'):''}}"
                                       id="schedule" type='text'
                                       class="form-control"/>
                            </div>

                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <div class="float-right">
                                <button id="draft" type="button" class="btn btn-default"><i
                                        class="fas fa-pencil-alt"></i> {{trans('customer.draft')}}
                                </button>
                                <button type="submit" class="btn btn-primary"><i
                                        class="far fa-envelope"></i> {{trans('customer.send')}}
                                </button>
                            </div>
                            <button id="reset" type="button" class="btn btn-default"><i
                                    class="fas fa-times"></i> {{trans('customer.reset')}}
                            </button>
                        </div>
                        <!-- /.card-footer -->
                    </form>
                </div>
                <!-- /.card -->
            </div>
            <div class="col-lg-4 col-md-5">
                <section class="chatbox">
                    <div class="header-number">
                        <i class="fa fa-arrow-left back-icon"></i>
                        <span id="from_number_mobaile_view"></span>
                        <div class="header-icon">
                            <i class="fa fa-video-camera"></i>
                            <i class="fa fa-phone"></i>
                            <i class="fa fa-search"></i>
                            <i class="fa fa-ellipsis-v"></i>
                        </div>
                    </div>

                    <section class="chat-window" id="msg_mobaile_view"></section>

                    <form class="chat-input">
                        <i class="fa fa-plus-circle plus-icon"></i>
                        <span>Type a message</span>
                        <button>
                            <i class="fa fa-paper-plane"></i>
                        </button>
                    </form>
                </section>
            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
    <input type="hidden" id="meg-time">
    <input type="hidden" id="whatsappType" value="{{request()->get('type')}}">

@endsection

@section('extra-scripts')
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/moment.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>

    <script !src="">
        "use strict";
        var select2 = $('#toNumbers').select2({
            minimumInputLength: 1,
            tags: true,
            tokenSeparators: [",", " "],
        })

        $('#fromNumber').select2({
            theme: 'bootstrap4'
        });

        $('#fromSenderId').select2({
            theme: 'bootstrap4'
        });
        $('#from_number').select2({
            multiple:false,
            placeholder:'Select a from number',
        });
        $('#whatsAppNumber').select2({
            multiple:false,
            placeholder:'Select a from number',
        });

        $(function () {
            "use strict";
            $('#schedule').daterangepicker({
                autoUpdateInput: true,
                singleDatePicker: true,
                timePicker: true,
                locale: {
                    format: 'MM/DD/YYYY hh:mm A'
                }
            });
        });
        $(function () {
            const whatsappType = $('#whatsappType').val()
            if(whatsappType == 'whatsapp_number'){
                $('#mms_files_input').removeClass('d-none');
            }
        });
        $('#isScheduled').on('change', function (e) {
            const checked = $(this).is(':checked');
            if (checked) {
                $('#schedule').show();
            } else {
                $('#schedule').hide();
            }
        })

        $('#reset').on('click', function (e) {
            e.preventDefault();
            $(select2).val('').trigger('change');
            $("#compose-textarea").val('');
            let checked = $("#isScheduled").is(':checked');
            if (checked) {
                $('#isScheduled').click().prop("checked", false);
            }
        })

        $('#draft').on('click', function (e) {
            e.preventDefault();
            const from = $('.from_number').val();
            const to = $('#toNumbers').val();
            const body = $('#compose-textarea').val();
            const checked = $("#isScheduled").is(':checked');
            const draft_id = $("#draft_id").val();
            let schedule = '';
            if (checked) {
                schedule = $('#schedule').val();
            }
            $.ajax({
                method: 'post',
                url: '{{route('customer.smsbox.draft.store')}}',
                data: {_token: '{{csrf_token()}}', from, to, body, checked, schedule, draft_id},
                success: function (res) {
                    if (res.status == 'success') {
                        notify('success', res.message);
                        var id = res.data.id;
                        $('#pre_draft').html("<input type='hidden' id='draft_id' name='draft_id' value='" + id + "'/>");

                    } else {
                        notify('danger', res.message);
                    }
                }
            })

        })

        $('#fromType').on('change',function (e) {
            const type = $(this).val();
            $('.from-number-section').hide();
            $('#' + type + "_section").show();
        });


        $('.select_type').on('change',function (e) {
            e.preventDefault()
            const type=$(this).val();
             if(type == 'sender_Id') {
                $('#numberfrom').addClass('d-none').removeClass('d-flex');
                 $('#whatsAppNumber').addClass('d-none').removeClass('d-flex');
                 $('#senderIdfrom').addClass('d-flex').removeClass('d-none');
            } else if (type == 'phone_number') {
                $('#senderIdfrom').addClass('d-none').removeClass('d-flex');
                $('#whatsAppNumber').addClass('d-none').removeClass('d-flex');
                $('#numberfrom').addClass('d-flex').removeClass('d-none');
            }else{
                 $('#numberfrom').addClass('d-none').removeClass('d-flex');
                 $('#senderIdfrom').addClass('d-none').removeClass('d-flex');
                 $('#whatsAppNumber').addClass('d-flex').removeClass('d-none');
             }
        });

        $('#phone_number').trigger('change');

        $(document).on('click', '.from_type_btn', function(e){

            $('.from_type_btn').removeClass('active');

            $(this).addClass('active');
        })
    </script>
    <script>
        (function($){
            $.fn.smsArea = function(options){

                //Generate Ascii Character Array
                var maxCh = 1000;
                var minCh = 0;
                var arrAscii = [];
                for(minCh =1;  minCh < maxCh; minCh++){
                    arrAscii.push(minCh * 160);
                }
                //End

                //Generate Unicode Character Array
                var unMaxCh = 1000;
                var unMinCh = 0;
                var arrUnicode = [];
                for(unMinCh =1;  unMinCh < unMaxCh; unMinCh++){
                    arrUnicode.push(unMinCh * 70);
                }
                //End

                var
                    e = this,
                    cutStrLength = 0,

                    s = $.extend({

                        cut: true,
                        maxSmsNum: 1000,
                        interval: 5,

                        counters: {
                            message: $('#smsCount'),
                            character: $('#smsLength')
                        },

                        lengths: {
                            ascii: arrAscii,
                            unicode: arrUnicode
                        }
                    }, options);


                e.keyup(function(){

                    clearTimeout(this.timeout);
                    this.timeout = setTimeout(function(){

                        var
                            smsType,
                            smsLength = 0,
                            smsCount = -1,
                            charsLeft = 0,
                            text = e.val(),
                            isUnicode = false;

                        for(var charPos = 0; charPos < text.length; charPos++){
                            switch(text[charPos]){
                                case "\n":
                                case "[":
                                case "]":
                                case "\\":
                                case "^":
                                case "{":
                                case "}":
                                case "|":
                                case "€":
                                    smsLength += 2;
                                    break;

                                default:
                                    smsLength += 1;
                            }

                            //!isUnicode && text.charCodeAt(charPos) > 127 && text[charPos] != "€" && (isUnicode = true)
                            if(text.charCodeAt(charPos) > 127 && text[charPos] != "€")
                                isUnicode = true;
                        }

                        if(isUnicode)   smsType = s.lengths.unicode;
                        else                smsType = s.lengths.ascii;

                        for(var sCount = 0; sCount < s.maxSmsNum; sCount++){

                            cutStrLength = smsType[sCount];
                            if(smsLength <= smsType[sCount]){

                                smsCount = sCount + 1;
                                charsLeft = smsType[sCount] - smsLength;
                                break
                            }
                            console.log(sCount, s.maxSmsNum);
                        }

                        if(s.cut) e.val(text.substring(0, cutStrLength));
                        smsCount == -1 && (smsCount = s.maxSmsNum, charsLeft = 0);

                        s.counters.message.html(smsCount);
                        s.counters.character.html(charsLeft);

                    }, s.interval)
                }).keyup();

            }}(jQuery));


        //Start
        $(function(){
            $('#compose-textarea').smsArea();
        })
        $('#fromType').trigger('change');
    </script>
    <script>
        $(function(){
           let type = $('#whatsappType').val();
            if (type){
                if (type == 'phone_number'){
                    const from = $('#from_number').val();
                    $("#from_number_mobaile_view").html(from);
                }else if (type == 'sender_id'){
                   const from = $('option:selected',$('#senderId')).attr('senderID');
                    $("#from_number_mobaile_view").html(from);
                }else if (type == 'whatsapp_number'){
                    const from = $('#whatsAppNumber').val();
                    $("#from_number_mobaile_view").html(from);
                }

            }else {
                const from = $('.from_number').val();
                $("#from_number_mobaile_view").html(from);
            }
        });
        $(document).on('change', '#fromType', function(e) {
            const type = $(this).val();
            let from_number = '';
            if(type == 'whatsapp_number') {
                 from_number = $('#whatsAppNumber').val();
                $('#mms_files_input').removeClass('d-none');
                $('#compose-textarea').removeClass('d-none');
                $('#compose-textarea').addClass('form-control compose-body');
            } else if (type == 'sender_id') {
                 from_number = $('option:selected',$('#senderId')).attr('senderID');
                $('#mms_files_input').addClass('d-none');
                $('#compose-textarea').removeClass('d-none');
                $('#compose-textarea').addClass('form-control compose-body');
            }else if (type == 'phone_number'){
                 from_number = $('.from_number').val();
                $('#mms_files_input').addClass('d-none');
                $('#compose-textarea').removeClass('d-none');
                $('#compose-textarea').addClass('form-control compose-body');
            }else if (type == 'voicecall'){
                $('#compose-textarea').addClass('d-none');
            }
            $("#from_number_mobaile_view").html(from_number);
        });

        $(document).on('change', '#whatsAppNumber', function(e) {
            e.preventDefault()
            const type = $('#fromType').val();
            let from_number = '';
            if (type == 'whatsapp_number') {
                from_number = $('#whatsAppNumber').val();
            }
            $("#from_number_mobaile_view").html(from_number);
        });
        $(document).on('change', '#senderId', function(e) {
            e.preventDefault()
            const type = $('#fromType').val();
            let from_number = '';
            if(type == 'sender_id') {
                from_number = $('option:selected',$('#senderId')).attr('senderID');
            }
            $("#from_number_mobaile_view").html(from_number);
        });
        $(document).on('change', '#from_number', function(e) {
            e.preventDefault()
            const type = $('#fromType').val();
            let from_number = '';
            if (type == 'phone_number') {
                from_number = $('#from_number').val();
            }
            $("#from_number_mobaile_view").html(from_number);
        });
        $("#compose-textarea").on("keyup change", function(e) {
            e.preventDefault()
            const type = $('#fromType').val();
            let from_number = '';
            if(type == 'sender_id') {
                from_number = $('option:selected',$('#senderId')).attr('senderID');
            } else if (type == 'phone_number') {
                from_number = $('#from_number').val();
            }else if (type == 'whatsapp_number'){
                from_number = $('#whatsAppNumber').val();
            }
            const checked = $("#isScheduled").is(':checked');
            let dateTime ='';
            if (checked) {
                dateTime = $('#schedule').val();
            }else {
                dateTime = $('#meg-time').val()
            }
            let data = $('#compose-textarea').val();
            let compose = data.replace(/\n/g,"<br />");
            $("#msg_mobaile_view").html(`<article class="msg-container msg-remote" id="msg-0">
                                <div class="mag-time">${dateTime}</div>
                                <div class="msg-box">
                                    <div class="flr">
                                        <div class="messages">
                                            <div class="msg">${compose}</div>
                                        </div>
                                    </div>
                                </div>
                                <span>J</span>
                            </article>`);
            $("#from_number_mobaile_view").html(from_number);

        });
        $('#schedule').on('change', function (e) {
            e.preventDefault()
            const type = $('#fromType').val();
            let from_number = '';
            if(type == 'sender_id') {
                from_number = $('option:selected',$('#senderId')).attr('senderID');
            } else if (type == 'phone_number') {
                from_number = $('#from_number').val();
            }else if (type == 'whatsapp_number'){
                from_number = $('#whatsAppNumber').val();
            }
            let data = $('#compose-textarea').val();
            let compose = data.replace(/\n/g,"<br />");
            const checked = $("#isScheduled").is(':checked');
            let dateTime ='';
            if (checked) {
                dateTime = $('#schedule').val();
            }else {
                dateTime = $('#meg-time').val()
            }
            if (compose){
                $("#msg_mobaile_view").html(`<article class="msg-container msg-remote" id="msg-0">
                                <div class="mag-time">${dateTime}</div>
                                <div class="msg-box">
                                    <div class="flr">
                                        <div class="messages">
                                            <div class="msg">${compose}</div>
                                        </div>
                                    </div>
                                </div>
                                <span>J</span>
                            </article>`);
            }

            $("#from_number_mobaile_view").html(from_number);
        });
        $('#isScheduled').on('change', function (e) {
            e.preventDefault()
            const type = $('#fromType').val();
            let from_number = '';
            if(type == 'sender_id') {
                from_number = $('option:selected',$('#senderId')).attr('senderID');
            } else if (type == 'phone_number') {
                from_number = $('#from_number').val();
            }else if (type == 'whatsapp_number'){
                from_number = $('#whatsAppNumber').val();
            }
            let data = $('#compose-textarea').val();
            let compose = data.replace(/\n/g,"<br />");
            const checked = $("#isScheduled").is(':checked');
            let dateTime ='';
            if (checked) {
                dateTime = $('#schedule').val();
            }else {
                dateTime = $('#meg-time').val()
            }
            if (compose){
                $("#msg_mobaile_view").html(`<article class="msg-container msg-remote" id="msg-0">
                                <div class="mag-time">${dateTime}</div>
                                <div class="msg-box">
                                    <div class="flr">
                                        <div class="messages">
                                            <div class="msg">${compose}</div>
                                        </div>
                                    </div>
                                </div>
                                <span>J</span>
                            </article>`);
            }

            $("#from_number_mobaile_view").html(from_number);
        });
        $('#mms_files').change(function(e) {
            const fileName = e.target.files;
            for (var i = 0; i < fileName.length; i++) {
                $("#img-jpg").append("<li>"+fileName[i].name+"</li>,");
            }
        });
    </script>
    <script>
        const myDate = new Date();
        let daysList = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        let monthsList = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Aug', 'Oct', 'Nov', 'Dec'];


        let date = myDate.getDate();
        let month = monthsList[myDate.getMonth()];
        let year = myDate.getFullYear();
        let day = daysList[myDate.getDay()];

        let today = `${date} ${month} ${year}, ${day}`;

        let amOrPm;
        let twelveHours = function () {
            if (myDate.getHours() > 12) {
                amOrPm = 'PM';
                let twentyFourHourTime = myDate.getHours();
                let conversion = twentyFourHourTime - 12;
                return `${conversion}`

            } else {
                amOrPm = 'AM';
                return `${myDate.getHours()}`
            }
        };
        let hours = twelveHours();
        let minutes = myDate.getMinutes();

        let currentTime = `${hours}:${minutes} ${amOrPm}`;
        $('#meg-time').val(today + ' ' + currentTime)
    </script>
    <script>
        $('#smsCalculator').on('click', function (e) {
            const fromType = $('#fromType').find(':selected').val();
            const message = $('#compose-textarea').val();
            const toNumbers = $('#toNumbers').val();
            if (fromType && message && toNumbers){
                $.ajax({
                    method: 'get',
                    url: '{{route('customer.smsbox.compose.sms.calculate')}}',
                    data: {from_type: fromType,message:message,to_numbers:toNumbers},
                    success: function (res) {
                        if (res.status == 'success') {
                            $('#smsCountTotal').text(res.data.totalSms);
                            $('#contectCount').text(res.data.totalNumber)
                            $('#amountCount').text(res.data.totalRate)
                        }
                    }
                });
                $('#composeErrorMessage').addClass('d-none')
                $('#smsCalculateData').removeClass('d-none')
            }else {
                $('#composeErrorMessage').removeClass('d-none')
                $('#smsCalculateData').addClass('d-none')
            }

        });
    </script>

@endsection


