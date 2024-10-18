<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Settings;

class ThemeController extends Controller
{
    public function index(){
        return  view('admin.theme.index');
    }
    public function sign_up_index(){
        return  view('admin.signupinfo.create');
    }
    public function omug_index(){
        return  view('admin.omug.create');
    }
    public function resources_index(){
        return  view('admin.resources.create');
    }
    public function teams_index(){
        return  view('admin.teams.create');
    }
    public function fees_index(){
        return  view('admin.fees.create');
    }
    public function courses_index(){
        return  view('admin.courses.create');
    }
    public function services_index(){
        return  view('admin.services.create');
    }
    public function welcome_section_index(){
        return  view('admin.welcome_section.create');
    }
    public function sign_up_info(Request $request){
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }

        $data = ['name' => 'sign_up_info'];
        $setting = auth()->user()->settings()->firstOrNew($data);
        $setting->value = json_encode($request->only('title','short_description','description'));
        $setting->save();

        cache()->flush();
        return redirect()->back()->with('success','Sign Up Info Theme successfully update');
    }
    public function resources(Request $request){
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }

        $data = ['name' => 'resources'];
        $setting = auth()->user()->settings()->firstOrNew($data);
        $setting->value = json_encode($request->only('title','description'));
        $setting->save();

        cache()->flush();
        return redirect()->back()->with('success','Resources Theme successfully update');
    }
    public function omug(Request $request){
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }

        if($request->hasFile('omug_image')){
            if (isset($request->pre_image) && !empty($request->pre_image)) {
                $oldImagePath = public_path('/uploads') . '/' . $request->pre_image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $file=$request->file('omug_image');
            $omug_image=time().'imo'.'.'.$file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $omug_image);
            $request['image']=$omug_image;
        }else{
            $request['image']=$request->pre_image;
        }

        $data = ['name' => 'omug'];
        $setting = auth()->user()->settings()->firstOrNew($data);
        $setting->value = json_encode($request->only('title','short_description','description','image','omug_youtube_link'));
        $setting->save();

        cache()->flush();
        return redirect()->back()->with('success','OMUG Theme successfully update');
    }
    public function team(Request $request){
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }

        $team_data = [];
        if($request->name) {
            foreach ($request->name as $key => $name) {

                $imagePath = '';
                if (isset($request->pre_image[$key]) && $request->pre_image[$key]) {
                    $imagePath = $request->pre_image[$key];
                } else if (isset($request->image[$key]) && $request->image[$key]) {
                    if (isset($request->pre_image[$key]) && !empty($request->pre_image[$key])) {
                        $oldImagePath = public_path('/uploads') . '/' . $request->pre_image[$key];
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                    $file = $request->file('image')[$key];
                    $imageName = time() . $key . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('/uploads'), $imageName);
                    $imagePath = $imageName;
                }


                $team_data[] = [
                    'name' => $name,
                    'work_title' => isset($request->work_title[$key]) ? $request->work_title[$key] : '',
                    'email' => isset($request->email[$key]) ? $request->email[$key] : '',
                    'image' => $imagePath,
                ];
            }
            $request['team_data'] = $team_data;
        }

        $data = ['name' => 'team'];
        $setting = auth()->user()->settings()->firstOrNew($data);
        $setting->value = json_encode($request->only('title','team_data'));
        $setting->save();

        cache()->flush();

        return redirect()->back()->with('success','Team Theme successfully updated');
    }
    public function services(Request $request){
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }


        $service_data = [];
        if($request->name) {
            foreach ($request->name as $key => $name) {
                $imagePath = '';
                if (isset($request->pre_image[$key]) && $request->pre_image[$key]) {
                    $imagePath = $request->pre_image[$key];
                } else if (isset($request->image[$key]) && $request->image[$key]) {
                    if (isset($request->pre_image[$key]) && !empty($request->pre_image[$key])) {
                        $oldImagePath = public_path('/uploads') . '/' . $request->pre_image[$key];
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                    $file = $request->file('image')[$key];
                    $imageName = time() . $key . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('/uploads'), $imageName);
                    $imagePath = $imageName;
                }

                $service_data[] = [
                    'name' => $name,
                    'image' => $imagePath,
                    'description' => isset($request->description[$key]) ? $request->description[$key] : '',
                ];
            }
        }


        $request['service_data'] = $service_data;

        $data = ['name' => 'services'];
        $setting = auth()->user()->settings()->firstOrNew($data);
        $setting->value = json_encode($request->only('title','sub_title','service_data'));
        $setting->save();
        cache()->flush();

        return redirect()->back()->with('success','Team Theme successfully updated');
    }
    public function fees(Request $request){
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }

        $data = ['name' => 'fees'];
        $setting = auth()->user()->settings()->firstOrNew($data);
        if($request->service) {
            foreach ($request->service as $key => $service) {
                $fees_data[] = [
                    'service' => $service,
                    'type_of_instrument' => isset($request->type_of_instrument[$key])?$request->type_of_instrument[$key]:'',
                    'uhn_rate' => isset($request->uhn_rate[$key])?$request->uhn_rate[$key]:'',
                    'ea_rate' => isset($request->ea_rate[$key])?$request->ea_rate[$key]:'',
                ];
            }
            $request['fees_data'] = $fees_data;
        }

        $data = ['name' => 'fees'];
        $setting = auth()->user()->settings()->firstOrNew($data);
        $setting->value = json_encode($request->only('title','sub_title','fees_data'));
        $setting->save();

        cache()->flush();
        return redirect()->back()->with('success','Fees Theme successfully update');
    }
    public function courses(Request $request){
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }
        if($request->hasFile('image')){
            if (isset($request->pre_image) && !empty($request->pre_image)) {
                $oldImagePath = public_path('/uploads') . '/' . $request->pre_image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $file=$request->file('image');
            $imageone=time().'imo'.'.'.$file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageone);
            $request['imageone']=$imageone;
        }else{
            $request['imageone']=$request->pre_image;
        }
        if($request->hasFile('image_two')){
            if (isset($request->pre_image_two) && !empty($request->pre_image_two)) {
                $oldImagePath = public_path('/uploads') . '/' . $request->pre_image_two;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $file=$request->file('image_two');
            $image_two=time().'imt'.'.'.$file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $image_two);
            $request['imagetwo']=$image_two;
        }
        else{
            $request['imagetwo']=$request->pre_image_two;
        }
        if($request->hasFile('image_three')){
            if (isset($request->pre_image_three) && !empty($request->pre_image_three)) {
                $oldImagePath = public_path('/uploads') . '/' .$request->pre_image_three;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $file=$request->file('image_three');
            $image_three=time().'imth'.'.'.$file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $image_three);
            $request['imagethree']=$image_three;
        }
        else{
            $request['imagethree']=$request->pre_image_three;
        }
        if($request->hasFile('image_four')){
            if (isset($request->pre_image_four) && !empty($request->pre_image_four)) {
                $oldImagePath = public_path('/uploads') . '/' . $request->pre_image_four;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $file=$request->file('image_four');
            $image_four=time().'imfo'.'.'.$file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $image_four);
            $request['imagefour']=$image_four;
        }
        else{
            $request['imagefour']=$request->pre_image_four;
        }
        $data = ['name' => 'courses'];
        $setting = auth()->user()->settings()->firstOrNew($data);
        $setting->value = json_encode($request->only('title','title_two','title_three','title_four','description','description_two','description_three','description_four',
                                                    'imageone','imagetwo','imagethree','imagefour'));
        $setting->save();

        cache()->flush();
        return redirect()->back()->with('success','Courses Theme successfully update');
    }
    public function welcome_section(Request $request){
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }
        if($request->hasFile('image')){
            if (isset($request->pre_image) && !empty($request->pre_image)) {
                $oldImagePath = public_path('/uploads') . '/' . $request->pre_image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $file=$request->file('image');
            $imageone=time().'imo'.'.'.$file->getClientOriginalExtension();
            $file->move(public_path('/uploads'), $imageone);
            $request['imageone']=$imageone;
        }else{
            $request['imageone']=$request->pre_image;
        }
        $data = ['name' => 'welcome_section'];
        $setting = auth()->user()->settings()->firstOrNew($data);
        $setting->value = json_encode($request->only('title','imageone','description','section_one_founded','section_one_count',
                                                    'section_one_experience','section_two_founded','section_two_count','section_two_experience',
                                                    'section_three_founded','section_three_count','section_three_experience'));
        $setting->save();

        cache()->flush();
        return redirect()->back()->with('success','Welcome Section Theme successfully update');
    }
    public function slider_index(){
        return view('admin.slider.index');
    }

    public function slider_store(Request $request){
        $home_slider_bg_images = [];
        $home_slider_sections = json_decode(get_settings('home_slider_section'), true);

        if ($request->hasfile('slider_bg_image')) {
            if (isset($home_slider_sections['home_slider_images'])) {
                foreach ($home_slider_sections['home_slider_images'] as $existingImage) {
                    $existingImagePath = public_path('/uploads/' . $existingImage);
                    if (file_exists($existingImagePath)) {
                        unlink($existingImagePath);
                    }
                }
            }

            foreach ($request->file('slider_bg_image') as $key => $image) {
                $filename = time() . 'slider' . $key . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('/uploads'), $filename);
                $home_slider_bg_images[] = $filename;
            }

            $request['home_slider_images'] = $home_slider_bg_images;
        } else {
            $request['home_slider_images'] = $home_slider_sections['home_slider_images'];
        }

        $data = ['name' => 'home_slider_section'];

        $home_slider_section = auth()->user()->settings()->firstOrNew($data);
        $home_slider_section->value = json_encode($request->only('slider_title', 'slider_sub_title', 'slider_short_description', 'home_slider_images','book_an_instruments'));
        $home_slider_section->save();
        cache()->flush();
        return redirect()->route('admin.theme.slider.index')->with('success', 'Successfully saved data');
    }

    public function contact_us_index(){
        return view('admin.home_contact_us.index');
    }

    public function contact_us_store(Request $request){

        $home_contact_us_sections = json_decode(get_settings('home_contact_us'), true);
        $data = ['name' => 'home_contact_us'];
        $home_contact_us_sections = auth()->user()->settings()->firstOrNew($data);
        $home_contact_us_sections->value = json_encode($request->only('contact_us_title', 'contact_us_short_description', 'contact_us_address', 'contact_us_google_map'));
        $home_contact_us_sections->save();
        cache()->flush();
        return redirect()->route('admin.theme.contact.index')->with('success', 'Successfully saved data');
    }

}
