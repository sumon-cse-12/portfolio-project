<?php

namespace App\Http\Controllers\Admin;

use App\Events\DeleteSmsData;
use App\Events\SendMail;
use App\Http\Controllers\Controller;
use App\Listeners\ProcessDeleteData;
use App\Models\BecameReseller;
use App\Models\Customer;
use App\Models\DbBackup;
use App\Models\EmailTemplate;
use App\Models\Expense;
use App\Models\Message;
use App\Models\MessageLog;
use App\Models\Number;
use App\Models\Settings;
use App\Models\SmsQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

class SettingsController extends Controller
{
    public function index()
    {
        $sendingSetting = json_decode(get_settings('sending_setting'));
        $data['offdays'] = isset($sendingSetting) && isset($sendingSetting->offdays) ? json_decode($sendingSetting->offdays) : [];
        $data['admin'] = auth()->user();
        $data['seller']=Customer::whereIn('type', ['master_reseller', 'reseller', 'master_reseller_customer', 'reseller_customer'])->first();
        return view('admin.settings.index', $data);
    }

    public function otpSettins(){
        $data['customers']=Customer::orderByDesc('created_at')->get();
        return view('admin.settings.otp_settings', $data);
    }


    public function activeOtpUser(Request $request)
    {

        $customers = Customer::select(['id', 'first_name', 'last_name','type', 'email', 'otp_status', 'created_at'])->where('otp_status', 'active');

        return datatables()->of($customers)
            ->addColumn('full_name', function ($q) {
                return $q->full_name;
            })
            ->addColumn('email', function ($q) {
                return $q->email;
            })
            ->addColumn('status', function (Customer $q) {
                if($q->otp_status=='active'){
                    $status= '<strong class="text-success"> '.ucfirst($q->status).' </strong>';
                }else{
                    $status= '<strong class="text-danger"> '.ucfirst($q->status).' </strong>';
                }
                return $status;
            })

            ->rawColumns(['action','status'])
            ->toJson();
    }

    public function getOtpStatus(Request $request){

        $customer= Customer::where('id', $request->customer_id)->first();

        if (!$customer){
            return response()->json(['message'=>'Customer Not Found']);
        }

        return response()->json(['data'=>$customer->otp_status]);
    }

    public function getGatewayNumber(Request  $request){

        $numbers= Number::select('number','id')->where('status','active')->where('from', $request->from)->get();

        return response()->json(['data'=>$numbers]);
    }

    public function otpSetting(Request $request){
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }
        $customer= Customer::where('id', $request->customer_id)->first();
        if (!$customer){
            return redirect()->back()->withErrors(['failed'=>'Invalid user']);
        }

        $customer->otp_status=$request->customer_otp_status;
        $customer->save();

        return redirect()->back()->with('success', 'OTP Setting Successfully Updated');
    }

    public function profile_update(Request $request)
    {
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }
        $request->validate([
            'u_name' => 'required',
            'email' => 'required|unique:users,email,' . auth()->id(),
            'profile' => 'image',
        ]);
        $pre_email = auth()->user()->email;
        $new_email = $request->email;
        $user = auth()->user();
        if ($pre_email != $new_email) {
            $user->email_verified_at = null;

            //TODO::send email here to verify email address
        }
        $user->name = $request->u_name;
        $user->email = $new_email;
        if ($request->password)
            $user->password = bcrypt($request->password);

        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $imageName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageName);
            $user->profile_picture = $imageName;
        }

        $user->save();
        cache()->flush();
        return redirect()->back()->with('success', 'Profile successfully updated');
    }

    public function app_update(Request $request)
    {
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }
        $request->validate([
            'app_name' => 'required',
            'logo'=>'image',
            'favicon'=>'image',
        ]);

        //TODO:: in future update the settings dynamically

        //update application name
        $data = ['name' => 'app_name'];
        $setting = auth()->user()->settings()->firstOrNew($data);
        $setting->value = $request->app_name;
        $setting->save();


        $data = ['name' => 'crisp_token'];
        $setting = auth()->user()->settings()->firstOrNew($data);
        $setting->value = $request->crisp_token;
        $setting->save();

        $data = ['name' => 'recaptcha_site_key'];
        $setting = auth()->user()->settings()->firstOrNew($data);
        $setting->value = $request->recaptcha_site_key;
        $setting->save();

        $data = ['name' => 'registration_status'];
        $setting = auth()->user()->settings()->firstOrNew($data);
        $setting->value = $request->registration_status;
        $setting->save();

        $data = ['name' => 'landing_page_status'];
        $setting = auth()->user()->settings()->firstOrNew($data);
        $setting->value = $request->landing_page_status;
        $setting->save();

        $data=['name'=>'contact_info'];
        $requestData=$request->only('phone_number','email_address','address');
        $setting = auth()->user()->settings()->firstOrNew($data);
        $setting->value = json_encode($requestData);
        $setting->save();

        $data = ['name' => 'notice_status'];
        $setting = auth()->user()->settings()->firstOrNew($data);
        $setting->value = $request->notice_status;
        $setting->save();

        $data = ['name' => 'maintence_mode'];
        $setting = auth()->user()->settings()->firstOrNew($data);
        $setting->value = $request->maintence_mode;
        $setting->save();

        if ($request->reseller_status=='disable'){
            $findSeller=Customer::whereIn('type', ['reseller', 'master_reseller'])->count();
            if ($findSeller && $findSeller > 0){
                return  redirect()->back()->withErrors(['failed'=>'You can not disable seller status at this moment']);
            }
        }
        if (!$request->reseller_status){
            $request['reseller_status']='enable';
        }
        $data = ['name' => 'reseller_status'];
        $setting = auth()->user()->settings()->firstOrNew($data);
        $setting->value = $request->reseller_status;
        $setting->save();

        $data = ['name' => 'recaptcha_key'];
        $setting = auth()->user()->settings()->firstOrNew($data);
        $setting->value = json_encode($request->only('recaptcha_secret_key', 'recaptcha_site_key'));
        $setting->save();

        //update favicon
        if ($request->hasFile('favicon')) {

            $file = $request->file('favicon');
            $favicon_name = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $favicon_name);

            $data = ['name' => 'app_favicon'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = $favicon_name;
            $setting->save();
        }

        //update logo
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $logo_name = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $logo_name);

            $data = ['name' => 'app_logo'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = $logo_name;
            $setting->save();
        }
        cache()->flush();
        return redirect()->back()->with('success', 'Application successfully updated');
    }

    public function sending_setting(Request $request){
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }
        $data=['name'=>'daily_send_limit'];
        $sendData=$request->only('send_limit');
        $setting = auth()->user()->settings()->firstOrNew($data);
        $setting->value = json_encode($sendData);
        $setting->save();

        $sendLimit=['name'=>'minute_send_limit'];
        $sendLimitData=$request->only('message_limit','minute_limit');
        $setting = auth()->user()->settings()->firstOrNew($sendLimit);
        $setting->value = json_encode($sendLimitData);
        $setting->save();

        $days=[];
        if($request->offday){
            foreach ($request->offday as $key=>$day){
                $days[]= strtolower($day);
            }
        }

        $request['offdays']= json_encode($days);
        $sendingSetting=['name'=>'sending_setting'];
        $sendingSettingData=$request->only('start_time','end_time','offdays');
        $setting = auth()->user()->settings()->firstOrNew($sendingSetting);
        $setting->value = json_encode($sendingSettingData);
        $setting->save();

        cache()->flush();
        return redirect()->back()->with('success', 'Sending setting successfully updated');
    }
    public function smtp_update(Request $request)
    {
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }
        $request->validate([
           'from'=>'required|email',
           'host'=>'required',
           'name'=>'required',
           'username'=>'required',
           'password'=>'required',
           'port'=>'required|numeric',
           'encryption'=>'required|in:ssl,tls',
        ]);
        unset($request['_token']);


        $from = "Picotech Support <demo@picotech.app>";
        $to = "Picotech Support <demo@picotech.app>";
        $subject = "Hi!";
        $body = "Hi,\n\nHow are you?";

        $host = $request->host;
        $port = $request->port;
        $username = $request->username;
        $password = $request->password;
        $config = array(
            'driver' => 'smtp',
            'host' => $host,
            'port' => $port,
            'from' => array('address' => $request->from, 'name' => $request->name),
            'encryption' => $request->encryption,
            'username' => $username,
            'password' => $password,
        );
        Config::set('mail', $config);

        try {
            Mail::send('sendMail', ['htmlData' => $body], function ($message) {
                $message->to("tuhin.picotech@gmail.com")->subject
                ("Setting check from picosms");
            });
        } catch (\Exception $ex) {
            return redirect()->back()->withErrors(['msg' => trans('Invalid email credentials')]);
        }


        foreach ($request->all() as $key => $req) {
            $data = ['name' => 'mail_' . $key];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = $request->$key;
            $setting->save();
        }
        //we need to flush the cache as settings are from cache
        cache()->flush();

        return back()->with('success', 'SMTP configuration successfully updated');
    }

    public function api_update(Request $request)
    {
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }
        $type = $request->gateway;

        if ($type == 'signalwire') {
            $project_id = $request->sw_project_id;
            $sw_space_url = $request->sw_space_url;
            $sw_token = $request->sw_token;
            $sw_status = $request->sw_status;

            $dataArray = [
                'sw_project_id' => $project_id,
                'sw_space_url' => $sw_space_url,
                'sw_token' => $sw_token,
                'sw_status' => $sw_status,
            ];

            $data = ['name' => 'signalwire'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }
        else if ($type == 'twilio') {

            $tw_sid = $request->tw_sid;
            $tw_token = $request->tw_token;
            $tw_status = $request->tw_status;
            $dataArray = [
                'tw_sid' => $tw_sid,
                'tw_token' => $tw_token,
                'tw_status' => $tw_status
            ];
            $data = ['name' => 'twilio'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }
        else if ($type == 'nexmo') {

            $nx_api_key = $request->nx_api_key;
            $nx_api_secret = $request->nx_api_secret;
            $nx_status = $request->nx_status;
            $dataArray = [
                'nx_api_key' => $nx_api_key,
                'nx_api_secret' => $nx_api_secret,
                'nx_status' => $nx_status
            ];

            $data = ['name' => 'nexmo'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }
        else if ($type == 'telnyx') {

            $tl_api_key = $request->tl_api_key;
            $tl_status = $request->tl_status;


            $dataArray = [
                'tl_api_key' => $tl_api_key,
                'tl_status' => $tl_status,
            ];

            $data = ['name' => 'telnyx'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }
        else if ($type == 'plivo') {

            $pl_auth_id = $request->pl_auth_id;
            $pl_auth_token = $request->pl_auth_token;
            $pl_status = $request->pl_status;

            $dataArray = [
                'pl_auth_id' =>$pl_auth_id,
                'pl_auth_token' =>$pl_auth_token,
                'pl_status' =>$pl_status,
            ];

            $data = ['name' => 'plivo'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }
        else if ($type == 'africastalking') {

            $africas_talking_username = $request->africas_talking_username;
            $africas_talking_api_key = $request->africas_talking_api_key;
            $africas_talking_status = $request->africas_talking_status;
            $africas_talking_url = $request->africas_talking_url;

            $dataArray = [
                'africas_talking_username' =>$africas_talking_username,
                'africas_talking_api_key' =>$africas_talking_api_key,
                'africas_talking_status' =>$africas_talking_status,
                'africas_talking_url' =>$africas_talking_url,
            ];

            $data = ['name' => 'africastalking'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }
        else if ($type == 'nrs') {

            $nrs_auth_token = $request->nrs_auth_token;
            $nrs_status = $request->nrs_status;

            $dataArray = [
                'nrs_auth_token' =>$nrs_auth_token,
                'nrs_status' =>$nrs_status,
            ];

            $data = ['name' => 'nrs'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'message_bird') {
            $message_bird_auth_token = $request->message_bird_auth_token;
            $message_bird_status = $request->message_bird_status;
            $message_bird_url = $request->message_bird_url;
            $dataArray = [
                'message_bird_auth_token' =>$message_bird_auth_token,
                'message_bird_url' =>$message_bird_url,
                'message_bird_status' =>$message_bird_status,
            ];
            $data = ['name' => 'message_bird'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'infobip') {
            $infobip_prefix_key = $request->infobip_prefix_key;
            $infobip_api_key = $request->infobip_api_key;
            $url_base_path = $request->url_base_path;
            $infobip_status = $request->infobip_status;
            $dataArray = [
                'infobip_prefix_key' => $infobip_prefix_key,
                'infobip_api_key' => $infobip_api_key,
                'url_base_path' => $url_base_path,
                'infobip_status' => $infobip_status,
            ];
            $data = ['name' => 'infobip'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'cheapglobalsms') {
            $cheap_global_password = $request->cheap_global_password;
            $cheap_global_account = $request->cheap_global_account;
            $cheap_global_status = $request->cheap_global_status;
            $dataArray = [
                'cheap_global_password' => $cheap_global_password,
                'cheap_global_account' => $cheap_global_account,
                'cheap_global_status' => $cheap_global_status,
            ];
            $data = ['name' => 'cheapglobalsms'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'plivo_powerpack') {
            $powerprek_auth_id = $request->powerprek_auth_id;
            $powerprek_auth_token = $request->powerprek_auth_token;
            $powerprek_status = $request->powerprek_status;
            $dataArray = [
                'powerprek_auth_id' => $powerprek_auth_id,
                'powerprek_auth_token' => $powerprek_auth_token,
                'powerprek_status' => $powerprek_status,
            ];
            $data = ['name' => 'plivo_powerpack'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'easysendsms') {
            $easysendsms_username = $request->easysendsms_username;
            $easysendsms_password = $request->easysendsms_password;
            $easysendsms_status = $request->easysendsms_status;
            $dataArray = [
                'easysendsms_username' => $easysendsms_username,
                'easysendsms_password' => $easysendsms_password,
                'easysendsms_status' => $easysendsms_status,
            ];
            $data = ['name' => 'easysendsms'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'twilio_copilot') {
            $twilio_copilot_auth_token = $request->twilio_copilot_auth_token;
            $twilio_copilot_sid = $request->twilio_copilot_sid;
            $twilio_copilot_status = $request->twilio_copilot_status;
            $dataArray = [
                'twilio_copilot_auth_token' => $twilio_copilot_auth_token,
                'twilio_copilot_sid' => $twilio_copilot_sid,
                'twilio_copilot_status' => $twilio_copilot_status,
            ];
            $data = ['name' => 'twilio_copilot'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'bulksms') {
            $bulksms_username = $request->bulksms_username;
            $bulksms_password = $request->bulksms_password;
            $bulksms_status = $request->bulksms_status;
            $dataArray = [
                'bulksms_username' => $bulksms_username,
                'bulksms_password' => $bulksms_password,
                'bulksms_status' => $bulksms_status,
            ];
            $data = ['name' => 'bulksms'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'ones_two_u') {
            $ones_two_u_username = $request->ones_two_u_username;
            $ones_two_u_password = $request->ones_two_u_password;
            $ones_two_u_status = $request->ones_two_u_status;
            $dataArray = [
                'ones_two_u_username' => $ones_two_u_username,
                'ones_two_u_password' => $ones_two_u_password,
                'ones_two_u_status' => $ones_two_u_status,
            ];
            $data = ['name' => 'ones_two_u'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'clickatel') {
            $clickatel_api_key = $request->clickatel_api_key;
            $clickatel_url = $request->clickatel_url;
            $clickatel_status = $request->clickatel_status;
            $dataArray = [
                'clickatel_url' => $clickatel_url,
                'clickatel_api_key' => $clickatel_api_key,
                'clickatel_status' => $clickatel_status,
            ];
            $data = ['name' => 'clickatel'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'route_mobile') {
            $route_mobile_username = $request->route_mobile_username;
            $route_mobile_password = $request->route_mobile_password;
            $route_mobile_status = $request->route_mobile_status;
            $route_mobile_url = $request->route_mobile_url;
            $dataArray = [
                'route_mobile_password' => $route_mobile_password,
                'route_mobile_username' => $route_mobile_username,
                'route_mobile_status' => $route_mobile_status,
                'route_mobile_url' => $route_mobile_url,
            ];
            $data = ['name' => 'route_mobile'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'hutch') {
            $dataArray = [
                'hutch_username' => $request->hutch_username,
                'hutch_password' => $request->hutch_password,
                'hutch_url' => $request->hutch_url,
                'hutch_status' => $request->hutch_status,
            ];
            $data = ['name' => 'hutch'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'estoresms') {
            $dataArray = [
                'estoresms_username' => $request->estoresms_username,
                'estoresms_password' => $request->estoresms_password,
                'estoresms_status' => $request->estoresms_status,
            ];
            $data = ['name' => 'estoresms'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'sms_global') {
            $dataArray = [
                'sms_global_username' => $request->sms_global_username,
                'sms_global_password' => $request->sms_global_password,
                'sms_global_url' => $request->sms_global_url,
                'sms_global_status' => $request->sms_global_status,
            ];
            $data = ['name' => 'sms_global'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'tyntec') {
            $dataArray = [
                'tyntec_status' => $request->tyntec_status,
                'tyntec_apikey' => $request->tyntec_apikey,
                'tyntec_url' => $request->tyntec_url,
            ];
            $data = ['name' => 'tyntec'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'karix') {
            $dataArray = [
                'karix_status' => $request->karix_status,
                'karix_auth_id' => $request->karix_auth_id,
                'karix_auth_token' => $request->karix_auth_token,
                'karix_url' => $request->karix_url,
            ];
            $data = ['name' => 'karix'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'bandwidth') {
            $dataArray = [
                'bandwidth_api_secret' => $request->bandwidth_api_secret,
                'bandwidth_auth_token' => $request->bandwidth_auth_token,
                'bandwidth_url' => $request->bandwidth_url,
                'bandwidth_app_id' => $request->bandwidth_app_id,
                'bandwidth_status' => $request->bandwidth_status,
            ];
            $data = ['name' => 'bandwidth'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'text_local') {
            $dataArray = [
                'text_local_status' => $request->text_local_status,
                'text_local_api_key' => $request->text_local_api_key,
                'text_local_url' => $request->text_local_url,
            ];
            $data = ['name' => 'text_local'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'route_net') {
            $dataArray = [
                'route_net_status' => $request->route_net_status,
                'route_net_app_id' => $request->route_net_app_id,
                'route_net_api_secret' => $request->route_net_api_secret,
                'route_net_url' => $request->route_net_url,
            ];
            $data = ['name' => 'route_net'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'hutchlk') {
            $dataArray = [
                'hutchlk_username' => $request->hutchlk_username,
                'hutchlk_password' => $request->hutchlk_password,
                'hutchlk_url' => $request->hutchlk_url,
                'hutchlk_status' => $request->hutchlk_status,
            ];
            $data = ['name' => 'hutchlk'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'teletopia') {
            $dataArray = [
                'teletopia_status' => $request->teletopia_status,
                'teletopia_username' => $request->teletopia_username,
                'teletopia_password' => $request->teletopia_password,
                'teletopia_url' => $request->teletopia_url,
            ];
            $data = ['name' => 'teletopia'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'broadcaster_mobile') {
            $dataArray = [
                'broadcaster_mobile_status' => $request->broadcaster_mobile_status,
                'broadcaster_mobile_api_key' => $request->broadcaster_mobile_api_key,
                'broadcaster_mobile_tag' => $request->broadcaster_mobile_tag,
                'broadcaster_mobile_country' => $request->broadcaster_mobile_country,
                'broadcaster_mobile_url' => $request->broadcaster_mobile_url,
                'broadcaster_mobile_auth_token' => $request->broadcaster_mobile_auth_token,
            ];
            $data = ['name' => 'broadcaster_mobile'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'solutions4mobiles') {
            $dataArray = [
                'solutions4mobiles_username' => $request->solutions4mobiles_username,
                'solutions4mobiles_password' => $request->solutions4mobiles_password,
                'solutions4mobiles_status' => $request->solutions4mobiles_status,
            ];
            $data = ['name' => 'solutions4mobiles'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'beemAfrica') {
            $dataArray = [
                'beemAfrica_api_key' => $request->beemAfrica_api_key,
                'beemAfrica_secret_key' => $request->beemAfrica_secret_key,
                'beemAfrica_url' => $request->beemAfrica_url,
                'beemAfrica_status' => $request->beemAfrica_status,
            ];
            $data = ['name' => 'beemAfrica'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'bulkSMSOnline') {
            $dataArray = [
                'bulkSMSOnline_status' => $request->bulkSMSOnline_status,
                'bulkSMSOnline_username' => $request->bulkSMSOnline_username,
                'bulkSMSOnline_password' => $request->bulkSMSOnline_password,
                'bulkSMSOnline_sms_type' => $request->bulkSMSOnline_sms_type,
                'bulkSMSOnline_url' => $request->bulkSMSOnline_url,
            ];
            $data = ['name' => 'bulkSMSOnline'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'bulkSMSOnline') {
            $dataArray = [
                'bulkSMSOnline_status' => $request->bulkSMSOnline_status,
                'bulkSMSOnline_username' => $request->bulkSMSOnline_username,
                'bulkSMSOnline_password' => $request->bulkSMSOnline_password,
                'bulkSMSOnline_sms_type' => $request->bulkSMSOnline_sms_type,
                'bulkSMSOnline_url' => $request->bulkSMSOnline_url,
            ];
            $data = ['name' => 'bulkSMSOnline'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'flowRoute') {
            $dataArray = [
                'flowRoute_status' => $request->flowRoute_status,
                'flowRoute_access_key' => $request->flowRoute_access_key,
                'flowRoute_api_secret' => $request->flowRoute_api_secret,
                'flowRoute_url' => $request->flowRoute_url,
            ];
            $data = ['name' => 'flowRoute'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'elitBuzzBD') {
            $dataArray = [
                'elitBuzzBD_status' => $request->elitBuzzBD_status,
                'elitBuzzBD_url' => $request->elitBuzzBD_url,
                'elitBuzzBD_api_key' => $request->elitBuzzBD_api_key,
            ];
            $data = ['name' => 'elitBuzzBD'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'greenWebBD') {
            $dataArray = [
                'greenWebBD_status' => $request->greenWebBD_status,
                'greenWebBD_url' => $request->greenWebBD_url,
                'greenWebBD_api_key' => $request->greenWebBD_api_key,
            ];
            $data = ['name' => 'greenWebBD'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'hablameV2') {
            $dataArray = [
                'hablameV2_api_token' => $request->hablameV2_api_token,
                'hablameV2_api_key' => $request->hablameV2_api_key,
                'hablameV2_url' => $request->hablameV2_url,
                'hablameV2_status' => $request->hablameV2_status,
                'hablameV2_server_cl' => $request->hablameV2_server_cl,
            ];
            $data = ['name' => 'hablameV2'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'zamtelCoZm') {
            $dataArray = [
                'zamtelCoZm_api_key' => $request->zamtelCoZm_api_key,
                'zamtelCoZm_url' => $request->zamtelCoZm_url,
                'zamtelCoZm_status' => $request->zamtelCoZm_status,
            ];
            $data = ['name' => 'zamtelCoZm'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'thinq') {
            $dataArray = [
                'thinq_status' => $request->thinq_status,
                'thinq_auth_token' => $request->thinq_auth_token,
                'thinq_account_id' => $request->thinq_account_id,
                'thinq_username' => $request->thinq_username,
            ];
            $data = ['name' => 'thinq'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }
        else if ($type == 'smpp') {
            $dataArray = [
                'smpp_ip_address' => $request->smpp_ip_address,
                'smpp_username' => $request->smpp_username,
                'smpp_password' => $request->smpp_password,
                'smpp_port' => $request->smpp_port,
                'smpp_status' => $request->smpp_status,
            ];
            $data = ['name' => 'smpp'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }
        else if ($type == 'bulksmsbd') {
            $dataArray = [
                'bulksmsbd_url' => $request->bulksmsbd_url,
                'bulksmsbd_username' => $request->bulksmsbd_username,
                'bulksmsbd_password' => $request->bulksmsbd_password,
                'bulksmsbd_status' => $request->bulksmsbd_status,
            ];
            $data = ['name' => 'bulksmsbd'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }
        else if ($type == 'metro_tel') {
            $dataArray = [
                'metro_tel_url' => $request->metro_tel_url,
                'metro_tel_api_key' => $request->metro_tel_api_key,
                'metro_tel_status' => $request->metro_tel_status,
            ];

            $data = ['name' => 'metro_tel'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }
        else if ($type == 'click_send') {
            $dataArray = [
                'click_send_password' => $request->click_send_password,
                'click_send_username' => $request->click_send_username,
                'click_send_status' => $request->click_send_status,
            ];
            $data = ['name' => 'click_send'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }
        else if ($type == 'sms_noc') {
            $dataArray = [
                'ajuratech_url' => $request->ajuratech_url,
                'ajuratech_api_key' => $request->ajuratech_api_key,
                'ajuratech_secret_key' => $request->ajuratech_secret_key,
                'ajuratech_status' => $request->ajuratech_status,
            ];
            $data = ['name' => 'sms_noc'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }
        else if ($type == 'sms_mkt') {
            $dataArray = [
                'sms_mkt_api_key' => $request->sms_mkt_api_key,
                'sms_mkt_secret_key' => $request->sms_mkt_secret_key,
                'sms_mkt_status' => $request->sms_mkt_status,
            ];
            $data = ['name' => 'sms_mkt'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }
        else if ($type == 'adn_sms') {
            $dataArray = [
                'adn_sms_api_key' => $request->adn_sms_api_key,
                'adn_sms_api_secret' => $request->adn_sms_api_secret,
                'adn_sms_url' => $request->adn_sms_url,
                'adn_sms_message_type' => $request->adn_sms_message_type,
                'adn_sms_status' => $request->adn_sms_status,
            ];
            $data = ['name' => 'adn_sms'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }

            cache()->flush();
        return response()->json(['status'=>'success','message'=>'API successfully updated']);
    }


    public function whatsapp_api(Request $request)
    {
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }
        $type = $request->whatsapp_gateway;

        if ($type == 'whatsapp_textlocal') {
            $apiKey=$request->textlocal_api_key;
            $dataArray = [
                'textlocal_api_key' => $apiKey,
            ];
            $data = ['name' => 'whatsapp_textlocal'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'whatsapp_twilio') {
            $tw_sid = $request->tw_sid;
            $tw_token = $request->tw_token;
            $dataArray = [
                'tw_sid' => $tw_sid,
                'tw_token' => $tw_token
            ];
            $data = ['name' => 'whatsapp_twilio'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }else if ($type == 'whatsapp_soniyal') {
            $apiKey=$request->soniyal_api_key;
            $dataArray = [
                'soniyal_api_key' => $apiKey,
                'soniyal_url'=> $request->soniyal_url,
            ];
            $data = ['name' => 'whatsapp_soniyal'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }
            cache()->flush();
        return redirect()->route('admin.settings.index')->with('success','Whatsapp API successfully updated');
    }
    public function voice_call_api(Request $request){

        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }

        $type = $request->voice_call_gateway;

        if ($type == 'voice_call_soniyal') {
            $username = $request->soniyal_username;
            $token = $request->soniyal_token;
            $caller_id = $request->soniyal_caller_id;
            $plan_id = $request->soniyal_plan_id;
            $domain_name= $request->soniyal_domain_name;
            $dataArray = [
                'username' => $username,
                'token' => $token,
                'caller_id' => $caller_id,
                'plan_id' => $plan_id,
                'domain_name' => $domain_name,
            ];
            $data = ['name' => 'voice_call_soniyal'];
            $setting = auth()->user()->settings()->firstOrNew($data);
            $setting->value = json_encode($dataArray);
            $setting->save();
        }
        cache()->flush();
        return redirect()->route('admin.settings.index')->with('success','Voice Call API successfully updated');
    }


    public function templateStore(Request $request)
    {
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }
        $request->validate([
            'subject' => 'required',
            'body' => 'required'
        ]);
        $user = auth()->user();
        $emailTemplate = isset($request->emailTemplateID) ? EmailTemplate::find($request->emailTemplateID) : new EmailTemplate();

        $emailTemplate->type = $request->type;
        $emailTemplate->user_id = $user->id;
        $emailTemplate->subject = $request->subject;
        $emailTemplate->body = $request->body;
        $emailTemplate->added_by = 'admin';
        $emailTemplate->status = 'active';

        $emailTemplate->save();
        cache()->forget('e_template_'.auth()->user()->id);
        cache()->flush();
        return redirect()->back()->with('success', trans('customer.message.message.setting_update'));
    }

    public function local_settings(Request $request)
    {
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }
        $request->validate([
            'language' => 'required',
            'date_time_format' => 'required',
            'date_time_separator' => 'required',
            'timezone' => 'required',
            'decimal_format' => 'required',
            'currency_symbol' => 'required',
            'currency_symbol_position' => 'required',
            'thousand_separator' => 'required',
            'decimals' => 'required',
            'direction' => 'in:rtl,ltr'

        ]);

        $availableLang = get_available_languages();
        $type = $request->language;

        if (!in_array($type, $availableLang)){
            abort('404');
        }

        session()->put('locale', $type);
        app()->setLocale($type);

        $localSetting = $request->only('thousand_separator', 'decimals', 'language', 'date_time_format', 'date_time_separator', 'timezone', 'decimal_format', 'currency_symbol', 'currency_code', 'currency_symbol_position', 'direction');
        $data = ['name' => 'local_setting'];
        $setting = auth()->user()->settings()->firstOrNew($data);
        $setting->value = json_encode($localSetting);
        $setting->save();
        cache()->flush();

        return redirect()->back()->with('success', trans('customer.message.local_setting_updated'));
    }

    public function cacheSettings(Request $request){

        $request->validate([
            'to'=>'required',
            'from'=>'required'
        ]);

        DB::beginTransaction();
        try {
            $ids = Message::whereBetween('created_at', [$request->from, $request->to])->pluck('id');

            foreach ($ids->chunk(4000) as $id){
                DeleteSmsData::dispatch($id);
            }


            DB::commit();
            return redirect()->route('admin.settings.index')->with('success',trans('customer.message.log_delete'));

        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->withErrors(['failed'=>$ex->getMessage()]);
        }

    }
    public function dbBackupList(){
        $data['files']=DbBackup::orderByDesc('created_at')->get();
        return view('admin.settings.db_backuplist', $data);
    }

    public function downloadDbBackup(Request  $request){
        $backup=DbBackup::where('id', $request->id)->firstOrFail();
        $filepath = storage_path().'/app/backup/' .$backup->file_name;

        if(\File::exists($filepath)){
            return Response::download($filepath);
        } else {
            abort('404');
        }
    }
    public function header_title(Request  $request){
        $request->validate([
            'header_title' => 'required',
        ]);
        $data = ['name' => $request->name];
        $setting = auth()->user()->settings()->firstOrNew($data);
        $setting->value = $request->header_title;
        $setting->save();
        cache()->flush();
        return redirect()->back()->with('success', 'Profile successfully updated');
    }
}
