<?php

namespace App\Http\Controllers;

use App\Models\BlogList;
use App\Models\Coverage;
use App\Models\Customer;
use App\Models\Domain;
use App\Models\FAQ;
use App\Models\Instrument;
use App\Models\InstrumentDetail;
use App\Models\Page;
use App\Models\Plan;
use App\Models\Publication;
use App\Models\Subscribe;
use App\Models\Template;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FrontController extends Controller
{

    public function maintenance(){

        $maintence=get_settings('maintence_mode');

        if($maintence && $maintence=='disable'){
            return redirect()->route('customer.dashboard');
        }

        return view('welcome');
    }

    public function home(Request $request){
        if(get_settings('landing_page_status')=='disable'){
            return redirect()->route('login');
        }
        $data['local_setting'] = json_decode(get_settings('local_setting'));
        $data['faqs'] = FAQ::where('status', 'active')->get();

        $data['instruments']=Instrument::orderByDesc('created_at')->get();

        $data['fees']=[];
        $data['teams']=get_settings('team')?json_decode(get_settings('team')):[];


        return  view('front.index',$data);
    }

    public function blog(){
        $data['blogs'] = BlogList::where('status','active')->get();
        return view('front.blog',$data);
    }  
    public function blog_details($slug)
    {
        $data['blog'] = BlogList::where('slug', $slug)->firstOrFail();
        $data['recent_blogs'] = BlogList::where('status','active')->orderByDesc('created_at')->get();
        return view('front.blog_details', $data);
    }
      
    public function publications(){
        $data['publications'] = Publication::where('status','active')->get();
        return view('front.publications',$data);
    }
    public function publications_details($slug){
        $data['publication']=Publication::where('slug', $slug)->firstOrFail();
        $data['recent_publications'] = Publication::where('status','active')->orderByDesc('created_at')->get();

        return view('front.publications_details',$data);
    }   

    public function coverage($id){
        $plan=Plan::findOrFail($id);
        if(!$plan->coverage_ids){
            return abort('404');
        }

        $coverages=Coverage::whereIn('id', json_decode($plan->coverage_ids))->get();
        $data['coverages']=$coverages;
        $data['coverage']=isset($coverages[0])?$coverages[0]:'';
        $data['plan_id']=$plan->id;
        $data['plan_title']=$plan->title;

        return view('front.coverage', $data);
    }

    public function get_coverage(Request $request){

        $coverage=Coverage::where('id', $request->country)->select('plain_sms','receive_sms','send_mms','receive_mms','send_voice_sms','receive_voice_sms','send_whatsapp_sms','receive_whatsapp_sms')->first();
        if(!$coverage){
            return response()->json(['status'=>'failed']);
        }

        return response()->json(['data'=>$coverage,'status'=>'success']);
    }


    public function aboutUs(Request $request){
        $host=$request->getHost();
        $domain=Domain::where('host', $host)->first();
        if ($domain) {
            $customer = Customer::find($domain->customer_id);
            $customerPlan=isset($customer->plan)?$customer->plan:'';
            $module = isset($customerPlan->module) ? json_decode($customerPlan->module) : [];
            if (!in_array('landing_page', $module)) {
                return redirect()->route('login');
            }
        }
        return view('front.about_us');
    }

    public function services(Request $request){

        $host=$request->getHost();
        $domain=Domain::where('host', $host)->first();
        if ($domain) {
            $customer = Customer::find($domain->customer_id);
            $customerPlan=isset($customer->plan)?$customer->plan:'';
            $module = isset($customerPlan->module) ? json_decode($customerPlan->module) : [];
            if (!in_array('landing_page', $module)) {
                return redirect()->route('login');
            }
        }
        return view('front.service');
    }
    public function pricing(Request $request){
        $host=$request->getHost();
        $domain=Domain::where('host', $host)->where('status', 'approved')->first();

        if ($domain) {
            $customer = Customer::find($domain->customer_id);
            $customerPlan=isset($customer->plan)?$customer->plan:'';
            $module = isset($customerPlan->module) ? json_decode($customerPlan->module) : [];
            if (!in_array('landing_page', $module)) {
                return redirect()->route('login');
            }
            $data['plans'] = Plan::where('admin_id',$customer->id)->where('status', 'active')->where('added_by', $customer->type)->get();
        }else {
            $data['plans'] = Plan::where('id', '!=', 1)->where('status', 'active')->where('added_by', 'admin')->where('plan_type', 'normal')->get();
        }
        return view('front.pricing',$data);
    }
    public function contact(Request  $request){
        $host=$request->getHost();
        $domain=Domain::where('host', $host)->first();
        if ($domain) {
            $customer = Customer::find($domain->customer_id);
            $customerPlan=isset($customer->plan)?$customer->plan:'';
            $module = isset($customerPlan->module) ? json_decode($customerPlan->module) : [];
            if (!in_array('landing_page', $module)) {
                return redirect()->route('login');
            }
        }
        return view('front.contact_us');
    }
    public function page($page){

        $data['page'] = Page::where('url',$page)->where('status','published')->firstOrFail();
        return  view('front.page',$data);
    }
    public function demo_login(){

        return view('front.login_demo');
    }
    public function verifyCode(Request $request){
       $code=$request->purchase_code;
       if(!$code){
           abort(404);
       }
        $client = new Client(['verify'=>false]);
        $res = $client->request('GET', 'http://verify.picotech.app/verify.php?purchase_code='.$code);
        $response= json_decode($res->getBody());

        if(isset($response->id) && $response->id){
            $data=[
                'code'=>$code,
                'id'=>$response->id,
                'checked_at'=>now()
            ];
            File::put(storage_path().'/framework/build',base64_encode(json_encode($data)));
            if($request->verify){
                return back();
            }
            return back()->with('success','Purchase code verified successfully');

        }else{
            File::delete(storage_path().'/framework/build');
            return back()->withErrors(['msg'=>'Invalid purchase code']);
        }

    }
}
