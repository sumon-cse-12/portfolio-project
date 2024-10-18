@extends('layouts.customer')

@section('title','Dynamic Campaign')

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/ion-rangeslider/css/ion.rangeSlider.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('css/msg_overview.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <style>
        .select2-container--default .select2-selection--single {
            min-height: 38px;
            border-radius: 4px 0 0 4px;
        }

        .active {
            margin: 0 auto;
            background: #7181844d;
            color: #121213;
            border-radius: 5px;
        }

        .campaign_side_bar {
            padding: 10px 20px;
        }

        .js-irs-2 {
            display: none !important;
        }

        #range_5 {
            display: none !important;
        }

        .irs-handle .single {
            cursor: pointer !important;
        }

        .active_btn {
            background: #ec0b0b !important;
            border-color: inherit !important;
        }

        .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
            background-color: #d4d9da !important;
        }

        #custom_tabs_one_tabContent .tab-pane {
            padding: 0px !important;
        }

        .campaign_side_bar {
            cursor: not-allowed !important;
        }
        .response_value{
            padding: 10px 0px 10px 20px;
            cursor: pointer;
            color: black !important;
            border-bottom: 0.5px solid #e0e2e6;
        }
        #showResponse{
            z-index: 99;
            color: black;
            overflow-y: auto;
            border-radius: 5px;
            position: absolute;
            background: #f2efef;
            top: 79%;
            width: 95%;
            left: 20px;
        }
        .from_type_btn.active{
            background-color: rgb(5 187 201) !important;
            border-color: rgb(5 187 201) !important;
            color: white;
        }
        .daterangepicker.show-calendar{
            top: 712px !important;
        }
        .loader-img-section img{
            height: 100%;
            width: 100%;
        }
        .loader-img-section{
            height: 90px;
            width: 90px;
            margin: 0 auto;
        }
        .modal_content{
            width: 100%;
        }
        .send-using-raw-number{
            margin-bottom: 5px !important;
        }
        label:not(.form-check-label):not(.custom-file-label) {
        font-weight: 600 !important;
        font-size: 13px !important;
        }
    </style>

@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12 mx-auto col-sm-12 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">@lang('customer.new_campaign')
                            <i  data-toggle="tooltip" data-placement="right" class="fa fa-question-circle alert-tooltip"
                                title="Make sure before importing contact groups, you run cron jobs"></i>
                        </h2>
                        <a class="btn btn-info float-right"
                           href="{{route('customer.dynamic.campaign')}}">@lang('customer.back')</a>
                    </div>
                    <form method="post" role="form" enctype="multipart/form-data" id="campaignForm" action="{{route('customer.dynamic.campaign.store')}}">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-8">
                                    @include('customer.dynamic_campaign.form')

                                    <div class="form-group text-right">
                                        <button type="button" id="send_schedule" class="btn btn-primary mr-2">@lang('Send Schedule')</button>
                                        <button type="button" class="btn btn-primary submit_campaign_form">@lang('Send Now')</button>
                                    </div>
                                </div>
                                <div class="col-md-4 col-4" id="mobileVersion">
                                    <div class="iphone"
                                         style="background-image: url('{{asset('images/iphone6.png')}}')">
                                        <div class="border">
                                            <div class="responsive-html5-chat">
                                                <form class="chat">
                                                    <span></span>
                                                    <div class="messages">
                                                        <div class="message">
                                                            <div class="myMessage"><p></p>
                                                                <date><b></b></date>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="text" placeholder="Your message" disabled="">
                                                    <input type="submit" value="Send" disabled="">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>


            </div>
            <!-- /.card -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->



    <div class="modal fade" id="importCsvLoaderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="text-center justify-content-center modal_content">
                <div class="loader-img-section">
                    <img style="width: 100%; height: 100%;" src="{{asset('images/loading-screen.gif')}}" alt="">
                </div>
            </div>
        </div>
    </div>

@endsection

@section('extra-scripts')
    <script src="{{asset('plugins/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('plugins/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/moment.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script !src="">
        "use strict";

        let isLoading = false;
        $.validator.addMethod("phone_number", function (value, element) {
            return new RegExp(/^[0-9\-\+]{9,15}$/).test(value);
        }, 'Invalid phone number');

        $(document).on('click','#send_schedule', function (e){

            $('#scheduleModal').modal('show');
            $('input[name=check_schedule]').val('yes')
        });

        $(document).on('change', '.frequency_type', function(e){
            const type=$(this).val();

            if(type=='onetime'){
                $('#section_schedule').removeClass('d-none');
            }else{
                $('#section_schedule').addClass('d-none');
            }
        });


        $(document).on('click','.submit_campaign_form', function (e){
            $('#scheduleModal').modal('hide');
            $('#campaignForm').submit();
        });

        $('#campaignForm').validate({
            rules: {
                title: {
                    required: true,
                },
                'from_number[]': {
                    required: true,
                },
                start_time: {
                    required: true,
                },
                end_time: {
                    required: true,
                },
            },
            messages: {
                title: {
                    required: 'Please enter campaign title',
                },
                'from_number[]': {
                    required: 'Please select an from number',
                },
                start_time: {
                    required: 'Please select campaign start time',
                },
                end_time: {
                    required: 'Please select campaign end time',
                },

            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });

        $('#forward_to_dial_code,#contact_dial_code').select2();

        $("#campaignToNumber").on('change', function (e) {
            let id = $(this).val();
            let contectCount = 0;
            try {
                $.ajax({
                    method: 'get',
                    url: '{{route('customer.group.get.numbers')}}',
                    data: {id: id},
                    success: function (res) {
                        console.log(res);
                        if (res.status == 'success') {
                            const totalContact = res.data.contact;
                            const tcontact = $('#contectCount').text();
                            const messageValue = $('#message').val();
                            $('#contectCount').text(Number(totalContact));


                            if (messageValue.length <= 0) {
                                $('#amountCount').text(totalContact);
                                return;
                            }
                            let totalMessage = parseInt($('#smsCount').text());
                            $('#amountCount').text((totalMessage * tcontact) - tcontact);
                            $('#message').trigger('keyup').trigger('change');
                        }
                    },
                    error: function(error) {
                        const messageValue = $('#message').val();
                        let totalMessage = Math.floor(messageValue.length / 155);
                        $('#amountCount').text(totalMessage);
                        $('#contectCount').text('0');
                        $('#message').trigger('keyup').trigger('change');
                    }
                })
            }catch(error){
                const messageValue = $('#message').val();
                let totalMessage = Math.floor(messageValue.length / 155);
                $('#amountCount').text(totalMessage);
                $('#contectCount').text('0');
            }

        });

        $('.select2-single').select2({
            multiple:false
        });

        $(document).on('click', '.from_type_btn', function(e){
            $('.from_type_btn').removeClass('active');
            $(this).addClass('active');
            const type=$(this).attr('data-type');
            $('.from_selected_type').val(type);

            if(type=='mms' || type=='whatsapp'){
                $('#mms_section').removeClass('d-none');
                $('input[name=message_file]').val('').attr('required', 'required');
            }else{
                $('#mms_section').addClass('d-none');
                $('input[name=message_file]').removeAttr('required').removeClass('is-invalid');
            }

            if(type=='voicecall'){
                $('#voice_section').removeClass('d-none');
            }else{
                $('#voice_section').addClass('d-none');
            }

            // if(type=='mms'){
            //     $('#sender_type').addClass('d-none');
            // }else{
            //     $('#sender_type').removeClass('d-none');
            // }

            $.ajax({
                type:'GET',
                url:'{{route('customer.get.capabilities.numbers')}}',
                data:{
                    type:type
                },

                success:function (res){
                    console.log(res);
                    if(res.status=='success'){
                        let contacts='';
                        let groups='';
                        $.each(res.numbers, function (index, value){
                            contacts+=`<option value='${JSON.stringify(value)}'>${value['number']}</option>`;
                        });
                        $.each(res.groups, function (index, value){
                            groups+=`<option value='${JSON.stringify(value)}'>${value['number']}</option>`;
                        });

                        $('#campaignFromNumber').html(`<optgroup label="Contacts">${contacts}</optgroup><optgroup label="Groups">${groups}</optgroup>`);
                        $('#campaignFromNumber').trigger('change');
                    }
                }
            })
        })
    </script>
          <script>
            $(document).ready(function() {
            const default_sender_id =  $("#default-sender-id").html();
            $('#message').val(default_sender_id+" ");
            });
          </script>
    <script>
        $(function () {
            $('#range_5').ionRangeSlider({
                min: 1,
                max: 500,
                type: 'single',
                step: 1,
                postfix: ' ',
                prettify: false,
                hasGrid: true
            })
        });

        var select2 = $('#campaignFromNumber').select2({
            minimumInputLength: 1,
            tags: true,
            tokenSeparators: [",", " "],
        })
        var select2 = $('#campaignToNumber').select2({
            minimumInputLength: 1,
            tags: true,
            tokenSeparators: [",", " "],
        })



        $(document).on('click', '.select_template', function (e) {
            e.preventDefault();
            const id = $(this).attr('data-id');
            $('#template_active_nav').val(id);
        });
        $('#campaignFromNumber').select2({
            tags: false,
            placeholder: 'Select an from number'
        });
        $('#campaignToNumber').select2({
            tags: false,
            placeholder: 'Select an from group'
        });

        function typeInTextarea(newText, el = document.activeElement) {
            const [start, end] = [el.selectionStart, el.selectionEnd];
            el.setRangeText(newText, start, end, 'select');
        }

        function variable(value){
            let text_to_insert = '{'+value+'}';
            const id = $('#template_active_nav').val();
            if (id) {
                typeInTextarea(text_to_insert, document.getElementById('message'));
            }else {
                typeInTextarea(text_to_insert, document.getElementById('message'));
            }
            $('#message').focus();
            const message = $('#message').val();
            $('#message').trigger('keyup');
            responsiveChatPush('.chat', '', 'me', '23.06.2023 14:30:7', message);
        }

        $('#fromType').on('change', function (e) {
            const type = $(this).val();
            $('.from-number-section').hide();
            $('#' + type + "_section").show();
        });
    </script>

    //Import Template
    <script>
        $(document).on('change', '#import-file', function(e){
            e.preventDefault();
            var fileInput = $('#import-file')[0];
            var imageFile = fileInput.files[0];

            // Create a new FormData object
            var formData = new FormData();
            formData.append('import_file', imageFile);
            $('#importCsvLoaderModal').modal('show');

            $.ajax({
                url: '{{route('customer.import.template')}}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if(res.status=='success'){
                        let html='';
                        let variables='';
                        let variable_texts='';
                        $.each(res.data, function (index, value){
                            html+=`<option ${value=='mobile_no'?'selected':''} value="${value}">${value}</option>`;
                            variables+=`<button type="button" data-name="${value}" onclick="variable('${value}')" class="btn btn-sm btn-primary sms_template_variable mt-2 ml-2">${value}</button>`;
                            if(value !='mobile_no') {
                                variable_texts += '{' + value + '} ';
                            }
                        });


                        $('#campaignToNumber').html(html);
                        $('#campaignToNumber').trigger('change');
                        $('#dynamic_veriable').html(variables);
                        const pre_message_value= $('#message').val();
                        $('#message').val(pre_message_value+variable_texts).trigger('keyup');
                        $('#messageFirstRow').val(res.first_row);
                        $('#messageFirstRow').smsArea({counters: {
                            message: $('#smsCountFirstRow'),
                            character: $('#smsLengthFirstRow')
                        }});
                    }else{
                        toastr.error(res.message,'Copied!', {timeOut: 2200});
                        ('#import-file').val('');
                    }




                    setTimeout(function () {
                        $('#importCsvLoaderModal').modal('hide');
                    }, 100);


                },
            });

            // hideM();
        });

// $(document).ready(function(){
    function hideM(){
        $('#importCsvLoaderModal').modal('hide');
    }
// })

    </script>

    <script>

        function responsiveChatPush(element, sender, origin, date, message) {
            // message=escapeHtml(message);
            let originClass;
            if (origin == 'me') {
                originClass = 'myMessage';
            } else {
                originClass = 'fromThem';
            }
            $(element + ' .messages').html('<div class="message"><div class="' + originClass + '"><p>' + message + '</p><date><b>' + sender + '</b> ' + date + '</date></div></div>');
        }

        function responsiveChat(element) {
            $(element).html('<div class="chat"><span></span><div class="messages" ></div><input type="text" placeholder="Your message" disabled><input type="submit" value="Send" disabled></div>');
        }

        responsiveChat('.responsive-html5-chat');

    </script>
    <script>

        $(document).on('click', '.response_value', function (e){

            let value = $(this).attr('data-title');
            $("#message").val(value);
            $('#message').trigger('keyup');
            $('#message').trigger('change');
        });




        $(document).on('keyup', '#message', function (e) {
            const data = $('#message').val();
            let message = data.replace(/\n/g,"<br />");
            responsiveChatPush('.chat', '', 'me', '23.06.2023 14:30:7', message);
            setTimeout(()=>{
                checkCharecter();
            },100)
        });

        function checkCharecter(){
            let totalContact = $('#contectCount').text();
            const sms = $('#smsCount').text();
            $('#amountCount').text(totalContact * sms);
        }

        $('#switch-input').change(function () {
            if ($(this).prop("checked")) {
                $('#schedule-time').removeClass('d-none');
            }else {
                $('#schedule-time').addClass('d-none');
            }
            // not checked
        });
        $('#response_value_disabled').on('click', false);

        $('.datetimepicker').daterangepicker({
            autoUpdateInput: true,
            singleDatePicker: true,
            timePicker: false,
            minDate:new Date(),
            locale: {
                format: 'MM/DD/YYYY'
            }
        });

        $('#start_time').on('change',function (e) {
            $('#end_time').attr('min',$(this).val());
        })
    </script>


    <script>
        let plain_sms = {{intval($plain_sms,'0')}};
        (function($){
            $.fn.smsArea = function(options){

                //Generate Ascii Character Array
                var maxCh = 1000;
                var minCh = 0;
                var arrAscii = [];
                for(minCh =1;  minCh < maxCh; minCh++){
                    arrAscii.push(minCh * 155);
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
                            smsCount = -plain_sms,
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
                        }

                        if(s.cut) e.val(text.substring(0, cutStrLength));
                        smsCount == -1 && (smsCount = s.maxSmsNum, charsLeft = 0);

                        s.counters.message.html(smsCount * plain_sms);
                        s.counters.character.html(charsLeft);

                    }, s.interval);
                }).keyup();

            }}(jQuery));


        //Start
        $(function(){
            $('#message').smsArea();

        })

    </script>
    <script>
        $(document).on('change', '.senderType', function (e){
            const type=$(this).val();
            const from_selected_type = $('.from_selected_type').val();
            if(!type){
                toastr.success('Select valid type','Copied!', {timeOut: 2200});
                return;
            }

            $.ajax({
                type:'GET',
                url:'{{route('customer.all.senders')}}',
                data:{
                    type:type,from_selected_type:from_selected_type
                },

                success: function(res){
                    if(res.status=='success'){
                        let numbers = '';
                        if(res.number.length > 0) {
                            $.each(res.number, function (index, value) {
                                numbers+= `<option value='${JSON.stringify(value)}'>${value['number']}</option>`;
                            });
                        }
                        let groups = '';
                        if(res.groups.length > 0) {
                            $.each(res.groups, function (index, value) {
                                groups+= `<option value='${JSON.stringify(value)}'>${value['value']}</option>`;
                            });
                        }
                        if(type=='number'){
                            $('#campaignFromNumber').html(`<optgroup label="From Number">${numbers}</optgroup><optgroup label="From Number Group">${groups}</optgroup>`);
                        }else{
                            $('#campaignFromNumber').html(`<optgroup label="From Sender">${numbers}</optgroup><optgroup label="From Sender Group">${groups}</optgroup>`);
                        }
                        // $('#campaignFromNumber').html(`<optgroup label="Form Numbers">${numbers}</optgroup><optgroup label="Form Groups">${groups}</optgroup>`);

                    }
                }
            })
        })
    </script>
@endsection


