<div class="form-group">
    <label for="first_name">@lang('admin.form.first_name')</label>
    <input value="{{isset($reseller_customer)?$reseller_customer->first_name:old('first_name')}}" type="text" name="first_name" class="form-control" id="first_name"
           placeholder="@lang('admin.form.input.first_name')">
</div>
<div class="form-group">
    <label for="last_name">@lang('admin.form.last_name')</label>
    <input value="{{isset($reseller_customer)?$reseller_customer->last_name:old('last_name')}}" type="text" name="last_name" class="form-control" id="last_name"
           placeholder="@lang('admin.form.input.last_name')">
</div>
<div class="form-group">
    <label for="email">@lang('admin.form.email')</label>
    <input value="{{isset($reseller_customer)?$reseller_customer->email:old('email')}}" type="email" name="email" class="form-control" id="email"
           placeholder="@lang('admin.email')" autocomplete="off" readonly
           onfocus="remove_readonly(this)">
</div>
<div class="form-group">
    <label for="password">@lang('admin.form.password')</label>
    <input type="password" name="password" class="form-control" id="password"
           placeholder="@lang('admin.password')">
</div>

@if(!isset($reseller_customer))
<div class="form-group" >
    <label for="status">{{trans('admin.select_plan')}}</label>
    <select class="form-control" name="plan_id">
        @foreach($plans as $plan)
            <option value="{{$plan->id}}">{{$plan->title}}</option>
        @endforeach
    </select>
</div>
@endif

<div class="form-group">
    <label for="status">@lang('admin.form.status')</label>
    <select class="form-control" name="status" id="status">
        <option {{isset($reseller_customer) && $reseller_customer->status=='Active'?'selected':(old('status')=='active'?'selected':'')}} value="active">Active</option>
        <option {{isset($reseller_customer) && $reseller_customer->status=='Inactive'?'selected':(old('status')=='inactive'?'selected':'')}} value="inactive">Inactive</option>
    </select>
</div>
