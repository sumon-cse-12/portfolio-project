<div class="form-group">
    <label for="first_name">@lang('admin.first') @lang('admin.name')</label>
    <input value="{{isset($customer)?$customer->first_name:old('first_name')}}" type="text" name="first_name" class="form-control" id="first_name"
           placeholder="@lang('admin.first') @lang('admin.name')">
</div>
<div class="form-group">
    <label for="last_name">@lang('admin.last') @lang('admin.name')</label>
    <input value="{{isset($customer)?$customer->last_name:old('last_name')}}" type="text" name="last_name" class="form-control" id="last_name"
           placeholder="@lang('admin.last') @lang('admin.name')">
</div>
<div class="form-group">
    <label for="email">@lang('admin.email')</label>
    <input value="{{isset($customer)?$customer->email:old('email')}}" type="email" name="email" class="form-control" id="email"
           placeholder="@lang('admin.email')" autocomplete="off" readonly
           onfocus="remove_readonly(this)">
</div>
<div class="form-group">
    <label for="password">@lang('admin.password')</label>
    <input type="password" name="password" class="form-control" id="password"
           placeholder="@lang('admin.password')">
</div>
{{-- <div class="form-group">
    <label for="status">@lang('admin.type')</label>
    <select class="form-control" name="type" id="type">
        <option {{isset($customer) && $customer->type=='normal'?'selected':(old('type')=='normal'?'selected':'')}} value="normal">Customer</option>
        @if(get_settings('reseller_status')=='enable')
        <option {{isset($customer) && $customer->type=='reseller'?'selected':(old('type')=='reseller'?'selected':'')}} value="reseller">Reseller</option>
        @endif
    </select>
</div> --}}
<div class="form-group">
    <label for="phone_number">{{ trans('admin.phone') }} {{ trans('admin.number') }}</label>
    <input type="number" class="form-control" name="phone_number" value="{{ isset($customer)?$customer->phone_number:'' }}" id="phone_number" placeholder="{{ trans('admin.phone') }} {{ trans('admin.number') }}">
</div>
<div class="form-group">
    <label for="image">{{ trans('admin.image') }}</label>
    <input type="file" class="form-control" name="image" value="{{ isset($customer)?$customer->image:'' }}" id="image" placeholder="{{ trans('admin.mata') }} {{ trans('admin.image') }}">
</div>

<div class="form-group customer_gateway {{isset($customer) && $customer->type !='normal'?'':'d-none'}}">
    <label for="status">@lang('admin.form.payment_gateway')</label>
    <select class="form-control select2 d-flex" multiple name="payment_gateway[]" id="gateway">
        @foreach(getAllPaymentGateway() as $key=>$gateway)
            <option {{isset($payment_gateway) && in_array($gateway, $payment_gateway)?'selected':''}} value="{{$gateway}}">{{ucfirst(str_replace('_',' ', $gateway))}}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="agent_code">{{ trans('admin.agent_code') }}</label>
    <input type="number" class="form-control" name="agent_code" value="{{ isset($customer)?$customer->agent_code:'' }}" id="agent_code" placeholder="{{ trans('admin.agent_code') }}" required>
</div>
<div class="form-group">
    <label for="status">@lang('admin.status')</label>
    <select class="form-control" name="status" id="status">
        <option {{isset($customer) && $customer->status=='Active'?'selected':(old('status')=='active'?'selected':'')}} value="active">Active</option>
        <option {{isset($customer) && $customer->status=='Inactive'?'selected':(old('status')=='inactive'?'selected':'')}} value="inactive">Inactive</option>
    </select>
</div>
