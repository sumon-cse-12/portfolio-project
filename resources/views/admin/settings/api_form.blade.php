
@php

$signalwire=json_decode(get_settings('signalwire'));
$twilio=json_decode(get_settings('twilio'));
$nexmo=json_decode(get_settings('nexmo'));
$telnyx=json_decode(get_settings('telnyx'));
$plivo=json_decode(get_settings('plivo'));
$africastalking=json_decode(get_settings('africastalking'));
$nrs=json_decode(get_settings('nrs'));
$message_bird=json_decode(get_settings('message_bird'));
$infobip=json_decode(get_settings('infobip'));
$cheap_global=json_decode(get_settings('cheapglobalsms'));
$plivo_powerpack=json_decode(get_settings('plivo_powerpack'));
$easysendsms=json_decode(get_settings('easysendsms'));
$twilio_copilot=json_decode(get_settings('twilio_copilot'));
$bulksms=json_decode(get_settings('bulksms'));
$ones_two_u=json_decode(get_settings('ones_two_u'));
$clickatel=json_decode(get_settings('clickatel'));
$route_mobile=json_decode(get_settings('route_mobile'));
$hutch=json_decode(get_settings('hutch'));
$estoresms=json_decode(get_settings('estoresms'));
$sms_global=json_decode(get_settings('sms_global'));
$tyntec=json_decode(get_settings('tyntec'));
$karix=json_decode(get_settings('karix'));
$bandwidth=json_decode(get_settings('bandwidth'));
$text_local=json_decode(get_settings('text_local'));
$route_net=json_decode(get_settings('route_net'));
$hutchlk=json_decode(get_settings('hutchlk'));
$broadcaster_mobile=json_decode(get_settings('broadcaster_mobile'));
$solutions4mobiles=json_decode(get_settings('solutions4mobiles'));
$beemAfrica=json_decode(get_settings('beemAfrica'));
$bulkSMSOnline=json_decode(get_settings('bulkSMSOnline'));
$flowRoute=json_decode(get_settings('flowRoute'));
$elitBuzzBD=json_decode(get_settings('elitBuzzBD'));
$greenWebBD=json_decode(get_settings('greenWebBD'));
$hablameV2=json_decode(get_settings('hablameV2'));
$zamtelCoZm=json_decode(get_settings('zamtelCoZm'));
$thinq=json_decode(get_settings('thinq'));
$smpp=json_decode(get_settings('smpp'));
$bulksmsbd=json_decode(get_settings('bulksmsbd'));
$metro_tel=json_decode(get_settings('metro_tel'));
$click_send=json_decode(get_settings('click_send'));
$ajuratech=json_decode(get_settings('sms_noc'));
$sms_mkt=json_decode(get_settings('sms_mkt'));
$adn_sms=json_decode(get_settings('adn_sms'));
@endphp

<div class="form-group">
    <label for="gateway">@lang('admin.settings.gateway')</label>
    <select required class="form-control select2" style="width: 100%" name="gateway" id="gateway">
        @foreach(getAllSmsGateway() as $api)
            <option value="{{$api}}">{{ucfirst(str_replace('_', ' ', $api))}}</option>
        @endforeach
    </select>
</div>
<div id="signalwire_section" class="api-section">
    <div class="form-group">
        <label for="project_id">@lang('admin.settings.project_id')</label>
        <input required value="{{isset($signalwire->sw_project_id)?$signalwire->sw_project_id:''}}" class="form-control" type="text" name="sw_project_id" id="project_id">
    </div>

    <div class="form-group">
        <label for="space_url">@lang('admin.settings.space_url')</label>
        <input required value="{{isset($signalwire->sw_space_url)?$signalwire->sw_space_url:''}}" class="form-control" type="text" name="sw_space_url" id="space_url">
    </div>

    <div class="form-group">
        <label for="sw_token">@lang('admin.settings.token')</label>
        <input required value="{{isset($signalwire->sw_token)?$signalwire->sw_token:''}}"  class="form-control" type="text" name="sw_token" id="sw_token">
    </div>
    <div class="form-group">
        <label >Status</label>
        <select name="sw_status" class="form-control" id="">
            <option {{isset($signalwire->sw_status) && $signalwire->sw_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($signalwire->sw_status) && $signalwire->sw_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>
<div id="twilio_section" style="display: none" class="api-section">
    <div class="form-group">
        <label for="tw_sid">@lang('admin.settings.sid')</label>
        <input required value="{{isset($twilio->tw_sid)?$twilio->tw_sid:''}}" class="form-control" type="text" name="tw_sid" id="tw_sid">
    </div>
    <div class="form-group">
        <label for="tw_token">@lang('admin.settings.token')</label>
        <input required value="{{isset($twilio->tw_token)?$twilio->tw_token:''}}" class="form-control" type="text" name="tw_token" id="tw_token">
    </div>
    <div class="form-group">
        <label >Status</label>
        <select name="tw_status" class="form-control" id="">
            <option {{isset($twilio->tw_status) && $twilio->tw_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($twilio->tw_status) && $twilio->tw_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>
<div id="nexmo_section" style="display: none" class="api-section">

    <div class="form-group">
        <label for="nx_api_key">@lang('admin.settings.api_key')</label>
        <input required value="{{isset($nexmo->nx_api_key)?$nexmo->nx_api_key:''}}" class="form-control" type="text" name="nx_api_key" id="nx_api_key">
    </div>

    <div class="form-group">
        <label for="nx_api_secret">@lang('admin.settings.api_secret')</label>
        <input required value="{{isset($nexmo->nx_api_secret)?$nexmo->nx_api_secret:''}}" class="form-control" type="text" name="nx_api_secret" id="nx_api_secret">
    </div>

    <div class="form-group">
        <label >Status</label>
        <select name="nx_status" class="form-control" id="">
            <option {{isset($nexmo->nx_status) && $nexmo->nx_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($nexmo->nx_status) && $nexmo->nx_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>

<div id="telnyx_section" style="display: none" class="api-section">

    <div class="form-group">
        <label for="tl_api_key">@lang('admin.settings.api_key')</label>
        <input required value="{{isset($telnyx->tl_api_key)?$telnyx->tl_api_key:''}}" class="form-control" type="text" name="tl_api_key" id="tl_api_key">
    </div>

    <div class="form-group">
        <label >Status</label>
        <select name="tl_status" class="form-control" id="">
            <option {{isset($telnyx->tl_status) && $telnyx->tl_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($telnyx->tl_status) && $telnyx->tl_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>

<div id="plivo_section" style="display: none" class="api-section">

    <div class="form-group">
        <label for="pl_auth_id">@lang('admin.settings.auth_id')</label>
        <input required value="{{isset($plivo->pl_auth_id)?$plivo->pl_auth_id:''}}" class="form-control" type="text" name="pl_auth_id" id="pl_auth_id">
    </div>
    <div class="form-group">
        <label for="pl_auth_token">@lang('admin.settings.auth_token')</label>
        <input required value="{{isset($plivo->pl_auth_id)?$plivo->pl_auth_token:''}}" class="form-control" type="text" name="pl_auth_token" id="pl_auth_token">
    </div>

    <div class="form-group">
        <label >Status</label>
        <select name="pl_status" class="form-control" id="">
            <option {{isset($plivo->pl_status) && $plivo->pl_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($plivo->pl_status) && $plivo->pl_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>

<div id="africastalking_section" style="display: none" class="api-section">

    <div class="form-group">
        <label for="africas_talking_username">@lang('admin.settings.username')</label>
        <input required value="{{isset($africastalking->africas_talking_username)?$africastalking->africas_talking_username:''}}" class="form-control" type="text" name="africas_talking_username" id="africas_talking_username">
    </div>
    <div class="form-group">
        <label for="africas_talking_api_key">@lang('admin.settings.api_key')</label>
        <input required value="{{isset($africastalking->africas_talking_api_key)?$africastalking->africas_talking_api_key:''}}" class="form-control" type="text" name="africas_talking_api_key" id="africas_talking_api_key">
    </div>

    <div class="form-group">
        <label >@lang('URL')</label>
        <input required value="{{isset($africastalking->africas_talking_url)?$africastalking->africas_talking_url:''}}" class="form-control" type="text" name="africas_talking_url" id="africas_talking_api_key">
    </div>

    <div class="form-group">
        <label >Status</label>
        <select name="africas_talking_status" class="form-control" id="">
            <option {{isset($africastalking->africas_talking_status) && $africastalking->africas_talking_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($africastalking->africas_talking_status) && $africastalking->africas_talking_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>

<div id="nrs_section" style="display: none" class="api-section">

    <div class="form-group">
        <label for="africas_talking_username">@lang('admin.settings.auth_token')</label>
        <input required value="{{isset($nrs->nrs_auth_token)?$nrs->nrs_auth_token:''}}" class="form-control" type="text" name="nrs_auth_token" id="nrs_auth_token">
    </div>

    <div class="form-group">
        <label >Status</label>
        <select name="nrs_status" class="form-control" id="">
            <option {{isset($nrs->nrs_status) && $nrs->nrs_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($nrs->nrs_status) && $nrs->nrs_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>

<div id="message_bird_section" style="display: none" class="api-section">
    <div class="form-group">
        <label for="message_bird_auth_token">@lang('admin.settings.auth_token')</label>
        <input required value="{{isset($message_bird->message_bird_auth_token)?$message_bird->message_bird_auth_token:''}}" class="form-control" type="text" name="message_bird_auth_token" id="message_bird_auth_token">
    </div>
    <div class="form-group">
        <label for="">@lang('admin.settings.url_base_path')</label>
        <input required value="{{isset($message_bird->message_bird_url)?$message_bird->message_bird_url:''}}" class="form-control" type="text" name="message_bird_url">
    </div>
    <div class="form-group">
        <label >Status</label>
        <select name="message_bird_status" class="form-control" id="">
            <option {{isset($message_bird->message_bird_status) && $message_bird->message_bird_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($message_bird->message_bird_status) && $message_bird->message_bird_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>

<div id="infobip_section" style="display: none" class="api-section">
    <div class="form-group">
        <label for="infobip_prefix_key">@lang('admin.settings.api_key_prefix')</label>
        <input required value="{{isset($infobip->infobip_prefix_key)?$infobip->infobip_prefix_key:''}}" class="form-control" type="text" name="infobip_prefix_key" id="infobip_prefix_key">
    </div>
    <div class="form-group">
        <label for="infobip_api_key">@lang('admin.settings.api_key')</label>
        <input required value="{{isset($infobip->infobip_api_key)?$infobip->infobip_api_key:''}}" class="form-control" type="text" name="infobip_api_key" id="infobip_api_key">
    </div>
    <div class="form-group">
        <label for="url_base_path">@lang('admin.settings.url_base_path')</label>
        <input required value="{{isset($infobip->url_base_path)?$infobip->url_base_path:''}}" class="form-control" type="text" name="url_base_path" id="url_base_path">
    </div>

    <div class="form-group">
        <label >Status</label>
        <select name="infobip_status" class="form-control" id="">
            <option {{isset($infobip->infobip_status) && $infobip->infobip_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($infobip->infobip_status) && $infobip->infobip_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>
<div id="cheapglobalsms_section" style="display: none" class="api-section">
    <div class="form-group">
        <label for="cheap_global_account">@lang('admin.settings.account')</label>
        <input required value="{{isset($cheap_global->cheap_global_account)?$cheap_global->cheap_global_account:''}}" class="form-control" type="text" name="cheap_global_account" id="cheap_global_account">
    </div>
    <div class="form-group">
        <label for="cheap_global_password">@lang('admin.settings.password')</label>
        <input required value="{{isset($cheap_global->cheap_global_password)?$cheap_global->cheap_global_password:''}}" class="form-control" type="text" name="cheap_global_password" id="cheap_global_password">
    </div>

    <div class="form-group">
        <label >Status</label>
        <select name="cheap_global_status" class="form-control" id="">
            <option {{isset($cheap_global->cheap_global_status) && $cheap_global->cheap_global_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($cheap_global->cheap_global_status) && $cheap_global->cheap_global_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>
<div id="plivo_powerpack_section" style="display: none" class="api-section">
    <div class="form-group">
        <label for="pl_auth_id">@lang('admin.settings.auth_id')</label>
        <input required value="{{isset($plivo_powerpack->powerprek_auth_id)?$plivo_powerpack->powerprek_auth_id:''}}" class="form-control" type="text" name="powerprek_auth_id" id="powerprek_auth_id">
    </div>
    <div class="form-group">
        <label for="pl_auth_token">@lang('admin.settings.auth_token')</label>
        <input required value="{{isset($plivo_powerpack->powerprek_auth_token)?$plivo_powerpack->powerprek_auth_token:''}}" class="form-control" type="text" name="powerprek_auth_token" id="powerprek_auth_token">
    </div>

    <div class="form-group">
        <label >Status</label>
        <select name="powerprek_status" class="form-control" id="">
            <option {{isset($plivo_powerpack->powerprek_status) && $plivo_powerpack->powerprek_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($plivo_powerpack->powerprek_status) && $plivo_powerpack->powerprek_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>
<div id="easysendsms_section" style="display: none" class="api-section">
    <div class="form-group">
        <label for="username">@lang('admin.settings.username')</label>
        <input required value="{{isset($easysendsms->easysendsms_username)?$easysendsms->easysendsms_username:''}}" class="form-control" type="text" name="easysendsms_username" id="easysendsms_username">
    </div>
    <div class="form-group">
        <label for="powerprek_auth_token">@lang('admin.settings.password')</label>
        <input required value="{{isset($easysendsms->easysendsms_password)?$easysendsms->easysendsms_password:''}}" class="form-control" type="text" name="easysendsms_password" id="easysendsms_password">
    </div>

    <div class="form-group">
        <label >Status</label>
        <select name="easysendsms_status" class="form-control" id="">
            <option {{isset($easysendsms->easysendsms_status) && $easysendsms->easysendsms_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($easysendsms->easysendsms_status) && $easysendsms->easysendsms_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>
<div id="twilio_copilot_section" style="display: none" class="api-section">
    <div class="form-group">
        <label for="twilio_copilot_sid">@lang('admin.settings.sid')</label>
        <input required value="{{isset($twilio_copilot->twilio_copilot_sid)?$twilio_copilot->twilio_copilot_sid:''}}" class="form-control" type="text" name="twilio_copilot_sid" id="twilio_copilot_sid">
    </div>
    <div class="form-group">
        <label for="twilio_copilot_auth_token">@lang('admin.settings.auth_token')</label>
        <input required value="{{isset($twilio_copilot->twilio_copilot_auth_token)?$twilio_copilot->twilio_copilot_auth_token:''}}" class="form-control" type="text" name="twilio_copilot_auth_token" id="twilio_copilot_auth_token">
    </div>

    <div class="form-group">
        <label >Status</label>
        <select name="twilio_copilot_status" class="form-control" id="">
            <option {{isset($twilio_copilot->twilio_copilot_status) && $twilio_copilot->twilio_copilot_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($twilio_copilot->twilio_copilot_status) && $twilio_copilot->twilio_copilot_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>
<div id="bulksms_section" style="display: none" class="api-section">
    <div class="form-group">
        <label for="bulksms_username">@lang('admin.settings.username')</label>
        <input required value="{{isset($bulksms->bulksms_username)?$bulksms->bulksms_username:''}}" class="form-control" type="text" name="bulksms_username" id="bulksms_username">
    </div>
    <div class="form-group">
        <label for="bulksms_password">@lang('admin.settings.password')</label>
        <input required value="{{isset($bulksms->bulksms_password)?$bulksms->bulksms_password:''}}" class="form-control" type="text" name="bulksms_password" id="bulksms_password">
    </div>

    <div class="form-group">
        <label >Status</label>
        <select name="bulksms_status" class="form-control" id="">
            <option {{isset($bulksms->bulksms_status) && $bulksms->bulksms_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($bulksms->bulksms_status) && $bulksms->bulksms_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>

<div id="ones_two_u_section" style="display: none" class="api-section">
    <div class="form-group">
        <label for="ones_two_u_username">@lang('admin.settings.username')</label>
        <input required value="{{isset($ones_two_u->ones_two_u_username)?$ones_two_u->ones_two_u_username:''}}" class="form-control" type="text" name="ones_two_u_username" id="ones_two_u_username">
    </div>
    <div class="form-group">
        <label for="ones_two_u_password">@lang('admin.settings.password')</label>
        <input required value="{{isset($ones_two_u->ones_two_u_password)?$ones_two_u->ones_two_u_password:''}}" class="form-control" type="text" name="ones_two_u_password" id="ones_two_u_password">
    </div>
    <div class="form-group">
        <label >Status</label>
        <select name="ones_two_u_status" class="form-control" id="">
            <option {{isset($ones_two_u->ones_two_u_status) && $ones_two_u->ones_two_u_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($ones_two_u->ones_two_u_status) && $ones_two_u->ones_two_u_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>

<div id="route_mobile_section" style="display: none" class="api-section">
    <div class="form-group">
        <label for="route_mobile_username">@lang('admin.settings.username')</label>
        <input required value="{{isset($route_mobile->route_mobile_username)?$route_mobile->route_mobile_username:''}}" class="form-control" type="text" name="route_mobile_username" id="route_mobile_username">
    </div>
    <div class="form-group">
        <label for="route_mobile_password">@lang('admin.settings.password')</label>
        <input required value="{{isset($route_mobile->route_mobile_password)?$route_mobile->route_mobile_password:''}}" class="form-control" type="text" name="route_mobile_password" id="route_mobile_password">
    </div>

    <div class="form-group">
        <label for="route_mobile_url">@lang('URL')</label>
        <input required value="{{isset($route_mobile->route_mobile_url)?$route_mobile->route_mobile_url:''}}" class="form-control" type="text" name="route_mobile_url" id="route_mobile_url">
    </div>

    <div class="form-group">
        <label >Status</label>
        <select name="route_mobile_status" class="form-control" id="">
            <option {{isset($route_mobile->route_mobile_status) && $route_mobile->route_mobile_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($route_mobile->route_mobile_status) && $route_mobile->route_mobile_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>

</div>

<div id="clickatel_section" style="display: none" class="api-section">
    <div class="form-group">
        <label for="clickatel_api_key">@lang('admin.settings.api_key')</label>
        <input required value="{{isset($clickatel->clickatel_api_key)?$clickatel->clickatel_api_key:''}}" class="form-control" type="text" name="clickatel_api_key" id="clickatel_api_key">
    </div>

    <div class="form-group">
        <label for="clickatel_api_key">{{trans('ClickTel Url')}}</label>
        <input required value="{{isset($clickatel->clickatel_url)?$clickatel->clickatel_url:''}}" class="form-control" type="text" name="clickatel_url" >
    </div>

    <div class="form-group">
        <label >Status</label>
        <select name="clickatel_status" class="form-control" id="">
            <option {{isset($clickatel->clickatel_status) && $clickatel->clickatel_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($clickatel->clickatel_status) && $clickatel->clickatel_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>

<div id="hutch_section" style="display: none" class="api-section">
    <div class="form-group">
        <label for="route_mobile_username">@lang('URL')</label>
        <input required value="{{isset($hutch->hutch_url)?$hutch->hutch_url:''}}" class="form-control" type="text" name="hutch_url" >
    </div>
    <div class="form-group">
        <label for="route_mobile_username">@lang('admin.settings.username')</label>
        <input required value="{{isset($hutch->hutch_username)?$hutch->hutch_username:''}}" class="form-control" type="text" name="hutch_username" >
    </div>
    <div class="form-group">
        <label for="route_mobile_password">@lang('admin.settings.password')</label>
        <input required value="{{isset($hutch->hutch_password)?$hutch->hutch_password:''}}" class="form-control" type="text" name="hutch_password">
    </div>

    <div class="form-group">
        <label >Status</label>
        <select name="hutch_status" class="form-control" id="">
            <option {{isset($hutch->hutch_status) && $hutch->hutch_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($hutch->hutch_status) && $hutch->hutch_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>

<div id="estoresms_section" style="display: none" class="api-section">
    <div class="form-group">
        <label for="">{{trans('admin.settings.username')}}</label>
        <input required value="{{isset($estoresms->estoresms_username)?$estoresms->estoresms_username:''}}" class="form-control" type="text" name="estoresms_username" >
    </div>
    <div class="form-group">
        <label for="route_mobile_username">@lang('admin.settings.password')</label>
        <input required value="{{isset($estoresms->estoresms_password)?$estoresms->estoresms_password:''}}" class="form-control" type="text" name="estoresms_password" >
    </div>
    <div class="form-group">
        <label >{{trans('Status')}}</label>
        <select name="estoresms_status" class="form-control" id="">
            <option {{isset($estoresms->estoresms_status) && $estoresms->estoresms_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($estoresms->estoresms_status) && $estoresms->estoresms_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>

<div id="sms_global_section" style="display: none" class="api-section">
    <div class="form-group">
        <label for="">{{trans('admin.settings.username')}}</label>
        <input required value="{{isset($sms_global->sms_global_username)?$sms_global->sms_global_username:''}}" class="form-control" type="text" name="sms_global_username" >
    </div>
    <div class="form-group">
        <label for="route_mobile_username">@lang('admin.settings.password')</label>
        <input required value="{{isset($sms_global->sms_global_password)?$sms_global->sms_global_password:''}}" class="form-control" type="text" name="sms_global_password" >
    </div>
    <div class="form-group">
        <label for="route_mobile_username">{{trans('URL')}}</label>
        <input required value="{{isset($sms_global->sms_global_url)?$sms_global->sms_global_url:''}}" class="form-control" type="text" name="sms_global_url" >
    </div>
    <div class="form-group">
        <label >{{trans('Status')}}</label>
        <select name="sms_global_status" class="form-control" id="">
            <option {{isset($sms_global->sms_global_status) && $sms_global->sms_global_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($sms_global->sms_global_status) && $sms_global->sms_global_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>


<div id="tyntec_section" style="display: none" class="api-section">
    <div class="form-group">
        <label for="">{{trans('admin.settings.api_key')}}</label>
        <input required value="{{isset($tyntec->tyntec_apikey)?$tyntec->tyntec_apikey:''}}" class="form-control" type="text" name="tyntec_apikey" >
    </div>

    <div class="form-group">
        <label for="route_mobile_username">{{trans('URL')}}</label>
        <input required value="{{isset($tyntec->tyntec_url)?$tyntec->tyntec_url:''}}" class="form-control" type="text" name="tyntec_url" >
    </div>
    <div class="form-group">
        <label >{{trans('Status')}}</label>
        <select name="tyntec_status" class="form-control" id="">
            <option {{isset($tyntec->tyntec_status) && $tyntec->tyntec_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($tyntec->tyntec_status) && $tyntec->tyntec_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>

<div id="karix_section" style="display: none" class="api-section">
    <div class="form-group">
        <label for="">{{trans('admin.settings.auth_id')}}</label>
        <input required value="{{isset($karix->karix_auth_id)?$karix->karix_auth_id:''}}" class="form-control" type="text" name="karix_auth_id" >
    </div>

    <div class="form-group">
        <label for="route_mobile_username">{{trans('admin.settings.auth_token')}}</label>
        <input required value="{{isset($karix->karix_auth_token)?$karix->karix_auth_token:''}}" class="form-control" type="text" name="karix_auth_token" >
    </div>
    <div class="form-group">
        <label for="route_mobile_username">{{trans('URL')}}</label>
        <input required value="{{isset($karix->karix_url)?$karix->karix_url:''}}" class="form-control" type="text" name="karix_url" >
    </div>
    <div class="form-group">
        <label >{{trans('Status')}}</label>
        <select name="karix_status" class="form-control" id="">
            <option {{isset($karix->karix_status) && $karix->karix_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($karix->karix_status) && $karix->karix_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>

<div id="bandwidth_section" style="display: none" class="api-section">
    <div class="form-group">
        <label for="">{{trans('admin.settings.auth_token')}}</label>
        <input required value="{{isset($bandwidth->bandwidth_auth_token)?$bandwidth->bandwidth_auth_token:''}}" class="form-control" type="text" name="bandwidth_auth_token" >
    </div>

    <div class="form-group">
        <label for="route_mobile_username">{{trans('admin.settings.api_secret')}}</label>
        <input required value="{{isset($bandwidth->bandwidth_api_secret)?$bandwidth->bandwidth_api_secret:''}}" class="form-control" type="text" name="bandwidth_api_secret" >
    </div>
    <div class="form-group">
        <label for="route_mobile_username">{{trans('Application ID')}}</label>
        <input required value="{{isset($bandwidth->bandwidth_app_id)?$bandwidth->bandwidth_app_id:''}}" class="form-control" type="text" name="bandwidth_app_id" >
    </div>

    <div class="form-group">
        <label for="route_mobile_username">{{trans('URL')}}</label>
        <input required value="{{isset($bandwidth->bandwidth_url)?$bandwidth->bandwidth_url:''}}" class="form-control" type="text" name="bandwidth_url" >
    </div>
    <div class="form-group">
        <label >{{trans('Status')}}</label>
        <select name="bandwidth_status" class="form-control" id="">
            <option {{isset($bandwidth->bandwidth_status) && $bandwidth->bandwidth_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($bandwidth->bandwidth_status) && $bandwidth->bandwidth_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>

<div id="text_local_section" style="display: none" class="api-section">
    <div class="form-group">
        <label for="">{{trans('admin.settings.api_key')}}</label>
        <input required value="{{isset($text_local->text_local_api_key)?$text_local->text_local_api_key:''}}" class="form-control" type="text" name="text_local_api_key" >
    </div>

    <div class="form-group">
        <label for="">{{trans('URL')}}</label>
        <input required value="{{isset($text_local->text_local_url)?$text_local->text_local_url:''}}" class="form-control" type="text" name="text_local_url" >
    </div>

    <div class="form-group">
        <label >{{trans('Status')}}</label>
        <select name="text_local_status" class="form-control" id="">
            <option {{isset($text_local->text_local_status) && $text_local->text_local_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($text_local->text_local_status) && $text_local->text_local_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>

<div id="route_net_section" style="display: none" class="api-section">

    <div class="form-group">
        <label for="route_mobile_username">{{trans('admin.settings.api_secret')}}</label>
        <input required value="{{isset($route_net->route_net_api_secret)?$route_net->route_net_api_secret:''}}" class="form-control" type="text" name="route_net_api_secret" >
    </div>
    <div class="form-group">
        <label for="route_mobile_username">{{trans('Application ID')}}</label>
        <input required value="{{isset($route_net->route_net_app_id)?$route_net->route_net_app_id:''}}" class="form-control" type="text" name="route_net_app_id" >
    </div>

    <div class="form-group">
        <label for="route_mobile_username">{{trans('URL')}}</label>
        <input required value="{{isset($route_net->route_net_url)?$route_net->route_net_url:''}}" class="form-control" type="text" name="route_net_url" >
    </div>
    <div class="form-group">
        <label >{{trans('Status')}}</label>
        <select name="route_net_status" class="form-control" id="">
            <option {{isset($route_net->route_net_status) && $route_net->route_net_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($route_net->route_net_status) && $route_net->route_net_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>

<div id="hutchlk_section" style="display: none" class="api-section">

    <div class="form-group">
        <label for="route_mobile_username">{{trans('admin.settings.username')}}</label>
        <input required value="{{isset($hutchlk->hutchlk_username)?$hutchlk->hutchlk_username:''}}" class="form-control" type="text" name="hutchlk_username" >
    </div>
    <div class="form-group">
        <label for="route_mobile_username">{{trans('admin.settings.password')}}</label>
        <input required value="{{isset($hutchlk->hutchlk_password)?$hutchlk->hutchlk_password:''}}" class="form-control" type="text" name="hutchlk_password" >
    </div>

    <div class="form-group">
        <label for="route_mobile_username">{{trans('URL')}}</label>
        <input required value="{{isset($hutchlk->hutchlk_url)?$hutchlk->hutchlk_url:''}}" class="form-control" type="text" name="hutchlk_url" >
    </div>
    <div class="form-group">
        <label >{{trans('Status')}}</label>
        <select name="hutchlk_status" class="form-control" id="">
            <option {{isset($hutchlk->hutchlk_status) && $hutchlk->hutchlk_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($hutchlk->hutchlk_status) && $hutchlk->hutchlk_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>

<div id="teletopia_section" style="display: none" class="api-section">

    <div class="form-group">
        <label for="route_mobile_username">{{trans('admin.settings.username')}}</label>
        <input required value="{{isset($teletopia->teletopia_username)?$teletopia->teletopia_username:''}}" class="form-control" type="text" name="teletopia_username" >
    </div>
    <div class="form-group">
        <label for="route_mobile_username">{{trans('admin.settings.password')}}</label>
        <input required value="{{isset($teletopia->teletopia_password)?$teletopia->teletopia_password:''}}" class="form-control" type="text" name="teletopia_password" >
    </div>

    <div class="form-group">
        <label for="route_mobile_username">{{trans('URL')}}</label>
        <input required value="{{isset($teletopia->teletopia_url)?$teletopia->teletopia_url:''}}" class="form-control" type="text" name="teletopia_url" >
    </div>
    <div class="form-group">
        <label >{{trans('Status')}}</label>
        <select name="teletopia_status" class="form-control" id="">
            <option {{isset($teletopia->teletopia_status) && $teletopia->teletopia_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($teletopia->teletopia_status) && $teletopia->teletopia_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>

<div id="broadcaster_mobile_section" style="display: none" class="api-section">
    <div class="form-group">
        <label for="route_mobile_username">{{trans('admin.settings.api_key')}}</label>
        <input required value="{{isset($broadcaster_mobile->broadcaster_mobile_api_key)?$broadcaster_mobile->broadcaster_mobile_api_key:''}}" class="form-control" type="text" name="broadcaster_mobile_api_key" >
    </div>
   <div class="form-group row">
       <div class="col-md-6">
           <div class="form-group">
               <label for="route_mobile_username">{{trans('Country')}}</label>
               <input required value="{{isset($broadcaster_mobile->broadcaster_mobile_country)?$broadcaster_mobile->broadcaster_mobile_country:''}}" class="form-control" type="text" name="broadcaster_mobile_country" >
           </div>
       </div>

       <div class="col-md-6">
           <div class="form-group">
               <label for="route_mobile_username">{{trans('Tag')}}</label>
               <input required value="{{isset($broadcaster_mobile->broadcaster_mobile_tag)?$broadcaster_mobile->broadcaster_mobile_tag:''}}" class="form-control" type="text" name="broadcaster_mobile_tag" >
           </div>
       </div>
   </div>

    <div class="form-group">
        <label for="route_mobile_username">{{trans('URL')}}</label>
        <input required value="{{isset($broadcaster_mobile->broadcaster_mobile_url)?$broadcaster_mobile->broadcaster_mobile_url:''}}" class="form-control" type="text" name="broadcaster_mobile_url" >
    </div>

    <div class="form-group">
        <label for="route_mobile_username">{{trans('admin.settings.auth_token')}}</label>
        <input required value="{{isset($broadcaster_mobile->broadcaster_mobile_auth_token)?$broadcaster_mobile->broadcaster_mobile_auth_token:''}}" class="form-control" type="text" name="broadcaster_mobile_auth_token" >
    </div>
    <div class="form-group">
        <label >{{trans('Status')}}</label>
        <select name="broadcaster_mobile_status" class="form-control" id="">
            <option {{isset($broadcaster_mobile->broadcaster_mobile_status) && $broadcaster_mobile->broadcaster_mobile_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($broadcaster_mobile->broadcaster_mobile_status) && $broadcaster_mobile->broadcaster_mobile_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>

<div id="solutions4mobiles_section" style="display: none" class="api-section">

    <div class="form-group">
        <label for="route_mobile_username">{{trans('admin.settings.username')}}</label>
        <input required value="{{isset($solutions4mobiles->solutions4mobiles_username)?$solutions4mobiles->solutions4mobiles_username:''}}" class="form-control" type="text" name="solutions4mobiles_username" >
    </div>
    <div class="form-group">
        <label for="route_mobile_username">{{trans('admin.settings.password')}}</label>
        <input required value="{{isset($solutions4mobiles->solutions4mobiles_password)?$solutions4mobiles->solutions4mobiles_password:''}}" class="form-control" type="text" name="solutions4mobiles_password" >
    </div>

    <div class="form-group">
        <label >{{trans('Status')}}</label>
        <select name="solutions4mobiles_status" class="form-control" id="">
            <option {{isset($solutions4mobiles->solutions4mobiles_status) && $solutions4mobiles->solutions4mobiles_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($solutions4mobiles->solutions4mobiles_status) && $solutions4mobiles->solutions4mobiles_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>


<div id="beemAfrica_section" style="display: none" class="api-section">
    <div class="form-group">
        <label for="">{{trans('admin.settings.api_key')}}</label>
        <input required value="{{isset($beemAfrica->beemAfrica_api_key)?$beemAfrica->beemAfrica_api_key:''}}" class="form-control" type="text" name="beemAfrica_api_key" >
    </div>
    <div class="form-group">
        <label for="">{{trans('admin.settings.secret_key')}}</label>
        <input required value="{{isset($beemAfrica->beemAfrica_secret_key)?$beemAfrica->beemAfrica_secret_key:''}}" class="form-control" type="text" name="beemAfrica_secret_key" >
    </div>
    <div class="form-group">
        <label >{{trans('URL')}}</label>
        <input required value="{{isset($beemAfrica->beemAfrica_url)?$beemAfrica->beemAfrica_url:''}}" class="form-control" type="text" name="beemAfrica_url" >
    </div>

    <div class="form-group">
        <label >{{trans('Status')}}</label>
        <select name="beemAfrica_status" class="form-control" id="">
            <option {{isset($beemAfrica->beemAfrica_status) && $beemAfrica->beemAfrica_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($beemAfrica->beemAfrica_status) && $beemAfrica->beemAfrica_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>



<div id="bulkSMSOnline_section" style="display: none" class="api-section">
    <div class="form-group">
        <label for="">{{trans('admin.settings.username')}}</label>
        <input required value="{{isset($bulkSMSOnline->bulkSMSOnline_username)?$bulkSMSOnline->bulkSMSOnline_username:''}}" class="form-control" type="text" name="bulkSMSOnline_username" >
    </div>
    <div class="form-group">
        <label for="">{{trans('admin.settings.password')}}</label>
        <input required value="{{isset($bulkSMSOnline->bulkSMSOnline_password)?$bulkSMSOnline->bulkSMSOnline_password:''}}" class="form-control" type="text" name="bulkSMSOnline_password" >
    </div>
    <div class="from-group row">
        <div class="form-group col-md-6">
            <label>{{trans('Message Type')}}</label>
            <select name="bulkSMSOnline_sms_type" class="form-control" id="">
                <option {{isset($bulkSMSOnline->bulkSMSOnline_sms_type) && $bulkSMSOnline->bulkSMSOnline_sms_type=='arabic'?'selected':''}} value="arabic">Arabic</option>
                <option {{isset($bulkSMSOnline->bulkSMSOnline_sms_type) && $bulkSMSOnline->bulkSMSOnline_sms_type=='other'?'selected':''}} value="other">Others</option>
            </select>
        </div>
        <div class="form-group col-md-6">
            <div class="form-group">
                <label>{{trans('URL')}}</label>
                <input required value="{{isset($bulkSMSOnline->bulkSMSOnline_url)?$bulkSMSOnline->bulkSMSOnline_url:''}}"
                       class="form-control" type="text" name="beemAfrica_url">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label >{{trans('Status')}}</label>
        <select name="bulkSMSOnline_status" class="form-control" id="">
            <option {{isset($bulkSMSOnline->bulkSMSOnline_status) && $bulkSMSOnline->bulkSMSOnline_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($bulkSMSOnline->bulkSMSOnline_status) && $bulkSMSOnline->bulkSMSOnline_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>

<div id="flowRoute_section" style="display: none" class="api-section">
    <div class="form-group">
        <label for="">{{trans('admin.settings.access_key')}}</label>
        <input required value="{{isset($flowRoute->flowRoute_access_key)?$flowRoute->flowRoute_access_key:''}}" class="form-control" type="text" name="flowRoute_access_key" >
    </div>
    <div class="form-group">
        <label for="">{{trans('admin.settings.api_secret')}}</label>
        <input required value="{{isset($flowRoute->flowRoute_api_secret)?$flowRoute->flowRoute_api_secret:''}}" class="form-control" type="text" name="flowRoute_api_secret" >
    </div>
    <div class="from-group ">
            <div class="form-group">
                <label>{{trans('URL')}}</label>
                <input required value="{{isset($flowRoute->flowRoute_url)?$flowRoute->flowRoute_url:''}}"
                       class="form-control" type="text" name="flowRoute_url">
            </div>
    </div>

    <div class="form-group">
        <label >{{trans('Status')}}</label>
        <select name="flowRoute_status" class="form-control" id="">
            <option {{isset($flowRoute->flowRoute_status) && $flowRoute->flowRoute_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($flowRoute->flowRoute_status) && $flowRoute->flowRoute_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>

<div id="elitBuzzBD_section" style="display: none" class="api-section">
    <div class="form-group">
        <label for="">{{trans('admin.settings.api_key')}}</label>
        <input required value="{{isset($elitBuzzBD->elitBuzzBD_api_key)?$elitBuzzBD->elitBuzzBD_api_key:''}}" class="form-control" type="text" name="elitBuzzBD_api_key" >
    </div>
    <div class="from-group ">
            <div class="form-group">
                <label>{{trans('URL')}}</label>
                <input required value="{{isset($elitBuzzBD->elitBuzzBD_url)?$elitBuzzBD->elitBuzzBD_url:''}}"
                       class="form-control" type="text" name="elitBuzzBD_url">
            </div>
    </div>

    <div class="form-group">
        <label >{{trans('Status')}}</label>
        <select name="elitBuzzBD_status" class="form-control" id="">
            <option {{isset($elitBuzzBD->elitBuzzBD_status) && $elitBuzzBD->elitBuzzBD_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($elitBuzzBD->elitBuzzBD_status) && $elitBuzzBD->elitBuzzBD_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>

<div id="greenWebBD_section" style="display: none" class="api-section">
    <div class="form-group">
        <label for="">{{trans('admin.settings.api_key')}}</label>
        <input required value="{{isset($greenWebBD->greenWebBD_api_key)?$greenWebBD->greenWebBD_api_key:''}}" class="form-control" type="text" name="greenWebBD_api_key" >
    </div>
    <div class="from-group ">
            <div class="form-group">
                <label>{{trans('URL')}}</label>
                <input required value="{{isset($greenWebBD->greenWebBD_url)?$greenWebBD->greenWebBD_url:''}}"
                       class="form-control" type="text" name="greenWebBD_url">
            </div>
    </div>

    <div class="form-group">
        <label >{{trans('Status')}}</label>
        <select name="greenWebBD_status" class="form-control" id="">
            <option {{isset($greenWebBD->greenWebBD_status) && $greenWebBD->greenWebBD_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($greenWebBD->greenWebBD_status) && $greenWebBD->greenWebBD_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>

<div id="hablameV2_section" style="display: none" class="api-section">
    <div class="form-group">
        <label for="">{{trans('admin.settings.api_key')}}</label>
        <input required value="{{isset($hablameV2->hablameV2_api_key)?$hablameV2->hablameV2_api_key:''}}"
               class="form-control" type="text" name="hablameV2_api_key">
    </div>
    <div class="form-group">
        <label for="">{{trans('admin.settings.api_token')}}</label>
        <input required value="{{isset($hablameV2->hablameV2_api_token)?$hablameV2->hablameV2_api_token:''}}"
               class="form-control" type="text" name="hablameV2_api_token">
    </div>
    <div class="from-group ">
        <div class="form-group">
            <label>{{trans('URL')}}</label>
            <input required value="{{isset($hablameV2->hablameV2_url)?$hablameV2->hablameV2_url:''}}"
                   class="form-control" type="text" name="hablameV2_url">
        </div>
    </div>
    <div class="from-group ">
        <div class="form-group">
            <label>{{trans('Server CL')}}</label>
            <input required value="{{isset($hablameV2->hablameV2_server_cl)?$hablameV2->hablameV2_server_cl:''}}"
                   class="form-control" type="text" name="hablameV2_server_cl">
        </div>
    </div>

    <div class="form-group">
        <label>{{trans('Status')}}</label>
        <select name="hablameV2_status" class="form-control" id="">
            <option
                {{isset($hablameV2->hablameV2_status) && $hablameV2->hablameV2_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option
                {{isset($hablameV2->hablameV2_status) && $hablameV2->hablameV2_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>

<div id="zamtelCoZm_section" style="display: none" class="api-section">
    <div class="form-group">
        <label for="">{{trans('admin.settings.api_key')}}</label>
        <input required value="{{isset($zamtelCoZm->zamtelCoZm_api_key)?$zamtelCoZm->zamtelCoZm_api_key:''}}"
               class="form-control" type="text" name="zamtelCoZm_api_key">
    </div>

    <div class="from-group ">
        <div class="form-group">
            <label>{{trans('URL')}}</label>
            <input required value="{{isset($zamtelCoZm->zamtelCoZm_url)?$zamtelCoZm->zamtelCoZm_url:''}}"
                   class="form-control" type="text" name="zamtelCoZm_url">
        </div>
    </div>

    <div class="form-group">
        <label>{{trans('Status')}}</label>
        <select name="zamtelCoZm_status" class="form-control" id="">
            <option
                {{isset($zamtelCoZm->zamtelCoZm_status) && $zamtelCoZm->zamtelCoZm_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option
                {{isset($zamtelCoZm->zamtelCoZm_status) && $zamtelCoZm->zamtelCoZm_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>


<div id="thinq_section" style="display: none" class="api-section">
    <div class="from-group ">
        <div class="form-group">
            <label>{{trans('Account ID')}}</label>
            <input required value="{{isset($thinq->thinq_account_id)?$thinq->thinq_account_id:''}}"
                   class="form-control" type="text" name="thinq_account_id">
        </div>
    </div>

    <div class="form-group">
        <label>{{trans('admin.settings.username')}}</label>
        <input required value="{{isset($thinq->thinq_username)?$thinq->thinq_username:''}}"
               class="form-control" type="text" name="thinq_username">
    </div>
    <div class="form-group">
        <label>{{trans('Auth Token')}}</label>
        <input required value="{{isset($thinq->thinq_auth_token)?$thinq->thinq_auth_token:''}}"
               class="form-control" type="text" name="thinq_auth_token">
    </div>

    <div class="form-group">
        <label>{{trans('Status')}}</label>
        <select name="thinq_status" class="form-control" id="">
            <option
                {{isset($thinq->thinq_status) && $thinq->thinq_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option
                {{isset($thinq->thinq_status) && $thinq->thinq_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>

<div id="smpp_section" style="display: none" class="api-section">
    <div class="from-group ">
        <div class="form-group">
            <label>{{trans('IP Address/URL')}}</label>
            <input required value="{{isset($smpp->smpp_ip_address)?$smpp->smpp_ip_address:''}}"
                   class="form-control" type="text" name="smpp_ip_address">
        </div>
    </div>

    <div class="form-group">
        <label>{{trans('Username')}}</label>
        <input required value="{{isset($smpp->smpp_username)?$smpp->smpp_username:''}}"
               class="form-control" type="text" name="smpp_username">
    </div>

    <div class="form-group">
        <label>{{trans('Password')}}</label>
        <input required value="{{isset($smpp->smpp_password)?$smpp->smpp_password:''}}"
               class="form-control" type="text" name="smpp_password">
    </div>

    <div class="form-group">
        <label>{{trans('Port')}}</label>
        <input required value="{{isset($smpp->smpp_port)?$smpp->smpp_port:''}}"
               class="form-control" type="text" name="smpp_port">
    </div>

    <div class="form-group">
        <label>{{trans('Status')}}</label>
        <select name="smpp_status" class="form-control" id="">
            <option
                {{isset($smpp->smpp_status) && $smpp->smpp_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option
                {{isset($smpp->smpp_status) && $smpp->smpp_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>

<div id="bulksmsbd_section" style="display: none" class="api-section">
    <div class="from-group ">
        <div class="form-group">
            <label>{{trans('IP Address/URL')}}</label>
            <input required value="{{isset($bulksmsbd->bulksmsbd_url)?$bulksmsbd->bulksmsbd_url:''}}"
                   class="form-control" type="text" name="bulksmsbd_url">
        </div>
    </div>

    <div class="form-group">
        <label>{{trans('Username')}}</label>
        <input required value="{{isset($bulksmsbd->bulksmsbd_username)?$bulksmsbd->bulksmsbd_username:''}}"
               class="form-control" type="text" name="bulksmsbd_username">
    </div>

    <div class="form-group">
        <label>{{trans('Password')}}</label>
        <input required value="{{isset($bulksmsbd->bulksmsbd_password)?$bulksmsbd->bulksmsbd_password:''}}"
               class="form-control" type="text" name="bulksmsbd_password">
    </div>

    <div class="form-group">
        <label>{{trans('Status')}}</label>
        <select name="bulksmsbd_status" class="form-control" id="">
            <option
                {{isset($bulksmsbd->bulksmsbd_status) && $bulksmsbd->bulksmsbd_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option
                {{isset($bulksmsbd->bulksmsbd_status) && $bulksmsbd->bulksmsbd_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>


<div id="metro_tel_section" style="display: none" class="api-section">
    <div class="from-group ">
        <div class="form-group">
            <label>{{trans('URL')}}</label>
            <input required value="{{isset($metro_tel->metro_tel_url)?$metro_tel->metro_tel_url:''}}"
                   class="form-control" type="text" name="metro_tel_url">
        </div>
    </div>

    <div class="from-group ">
        <div class="form-group">
            <label>{{trans('API Kye')}}</label>
            <input required value="{{isset($metro_tel->metro_tel_api_key)?$metro_tel->metro_tel_api_key:''}}"
                   class="form-control" type="text" name="metro_tel_api_key">
        </div>
    </div>

    <div class="form-group">
        <label>{{trans('Status')}}</label>
        <select name="metro_tel_status" class="form-control" id="">
            <option
                {{isset($metro_tel->metro_tel_status) && $metro_tel->metro_tel_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option
                {{isset($metro_tel->metro_tel_status) && $metro_tel->metro_tel_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>


<div id="click_send_section" style="display: none" class="api-section">
    <div class="from-group ">
        <div class="form-group">
            <label>{{trans('Password')}}</label>
            <input required value="{{isset($click_send->click_send_password)?$click_send->click_send_password:''}}"
                   class="form-control" type="text" name="click_send_password">
        </div>
    </div>

    <div class="from-group ">
        <div class="form-group">
            <label>{{trans('Username')}}</label>
            <input required value="{{isset($click_send->click_send_username)?$click_send->click_send_username:''}}"
                   class="form-control" type="text" name="click_send_username">
        </div>
    </div>

    <div class="form-group">
        <label>{{trans('Status')}}</label>
        <select name="click_send_status" class="form-control" id="">
            <option
                {{isset($click_send->click_send_status) && $click_send->click_send_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option
                {{isset($click_send->click_send_status) && $click_send->click_send_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>




<div id="sms_noc_section" style="display: none" class="api-section">
    <div class="from-group ">
        <div class="form-group">
            <label>{{trans('URL')}}</label>
            <input required value="{{isset($ajuratech->ajuratech_url)?$ajuratech->ajuratech_url:''}}"
                   class="form-control" type="text" name="ajuratech_url">
        </div>
    </div>

    <div class="from-group ">
        <div class="form-group">
            <label>{{trans('API Key')}}</label>
            <input required value="{{isset($ajuratech->ajuratech_api_key)?$ajuratech->ajuratech_api_key:''}}"
                   class="form-control" type="text" name="ajuratech_api_key">
        </div>
    </div>
    <div class="from-group ">
        <div class="form-group">
            <label>{{trans('Secret Key')}}</label>
            <input required value="{{isset($ajuratech->ajuratech_secret_key)?$ajuratech->ajuratech_secret_key:''}}"
                   class="form-control" type="text" name="ajuratech_secret_key">
        </div>
    </div>

    <div class="form-group">
        <label>{{trans('Status')}}</label>
        <select name="ajuratech_status" class="form-control" id="">
            <option
                {{isset($ajuratech->ajuratech_status) && $ajuratech->ajuratech_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option
                {{isset($ajuratech->ajuratech_status) && $ajuratech->ajuratech_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>

<div id="sms_mkt_section" style="display: none" class="api-section">
    <div class="form-group">
        <label for="sms_mkt_api_key">{{trans('admin.settings.api_key')}}</label>
        <input required value="{{isset($sms_mkt->sms_mkt_api_key)?$sms_mkt->sms_mkt_api_key:''}}" class="form-control" type="text" name="sms_mkt_api_key" id="sms_mkt_api_key">
    </div>

    <div class="form-group">
        <label for="sms_mkt_secret_key">{{trans('admin.settings.secret_key')}}</label>
        <input required value="{{isset($sms_mkt->sms_mkt_secret_key)?$sms_mkt->sms_mkt_secret_key:''}}" class="form-control" type="text" name="sms_mkt_secret_key" >
    </div>

    <div class="form-group">
        <label for="sms_mkt_status">{{trans('Status')}}</label>
        <select name="sms_mkt_status" class="form-control" id="sms_mkt_status">
            <option {{isset($sms_mkt->sms_mkt_status) && $sms_mkt->sms_mkt_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($sms_mkt->sms_mkt_status) && $sms_mkt->sms_mkt_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>

<div id="adn_sms_section" style="display: none" class="api-section">
    <div class="form-group">
        <label for="">{{trans('admin.settings.api_key')}}</label>
        <input  value="{{isset($adn_sms->adn_sms_api_key)?$adn_sms->adn_sms_api_key:''}}" class="form-control" type="text" name="adn_sms_api_key">
    </div>

    <div class="form-group">
        <label for="">{{trans('admin.settings.secret_key')}}</label>
        <input  value="{{isset($adn_sms->adn_sms_api_secret)?$adn_sms->adn_sms_api_secret:''}}" class="form-control" type="text" name="adn_sms_api_secret" >
    </div>


    <div class="form-group">
        <label for="">{{trans('URL')}}</label>
        <input  value="{{isset($adn_sms->adn_sms_url)?$adn_sms->adn_sms_url:''}}" class="form-control" type="text" name="adn_sms_url" >
    </div>


    <div class="form-group">
        <label for="sms_mkt_status">{{trans('Message Type')}}</label>
        <select name="adn_sms_message_type" class="form-control">
            <option {{isset($adn_sms->adn_sms_message_type) && $adn_sms->adn_sms_message_type=='TEXT'?'selected':''}} value="TEXT">{{trans('TEXT')}}</option>
            <option {{isset($adn_sms->adn_sms_message_type) && $adn_sms->adn_sms_message_type=='UNICODE'?'selected':''}} value="UNICODE">{{trans('UNICODE')}}</option>
        </select>
    </div>
    <div class="form-group">
        <label for="sms_mkt_status">{{trans('Status')}}</label>
        <select name="adn_sms_status" class="form-control">
            <option {{isset($adn_sms->adn_sms_status) && $adn_sms->adn_sms_status=='inactive'?'selected':''}} value="inactive">{{trans('Inactive')}}</option>
            <option {{isset($adn_sms->adn_sms_status) && $adn_sms->adn_sms_status=='active'?'selected':''}} value="active">{{trans('Active')}}</option>
        </select>
    </div>
</div>
