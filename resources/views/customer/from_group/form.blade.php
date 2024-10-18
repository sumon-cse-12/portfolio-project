<div class="form-group">
    <label for="name">@lang('customer.group_name') *</label>
    <input value="{{isset($group) && $group->name?$group->name:old('name')}}" type="text" name="name"
           class="form-control" id="name"
           placeholder="@lang('customer.group_name')">
</div>
<div class="form-group">
    <label for="">Sender Type</label>
    <select name="type" class="form-control" id="senderType">
        <option {{isset($group) && $group->type=='number'?'selected':''}} value="number">SMS</option>
        <option {{isset($group) && $group->type=='sender_id'?'selected':''}} value="sender_id">SenderID</option>
        <option {{isset($group) && $group->type=='whatsapp'?'selected':''}} value="whatsapp">Whatsapp</option>
        {{-- <option {{isset($group) && $group->type=='voice'?'selected':''}} value="voice">Voice</option>
        <option {{isset($group) && $group->type=='number'?'selected':''}} value="mms">MMS</option> --}}
    </select>
</div>

<div class="form-group numbers-section" id="numbers_section">
    <label for="">{{trans('customer.select_from_numbers')}}</label>
    <select name="from_numbers[]" class="form-control select2">
        @if(isset($numbers['sms_numbers']))
            @foreach($numbers['sms_numbers'] as $key=>$number)
                <option value="{{$number->number}}">{{$number->number}}</option>
            @endforeach
        @else
            <option value="">No Data Available</option>
        @endif
    </select>
</div>

{{--{{dd($numbers['mms_numbers'])}}--}}
<div class="form-group numbers-section" id="mms_section" style="display: none">
    <label for="">{{trans('customer.select_mms_numbers')}}</label>
    <select name="mms_numbers[]" class="form-control select2">
        @if(isset($numbers['mms_numbers']))
            @foreach($numbers['mms_numbers'] as $key=>$number)
                <option value="{{$number->number}}">{{$number->number}}</option>
            @endforeach
        @else
            <option value="">No Data Available</option>
        @endif
    </select>
</div>

<div class="form-group numbers-section" id="whatsapp_section" style="display: none">
    <label for="">{{trans('customer.select_mms_numbers')}}</label>
    <select name="whatsapp_numbers[]" class="form-control select2">
        @if(isset($numbers['whatsapp_numbers']))
            @foreach($numbers['whatsapp_numbers'] as $key=>$number)
                <option value="{{$number->number}}">{{$number->number}}</option>
            @endforeach
        @else
            <option value="">No Data Available</option>
        @endif
    </select>
</div>

<div class="form-group numbers-section" id="sender_id_section" style="display: none">
    <label for="">{{trans('customer.select_sender_ids')}}</label>
    <select name="sender_ids[]" class="form-control select2 sender_ids_sec" id="edit-sender-id-section">
        @if(isset($users_senders_ids))
            @foreach($users_senders_ids as $key=>$number)
                <option value="{{$number->sender_id}}">{{$number->sender_id}}</option>
            @endforeach
            @elseif (isset($from_sender_groups))
            @foreach ($from_sender_groups as $sender_number)
            <option value="{{$sender_number->number}}">{{$sender_number->number}}</option>
            @endforeach
        @else
            <option value="">No Data Available</option>
        @endif
    </select>
</div>

<div class="form-group numbers-section" id="voice_section" style="display: none">
    <label for="">{{trans('customer.select_voice_numbers')}}</label>
    <select name="voice_numbers[]" class="form-control select2">
        @if(isset($numbers['voice_numbers']))
            @foreach($numbers['voice_numbers'] as $key=>$number)
                <option value="{{$number->number}}">{{$number->number}}</option>
            @endforeach
        @else
            <option value="">No Data Available</option>
        @endif
    </select>
</div>


<div class="form-group">
    <label for="status">@lang('customer.status')</label>
    <select name="status" id="status" class="form-control">
        <option {{isset($group) && $group->status=='active'?'selected':''}} value="active">Active</option>
        <option {{isset($group) && $group->status=='inactive'?'selected':''}} value="inactive">Inactive</option>
    </select>
</div>
