<div class="form-group">
    <label for="first_name">@lang('admin.form.first_name')</label>
    <input value="{{isset($staff)?$staff->first_name:old('first_name')}}" type="text" name="first_name" class="form-control" id="first_name"
           placeholder="@lang('admin.form.input.first_name')">
</div>
<div class="form-group">
    <label for="last_name">@lang('admin.form.last_name')</label>
    <input value="{{isset($staff)?$staff->last_name:old('last_name')}}" type="text" name="last_name" class="form-control" id="last_name"
           placeholder="@lang('admin.form.input.last_name')">
</div>
<div class="form-group">
    <label for="email">@lang('admin.form.email')</label>
    <input value="{{isset($staff)?$staff->email:old('email')}}" type="email" name="email" class="form-control" id="email"
           placeholder="@lang('admin.email')" autocomplete="off" readonly
           onfocus="remove_readonly(this)">
</div>
<div class="form-group">
    <label for="password">@lang('admin.form.password')</label>
    <input type="password" name="password" class="form-control" id="password"
           placeholder="@lang('admin.password')">
</div>
@if(isset($roles))
<div class="form-group ">
    <label for="status">@lang('Role')</label>
    <select class="form-control" name="role_id" id="status">
        @foreach($roles as $role)
            <option {{isset($staff) && $staff->role_id==$role->id?'selected':(old('role_id')=='active'?'selected':'')}}
                    value="{{$role->id}}">{{str_replace('_','-',$role->name)}}</option>
        @endforeach
    </select>
</div>
@endif
