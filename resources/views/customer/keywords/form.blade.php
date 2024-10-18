<div class="form-group">
    <label for="opt_type">{{trans('Number')}}</label>
    <select name="number_id" class="form-control" id="customer_numbers">
        @foreach($numbers as $number)
            <option {{isset($keyword) && $keyword->customer_number_id==$number->id?'selected':''}} value="{{$number->id}}">{{$number->number}}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="word">@lang('customer.keyword') *</label>
    <input value="{{isset($keyword) && $keyword->word?$keyword->word:old('word')}}" type="text" name="word"
           class="form-control" id="word"
           placeholder="@lang('customer.keyword')">
</div>

<div class="form-group">
    <label for="opt_type">{{trans('customer.opt_type')}}</label>
    <select name="type" class="form-control" id="">
        <option {{isset($keyword) && $keyword->type=='opt_in'?'selected':''}} value="opt_in">{{trans('Optin')}}</option>
        <option {{isset($keyword) && $keyword->type=='opt_out'?'selected':''}} value="opt_out">{{trans('Optout')}}</option>
    </select>
</div>

<div class="form-group">
    <label for="confirm_message">@lang('Message')</label>
    <input value="{{isset($keyword) && $keyword->confirm_message?$keyword->confirm_message:old('confirm_message')}}" type="text" name="confirm_message"
           class="form-control" id="confirm_message"
           placeholder="Confirm Message">
</div>

<div class="form-group d-none">
    <label for="optout_message">@lang('customer.optout_message')</label>
    <input value="{{isset($keyword) && $keyword->optout_message?$keyword->optout_message:old('optout_message')}}" type="text" name="optout_message"
           class="form-control" id="optout_message"
           placeholder="Optout Message">
</div>


<div class="form-group {{isset($keyword) && $keyword->type=='opt_out'?'d-none':''}}" id="contactGroup">
    <label for="opt_type">{{trans('Contact Group')}}</label>
    <select name="group_id" class="form-control groups" id="">
        @foreach($groups as $group)
            <option {{isset($keyword) && $keyword->group_id==$group->id?'selected':''}} value="{{$group->id}}">{{$group->name}}</option>
        @endforeach
    </select>
</div>


