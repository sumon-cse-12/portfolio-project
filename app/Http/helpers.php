<?php

use App\Models\Contact;
use App\Models\Customer;
use App\Models\CustomerNumber;
use App\Models\Domain;
use App\Models\Keyword;
use App\Models\KeywordContact;
use App\Models\Notice;
use Illuminate\Support\Facades\Log;
use Barryvdh\TranslationManager\Models\Translation;

function isSidebarActive($routeName)
{
    return request()->routeIs($routeName) ? 'active' : '';

}
function isSidebarTrue($routeNames)
{
    $istrue = false;
    foreach ($routeNames as $routeName){
        if (request()->routeIs($routeName)){
            $istrue = true;
            break;
        }
    }
    return $istrue;

}

function getCountryDialCode($phoneNumber){
    if(strlen($phoneNumber)==10){
        return "";
    }
    $phoneNumber=str_replace("+","",$phoneNumber);
    // get your list of country codes
    $ccodes=[];
    foreach(getCountryCode() as $country){
        $ccodes[$country['code']]="+".$country['code'];
    }

    krsort( $ccodes );
    $dialCode=null;
    foreach( $ccodes as $key=>$value )
    {
        if ( substr( $phoneNumber, 0, strlen( $key ) ) == $key )
        {
            // match
            $dialCode = $value;
            break;
        }
    }

    return $dialCode;
}

function getPhoneNumberWithoutDialCode($phoneNumber){
    if(strlen($phoneNumber)==10){
        return $phoneNumber;
    }
    $number=str_replace("+","",$phoneNumber);
    $dialCode=getCountryDialCode($number);
    if($dialCode){
        $withoutDial= preg_replace('/^' . preg_quote($dialCode, '/') . '/', '', $phoneNumber);
        if($withoutDial==$phoneNumber){
            $withoutDial= preg_replace('/^' . preg_quote($dialCode, '/') . '/', '', "+".$number);
        }
        return $withoutDial;
    }else{
        return $phoneNumber;
    }

}

function getPhoneNumberWithDialCode($phoneNumber,$dialcode=null){
    if($dialcode){
        return $dialcode.getPhoneNumberWithoutDialCode($phoneNumber);
    }
    return getCountryDialCode($phoneNumber).getPhoneNumberWithoutDialCode($phoneNumber);
}

function responseCode()
{

    return [
        '1001' => 'User not found',
        '1002' => 'Invalid Sender Id / Masking',
        '1003' => 'API Not Found',
        '1004' => 'Invalid Whatsapp Number',
        '1005' => 'Invalid Non SenderID / Non Masking',
        '1007' => 'Insufficient Balance',
        '1008' => 'Empty Message',
        '1009' => 'Message Type Not Set(text / unicode)',
        '1010' => 'Invalid Number',
        '1011' => 'Don\'t have Enough Non Masking Credit',
        '1012' => 'Don\'t have Enough Masking Credit',
        '1013' => 'Number not found please contact with administrator',
        '1014' => 'You have to Configure SMS Gateway',
        '1015' => 'Message sent partially',
        '1016' => 'Message sent successfully',
        '1017' => 'Customer doesn\'t have any Active Plan',
        '1018' => 'Please Activate the OTP',
        '1019' => 'This Provider is Inactive, Please Contact with Administrator',
        '1020' => 'Please Configure Provider Credentials',
        '1021' => 'Template Not Found',
        '1022' => 'Campaign created successfully',
    ];
}

function getLastNDays($days, $format = 'Y-m-d')
{
    $m = date("m");
    $de = date("d");
    $y = date("Y");
    $dateArray = array();
    for ($i = 0; $i <= $days - 1; $i++) {
        $dateArray[] = '"' . date($format, mktime(0, 0, 0, $m, ($de - $i), $y)) . '"';
    }
    return array_reverse($dateArray);
}


function get_gateway_description($server)
{
    $des = '';

    if ($server == 'signalwire') {
        $des = "";
    } elseif ($server == 'click_send') {
        $des = "";
    } elseif ($server == 'metro_tel') {
        $des = "";
    } elseif ($server == 'bulksmsbd') {
        $des = "";
    } elseif ($server == 'thinq') {
        $des = "";
    } elseif ($server == 'zamtelCoZm') {
        $des = "";
    } elseif ($server == 'hablameV2') {
        $des = "";
    } elseif ($server == 'bulkSMSOnline') {
        $des = "";
    } elseif ($server == 'greenWebBD') {
        $des = "";
    } elseif ($server == 'elitBuzzBD') {
        $des = "";
    } elseif ($server == 'beemAfrica') {
        $des = "";
    } elseif ($server == 'flowRoute') {
        $des = "";
    } elseif ($server == 'solutions4mobiles') {
        $des = "";
    } elseif ($server == 'broadcaster_mobile') {
        $des = "";
    } elseif ($server == 'hutchlk') {
        $des = "";
    } elseif ($server == 'twilio') {
        $des = "Twilio Description";
    } elseif ($server == 'vonage') {
        $des = "";
    } elseif ($server == 'telnyx') {
        $des = "";
    } elseif ($server == 'plivo') {
        $des = "";
    } elseif ($server == 'africastalking') {
        $des = "";
    } elseif ($server == 'nrs') {
        $des = "";
    } elseif ($server == 'message_bird') {
        $des = "";
    } elseif ($server == 'infobip') {
        $des = "";
    } elseif ($server == 'infobip') {
        $des = "";
    } elseif ($server == 'cheapglobalsms') {
        $des = "";
    } elseif ($server == 'plivo_powerpack') {
        $des = "";
    } elseif ($server == 'easysendsms') {
        $des = "";
    } elseif ($server == 'twilio_copilot') {
        $des = "";
    } elseif ($server == 'bulksms') {
        $des = "";
    } elseif ($server == 'ones_two_u') {
        $des = "";
    } elseif ($server == 'route_mobile') {
        $des = "";
    } elseif ($server == 'clickatel') {
        $des = "";
    } elseif ($server == 'hutch') {
        $des = "";
    } elseif ($server == 'estoresms') {
        $des = "";
    } elseif ($server == 'sms_global') {
        $des = "";
    } elseif ($server == 'tyntec') {
        $des = "";
    } elseif ($server == 'karix') {
        $des = "";
    } elseif ($server == 'bandwidth') {
        $des = "";
    } elseif ($server == 'text_local') {
        $des = "";
    } elseif ($server == 'route_net') {
        $des = "";
    } elseif ($server == 'smpp') {
        $des = "";
    }
    return $des;
}

//server fields
function servers_fields($server)
{
    $fields = [];

    if ($server == 'signalwire') {
        $fields = [
            'sw_project_id', 'sw_space_url', 'sw_token'
        ];
    } elseif ($server == 'click_send') {
        $fields = [
            'click_send_password', 'click_send_username'
        ];
    } elseif ($server == 'metro_tel') {
        $fields = [
            'metro_tel_url', 'metro_tel_api_key'
        ];
    } elseif ($server == 'bulksmsbd') {
        $fields = [
            'bulksmsbd_url', 'bulksmsbd_username', 'bulksmsbd_password'
        ];
    } elseif ($server == 'thinq') {
        $fields = [
            'thinq_account_id', 'thinq_username', 'thinq_auth_token'
        ];
    } elseif ($server == 'zamtelCoZm') {
        $fields = [
            'zamtelCoZm_api_key', 'zamtelCoZm_url'
        ];
    } elseif ($server == 'hablameV2') {
        $fields = [
            'hablameV2_api_key', 'hablameV2_api_token', 'hablameV2_url', 'hablameV2_server_cl'
        ];
    } elseif ($server == 'bulkSMSOnline') {
        $fields = [
            'bulkSMSOnline_username', 'bulkSMSOnline_password', 'bulkSMSOnline_sms_type', 'bulkSMSOnline_url'
        ];
    } elseif ($server == 'greenWebBD') {
        $fields = [
            'greenWebBD_api_key', 'greenWebBD_url'
        ];
    } elseif ($server == 'elitBuzzBD') {
        $fields = [
            'elitBuzzBD_api_key', 'elitBuzzBD_url'
        ];
    } elseif ($server == 'beemAfrica') {
        $fields = [
            'beemAfrica_api_key', 'beemAfrica_secret_key', 'beemAfrica_url'
        ];
    } elseif ($server == 'flowRoute') {
        $fields = [
            'beemAfrica_api_key', 'beemAfrica_secret_key', 'beemAfrica_url'
        ];
    } elseif ($server == 'solutions4mobiles') {
        $fields = [
            'solutions4mobiles_username', 'solutions4mobiles_password'
        ];
    } elseif ($server == 'broadcaster_mobile') {
        $fields = [
            'broadcaster_mobile_api_key', 'broadcaster_mobile_country', 'broadcaster_mobile_tag', 'broadcaster_mobile_url', 'broadcaster_mobile_auth_token'
        ];
    } elseif ($server == 'hutchlk') {
        $fields = [
            'hutchlk_username', 'hutchlk_password', 'hutchlk_url'
        ];
    } elseif ($server == 'twilio') {
        $fields = [
            'tw_sid', 'tw_token'
        ];
    } elseif ($server == 'vonage') {
        $fields = [
            'vg_api_key', 'vg_api_secret'
        ];
    } elseif ($server == 'telnyx') {
        $fields = [
            'tl_api_key'
        ];
    } elseif ($server == 'plivo') {
        $fields = [
            'pl_auth_id', 'pl_auth_token'
        ];
    } elseif ($server == 'africastalking') {
        $fields = [
            'africas_talking_username', 'africas_talking_api_key', 'africas_talking_url'
        ];
    } elseif ($server == 'nrs') {
        $fields = [
            'nrs_auth_token'
        ];
    } elseif ($server == 'message_bird') {
        $fields = [
            'message_bird_auth_token', 'message_bird_url'
        ];
    } elseif ($server == 'infobip') {
        $fields = [
            'infobip_prefix_key', 'infobip_api_key', 'url_base_path'
        ];
    } elseif ($server == 'infobip') {
        $fields = [
            'infobip_prefix_key', 'infobip_api_key', 'url_base_path'
        ];
    } elseif ($server == 'cheapglobalsms') {
        $fields = [
            'cheap_global_account', 'cheap_global_password'
        ];
    } elseif ($server == 'plivo_powerpack') {
        $fields = [
            'powerprek_auth_id', 'powerprek_auth_token'
        ];
    } elseif ($server == 'easysendsms') {
        $fields = [
            'easysendsms_username', 'easysendsms_password'
        ];
    } elseif ($server == 'twilio_copilot') {
        $fields = [
            'twilio_copilot_sid', 'twilio_copilot_auth_token'
        ];
    } elseif ($server == 'bulksms') {
        $fields = [
            'bulksms_username', 'bulksms_password'
        ];
    } elseif ($server == 'ones_two_u') {
        $fields = [
            'ones_two_u_username', 'ones_two_u_password'
        ];
    } elseif ($server == 'route_mobile') {
        $fields = [
            'route_mobile_username', 'route_mobile_password', 'route_mobile_url'
        ];
    } elseif ($server == 'clickatel') {
        $fields = [
            'clickatel_api_key', 'clickatel_url'
        ];
    } elseif ($server == 'hutch') {
        $fields = [
            'hutch_url', 'hutch_username', 'hutch_password'
        ];
    } elseif ($server == 'estoresms') {
        $fields = [
            'estoresms_username', 'estoresms_password'
        ];
    } elseif ($server == 'sms_global') {
        $fields = [
            'sms_global_username', 'sms_global_password', 'sms_global_url'
        ];
    } elseif ($server == 'tyntec') {
        $fields = [
            'tyntec_apikey', 'tyntec_url'
        ];
    } elseif ($server == 'karix') {
        $fields = [
            'karix_auth_id', 'karix_auth_token', 'karix_url'
        ];
    } elseif ($server == 'bandwidth') {
        $fields = [
            'bandwidth_auth_token', 'bandwidth_api_secret', 'bandwidth_app_id', 'bandwidth_url'
        ];
    } elseif ($server == 'text_local') {
        $fields = [
            'text_local_api_key', 'text_local_url'
        ];
    } elseif ($server == 'route_net') {
        $fields = [
            'route_net_api_secret', 'route_net_app_id', 'route_net_url'
        ];
    } elseif ($server == 'smpp') {
        $fields = [
            'smpp_ip_address','smpp_username','smpp_password','smpp_port'
        ];
    }
    return $fields;
}

function format_gateway_name($field)
{
    return ucwords(str_replace('_', ' ', $field));
}
function checkModule($name){
    $customer=auth('customer')->user();
    $cacheCustomerPLan = cache('current_plan_'.$customer->id);
    $customerPlan=isset($cacheCustomerPLan)?$cacheCustomerPLan:'';
    if($customerPlan && isset($customerPlan->module) && $customerPlan->module !='null'){
        $module= json_decode($customerPlan->module);
        if (in_array($name, $module)){
            return true;
        }
    }
}

function voice_sms_lang(){
    $lang=[
        'en',
        'sp',
        'cn'
    ];

    return $lang;
}

function getNotices($limit=null){
    $user=auth('customer')->user();
    if($limit && $limit > 0) {
        $notices = Notice::where('status', 'active')->where('for', $user->type)->orWhere('for', 'all')->orderByDesc('created_at')->limit($limit)->get();
    }else {
        $notices = Notice::where('status', 'active')->where('for', $user->type)->orWhere('for', 'all')->get();
    }
    if($notices->isNotEmpty()){
        $notices=$notices;
    }else{
        $notices='null';
    }
    return $notices;
}

function checkMasking()
{
    $customer = auth('customer')->user();
    $cacheCustomerPLan = cache('current_plan_'.$customer->id);
    if (!$cacheCustomerPLan) {
        return 'no';
    }
    $cache_in_seconds = env('CACHE_TIME');
    $cachePlans = cache('check_plan_masking_' . $customer->id);
    if (is_null($cachePlans)) {
        $plan = \App\Models\Plan::where('id', $customer->plan->plan_id)->first();
        $plan = cache()->remember('check_plan_masking_' . $customer->id, $cache_in_seconds, function () use ($plan) {
            return $plan;
        });
    } else {
        $plan = $cachePlans;
    }

    $maskingStatus = $plan && isset($plan->masking) ? $plan->masking : 'no';

    return $maskingStatus;
}


function checkVerifySeller()
{
    $cache_in_seconds = env('CACHE_TIME');
    $customer=auth('customer')->user();
    $cacheSellerRequest = cache('wallet_'.$customer->id);
    if (is_null($cacheSellerRequest)) {
        $sellerRequest = \App\Models\BecameReseller::where('customer_id', $customer->id)->where('status', 'approved')->first();
        $sellerRequest = cache()->remember('wallet_'.$customer->id, $cache_in_seconds, function () use ($sellerRequest) {
            return $sellerRequest;
        });
    } else {
        $sellerRequest = $cacheSellerRequest;
    }

        return $sellerRequest ? 'true' : 'false';

}
function get_pages($position)
{

    $pages = cache('pages');

    if (!$pages) {
        $pages = \App\Models\Page::where('status', 'published')->orderBy('created_at', 'desc')->get();

        $sortSettings = [];
        foreach ($pages as $page) {
            $sortSettings[$page->position][] = $page;
        }
        cache()->remember('pages', 10800, function () use ($sortSettings) {
            return $sortSettings;
        });
    } else {
        $sortSettings = $pages;
    }

    return isset($sortSettings[$position]) ? $sortSettings[$position] : [];
}
function getAllVoiceCallGateway(){
    $api = ['voice_call_soniyal'];
    return $api;
}

function api_availability(){
    $current_plan=auth('customer')->user()->plan;
    if($current_plan && $current_plan->api_availability=='yes'){
        return true;
    }else{
        return false;
    }
}
function getAllSmsGateway(){
    $api = [
        'signalwire',
        'twilio',
        'nexmo',
        'telnyx',
        'plivo',
        'africastalking',
        'nrs',
        'message_bird',
        'infobip',
        'cheapglobalsms',
        'plivo_powerpack',
       'easysendsms',
        'twilio_copilot',
        'bulksms',
        'ones_two_u',
        'clickatel',
        'route_mobile',
        'hutch',
        'estoresms',
        'sms_global',
        'tyntec',
        'karix',
        'bandwidth',
        'text_local',
        'route_net',
        'hutchlk',
        'broadcaster_mobile',
        'solutions4mobiles',
        'beemAfrica',
        'bulkSMSOnline',
        'flowRoute',
        'elitBuzzBD',
       'greenWebBD',
        'hablameV2',
        'zamtelCoZm',
        'thinq',
        'bulksmsbd',
        'smpp',
        'metro_tel',
        'click_send',
        'sms_noc',
        'sms_mkt',
        'adn_sms'
    ];

    return $api;
}
function getAllWhatsAppGateway(){
    $api = [
        'whatsapp_twilio',
        'whatsapp_textlocal',
        'whatsapp_soniyal',
    ];

    return $api;
}
function get_settings($name)
{
    $cache_in_seconds = env('CACHE_TIME');
    $value = cache('settings');
    $customer_settings = cache('customer_settings');
    $seller_settings = cache('seller_settings');
    if ($name == 'recaptcha_key' || $name == 'template' || $name == 'app_name' || $name == 'app_logo' || $name =='contact_info' || $name =='app_favicon' || $name =='for_seller' || $name == 'home_slider_section' || $name == 'home_contact_us') {
        $host = \Illuminate\Support\Facades\Request::getHost();
        $cacheDomain = cache('domain_data_'.$host);
        if (is_null($cacheDomain)) {
            // $domain = Domain::where('host', $host)->where('status', 'approved')->first();
            $domain='';
            $domain = cache()->remember('domain_data_'.$host, $cache_in_seconds, function () use ($domain) {
                return $domain;
            });
        } else {
            $domain = $cacheDomain;
        }
        if ($domain) {
            if (!$customer_settings) {
                $customer = Customer::find($domain->customer_id);
                $customerSetting = $customer->settings()->get();
                $customerSettings = ['for_seller' => 'true'];
                foreach ($customerSetting as $setting) {
                    $customerSettings[$setting->name] = $setting->value;
                }
                cache()->remember('customer_settings', 10800, function () use ($customerSettings) {
                    return $customerSettings;
                });
            } else {
                $customerSettings = $customer_settings;
            }
            return isset($customerSettings[$name])?$customerSettings[$name]:'';
        }
    }

    if (!$value) {
        if (\Schema::hasTable('settings')) {
            $settings = \App\Models\Settings::where('admin_id', 1)->get();
            $sortSettings = [];
            foreach ($settings as $setting) {
                $sortSettings[$setting->name] = $setting->value;
            }
            cache()->remember('settings', 10800, function () use ($sortSettings) {
                return $sortSettings;
            });
        }
    } else {
        $sortSettings = $value;
    }

    return isset($sortSettings[$name]) ? $sortSettings[$name] : '';
}

function getCustomerSettings($name)
{
    $customer_settings = cache('customer_settings');

    if (!$customer_settings) {
        $customerSetting = auth('customer')->user()->settings()->get();
        $customerSettings = [];
        foreach ($customerSetting as $setting) {
            $customerSettings[$setting->name] = $setting->value;
        }
        cache()->remember('customer_settings', 10800, function () use ($customerSettings) {
            return $customerSettings;
        });
    } else {
        $customerSettings = $customer_settings;
    }

    return isset($customerSettings[$name]) ? $customerSettings[$name] : '';
}
function formatDate($date)
{

    $dateTime = new DateTime($date);
    $time = $dateTime->format("H:i A");
    $setting = json_decode(get_settings('local_setting'));
    if (!isset($setting->date_time_format) || !isset($setting->date_time_separator) || !isset($setting->timezone)) return $date;
    $date = str_replace([',', '_'], [', ', ' '], Carbon\Carbon::createFromTimeString($date)->timezone($setting->timezone)->format(str_replace(' ', $setting->date_time_separator, $setting->date_time_format)));
    $time=isset($time)?$time:'';
    $dateWithTime = $date.' ,'.$time;
    return $dateWithTime;
}
function formatNumber($number)
{
//    $number=round((int)$number);
    $setting = json_decode(get_settings('local_setting'));
    if (!isset($setting->decimal_format) || !isset($setting->decimals) || !isset($setting->thousand_separator)) return $number;

    try {
        return number_format($number, $setting->decimals, $setting->decimal_format, $setting->thousand_separator);
    } catch (\Exception $ex) {
        return $number;
    }
}
function get_email_template($template,$type=null)
{
    if($type=='customer') {
        $customer=auth('customer')->user();
        $temp = \App\Models\EmailTemplate::where('type', $template)->where('added_by', $customer->type)->where('user_id', $customer->id)->where('status', 'active')->first();
        return $temp;
    }else{
        $temp = \App\Models\EmailTemplate::where('type', $template)->where('added_by', 'admin')->where('status', 'active')->first();
        return $temp;
    }

}

function getAllTimeZones()
{
    $timezone = array();
    $timestamp = time();

    foreach (timezone_identifiers_list(\DateTimeZone::ALL) as $key => $t) {
        date_default_timezone_set($t);
        $timezone[$key]['zone'] = $t;
        $timezone[$key]['GMT_difference'] = date('P', $timestamp);
    }
    $timezone = collect($timezone)->sortBy('GMT_difference');
    return $timezone;
}

function get_available_languages()
{
    $value = cache('language');

    if (!$value) {
        $all_locales = '';
        // $all_locales = Translation::select('locale')->groupBy('locale')->get()->pluck('locale')->toArray();
        cache()->remember('language', 10800, function () use ($all_locales) {
            return $all_locales;
        });
        return $all_locales;
    } else {
        return $value;
    }
}

function getAllPaymentGateway(){
    return [
        'paypal',
        'stripe',
        'paytm',
        'mollie',
        'paystack',
        'flutterwavw',
        'voguepay',
        'iyzico',
        'authorize_net',
        'coinpayment',
        'sslcommerz',
        'uddoktapay',
    ];
}
function getCountryCode()
{
    $countryArray = array(
        'AD' => array('name' => 'ANDORRA', 'code' => '376'),
        'AE' => array('name' => 'UNITED ARAB EMIRATES', 'code' => '971'),
        'AF' => array('name' => 'AFGHANISTAN', 'code' => '93'),
        'AG' => array('name' => 'ANTIGUA AND BARBUDA', 'code' => '1268'),
        'AI' => array('name' => 'ANGUILLA', 'code' => '1264'),
        'AL' => array('name' => 'ALBANIA', 'code' => '355'),
        'AM' => array('name' => 'ARMENIA', 'code' => '374'),
        'AN' => array('name' => 'NETHERLANDS ANTILLES', 'code' => '599'),
        'AO' => array('name' => 'ANGOLA', 'code' => '244'),
        'AQ' => array('name' => 'ANTARCTICA', 'code' => '672'),
        'AR' => array('name' => 'ARGENTINA', 'code' => '54'),
        'AS' => array('name' => 'AMERICAN SAMOA', 'code' => '1684'),
        'AT' => array('name' => 'AUSTRIA', 'code' => '43'),
        'AU' => array('name' => 'AUSTRALIA', 'code' => '61'),
        'AW' => array('name' => 'ARUBA', 'code' => '297'),
        'AZ' => array('name' => 'AZERBAIJAN', 'code' => '994'),
        'BA' => array('name' => 'BOSNIA AND HERZEGOVINA', 'code' => '387'),
        'BB' => array('name' => 'BARBADOS', 'code' => '1246'),
        'BD' => array('name' => 'BANGLADESH', 'code' => '880'),
        'BE' => array('name' => 'BELGIUM', 'code' => '32'),
        'BF' => array('name' => 'BURKINA FASO', 'code' => '226'),
        'BG' => array('name' => 'BULGARIA', 'code' => '359'),
        'BH' => array('name' => 'BAHRAIN', 'code' => '973'),
        'BI' => array('name' => 'BURUNDI', 'code' => '257'),
        'BJ' => array('name' => 'BENIN', 'code' => '229'),
        'BL' => array('name' => 'SAINT BARTHELEMY', 'code' => '590'),
        'BM' => array('name' => 'BERMUDA', 'code' => '1441'),
        'BN' => array('name' => 'BRUNEI DARUSSALAM', 'code' => '673'),
        'BO' => array('name' => 'BOLIVIA', 'code' => '591'),
        'BR' => array('name' => 'BRAZIL', 'code' => '55'),
        'BS' => array('name' => 'BAHAMAS', 'code' => '1242'),
        'BT' => array('name' => 'BHUTAN', 'code' => '975'),
        'BW' => array('name' => 'BOTSWANA', 'code' => '267'),
        'BY' => array('name' => 'BELARUS', 'code' => '375'),
        'BZ' => array('name' => 'BELIZE', 'code' => '501'),
        'CA' => array('name' => 'CANADA', 'code' => '1'),
        'CC' => array('name' => 'COCOS (KEELING) ISLANDS', 'code' => '61'),
        'CD' => array('name' => 'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'code' => '243'),
        'CF' => array('name' => 'CENTRAL AFRICAN REPUBLIC', 'code' => '236'),
        'CG' => array('name' => 'CONGO', 'code' => '242'),
        'CH' => array('name' => 'SWITZERLAND', 'code' => '41'),
        'CI' => array('name' => 'COTE D IVOIRE', 'code' => '225'),
        'CK' => array('name' => 'COOK ISLANDS', 'code' => '682'),
        'CL' => array('name' => 'CHILE', 'code' => '56'),
        'CM' => array('name' => 'CAMEROON', 'code' => '237'),
        'CN' => array('name' => 'CHINA', 'code' => '86'),
        'CO' => array('name' => 'COLOMBIA', 'code' => '57'),
        'CR' => array('name' => 'COSTA RICA', 'code' => '506'),
        'CU' => array('name' => 'CUBA', 'code' => '53'),
        'CV' => array('name' => 'CAPE VERDE', 'code' => '238'),
        'CX' => array('name' => 'CHRISTMAS ISLAND', 'code' => '61'),
        'CY' => array('name' => 'CYPRUS', 'code' => '357'),
        'CZ' => array('name' => 'CZECH REPUBLIC', 'code' => '420'),
        'DE' => array('name' => 'GERMANY', 'code' => '49'),
        'DJ' => array('name' => 'DJIBOUTI', 'code' => '253'),
        'DK' => array('name' => 'DENMARK', 'code' => '45'),
        'DM' => array('name' => 'DOMINICA', 'code' => '1767'),
        'DO' => array('name' => 'DOMINICAN REPUBLIC', 'code' => '1809'),
        'DZ' => array('name' => 'ALGERIA', 'code' => '213'),
        'EC' => array('name' => 'ECUADOR', 'code' => '593'),
        'EE' => array('name' => 'ESTONIA', 'code' => '372'),
        'EG' => array('name' => 'EGYPT', 'code' => '20'),
        'ER' => array('name' => 'ERITREA', 'code' => '291'),
        'ES' => array('name' => 'SPAIN', 'code' => '34'),
        'ET' => array('name' => 'ETHIOPIA', 'code' => '251'),
        'FI' => array('name' => 'FINLAND', 'code' => '358'),
        'FJ' => array('name' => 'FIJI', 'code' => '679'),
        'FK' => array('name' => 'FALKLAND ISLANDS (MALVINAS)', 'code' => '500'),
        'FM' => array('name' => 'MICRONESIA, FEDERATED STATES OF', 'code' => '691'),
        'FO' => array('name' => 'FAROE ISLANDS', 'code' => '298'),
        'FR' => array('name' => 'FRANCE', 'code' => '33'),
        'GA' => array('name' => 'GABON', 'code' => '241'),
        'GB' => array('name' => 'UNITED KINGDOM', 'code' => '44'),
        'GD' => array('name' => 'GRENADA', 'code' => '1473'),
        'GE' => array('name' => 'GEORGIA', 'code' => '995'),
        'GH' => array('name' => 'GHANA', 'code' => '233'),
        'GI' => array('name' => 'GIBRALTAR', 'code' => '350'),
        'GL' => array('name' => 'GREENLAND', 'code' => '299'),
        'GM' => array('name' => 'GAMBIA', 'code' => '220'),
        'GN' => array('name' => 'GUINEA', 'code' => '224'),
        'GQ' => array('name' => 'EQUATORIAL GUINEA', 'code' => '240'),
        'GR' => array('name' => 'GREECE', 'code' => '30'),
        'GT' => array('name' => 'GUATEMALA', 'code' => '502'),
        'GU' => array('name' => 'GUAM', 'code' => '1671'),
        'GW' => array('name' => 'GUINEA-BISSAU', 'code' => '245'),
        'GY' => array('name' => 'GUYANA', 'code' => '592'),
        'HK' => array('name' => 'HONG KONG', 'code' => '852'),
        'HN' => array('name' => 'HONDURAS', 'code' => '504'),
        'HR' => array('name' => 'CROATIA', 'code' => '385'),
        'HT' => array('name' => 'HAITI', 'code' => '509'),
        'HU' => array('name' => 'HUNGARY', 'code' => '36'),
        'ID' => array('name' => 'INDONESIA', 'code' => '62'),
        'IE' => array('name' => 'IRELAND', 'code' => '353'),
        'IL' => array('name' => 'ISRAEL', 'code' => '972'),
        'IM' => array('name' => 'ISLE OF MAN', 'code' => '44'),
        'IN' => array('name' => 'INDIA', 'code' => '91'),
        'IQ' => array('name' => 'IRAQ', 'code' => '964'),
        'IR' => array('name' => 'IRAN, ISLAMIC REPUBLIC OF', 'code' => '98'),
        'IS' => array('name' => 'ICELAND', 'code' => '354'),
        'IT' => array('name' => 'ITALY', 'code' => '39'),
        'JM' => array('name' => 'JAMAICA', 'code' => '1876'),
        'JO' => array('name' => 'JORDAN', 'code' => '962'),
        'JP' => array('name' => 'JAPAN', 'code' => '81'),
        'KE' => array('name' => 'KENYA', 'code' => '254'),
        'KG' => array('name' => 'KYRGYZSTAN', 'code' => '996'),
        'KH' => array('name' => 'CAMBODIA', 'code' => '855'),
        'KI' => array('name' => 'KIRIBATI', 'code' => '686'),
        'KM' => array('name' => 'COMOROS', 'code' => '269'),
        'KN' => array('name' => 'SAINT KITTS AND NEVIS', 'code' => '1869'),
        'KP' => array('name' => 'KOREA DEMOCRATIC PEOPLES REPUBLIC OF', 'code' => '850'),
        'KR' => array('name' => 'KOREA REPUBLIC OF', 'code' => '82'),
        'KW' => array('name' => 'KUWAIT', 'code' => '965'),
        'KY' => array('name' => 'CAYMAN ISLANDS', 'code' => '1345'),
        'KZ' => array('name' => 'KAZAKSTAN', 'code' => '7'),
        'LA' => array('name' => 'LAO PEOPLES DEMOCRATIC REPUBLIC', 'code' => '856'),
        'LB' => array('name' => 'LEBANON', 'code' => '961'),
        'LC' => array('name' => 'SAINT LUCIA', 'code' => '1758'),
        'LI' => array('name' => 'LIECHTENSTEIN', 'code' => '423'),
        'LK' => array('name' => 'SRI LANKA', 'code' => '94'),
        'LR' => array('name' => 'LIBERIA', 'code' => '231'),
        'LS' => array('name' => 'LESOTHO', 'code' => '266'),
        'LT' => array('name' => 'LITHUANIA', 'code' => '370'),
        'LU' => array('name' => 'LUXEMBOURG', 'code' => '352'),
        'LV' => array('name' => 'LATVIA', 'code' => '371'),
        'LY' => array('name' => 'LIBYAN ARAB JAMAHIRIYA', 'code' => '218'),
        'MA' => array('name' => 'MOROCCO', 'code' => '212'),
        'MC' => array('name' => 'MONACO', 'code' => '377'),
        'MD' => array('name' => 'MOLDOVA, REPUBLIC OF', 'code' => '373'),
        'ME' => array('name' => 'MONTENEGRO', 'code' => '382'),
        'MF' => array('name' => 'SAINT MARTIN', 'code' => '1599'),
        'MG' => array('name' => 'MADAGASCAR', 'code' => '261'),
        'MH' => array('name' => 'MARSHALL ISLANDS', 'code' => '692'),
        'MK' => array('name' => 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'code' => '389'),
        'ML' => array('name' => 'MALI', 'code' => '223'),
        'MM' => array('name' => 'MYANMAR', 'code' => '95'),
        'MN' => array('name' => 'MONGOLIA', 'code' => '976'),
        'MO' => array('name' => 'MACAU', 'code' => '853'),
        'MP' => array('name' => 'NORTHERN MARIANA ISLANDS', 'code' => '1670'),
        'MR' => array('name' => 'MAURITANIA', 'code' => '222'),
        'MS' => array('name' => 'MONTSERRAT', 'code' => '1664'),
        'MT' => array('name' => 'MALTA', 'code' => '356'),
        'MU' => array('name' => 'MAURITIUS', 'code' => '230'),
        'MV' => array('name' => 'MALDIVES', 'code' => '960'),
        'MW' => array('name' => 'MALAWI', 'code' => '265'),
        'MX' => array('name' => 'MEXICO', 'code' => '52'),
        'MY' => array('name' => 'MALAYSIA', 'code' => '60'),
        'MZ' => array('name' => 'MOZAMBIQUE', 'code' => '258'),
        'NA' => array('name' => 'NAMIBIA', 'code' => '264'),
        'NC' => array('name' => 'NEW CALEDONIA', 'code' => '687'),
        'NE' => array('name' => 'NIGER', 'code' => '227'),
        'NG' => array('name' => 'NIGERIA', 'code' => '234'),
        'NI' => array('name' => 'NICARAGUA', 'code' => '505'),
        'NL' => array('name' => 'NETHERLANDS', 'code' => '31'),
        'NO' => array('name' => 'NORWAY', 'code' => '47'),
        'NP' => array('name' => 'NEPAL', 'code' => '977'),
        'NR' => array('name' => 'NAURU', 'code' => '674'),
        'NU' => array('name' => 'NIUE', 'code' => '683'),
        'NZ' => array('name' => 'NEW ZEALAND', 'code' => '64'),
        'OM' => array('name' => 'OMAN', 'code' => '968'),
        'PA' => array('name' => 'PANAMA', 'code' => '507'),
        'PE' => array('name' => 'PERU', 'code' => '51'),
        'PF' => array('name' => 'FRENCH POLYNESIA', 'code' => '689'),
        'PG' => array('name' => 'PAPUA NEW GUINEA', 'code' => '675'),
        'PH' => array('name' => 'PHILIPPINES', 'code' => '63'),
        'PK' => array('name' => 'PAKISTAN', 'code' => '92'),
        'PL' => array('name' => 'POLAND', 'code' => '48'),
        'PM' => array('name' => 'SAINT PIERRE AND MIQUELON', 'code' => '508'),
        'PN' => array('name' => 'PITCAIRN', 'code' => '870'),
        'PR' => array('name' => 'PUERTO RICO', 'code' => '1'),
        'PT' => array('name' => 'PORTUGAL', 'code' => '351'),
        'PW' => array('name' => 'PALAU', 'code' => '680'),
        'PY' => array('name' => 'PARAGUAY', 'code' => '595'),
        'QA' => array('name' => 'QATAR', 'code' => '974'),
        'RO' => array('name' => 'ROMANIA', 'code' => '40'),
        'RS' => array('name' => 'SERBIA', 'code' => '381'),
        'RU' => array('name' => 'RUSSIAN FEDERATION', 'code' => '7'),
        'RW' => array('name' => 'RWANDA', 'code' => '250'),
        'SA' => array('name' => 'SAUDI ARABIA', 'code' => '966'),
        'SB' => array('name' => 'SOLOMON ISLANDS', 'code' => '677'),
        'SC' => array('name' => 'SEYCHELLES', 'code' => '248'),
        'SD' => array('name' => 'SUDAN', 'code' => '249'),
        'SE' => array('name' => 'SWEDEN', 'code' => '46'),
        'SG' => array('name' => 'SINGAPORE', 'code' => '65'),
        'SH' => array('name' => 'SAINT HELENA', 'code' => '290'),
        'SI' => array('name' => 'SLOVENIA', 'code' => '386'),
        'SK' => array('name' => 'SLOVAKIA', 'code' => '421'),
        'SL' => array('name' => 'SIERRA LEONE', 'code' => '232'),
        'SM' => array('name' => 'SAN MARINO', 'code' => '378'),
        'SN' => array('name' => 'SENEGAL', 'code' => '221'),
        'SO' => array('name' => 'SOMALIA', 'code' => '252'),
        'SR' => array('name' => 'SURINAME', 'code' => '597'),
        'ST' => array('name' => 'SAO TOME AND PRINCIPE', 'code' => '239'),
        'SV' => array('name' => 'EL SALVADOR', 'code' => '503'),
        'SY' => array('name' => 'SYRIAN ARAB REPUBLIC', 'code' => '963'),
        'SZ' => array('name' => 'SWAZILAND', 'code' => '268'),
        'TC' => array('name' => 'TURKS AND CAICOS ISLANDS', 'code' => '1649'),
        'TD' => array('name' => 'CHAD', 'code' => '235'),
        'TG' => array('name' => 'TOGO', 'code' => '228'),
        'TH' => array('name' => 'THAILAND', 'code' => '66'),
        'TJ' => array('name' => 'TAJIKISTAN', 'code' => '992'),
        'TK' => array('name' => 'TOKELAU', 'code' => '690'),
        'TL' => array('name' => 'TIMOR-LESTE', 'code' => '670'),
        'TM' => array('name' => 'TURKMENISTAN', 'code' => '993'),
        'TN' => array('name' => 'TUNISIA', 'code' => '216'),
        'TO' => array('name' => 'TONGA', 'code' => '676'),
        'TR' => array('name' => 'TURKEY', 'code' => '90'),
        'TT' => array('name' => 'TRINIDAD AND TOBAGO', 'code' => '1868'),
        'TV' => array('name' => 'TUVALU', 'code' => '688'),
        'TW' => array('name' => 'TAIWAN, PROVINCE OF CHINA', 'code' => '886'),
        'TZ' => array('name' => 'TANZANIA, UNITED REPUBLIC OF', 'code' => '255'),
        'UA' => array('name' => 'UKRAINE', 'code' => '380'),
        'UG' => array('name' => 'UGANDA', 'code' => '256'),
        'US' => array('name' => 'UNITED STATES', 'code' => '1'),
        'UY' => array('name' => 'URUGUAY', 'code' => '598'),
        'UZ' => array('name' => 'UZBEKISTAN', 'code' => '998'),
        'VA' => array('name' => 'HOLY SEE (VATICAN CITY STATE)', 'code' => '39'),
        'VC' => array('name' => 'SAINT VINCENT AND THE GRENADINES', 'code' => '1784'),
        'VE' => array('name' => 'VENEZUELA', 'code' => '58'),
        'VG' => array('name' => 'VIRGIN ISLANDS, BRITISH', 'code' => '1284'),
        'VI' => array('name' => 'VIRGIN ISLANDS, U.S.', 'code' => '1340'),
        'VN' => array('name' => 'VIET NAM', 'code' => '84'),
        'VU' => array('name' => 'VANUATU', 'code' => '678'),
        'WF' => array('name' => 'WALLIS AND FUTUNA', 'code' => '681'),
        'WS' => array('name' => 'SAMOA', 'code' => '685'),
        'XK' => array('name' => 'KOSOVO', 'code' => '381'),
        'YE' => array('name' => 'YEMEN', 'code' => '967'),
        'YT' => array('name' => 'MAYOTTE', 'code' => '262'),
        'ZA' => array('name' => 'SOUTH AFRICA', 'code' => '27'),
        'ZM' => array('name' => 'ZAMBIA', 'code' => '260'),
        'ZW' => array('name' => 'ZIMBABWE', 'code' => '263')
    );
    return $countryArray;
}


function sms_template_variables()
{

    $arry = [
        '{first_name}' => 'first_name',
        '{last_name}' => 'last_name',
        '{zip_code}' => 'zip_code',
        '{address}' => 'address',
        '{state}' => 'state',
        '{city}' => 'city',
        '{email}' => 'email',

    ];

    return $arry;
}

function setEnv($key, $value)
{
    file_put_contents(app()->environmentFilePath(), str_replace(
        $key . '=' . env($value),
        $key . '=' . $value,
        file_get_contents(app()->environmentFilePath())
    ));
}
function formatNumberWithCurrSymbol($number)
{
    $setting = json_decode(get_settings('local_setting'));
    $formattedNumber = formatNumber($number);
    if (!isset($setting->currency_symbol_position) || !isset($setting->currency_symbol)) return $formattedNumber;
    if ($setting->currency_symbol_position == 'after')
        return $formattedNumber . $setting->currency_symbol;
    else if ($setting->currency_symbol_position == 'before')
        return $setting->currency_symbol . $formattedNumber;
    else
        return $formattedNumber;
}

