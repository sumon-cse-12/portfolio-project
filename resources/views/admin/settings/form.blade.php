<div class="form-group">
    <label for="u_name">@lang('admin.name')</label>
    <input value="{{old('u_name')??isset($admin)?$admin->name:''}}" type="text" name="u_name" class="form-control" id="u_name"
           placeholder="@lang('admin.name')">
</div>
<div class="form-group">
    <label for="email">@lang('admin.email')</label>
    <input value="{{old('email')??isset($admin)?$admin->email:''}}" type="email" name="email" class="form-control" id="email"
           placeholder="@lang('admin.email')" autocomplete="off" readonly
           onfocus="remove_readonly(this)">
</div>
<div class="form-group">
    <label for="password">@lang('admin.password')</label>
    <input type="password" name="password" class="form-control" id="u_password"
           placeholder="@lang('admin.password')" autocomplete="off" readonly
           onfocus="remove_readonly(this)">
</div>
<div class="form-group">
    <label for="profile">@lang('admin.profile_picture')</label>
    <div class="input-group">
        <div class="custom-file">
            <input name="profile" type="file" class="custom-file-input" id="profile">
            <label class="custom-file-label" for="profile">@lang('admin.choose_file')</label>
        </div>
    </div>
</div>

