<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerSettings;
use App\Models\Settings;
use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index(){
        $customer=auth('customer')->user();

        if($customer->type =='reseller' || $customer->type =='master_reseller'){
            $data['templateData'] = auth('customer')->user()->settings()->where('name','template')->first();
            $data['app_name'] = auth('customer')->user()->settings()->where('name','app_name')->first();
            $contactData = auth('customer')->user()->settings()->where('name', 'contact_info')->first();
            $data['contactData'] = isset($contactData->value)?json_decode($contactData->value):'';
            $data['recaptcha'] = auth('customer')->user()->settings()->where('name', 'recaptcha_key')->first();
        return  view('customer.template.index', $data);
        }else{
            return abort('404');
        }
    }
    public function store(Request $request){
        $data_template = auth('customer')->user()->settings()->where('name','template')->first();
        if ($data_template){
            $template = json_decode($data_template->value);
        }

         if(isset($template->bg_image_file_name)){
            $request['bg_image_file_name'] = $template->bg_image_file_name;
         }
        if ($request->hasFile('bg_image')) {
            $file = $request->file('bg_image');
            $imageName = time() . '_1' . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageName);
           $request['bg_image_file_name'] = $imageName;
        }

        if(isset($template->first_img_file_name)){
           $request['first_img_file_name'] = $template->first_img_file_name;
        }

        $data = ['name' => 'recaptcha_key'];
        $setting = auth('customer')->user()->settings()->firstOrNew($data);
        $setting->value = json_encode($request->only('recaptcha_secret_key', 'recaptcha_site_key'));
        $setting->save();

        if ($request->hasFile('first_img')) {
            $file = $request->file('first_img');
            $imageOneName = time(). '_2'. '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageOneName);
            $request['first_img_file_name'] = $imageOneName;
        }

        if(isset($template->sec_img_file_name)){
            $request['sec_img_file_name'] = $template->sec_img_file_name;
        }
        if ($request->hasFile('sec_img')) {
            $file = $request->file('sec_img');
            $imageTwoName = time(). '_3' . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageTwoName);
            $request['sec_img_file_name'] = $imageTwoName;
        }

        if(isset($template->thr_img_file_name)){
            $request['thr_img_file_name'] = $template->thr_img_file_name;
        }


        if ($request->hasFile('thr_img')) {
            $file = $request->file('thr_img');
            $imageThreeName = time(). '_4' . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageThreeName);
            $request['thr_img_file_name'] = $imageThreeName;
        }

       if(isset($template->sec_thr_bg_image_file)){
           $request['sec_thr_bg_image_file'] = $template->sec_thr_bg_image_file;
       }

        if ($request->hasFile('sec_thr_bg_image')) {
            $file = $request->file('sec_thr_bg_image');
            $imagefourName = time(). '_5' . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imagefourName);
            $request['sec_thr_bg_image_file'] = $imagefourName;
        }

        if(isset($template->sec_seven_bg_image_file)){
            $request['sec_seven_bg_image_file'] = $template->sec_seven_bg_image_file;
        }

        if ($request->hasFile('sec_seven_bg_image')) {
            $file = $request->file('sec_seven_bg_image');
            $imageFiveName = time(). '_6' . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageFiveName);
            $request['sec_seven_bg_image_file'] = $imageFiveName;
        }

        if (isset($data_template) && $data_template->name == 'template'){
            $template = auth('customer')->user()->settings()->where('name', 'template')->first();
            $template->value = json_encode($request->only('title','description','description','first_title','first_description','sec_title','sec_description','thr_title','thr_description','sec_thr_title','sec_thr_description','main_title','sec_four_title','sec_four_description','sec_five_title','sec_six_title','sec_seven_title','sec_seven_description','bg_image_file_name','first_img_file_name','sec_img_file_name','thr_img_file_name','sec_thr_bg_image_file','sec_seven_bg_image_file'));
            $template->save();
        }else{
            $template = new CustomerSettings();
            $template->name = 'template';
            $template->value = json_encode($request->only('title','description','description','first_title','first_description','sec_title','sec_description','thr_title','thr_description','sec_thr_title','sec_thr_description','main_title','sec_four_title','sec_four_description','sec_five_title','sec_six_title','sec_seven_title','sec_seven_description','bg_image_file_name','first_img_file_name','sec_img_file_name','thr_img_file_name','sec_thr_bg_image_file','sec_seven_bg_image_file'));
            $template->customer_id = auth('customer')->user()->id;
            $template->save();
        }

        $data=['name'=>'contact_info'];
        $contactData=$request->only('address', 'email_address', 'phone_number');
        $setting = auth('customer')->user()->settings()->firstOrNew($data);
        $setting->value = json_encode($contactData);
        $setting->save();


        //update application name
        $data = ['name' => 'app_name'];
        $setting = auth('customer')->user()->settings()->firstOrNew($data);
        $setting->value = $request->app_name;
        $setting->save();

        //update favicon
        if ($request->hasFile('favicon')) {

            $file = $request->file('favicon');
            $favicon_name = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $favicon_name);

            $data = ['name' => 'app_favicon'];
            $setting = auth('customer')->user()->settings()->firstOrNew($data);
            $setting->value = $favicon_name;
            $setting->save();
        }

        //update logo
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $logo_name = time() . 'l.' . $file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $logo_name);

            $data = ['name' => 'app_logo'];
            $setting =auth('customer')->user()->settings()->firstOrNew($data);
            $setting->value = $logo_name;
            $setting->save();
        }


        cache()->forget('customer_settings');
        return redirect()->back()->with('success','Template successfully update');
    }
}
