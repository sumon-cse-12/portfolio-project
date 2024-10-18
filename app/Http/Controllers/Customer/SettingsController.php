<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\AuthorizationToken;
use App\Models\BecameReseller;
use App\Models\CustomerSettings;
use App\Models\EmailTemplate;
use App\Models\SmsTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SettingsController extends Controller
{
    public function index()
    {
        $data['customer']=$customer = auth('customer')->user();
        $settings=$customer->settings;
        $customer_settings=[];
        foreach ($settings as $setting){
            $customer_settings[$setting->name]=$setting->value;
        }
        $data['sms_templates']= SmsTemplate::where('customer_id',$customer->id)->get();

        $data['customer_settings']=$customer_settings;
        $data['gateway']=isset($customer_settings['payment_gateway'])?json_decode($customer_settings['payment_gateway']):[];
        $data['otp_setting']=isset($customer_settings['otp_setting'])?json_decode($customer_settings['otp_setting']):[];

        $gatewaysValues =auth('customer')->user()->settings()->where('name', 'payment_gateway_values')->first();

        $data['gateway_values'] = isset($gatewaysValues->value)?json_decode($gatewaysValues->value):'';
        $data['domain']=$customer->domain()->first();
        $data['verification']=BecameReseller::where('customer_id', $customer->id)->where('status', 'approved')->first();
        $data['gateways']=$customer->settings->where('name', 'payment_gateway')->first();
        $data['admin_otp_setting'] = get_settings('otp_setting') ? json_decode(get_settings('otp_setting')) : '';
        $smtp_setting=auth('customer')->user()->settings()->where('name', 'smtp_setting')->first();
        $data['smtp_setting'] = isset($smtp_setting->value)?json_decode($smtp_setting->value):'';
        $data['authorizationToken']= AuthorizationToken::where('customer_id', $customer->id)->first();

        $inbound_setting=auth('customer')->user()->settings()->where('name', 'inbound_setting')->first();
        $data['sender_setting'] = $inbound_setting?json_decode($inbound_setting->value):'';

        $data['numbers']=auth('customer')->user()->numbers()->where('expire_date', '>', now())->get();
        $data['sender_ids']=auth('customer')->user()->sender_ids()->where('expire_date', '>', now())->get();


        return view('customer.settings.index', $data);
    }

    public function profile_update(Request $request)
    {
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }
        $request->validate([
            'first_name' => 'required',
            'email' => 'required|unique:customers,email,' . auth('customer')->id(),
            'profile'=>'image'
        ]);

        $user = auth('customer')->user();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;

        if ($request->hasFile('profile')){
            $file=$request->file('profile');
            $imageName = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageName);
            $user->profile_picture=$imageName;
        }
        $user->save();
        return redirect()->back()->with('success', 'Profile successfully updated');
    }

    public function password_update(Request $request)
    {
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);
        $customer = auth('customer')->user();

        if (!Hash::check($request->old_password, $customer->password)) {
            return back()->with('fail', 'Invalid old password. Please try with valid password');
        }

        $customer->password = bcrypt($request->new_password); //remove the bcrypt
        $customer->save();

        return redirect()->back()->with('success', 'Password successfully changed');

    }

    public function notification_update(Request $request)
    {
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }
        $request->validate([
            'isChecked' => 'required|in:true,false'
        ]);
        $data = [
            'name' => 'email_notification',
        ];

        $setting = auth('customer')->user()->settings()->firstOrNew($data);
        $setting->value = $request->isChecked;
        $setting->save();

        return response()->json(['status' => 'success', 'message' => 'Email notification updated']);
    }

    public function webhookUpdate(Request $request)
    {
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }
        $request->validate([
            'type'=>'required|in:get,post',
        ]);

        $data = [
            'name' => 'webhook',
        ];
        $customerNumbers = auth('customer')->user()->numbers;

        $setting = auth('customer')->user()->settings()->firstOrNew($data);

        $updatedId= [];
        foreach ($customerNumbers as $customerNumber){
            if (!$customerNumber->webhook_url || isset(json_decode($setting->value)->url) &&  $customerNumber->webhook_url == json_decode($setting->value)->url) {
                $updatedId[] = $customerNumber->id;
            }
        }

        $setting->value = json_encode($request->only('url','type'));
        $setting->save();
        $customerNumberUpdate = $customerNumbers->whereIn('id', $updatedId);
        foreach ($customerNumberUpdate as $update){
            $update->webhook_url = $request->url;
            $update->webhook_method = $request->type;
            $update->save();
        }

        return response()->json(['status' => 'success', 'message' => 'Webhook updated successfully']);
    }

    public function accessStaffViewMessage(Request $request)
    {
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }
        $data = [
            'name' => 'view_staff_message',
        ];
        $pre_settings= auth('customer')->user()->settings()->where('name','view_staff_message')->first();
        $type='no';
        if($pre_settings && $pre_settings->value && $pre_settings->value=='no'){
            $type='yes';
        }
        $setting = auth('customer')->user()->settings()->firstOrNew($data);
        $setting->value = $type;
        $setting->save();
        return response()->json(['status' => 'success','type'=>$setting->value, 'message' => 'Staff Message View Access Updated']);
    }

    public function dataPosting(Request $request)
    {
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }
        $request->validate([
            'type'=>'required|in:get,post',
        ]);

        $data = [
            'name' => 'data_posting',
        ];
        $setting = auth('customer')->user()->settings()->firstOrNew($data);
        $setting->value = json_encode($request->only('url','type'));
        $setting->save();
        cache()->flush();
        return response()->json(['status' => 'success', 'message' => 'Data Posting URL updated successfully']);
    }

    public function downloadSample($type,Request $request){
        if($type=='group'){
            return response()->download(public_path('csv/sample-group.csv'));
        }
    }

    public function otpSettings(Request $request)
    {
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }
        $data = [
            'name' => 'otp_setting',
        ];
        $setting = auth('customer')->user()->settings()->firstOrNew($data);
        $setting->value = json_encode($request->only('phone_number','from_type','sender_id','status'));
        $setting->save();

        return redirect()->back()->with('success','OTP Settings Successfully Updated');
    }

    public function smtp_update(Request $request)
    {
       try{
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
                   $message->to("sagor.picotech@gmail.com")->subject
                   ("Setting check from picosms");
               });
           } catch (\Exception $ex) {
               return redirect()->back()->withErrors(['msg' => trans('Invalid email credentials')]);
           }


           $arrayData = array(
               'host' => $host,
               'port' => $port,
               'from' => $request->from,
               'name' => $request->name,
               'encryption' => $request->encryption,
               'username' => $request->username,
               'password' => $request->password,
           );

           $data = ['name' => 'smtp_setting'];
           $setting = auth('customer')->user()->settings()->firstOrNew($data);
           $setting->value = json_encode($arrayData);
           $setting->save();

           //we need to flush the cache as settings are from cache
           cache()->flush();

           return back()->with('success', 'SMTP configuration successfully updated');
       }catch(\Exception $ex){
           return back()->withErrors(['failed'=>$ex->getMessage()]);
       }
    }

    public function templateStore(Request $request)
    {
        DB::beginTransaction();
        try {
            if (env("APP_DEMO")) {
                return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
            }
            $request->validate([
                'subject' => 'required',
                'body' => 'required'
            ]);
            $user = auth('customer')->user();

            $emailTemplate = isset($request->emailTemplateID) ? EmailTemplate::find($request->emailTemplateID) : new EmailTemplate();

            $emailTemplate->type = $request->type;

            $emailTemplate->user_id = $user->id;
            $emailTemplate->added_by = auth('customer')->user()->type;
            $emailTemplate->subject = $request->subject;
            $emailTemplate->body = $request->body;
            $emailTemplate->status = 'active';

            $emailTemplate->save();
            cache()->flush();
            DB::commit();
            return redirect()->back()->with('success', trans('customer.message.template_update'));

        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->withErrors(['failed' => $ex->getMessage()]);
        }
    }

    public function inbound_settings(Request $request){
        $inbound_setting=auth('customer')->user()->settings()->where('name', 'inbound_setting')->first();

        $data = [
            'name' => 'inbound_setting',
        ];
        if ($request->sender_type=='number'){
            $json_data=json_encode($request->only('number','sender_type'));
        }else {
            $json_data = json_encode($request->only('sender_id', 'sender_type'));
        }

        $setting = auth('customer')->user()->settings()->firstOrNew($data);
        $setting->value = $json_data;
        $setting->save();


        return redirect()->back()->with('success', 'Inbound Setting Successfully Updated');

    }

}
