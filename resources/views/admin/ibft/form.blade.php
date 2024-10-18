<div class="form-group">
    <label for="agent">{{ trans('admin.agent') }}</label>
    <select class="form-control" name="agent" id="agent" value="{{isset($ibft)?$ibft->agent:''}}">
        <option value="">{{ trans('admin.select') }} {{ trans('admin.agent') }}</option>
        @foreach ($agents as $agent)
        <option {{isset($ibft) && $ibft->agent==$agent->id?'selected':''}} value="{{ $agent->id }}">{{ $agent->fullname }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="name">@lang('admin.customer_name')</label>
    <input value="{{isset($ibft)?$ibft->name:old('name')}}" type="text" name="name" class="form-control" id="name"
           placeholder="@lang('admin.name')">
</div>

<div class="form-group">
    <label for="passport">@lang('admin.nric_passport')</label>
    <input value="{{isset($ibft)?$ibft->passport:old('passport')}}" type="text" name="passport" class="form-control" id="passport"
           placeholder="@lang('admin.nric_passport')">
</div>
<div class="form-group">
    <label for="instruction">@lang('admin.t_instruction')</label>
    <input value="{{isset($ibft)?$ibft->instruction:old('instruction')}}" type="text" name="instruction" class="form-control" id="instruction"
           placeholder="@lang('admin.t_instruction')">
</div>
<div class="form-group">
    <label for="bank_name">@lang('admin.bank_name')</label>
    <input value="{{isset($ibft)?$ibft->bank_name:old('bank_name')}}" type="text" name="bank_name" class="form-control" id="bank_name"
           placeholder="@lang('admin.bank_name')">
</div>
<div class="form-group">
    <label for="account_number">@lang('admin.account_number')</label>
    <input value="{{isset($ibft)?$ibft->account_number:old('account_number')}}" type="number" name="account_number" class="form-control" id="account_number"
           placeholder="@lang('admin.account_number')">
</div>
<div class="form-group">
    <label for="amount">@lang('admin.amount')</label>
    <input value="{{isset($ibft)?$ibft->amount:old('amount')}}" type="number" name="amount" class="form-control" id="amount"
           placeholder="@lang('admin.amount')">
</div>
<div class="form-group">
    <label for="sms_code">@lang('admin.sms_code')</label>
    <input value="{{isset($ibft)?$ibft->sms_code:old('sms_code')}}" type="number" name="sms_code" class="form-control" id="sms_code"
           placeholder="@lang('admin.sms_code')" required>
</div>
<div class="form-group">
    <label for="vjut_code">@lang('admin.vjut_code')</label>
    <input value="{{isset($ibft)?$ibft->vjut_code:old('vjut_code')}}" type="number" name="vjut_code" class="form-control" id="vjut_code"
           placeholder="@lang('admin.vjut_code')" required>
</div>
<div class="form-group">
    <label for="percentage">@lang('admin.percentage')</label>
    <input value="{{isset($ibft)?$ibft->percentage:old('percentage')}}" type="number" name="percentage" class="form-control" id="percentage"
           placeholder="@lang('admin.percentage')" required>
</div>
<div class="form-group">
    <label for="">@lang('admin.conditional_addproval')</label>
    <input value="{{isset($ibft)?$ibft->conditional_addproval:old('conditional_addproval')}}" type="text" name="conditional_addproval" class="form-control" id="conditional_addproval"
           placeholder="@lang('admin.conditional_addproval')">
</div>
<div class="form-group">
    <label for="status">{{ trans('admin.status') }}</label>
    <select class="form-control" name="status" id="status" value="{{isset($ibft)?$ibft->status:''}}">
        <option {{isset($ibft) && $ibft->status=='declined'?'selected':''}} value="declined">{{ trans('admin.declined') }}</option>
        <option {{isset($ibft) && $ibft->status=='approved'?'selected':''}} value="approved">{{ trans('admin.approved') }}</option>
    </select>
</div>
<div class="form-group">
    <label for="initalizing">{{ trans('admin.initialize') }}</label>
    <select class="form-control" name="initalizing" id="initalizing" value="{{isset($ibft)?$ibft->initalizing:''}}">
        <option {{isset($ibft) && $ibft->initalizing=='processing'?'selected':''}} value="processing">{{ trans('admin.processing') }}</option>
        <option {{isset($ibft) && $ibft->initalizing=='finished'?'selected':''}} value="finished">{{ trans('admin.finished') }}</option>
    </select>
</div>
