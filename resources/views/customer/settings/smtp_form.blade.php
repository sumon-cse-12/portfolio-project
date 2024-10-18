
<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="host">@lang('admin.form.mail_name')</label>
            <input value="{{isset($smtp_setting->name)?$smtp_setting->name:''}}" type="text" name="name" class="form-control" id="name"
                   placeholder="@lang('admin.form.mail_name')">
        </div>
    </div>
    <div class="col-sm-6">

        <div class="form-group">
            <label for="host">@lang('admin.form.mail_from')</label>
            <input value="{{isset($smtp_setting->from)?$smtp_setting->from:''}}" type="email" name="from" class="form-control" id="from"
                   placeholder="@lang('admin.form.mail_from')">
        </div>
    </div>
    <div class="col-sm-6">

        <div class="form-group">
            <label for="host">@lang('admin.form.mail_host')</label>
            <input value="{{isset($smtp_setting->host)?$smtp_setting->host:''}}" type="text" name="host" class="form-control" id="host"
                   placeholder="@lang('admin.form.mail_host')">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="host">@lang('admin.form.mail_port')</label>
            <input value="{{isset($smtp_setting->port)?$smtp_setting->port:''}}" type="number" name="port" class="form-control" id="port"
                   placeholder="@lang('admin.form.mail_port')">
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="host">@lang('admin.form.mail_username')</label>
            <input value="{{isset($smtp_setting->username)?$smtp_setting->username:''}}" type="text" name="username" class="form-control"
                   id="username"
                   placeholder="@lang('admin.form.mail_username')">
        </div>
    </div>
    <div class="col-sm-6">

        <div class="form-group">
            <label for="host">@lang('admin.form.mail_password')</label>
            <input value="{{isset($smtp_setting->password)?$smtp_setting->password:''}}" type="password" name="password" class="form-control"
                   id="password"
                   placeholder="@lang('admin.form.mail_password')">
        </div>
    </div>
    <div class="col-sm-12">

        <div class="form-group">
            <label for="encryption">@lang('admin.form.mail_encryption')</label>
            <select class="form-control" name="encryption" id="encryption">
                <option {{isset($smtp_setting->encryption) && $smtp_setting->encryption=='tls'?'selected':''}} value="tls">TLS</option>
                <option {{isset($smtp_setting->encryption) && $smtp_setting->encryption=='ssl'?'selected':''}} value="ssl">SSL</option>
            </select>
        </div>

    </div>
</div>






