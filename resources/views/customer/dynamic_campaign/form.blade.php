
<div class="campaign_section" id="bulk_send_section">
    <input type="hidden" name="dynamic_campaign" value="yes">
    <div class="card">
        <div class="card-body">
            <div class="form-group mt-3">
                <label for="">Campaign Title</label>
                <input type="text" class="form-control" value="{{old('title')??(isset($campaign)?$campaign->title:'')}}" required placeholder="Enter Campaign Title" name="title">
            </div>

            <div class="form-group pb-1">
                <label class="mb-3 w-100" for="">{{trans('customer.select_sending_type')}}</label>
                <button type="button" class="btn btn-default from_type_btn active" data-type="sms">SMS</button>
                <button type="button" class="btn btn-default from_type_btn d-none" data-type="mms">MMS</button>
                <button type="button" class="btn btn-default from_type_btn" data-type="whatsapp">Whatsapp SMS</button>
                <button type="button" class="btn btn-default from_type_btn d-none" data-type="voicecall">Voice SMS</button>
            </div>
            <input type="hidden" class="from_selected_type" name="from_selected_type" value="sms">

            <!--SMS !-->
            <div class="form-group from-section" id="sms_section">
                <div class="form-group" id="sender_type">
                    <label for="">Select Sender Type</label>
                    <select name="type" class="form-control senderType">
                        <option value="sender_id">SenderID</option>
                        <option value="number">Number</option>
                    </select>
                </div>

                <div class="form-group from-number-section" id="phone_number_section">
                    <label for="">Select Senders</label> <span class="d-none" id="default-sender-id">{{isset($user_sender_ids[0]) && isset($user_sender_ids[0]['number'])?$user_sender_ids[0]['number']:''}}</span>
                    <select name="from_number[]" id="campaignFromNumber" class="select2 compose-select"
                            multiple="multiple"
                            data-placeholder="{{trans('Select Senders')}}:">

                        @isset($user_sender_ids)
                            <optgroup label="From Senders">
                                @foreach($user_sender_ids as $key=>$number)
                                    <option {{$key==0?'selected':''}} value="{{json_encode($number)}}">{{$number['number']}}</option>
                                @endforeach
                            </optgroup>
                        @endisset

                        @isset($senderid_from_groups)
                            <optgroup label="From Sender Group">
                                @foreach($senderid_from_groups as $number)
                                    <option value="{{json_encode($number)}}">{{$number['number']}}</option>
                                @endforeach
                            </optgroup>
                        @endisset
                    </select>
                </div>
            </div>


            <div class="form-group">
                <label for="">Import Dynamic Template</label>
                <input type="file" name="import_file" class="form-control p-1" id="import-file">
            </div>


            <div class="form-group to-number-section pb-3" id="number_section">
                <label for="">Select To <span class="dynamic-c-select-to">(Choose a column which contain contact numbers)</span></label>
                <select name="to_number_key" id="campaignToNumber" class="select2-single compose-select" data-value=""
                        data-placeholder="{{trans('customer.select_to_field')}}:">

                </select>
                <small class="float-right">To Number Format Should Be Like This: 60585000000</small>
                <div class="text-right d-none">
                    Time zone (<b class="sms-text">{{config('app.timezone')}}</b>) Total Contacts (<b class="sms-text" id="contectCount">0</b>) || Total Message ({{isset(json_decode(get_settings('local_setting'))->currency_symbol)?json_decode(get_settings('local_setting'))->currency_symbol:''}}<b class="sms-text" id="amountCount">0</b>)
                </div>
            </div>

            <div class="form-group d-none" id="mms_section">
                <label for="">{{trans('Select MMS File')}}</label>
                <input type="file" class="form-control p-1" multiple name="message_files[]">
            </div>

            <div class="row d-none" id="voice_section">
                <div class="col-md-6">
                    <label for="">{{trans('Select Language')}}</label>
                    <select name="language" class="form-control select2-single" >
                        @foreach(voice_sms_lang() as $lang)
                            <option value="{{$lang}}">{{strtoupper($lang)}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="">Select Gender</label>
                    <select name="voice_type" class="form-control">
                        <option value="men">Men</option>
                        <option value="women">Women</option>
                    </select>
                </div>

            </div>

            <div class="form-group">
                <label for="messageFirstRow">1st Row Message</label>
                <textarea disabled class="disable form-control" autocomplete="off" placeholder="Type your message here..." cols="2" rows="2" id="messageFirstRow"></textarea>
                <div class="text-right">
                    <b id="smsCountFirstRow" class="text-primary"></b> SMS (<b id="smsLengthFirstRow" class="text-primary"></b>) Characters left
                </div>
            </div>

            <div class="form-group">
                <label for="">Message Body</label>
                <textarea name="template_body" class="type_message form-control" autocomplete="off" type="text" placeholder="Type your message here..." cols="2" rows="2" id="message"></textarea>
                <div class="text-right">
                    <b id="smsCount" class="text-primary"></b> SMS (<b id="smsLength" class="text-primary"></b>) Characters left
                </div>
                <div id="dynamic_veriable">

                </div>
            </div>


            <input class="switch-input" name="check_schedule" type="hidden" id="switch-input">


            <div class="row mt-3 d-none">
                <div class="col-sm-8">
                    <div class="form-group">
                        <label for="">Phone Numbers</label>
                        <textarea readonly="readonly" name="to_number" id="phone_numbers" cols="4" rows="12" class="form-control">{{old('to_number')?old('to_number'):''}}</textarea>

                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="">Groups</label>
                        <div>
                            @foreach($groups as $group)
                                <button type="button" data-id="{{$group->id}}"
                                        class="btn btn-primary group btn-sm mt-2">{{$group->name."(".$group->contacts_count.")"}}</button>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="template_active_nav">
<input type="hidden" id="totalmessage">

<div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Schedule Date Time</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">Choose SMS Frequency</label>
                    </div>
                    <div class="form-group">
                        <input type="radio" name="frequency_type" checked value="onetime" class="frequency_type" id="sendInstant">
                        <label for="sendInstant">Schedule</label>

                        <input type="radio" name="frequency_type" value="daily" class="frequency_type ml-3" id="sendDaily">
                        <label for="sendDaily">Daily</label>

                        <input type="radio" name="frequency_type" value="weekly" class="frequency_type ml-3" id="sendWeekly">
                        <label for="sendWeekly">Weekly</label>

                        <input type="radio" name="frequency_type" value="monthly" class="frequency_type ml-3" id="sendMonthly">
                        <label for="sendMonthly">Monthly</label>

                    </div>
                </div>

                <div class="row" id="section_schedule">
                    <div class="form-group col-sm-6 col-6">
                        <label for="">Start Date</label>
                        <input name="start_date" value="{{old('start_date')??(isset($campaign)??$campaign->start_date)}}" type='text' class="form-control datetimepicker date_range"/>
                    </div>
                    <div class="form-group col-sm-6 col-6">
                        <label for="">End Date</label>
                        <input name="end_date" value="{{old('end_date')??(isset($campaign)??$campaign->end_date)}}" type='text' class="form-control datetimepicker date_range"/>
                    </div>

                    <div class="form-group col-sm-6 col-6 mt-2">
                        <label for="start_time">Start Time</label>
                        <input id="start_time" name="start_time" value="{{old('start_time')??(isset($campaign)?$campaign->start_time:'')}}" type='time' class="form-control "/>
                    </div>
                    <div class="form-group col-sm-6 col-6 mt-2">
                        <label for="end_time">End Time</label>
                        <input id="end_time" max="23:59" name="end_time" value="{{old('end_time')??(isset($campaign)?$campaign->end_time:'')}}" type='time' class="form-control"/>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button"  class="btn btn-primary submit_campaign_form">Confirm</button>
            </div>
        </div>
    </div>
</div>


