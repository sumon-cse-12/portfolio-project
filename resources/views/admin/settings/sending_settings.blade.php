<div class="form-group">
    <label for="logo">Daily Message Send Limit</label>
    <div class="input-group">
        <input type="number" name="send_limit" value="{{isset(json_decode(get_settings('daily_send_limit'))->send_limit)?json_decode(get_settings('daily_send_limit'))->send_limit:2800}}" class="form-control">
    </div>
</div>


<div class="form-group">
    <label for="logo">Message Limit</label>
    <div class="input-group">
        <input type="number" name="message_limit" placeholder="Enter Message Limit" value="{{isset(json_decode(get_settings('minute_send_limit'))->message_limit)?json_decode(get_settings('minute_send_limit'))->message_limit:0}}" class="form-control message_limit">
    </div>
</div>
<div class="form-group">
    <label for="logo">Minutes</label>
    <div class="input-group">
        <input type="number" name="minute_limit" placeholder="Enter Minutes" value="{{isset(json_decode(get_settings('minute_send_limit'))->minute_limit)?json_decode(get_settings('minute_send_limit'))->minute_limit:0}}" class="form-control minutes">
    </div>
</div>
<span class="text-danger">Per <span id="minutes">{{isset(json_decode(get_settings('minute_send_limit'))->minute_limit)?json_decode(get_settings('minute_send_limit'))->minute_limit:0}}</span>
    Minutes message limit will be <span id="message_limit">{{isset(json_decode(get_settings('minute_send_limit'))->message_limit)?json_decode(get_settings('minute_send_limit'))->message_limit:0}}</span></span>


@php $sendingSetting = json_decode(get_settings('sending_setting'));  @endphp

<div class="row mt-2">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="logo">From Time</label>
            <div class="input-group">
                <input type="time" name="start_time" placeholder="Enter start time" value="{{isset($sendingSetting) && isset($sendingSetting->start_time)?$sendingSetting->start_time:''}}" class="form-control">
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="logo">To Time</label>
            <div class="input-group">
                <input type="time" name="end_time" placeholder="Enter end time" value="{{isset($sendingSetting) && isset($sendingSetting->end_time)?$sendingSetting->end_time:''}}" class="form-control">
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group">
            <label for="logo">Off day</label>
            <select name="offday[]" multiple class="form-control select2" id="offDay">
                <option  value="saturday">Saturday</option>
                <option  value="sunday">Sunday</option>
                <option  value="monday">Monday</option>
                <option  value="tuesday">Tuesday</option>
                <option  value="wednesday">Wednesday</option>
                <option  value="thursday">Thursday</option>
                <option  value="friday">Friday</option>
            </select>
        </div>
    </div>
</div>



