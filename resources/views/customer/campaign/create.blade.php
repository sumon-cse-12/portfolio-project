@extends('layouts.customer')

@section('title','Campaign')

@section('extra-css')
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/ion-rangeslider/css/ion.rangeSlider.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('css/msg_overview.css')}}">
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

        .response_value {
            padding: 10px 0px 10px 20px;
            cursor: pointer;
            color: black !important;
            border-bottom: 0.5px solid #e0e2e6;
        }

        #showResponse {
            z-index: 99;
            color: black;
            overflow-y: auto;
            border-radius: 5px;
            position: absolute;
            background: #f2efef;
            top: 87%;
            width: 95%;
            left: 20px;
        }

        .from_type_btn.active {
            background-color: rgb(5 187 201) !important;
            border-color: rgb(5 187 201) !important;
            color: white;
        }

        .daterangepicker.show-calendar {
            /* bottom: 0px !important; */
            top: 880px !important;
        }

        .send-using-raw-number {
            margin-bottom: 5px !important;
        }

        label:not(.form-check-label):not(.custom-file-label) {
            font-weight: 600 !important;
            font-size: 13px !important;
        }
        #downloadDuplicates{
            display: none;
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
                            <i data-toggle="tooltip" data-placement="right" class="fa fa-question-circle alert-tooltip"
                               title="Make sure before importing contact groups, you run cron jobs"></i>
                        </h2>
                        <a class="btn btn-info float-right"
                           href="{{route('customer.campaign.index')}}">@lang('customer.back')</a>
                    </div>
                    <form method="post" role="form" enctype="multipart/form-data" id="campaignForm"
                          action="{{route('customer.campaign.store')}}">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-8">
                                    @include('customer.campaign.form')

                                    <div class="form-group text-right">
                                        <button type="button" id="send_schedule"
                                                class="btn btn-primary mr-2">@lang('Send Schedule')</button>
                                        <button type="button"
                                                class="btn btn-primary submit_campaign_form">@lang('Send Now')</button>
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
                                                                <date><b></b> 23.06.2023 14:30:7</date>
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

    <!-- Modal -->

@endsection

@section('extra-scripts')
    <script src="{{asset('plugins/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('plugins/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/moment.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>
    <script !src="">
        "use strict";

        let isLoading = false;
        $.validator.addMethod("phone_number", function (value, element) {
            return new RegExp(/^[0-9\-\+]{9,15}$/).test(value);
        }, 'Invalid phone number');

        $(document).on('click', '#send_schedule', function (e) {

            $('#scheduleModal').modal('show');
            $('input[name=check_schedule]').val('yes')
        });

        $(document).on('click', '.submit_campaign_form', function (e) {
            $('#scheduleModal').modal('hide');
            $('#campaignForm').submit();
        });

        $(document).on('change', '.frequency_type', function(e){
            const type=$(this).val();

            if(type=='onetime'){
                $('#section_schedule').removeClass('d-none');
            }else{
                $('#section_schedule').addClass('d-none');
            }
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
                        if (res.status == 'success') {
                            const totalContact = res.data.totalNumber;

                            $('#contectCount').text(Number(totalContact));


                            let totalMessage = parseInt($('#smsCount').text());
                            $('#amountCount').text(totalMessage * totalContact);
                            $('#message').trigger('keyup').trigger('change');
                        }
                    },
                    error: function (error) {
                        const messageValue = $('#message').val();
                        let totalMessage = Math.floor(messageValue.length / 155);
                        $('#amountCount').text(totalMessage);
                        $('#contectCount').text('0');
                        $('#message').trigger('keyup').trigger('change');
                    }
                })
            } catch (error) {
                const messageValue = $('#smsCount').text();
                $('#amountCount').text(messageValue);
                $('#contectCount').text('0');
            }

        });

        $('.select2-single').select2({
            multiple: false
        });

        $(document).on('click', '.from_type_btn', function (e) {
            $('.from_type_btn').removeClass('active');
            $(this).addClass('active');
            const type = $(this).attr('data-type');
            $('.from_selected_type').val(type);

            if (type == 'mms' || type == 'whatsapp') {
                $('#mms_section').removeClass('d-none');
                $('input[name=message_file]').val('').attr('required', 'required');
            } else {
                $('#mms_section').addClass('d-none');
                $('input[name=message_file]').removeAttr('required').removeClass('is-invalid');
            }

            if (type == 'voicecall') {
                $('#voice_section').removeClass('d-none');
            } else {
                $('#voice_section').addClass('d-none');
            }

            // if(type=='mms'){
            //     $('#sender_type').addClass('d-none');
            // }else{
            //     $('#sender_type').removeClass('d-none');
            // }

            $.ajax({
                type: 'GET',
                url: '{{route('customer.get.capabilities.numbers')}}',
                data: {
                    type: type
                },

                success: function (res) {
                    if (res.status == 'success') {
                        let contacts = '';
                        let groups = '';
                        $.each(res.numbers, function (index, value) {
                            contacts += `<option value='${JSON.stringify(value)}'>${value['number']}</option>`;
                        });
                        $.each(res.groups, function (index, value) {
                            groups += `<option value='${JSON.stringify(value)}'>${value['number']}</option>`;
                        });

                        $('#campaignFromNumber').html(`<optgroup label="From Number">${contacts}</optgroup><optgroup label="From Group">${groups}</optgroup>`);
                        $('#campaignFromNumber').trigger('change');
                    }
                }
            })
        })
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

        $('#template').select2({
            placeholder: "Select an template",
            allowClear: true
        }).on('select2:select', function (e) {
            let data = e.params.data;
            const name = $(data.element).attr('data-name');
            const body = $(data.element).attr('data-body');
            const id = $(data.element).attr('data-id');


            $('#custom_tabs_one_tabContent').append(`
                     <div class="tab-pane fade " id="custom_tabs_one_home_tab_${id}" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                            <textarea name="template_body[]" class="form-control" id="sms_template_body_${id}" cols="4"  rows="10">${body}</textarea>
                    </div>`);
            responsiveChatPush('.chat', '', 'me', '23.06.2023 14:30:7', body);

            $('#custom_tabs_one_tab').append(`
                        <li class="nav-item">
                            <a class="nav-link select_template" id="nav_tab_${id}"  data-toggle="pill" href="#custom_tabs_one_home_tab_${id}" role="tab" data-id="${id}" aria-controls="custom-tabs-one-home" aria-selected="true">${name}</a>
                        </li>`);
            $('.select_template').last().trigger('click');
        }).on('select2:unselect', function (e) {
            let data = e.params.data;
            const id = $(data.element).attr('data-id');

            $('#custom_tabs_one_home_tab_' + id).remove();
            $('#nav_tab_' + id).remove();
        });

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

        $('.sms_template_variable').on('click', function (e) {
            let text_to_insert = $(this).attr('data-name');
            const id = $('#template_active_nav').val();
            if (id) {
                typeInTextarea(text_to_insert, document.getElementById('message'));
            } else {
                typeInTextarea(text_to_insert, document.getElementById('message'));
            }
            $('#message').focus();
            const message = $('#message').val();
            $('#message').trigger('keyup');
            responsiveChatPush('.chat', '', 'me', '23.06.2023 14:30:7', message);
        });


        $('#fromType').on('change', function (e) {
            const type = $(this).val();
            $('.from-number-section').hide();
            $('#' + type + "_section").show();
        });
    </script>
    <script>
        $(document).ready(function () {
            const default_sender_id = $("#default-sender-id-name").html();
            $('#message').val(default_sender_id + " ");
        });
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

        $(document).on('click', '.response_value', function (e) {

            let value = $(this).attr('data-title');
            $("#message").val(value);
            $('#showResponse').addClass('d-none');
            $('#message').trigger('keyup');
            $('#message').trigger('change');
        });


        $(document).on('click', '#message', function (e) {
            $('#showResponse').removeClass('d-none');
        });


        $(document).on('keyup', '#message', function (e) {
            $('#showResponse').addClass('d-none');
            const data = $('#message').val();
            let message = data.replace(/\n/g, "<br />");
            responsiveChatPush('.chat', '', 'me', '23.06.2023 14:30:7', message);
            setTimeout(() => {
                checkCharecter();
            }, 100);
            handleSmsCounter();
        });

        function handleSmsCounter(){
            const to_number_type=$('input[name="recipient_type"]:checked').val();

            if(to_number_type=='paste_number') {
                const total_numbers = $('#paste_contactCount').text();
                const duplicate_numbers = $('.show_duplicate_numbers').text();
                const sms_counter=$('#smsCount').text();

                let total_unique_numbers=total_numbers - duplicate_numbers;
                if(total_unique_numbers < 0){
                    total_unique_numbers=0;
                }

                let total_sms=0;
                total_sms=sms_counter * total_unique_numbers;

                $('#amountCount_p').text(total_sms);

            }else{
                var selectedOptions = $('#campaignToNumber').select2('data');
                let counter=0;
                // Iterate through the selected options and retrieve attributes
                for (var i = 0; i < selectedOptions.length; i++) {
                    var option = selectedOptions[i].element;
                    var value = option.value;
                    var attr1 = $(option).data('count');

                    counter=counter+attr1;
                }

                const sms_counter=$('#smsCount').text();
                if(counter <=0){
                    counter=0;
                }

                $('#amountCount_g').text(sms_counter * counter);
                $('#contectCount').text(counter)

            }
        }
        function checkCharecter() {
            let totalContact = $('#contectCount').text();
            const sms = $('#smsCount').text();
            $('#amountCount').text(totalContact * sms);
        }

        $('#switch-input').change(function () {
            if ($(this).prop("checked")) {
                $('#schedule-time').removeClass('d-none');
            } else {
                $('#schedule-time').addClass('d-none');
            }
            // not checked
        });
        $('#response_value_disabled').on('click', false);
        $(document).mouseup(function (e) {
            var container = $("#showResponse");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                $('#showResponse').addClass('d-none');
            }
        });
        $('.datetimepicker').daterangepicker({
            autoUpdateInput: true,
            singleDatePicker: true,
            timePicker: false,
            minDate: new Date(),
            locale: {
                format: 'MM/DD/YYYY'
            }
        });

        $('#start_time').on('change', function (e) {
            $('#end_time').attr('min', $(this).val());
        })
    </script>


    <script>
        let plain_sms = {{intval($plain_sms,'0')}};

        (function ($) {
            $.fn.smsArea = function (options) {

                //Generate Ascii Character Array
                var maxCh = 1000;
                var minCh = 0;
                var arrAscii = [];
                for (minCh = 1; minCh < maxCh; minCh++) {
                    arrAscii.push(minCh * 155);
                }
                //End

                //Generate Unicode Character Array
                var unMaxCh = 1000;
                var unMinCh = 0;
                var arrUnicode = [];
                for (unMinCh = 1; unMinCh < unMaxCh; unMinCh++) {
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


                e.keyup(function () {

                    clearTimeout(this.timeout);
                    this.timeout = setTimeout(function () {

                        var
                            smsType,
                            smsLength = 0,
                            smsCount = -plain_sms,
                            charsLeft = 0,
                            text = e.val(),
                            isUnicode = false;

                        for (var charPos = 0; charPos < text.length; charPos++) {
                            switch (text[charPos]) {
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
                            if (text.charCodeAt(charPos) > 127 && text[charPos] != "€")
                                isUnicode = true;
                        }

                        if (isUnicode) smsType = s.lengths.unicode;
                        else smsType = s.lengths.ascii;

                        for (var sCount = 0; sCount < s.maxSmsNum; sCount++) {

                            cutStrLength = smsType[sCount];
                            if (smsLength <= smsType[sCount]) {

                                smsCount = sCount + 1;
                                charsLeft = smsType[sCount] - smsLength;
                                break
                            }
                        }

                        if (s.cut) e.val(text.substring(0, cutStrLength));
                        smsCount == -1 && (smsCount = s.maxSmsNum, charsLeft = 0);

                        s.counters.message.html(smsCount * plain_sms);
                        s.counters.character.html(charsLeft);

                    }, s.interval);
                }).keyup();

            }
        }(jQuery));


        //Start
        $(function () {
            $('#message').smsArea();

        })

    </script>
    <script>
        $(document).on('change', '.senderType', function (e) {
            const type = $(this).val();
            const from_selected_type = $('.from_selected_type').val();
            if (!type) {
                toastr.success('Select valid type', 'Copied!', {timeOut: 2200});
                return;
            }

            $.ajax({
                type: 'GET',
                url: '{{route('customer.all.senders')}}',
                data: {
                    type: type, from_selected_type: from_selected_type
                },

                success: function (res) {
                    if (res.status == 'success') {
                        let numbers = '';
                        if (res.number.length > 0) {
                            $.each(res.number, function (index, value) {
                                numbers += `<option value='${JSON.stringify(value)}'>${value['number']}</option>`;
                            });
                        }
                        let groups = '';
                        if (res.groups.length > 0) {
                            $.each(res.groups, function (index, value) {
                                groups += `<option value='${JSON.stringify(value)}'>${value['value']}</option>`;
                            });
                        }
                        if (type == 'number') {
                            $('#campaignFromNumber').html(`<optgroup label="From Number">${numbers}</optgroup><optgroup label="From Number Group">${groups}</optgroup>`);
                        } else {
                            $('#campaignFromNumber').html(`<optgroup label="From Sender">${numbers}</optgroup><optgroup label="From Sender Group">${groups}</optgroup>`);
                        }

                    }
                }
            })
        });


        $('#message').on('keyup', function (e) {

            const total_contact = $('#contectCount').text();
            const total_sms = $('#smsCount').text();

            $('#showResponse').addClass('d-none');
            $('#amountCount').text(total_contact * total_sms);
        });

        $(document).on('change', '.recipient_type', function (e) {
            const type = $(this).val();

            $('.recipient-section').hide();
            $('.recipient_' + type).show();

        });

        $(document).ready(function () {

            $('#paste_number_field').on('keyup input', function (e) {
                const numbers = $(this).val();
                let totalNumbers = 0;
                let totalCountNumbers = 0;
                let all_past_number = numbers.replace(/\s+/g, ',');
                totalNumbers = all_past_number.split(',');

                $.each(totalNumbers, function (index, value) {
                    if (value) {
                        totalCountNumbers = totalCountNumbers + 1;
                    }
                });

                var textareaContent = $('#paste_number_field').val();
                var numbersArray = textareaContent.split('\n').map(function (number) {
                    if(number) {
                        return number.trim();
                    }
                });

                var duplicates = findDuplicates(numbersArray);
                var invalidNumbers = findInvalidNumbers(numbersArray);

                console.log(invalidNumbers,duplicates);
                if (duplicates.length <= 0) {
                    duplicates = 0;
                } else {
                    duplicates = duplicates.length;
                }
                if (invalidNumbers.length <= 0) {
                    invalidNumbers = 0;
                } else {
                    invalidNumbers = invalidNumbers.length;
                }



                let unwanted_number=duplicates + invalidNumbers;

                let sms_count = $('#smsCount').text();
                let numbers_counter = totalCountNumbers - unwanted_number;

                if (numbers_counter < 0) {
                    numbers_counter = 0;
                }

                $('.show_duplicate_numbers').text(unwanted_number);
                $('.show_invalid_numbers').text(invalidNumbers);
                $('#paste_contactCount').text(totalCountNumbers);

                handleSmsCounter();
            });

            $('.download_btn').on('click', function (e) {

                var textareaContent = $('#paste_number_field').val();
                var numbersArray = textareaContent.split('\n').map(function (number) {
                    return number.trim();
                });

                var duplicates = findDuplicates(numbersArray);
                var invalidNumbers = findInvalidNumbers(numbersArray);

                // Combine duplicates and invalid numbers
                var combinedData = duplicates.concat(invalidNumbers);

                if (combinedData.length <= 0) {
                    return;
                }

                // Create XLSX sheet with combined data
                var sheet = XLSX.utils.json_to_sheet(combinedData.map(function (item) {
                    return {'Numbers': item};
                }));

                // Create a blob for the XLSX file
                var blob = xlsxWrite(sheet, {
                    bookType: 'xlsx',
                    mimeType: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });

                // Step 5: Provide a way to download the file
                downloadFile(blob, 'duplicates_and_invalid_numbers.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            });

            function findDuplicates(array) {
                var sortedArray = array.slice().sort();
                var duplicates = [];
                for (var i = 0; i < sortedArray.length - 1; i++) {
                    if (sortedArray[i + 1] === sortedArray[i]) {
                        if(sortedArray[i]) {
                            duplicates.push(sortedArray[i]);
                        }
                    }
                }
                return duplicates;
            }

            function findInvalidNumbers(array) {
                let push_invalid=[];
                jQuery.each(array, function(index, value){
                    if(value && (String(value).length < 10 || String(value).length > 12)){
                        push_invalid.push(value);
                    }
                });
                return push_invalid;
            }


            function downloadFile(content, filename, mimeType) {
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(content);
                link.download = filename;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }

            function xlsxWrite(sheet, options) {
                var workbook = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(workbook, sheet, 'Duplicates');

                // Convert the workbook to a binary string
                var arrayBuffer = XLSX.write(workbook, {
                    bookType: 'xlsx',
                    mimeType: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    bookSST: false,
                    type: 'binary'
                });

                // Convert the binary string to a Blob
                var blob = new Blob([s2ab(arrayBuffer)], {type: options.mimeType});
                return blob;
            }

            function s2ab(s) {
                var buf = new ArrayBuffer(s.length);
                var view = new Uint8Array(buf);
                for (var i = 0; i !== s.length; ++i) {
                    view[i] = s.charCodeAt(i) & 0xFF;
                }
                return buf;
            }

        });

        $('#checkDuplicate').on('click',function(e){
            e.preventDefault();
            let file = $('[name="import_xls"]')[0].files[0]
            let fd = new FormData();
            fd.append('import_xls', file);
            fd.append('_token', '{{csrf_token()}}');
            $.ajax({
                    url: '{{route('customer.campaign.check.duplicate_numbers')}}',
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    data: fd,
                    success: function (data) {
                        if(data.count && data.file){
                            $('#downloadDuplicates').attr('data-id',data.file).css('display','block');
                            $('#duplicate_count').text(data.count);

                        }
                    },
                    error: function (jqxhr, status, msg) {
                        console.log(msg);
                    }
                });
        });

        $('#downloadDuplicates').on('click',function(e){
            e.preventDefault();
            const dataId=$(this).attr('data-id');

            var anchor = document.createElement('a');
            anchor.href = '{{route('customer.campaign.check.duplicate_numbers')}}'+"?id="+dataId;
            anchor.target="_blank";
            anchor.click();
        })

        // $(document).ready(function () {
        //
        //     $('#paste_number_field').on('keyup or paste', function (e) {
        //         const numbers = $(this).val();
        //         let totalNumbers = 0;
        //         let totalCountNumbers = 0;
        //         let all_past_number = numbers.replace(/\s+/g, ',');
        //         totalNumbers = all_past_number.split(',');
        //         $.each(totalNumbers, function (index, value) {
        //             if (value) {
        //                 totalCountNumbers = totalCountNumbers + 1;
        //             }
        //         });
        //         var textareaContent = $('#paste_number_field').val();
        //         var numbersArray = textareaContent.split('\n').map(function (number) {
        //             return number.trim();
        //         });
        //         var duplicates = findDuplicates(numbersArray);
        //         if (duplicates.length <= 0) {
        //             duplicates = 0;
        //         } else {
        //             duplicates = duplicates.length;
        //         }
        //
        //         let sms_count = $('#smsCount').text();
        //         let numbers_counter = totalCountNumbers - duplicates;
        //         if (numbers_counter < 0) {
        //             numbers_counter = 0;
        //         }
        //
        //         // $('#smsCount').text(sms_count * numbers_counter)
        //         $('.show_duplicate_numbers').text(duplicates)
        //         $('#paste_contactCount').text(totalCountNumbers);
        //
        //         handleSmsCounter();
        //     });
        //
        //
        //     $('.download_btn').on('click', function (e) {
        //
        //         var textareaContent = $('#paste_number_field').val();
        //         var numbersArray = textareaContent.split('\n').map(function (number) {
        //             return number.trim();
        //         });
        //         var duplicates = findDuplicates(numbersArray);
        //         var csvContent = Papa.unparse({
        //             fields: ['Duplicate Numbers'],
        //             data: duplicates.map(function (duplicate) {
        //                 return [duplicate];
        //             })
        //         });
        //         if (duplicates.length <= 0) {
        //             return;
        //         }
        //
        //         var sheet = XLSX.utils.json_to_sheet(duplicates.map(function (duplicate) {
        //             return {'Numbers': duplicate};
        //         }));
        //
        //         // Create a blob for the XLSX file
        //         var blob = xlsxWrite(sheet, {
        //             bookType: 'xlsx',
        //             mimeType: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        //         });
        //
        //         // Step 5: Provide a way to download the file
        //         downloadFile(blob, 'duplicate-numbers.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        //     })
        //
        //     function findDuplicates(array) {
        //         var sortedArray = array.slice().sort();
        //         var duplicates = [];
        //         for (var i = 0; i < sortedArray.length - 1; i++) {
        //             if (sortedArray[i + 1] === sortedArray[i]) {
        //                 console.log(sortedArray[i]);
        //                 duplicates.push(sortedArray[i]);
        //             }
        //         }
        //         return duplicates;
        //     }
        //
        //     function downloadFile(content, filename, mimeType) {
        //         var link = document.createElement('a');
        //         link.href = window.URL.createObjectURL(content);
        //         link.download = filename;
        //         document.body.appendChild(link);
        //         link.click();
        //         document.body.removeChild(link);
        //     }
        //
        //     function xlsxWrite(sheet, options) {
        //         var workbook = XLSX.utils.book_new();
        //         XLSX.utils.book_append_sheet(workbook, sheet, 'Duplicates');
        //
        //         // Convert the workbook to a binary string
        //         var arrayBuffer = XLSX.write(workbook, {
        //             bookType: 'xlsx',
        //             mimeType: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        //             bookSST: false,
        //             type: 'binary'
        //         });
        //
        //         // Convert the binary string to a Blob
        //         var blob = new Blob([s2ab(arrayBuffer)], {type: options.mimeType});
        //         return blob;
        //     }
        //
        //     function s2ab(s) {
        //         var buf = new ArrayBuffer(s.length);
        //         var view = new Uint8Array(buf);
        //         for (var i = 0; i !== s.length; ++i) {
        //             view[i] = s.charCodeAt(i) & 0xFF;
        //         }
        //         return buf;
        //     }
        // });
    </script>
@endsection

