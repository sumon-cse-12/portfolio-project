<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\BillingRequest;
use App\Models\CustomerPlan;
use App\Models\Number;
use App\Models\Plan;
use Illuminate\Http\Request;


class BillingController extends Controller
{
    public function index(){
        $authUser= auth('customer')->user();
        if($authUser->type=='reseller_customer'){
            $data['plans']=Plan::where('admin_id', $authUser->admin_id)->where('status','active')->where('added_by', 'reseller')->get();
        }else if($authUser->type=='reseller' && $authUser->added_by=='admin') {
            $data['plans'] = Plan::where('status', 'active')->where('added_by', 'admin')->where('enable_for', 'reseller')->where('id', '!=', '1')->get();
        }else{
            $data['plans']=Plan::where('status','active')->where('added_by', 'admin')->where('id', '!=', '1')->get();
        }
        $data['customer_plan']=auth('customer')->user()->plan;
        $data['billing_requests']=BillingRequest::where('customer_id', auth('customer')->user()->id)->get();
        $data['pending_request']=BillingRequest::where('customer_id', auth('customer')->user()->id)->where('status', 'pending')->orderByDesc('created_at')->first();

        return view('customer.billings.index',$data);
    }

    public function resellerPlan(){
        $data['plans']=Plan::where('status','active')->where('added_by', 'admin')->get();
        $data['customer_plan']=auth('customer')->user()->plan;
        return view('customer.billings.reseller_plan', $data);
    }
    public function update(Request $request){
        $request->validate([
            'id'=>'required|exists:plans'
        ]);
        $plan=Plan::find($request->id);
        if(!$plan){
            return redirect()->back()->with('fail','You plan not found');
        }
        $pre_plan=auth('customer')->user()->plan;
        if(isset($pre_plan) && $pre_plan->plan_id==$request->id){
            return redirect()->back()->with('fail','You are already subscribed to this plan');
        }
        $customer=auth('customer')->user();
        $preBilling=BillingRequest::where(['plan_id'=>$plan->id,'customer_id'=>$customer->id,'status'=>'pending'])->first();
        if($preBilling){
            return redirect()->back()->with('fail','You already have a pending request with this plan. Please wait for the admin reply.');
        }

        BillingRequest::where(['customer_id'=>$customer->id,'status'=>'pending'])->delete();

        $planReq=new BillingRequest();
        $planReq->admin_id=$plan->admin_id;
        $planReq->customer_id=$customer->id;
        $planReq->plan_id=$plan->id;
        $planReq->save();

        // TODO:: send email to customer here

        return redirect()->back()->with('success','We have received your request. We Will contact with you shortly');
    }

    public function changePLan()
    {
        $customer= auth('customer')->user();
        $billingRequest=BillingRequest::where('customer_id', $customer->id)->where('status', 'pending')->first();
        if ($billingRequest){
            $billingRequest->delete();
        }
        return redirect()->route('customer.billing.index');
    }

    public function cancelRequest(Request $request){
        $billing_req=BillingRequest::where('id', $request->id)->where('customer_id', auth('customer')->user()->id)->firstOrFail();

        if($billing_req->status=='pending'){
            $billing_req->status='rejected';
            $billing_req->save();
        }

        return redirect()->back()->with('success', 'Billing Request Successfully Canceled');
    }
}
