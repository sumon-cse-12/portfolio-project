<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TemplateController extends Controller
{
    public function index(){
        return  view('admin.template.index');
    }
    public function store(Request $request){  
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }
        $data_template = auth()->user()->settings()->where('name','template')->first();
        if ($data_template && isset($data_template->value)){
            $template = json_decode($data_template->value);
        }

         if(isset($template) && isset($template->bg_image_file_name)){
            $request['bg_image_file_name'] = $template->bg_image_file_name;
         }
        if ($request->hasFile('bg_image')) {
            $file = $request->file('bg_image');
            $imageName = time() . '_1' . '.' . $file->getClientOriginalExtension();
            if(isset($template) && isset($template->bg_image_file_name)){
                $file_path = public_path('/uploads') . '/' . $template->bg_image_file_name;
                if (File::exists($file_path)) {
                    unlink($file_path);
                }
             }
     
            $file->move(public_path('/uploads'), $imageName);
           $request['bg_image_file_name'] = $imageName;
        }

        if(isset($template) && isset($template->first_img_file_name)){
           $request['first_img_file_name'] = $template->first_img_file_name;
        }

        if ($request->hasFile('first_img')) {
            $file = $request->file('first_img');
            $imageOneName = time(). '_2'. '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageOneName);
            $request['first_img_file_name'] = $imageOneName;
        }

        if(isset($template) && isset($template->sec_img_file_name)){
            $request['sec_img_file_name'] = $template->sec_img_file_name;
        }
        if ($request->hasFile('sec_img')) {
            $file = $request->file('sec_img');
            $imageTwoName = time(). '_3' . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageTwoName);
            $request['sec_img_file_name'] = $imageTwoName;
        }


        if(isset($template) && Isset($template->thr_img_file_name)){
            $request['thr_img_file_name'] = $template->thr_img_file_name;
        }


        if ($request->hasFile('thr_img')) {
            $file = $request->file('thr_img');
            $imageThreeName = time(). '_4' . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageThreeName);
            $request['thr_img_file_name'] = $imageThreeName;
        }

       if(isset($template) && isset($template->sec_thr_bg_image_file)){
           $request['sec_thr_bg_image_file'] = $template->sec_thr_bg_image_file;
       }

        if ($request->hasFile('sec_thr_bg_image')) {
            $file = $request->file('sec_thr_bg_image');
            $imagefourName = time(). '_5' . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imagefourName);
            $request['sec_thr_bg_image_file'] = $imagefourName;
        }

        if(isset($template) && isset($template->sec_seven_bg_image_file)){
            $request['sec_seven_bg_image_file'] = $template->sec_seven_bg_image_file;
        }

        if ($request->hasFile('sec_seven_bg_image')) {
            $file = $request->file('sec_seven_bg_image');
            $imageFiveName = time(). '_6' . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageFiveName);
            $request['sec_seven_bg_image_file'] = $imageFiveName;
        }
        $gateWayImages=isset($data_template->value)?json_decode($data_template->value):'';
        $gatewayImages=[];
        if (isset($gateWayImages->payment_gateway_img)) {
            foreach (json_decode($gateWayImages->payment_gateway_img) as $pics) {
                $gatewayImages[] = $pics;
            }
        }
        if ($request->hasfile('payment_gateways')){
            foreach ($request->file('payment_gateways') as $key => $image) {
                $filename = time() . 'payment'.$key. '.' . $image->getClientOriginalExtension();
                $image->move(public_path('/uploads'), $filename);
                $gatewayImages[]=$filename;
            }
        }
        if(isset($template) && isset($template->section_two_bg_image_file)){
            $request['section_two_bg_image_file'] = $template->section_two_bg_image_file;
        }

        if ($request->hasFile('section_two_bg_image')) {
            $file = $request->file('section_two_bg_image');
            if(isset($template) && isset($template->section_two_bg_image_file)){
                $file_path = public_path('/uploads') . '/' . $template->section_two_bg_image_file;
                if (File::exists($file_path)) {
                    unlink($file_path);
                }
             }
            $imageSecTwo = time(). '543' . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageSecTwo);
            $request['section_two_bg_image_file'] = $imageSecTwo;
        }
        $feature_icon = isset($template->section_two_feature_items)?($template->section_two_feature_items):'';
        $feature_icon = [];
        if($request->section_two_feature_title && $request->section_two_feature_description){
            foreach($request->section_two_feature_title as $key => $item) {
                if(isset($request->section_two_feature_icon[$key]) && $request->section_two_feature_icon[$key] || isset($request->pre_sec_two_feature_icon[$key]) && $request->pre_sec_two_feature_icon[$key]){
                    $filename = '';
                    if(isset($request->section_two_feature_icon[$key]) && $request->section_two_feature_icon[$key]){

                        $file = $request->file('section_two_feature_icon')[$key];
                        $filename = time() . 'feature_icon'.$key. '.' . $file->getClientOriginalExtension();
                        $file->move(public_path('/uploads'), $filename);

                    }else if(isset($request->pre_sec_two_feature_icon[$key]) && $request->pre_sec_two_feature_icon[$key]){
                       $filename = $request->pre_sec_two_feature_icon[$key];
                    }
             
                $feature_icon[] = [
                    'sec_two_feature_title'=> $item,
                    'sec_two_feature_des'=> $request->section_two_feature_description[$key],
                    'sec_two_feature_icon' => $filename
                ];
            }
        }

        }

        $section_three_feature = isset($template->section_three_feature_items)?($template->section_three_feature_items):'';
        $section_three_feature = [];
        if($request->section_three_feature_title ){
            foreach($request->section_three_feature_title as $key => $item) {
                if(isset($request->section_three_feature_icon[$key]) && $request->section_three_feature_icon[$key] || isset($request->pre_section_three_feature_icon[$key]) && $request->pre_section_three_feature_icon[$key]){
                    $filename = '';
                    if(isset($request->section_three_feature_icon[$key]) && $request->section_three_feature_icon[$key]){

                        $file = $request->file('section_three_feature_icon')[$key];
                        $filename = time() . 'sec_thr_f_i'.$key. '.' . $file->getClientOriginalExtension();
                        $file->move(public_path('/uploads'), $filename);

                    }else if(isset($request->pre_section_three_feature_icon[$key]) && $request->pre_section_three_feature_icon[$key]){
                       $filename = $request->pre_section_three_feature_icon[$key];
                    }
             
                    $section_three_feature[] = [
                    'sec_three_feature_title'=> $item,
                    'sec_three_feature_icon' => $filename
                ];
            }
        }

        }

        $section_four_feature = isset($template->section_four_features)?($template->section_four_features):'';
        $section_four_feature = [];
        if($request->section_four_feature_des ){
            foreach($request->section_four_feature_des as $key => $item) {
                $section_four_feature[] = [
                    'section_four_feature_des'=> $item,
                ];
            }
        }
        if(isset($template) && isset($template->section_four_bg_image_file_name)){
            $request['section_four_bg_image_file_name'] = $template->section_four_bg_image_file_name;
        }
        if ($request->hasFile('section_four_bg_image')) {
            $file = $request->file('section_four_bg_image');
            if(isset($template) && isset($template->section_four_bg_image_file_name)){
                $file_path = public_path('/uploads') . '/' . $template->section_four_bg_image_file_name;
                if (File::exists($file_path)) {
                    unlink($file_path);
                }
             }
            $imageSectionFour = time(). 'sec_four' . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageSectionFour);
            $request['section_four_bg_image_file_name'] = $imageSectionFour;
        }
        if(isset($template) && isset($template->section_five_bg_image_file_name)){
            $request['section_five_bg_image_file_name'] = $template->section_five_bg_image_file_name;
        }
        if ($request->hasFile('section_five_bg_image')) {
            $file = $request->file('section_five_bg_image');
            if(isset($template) && isset($template->section_five_bg_image_file_name)){
                $file_path = public_path('/uploads') . '/' . $template->section_five_bg_image_file_name;
                if (File::exists($file_path)) {
                    unlink($file_path);
                }
             }
            $imageSectionFive = time(). 'sec_five' . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageSectionFive);
            $request['section_five_bg_image_file_name'] = $imageSectionFive;
        } 
        if(isset($template) && isset($template->about_us_bg_image_file_name)){
            $request['about_us_bg_image_file_name'] = $template->about_us_bg_image_file_name;
        }
        if ($request->hasFile('about_us_bg_image')) {
            $file = $request->file('about_us_bg_image');
            if(isset($template) && isset($template->about_us_bg_image_file_name)){
                $file_path = public_path('/uploads') . '/' . $template->about_us_bg_image_file_name;
                if (File::exists($file_path)) {
                    unlink($file_path);
                }
             }
            $imageSectionAbout = time(). 'about' . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageSectionAbout);
            $request['about_us_bg_image_file_name'] = $imageSectionAbout;
        }


        $request['section_two_feature_items'] = json_encode($feature_icon);
        $request['section_four_features'] = json_encode($section_four_feature);
        $request['section_three_feature_items'] = json_encode($section_three_feature);
        $request['payment_gateway_desc']=clean($request->payment_desc);
        $request['payment_gateway_img']=json_encode($gatewayImages);

        if (isset($data_template) && $data_template->name == 'template'){
            $template = Settings::where('name', 'template')->first();
            $template->value = json_encode($request->only('payment_gateway_desc','payment_gateway_img','sec_two_first_title','section_two_description','section_two_bg_image_file','section_two_feature_items','section_three_title','section_three_feature_items','section_four_title','section_four_description','section_four_features','section_four_bg_image_file_name','section_five_bg_image_file_name','section_five_title','section_five_sub_title','section_five_description', 'contact_us_title','subscribe_title','plan_title','faq_title','about_us_bg_image_file_name','about_us_title','about_us_description','bg_image_file_name','title','description'));
            $template->save();
        }else{
            $template = new Settings();
            $template->name = 'template';
            $template->value = json_encode($request->only('payment_gateway_desc','payment_gateway_img','sec_two_first_title','section_two_description','section_two_bg_image_file','section_two_feature_items','section_three_title','section_three_feature_items','section_four_title','section_four_description','section_four_features','section_four_bg_image_file_name','section_five_bg_image_file_name','section_five_title','section_five_sub_title','section_five_description', 'contact_us_title','subscribe_title','plan_title','faq_title','about_us_bg_image_file_name','about_us_title','about_us_description','bg_image_file_name','title','description'));
            $template->admin_id = auth()->user()->id;
            $template->save();
        }
        cache()->flush();
        return redirect()->back()->with('success','Template successfully update');
    }

    public function paymentPartnerImage(Request $request)
    {
        $data_template = auth()->user()->settings()->where('name','template')->first();
        $images=json_decode($data_template->value);
        $allValues=[];
        foreach ($images as $key=>$item) {
            $allValues[$key]=$item;
        }
        $partnerImages = $allValues['payment_gateway_img'];
        $pics = json_decode($partnerImages);
        if (($key = array_search($request->image, $pics)) !== false) {
            $this->instantImageDelete($pics[$key]);
            unset($pics[$key]);
        }
        $newPics = [];
        foreach ($pics as $pic) {
            $newPics[] = $pic;
        }

        $allValues['payment_gateway_img']=json_encode($newPics);
        $data_template->value=json_encode($allValues);
        $data_template->save();

        cache()->flush();
        return response()->json(['status' => 'success', 'message' => trans('Pic successfully deleted')]);
    }


    function instantImageDelete($pics)
    {
        if ($pics) {
            $fileN = public_path('/uploads') . '/' . $pics;
            if (File::exists($fileN))
                unlink($fileN);
        }
    }
    public function theme(){

        return view('admin.template.theme');
    }

    public function themeStore(Request $request){
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }
        $requestData=[
            'type'=>$request->type,
            'navbar_color'=>$request->navbar_color,
            'left_sidebar'=>$request->left_sidebar,
            'active_sidebar'=>$request->active_sidebar,
            'collapse_sidebar'=>$request->collapse_sidebar,
        ];
        $data = ['name' => 'theme_customize'];
        $setting = auth()->user()->settings()->firstOrNew($data);
        $setting->value = json_encode($requestData);
        $setting->save();
        cache()->flush();
        return redirect()->route('admin.theme.customize')->with('success', 'Successfully save data');
    }
}
