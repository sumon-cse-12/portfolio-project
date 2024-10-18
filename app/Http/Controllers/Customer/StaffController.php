<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerSettings;
use App\Models\Plan;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    public function index(){
        return view('customer.staff.index');
    }
    public function create()
    {

        return view('customer.staff.create');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|unique:customers',
                'password' => 'required',
                'status' => 'required',
            ]);

            $customer=new Customer();
            $customer->first_name=$request->first_name;
            $customer->last_name=$request->last_name;
            $customer->email=$request->email;
            $customer->password=$request->password;
            $customer->status=$request->status;
            $customer->email_verified_at=now();
            $customer->admin_id=auth('customer')->user()->id;
            $customer->type= 'staff';
            $customer->added_by= auth('customer')->user()->type;
            $customer->profile_picture='default_profile.png	';
            $customer->save();

            $setting= new CustomerSettings();
            $setting->customer_id = $customer->id;
            $setting->name = 'email_notification';
            $setting->value = 'true';
            $setting->save();

            //Assigning plan to customer
            $plan = Plan::first();
            if ($plan->recurring_type == 'weekly') {
                $time = \Illuminate\Support\Carbon::now()->addWeek();
            } else if ($plan->recurring_type == 'monthly') {
                $time = Carbon::now()->addMonth();
            } else if ($plan->recurring_type == 'yearly') {
                $time = Carbon::now()->addYear();
            } else if ($plan->recurring_type == 'custom') {
                $date = json_decode($plan->custom_date);
                $time = isset($date->from) ? new \DateTime($date->from) : '';
            }

            $customer->plan()->create([
                'is_current' => 'yes', 'price' => $plan->price, 'expire_date' => $time, 'plan_id' => $plan->id,
                'sms_sending_limit' => $plan->sms_sending_limit, 'max_contact' => $plan->max_contact, 'contact_group_limit' => $plan->contact_group_limit,
                'sms_unit_price' => $plan->sms_unit_price, 'free_sms_credit' => $plan->free_sms_credit,
                'coverage_ids' => $plan->coverage_ids,
                'api_availability' => $plan->api_availability, 'sender_id_verification' => $plan->sender_id_verification,
                'unlimited_sms_send' => $plan->unlimited_sms_send, 'unlimited_contact' => $plan->unlimited_contact, 'unlimited_contact_group' => $plan->unlimited_contact_group
            ]);

            DB::commit();
            return redirect()->route('customer.staff.index')->with('success', 'Staff successfully created');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['failed' => $e->getMessage()]);
        }
    }


    public function getAll(){
        $staffs = Customer::where('admin_id', auth('customer')->user()->id)->where('type', 'staff');
        return datatables()->of($staffs)
            ->addColumn('action',function($q){
                return "<a class='btn btn-sm btn-info' href='".route('customer.staff.edit',[$q->id])."'>Edit</a> &nbsp; &nbsp;".
                    '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this staff?"
                                        data-action='.route('customer.staff.destroy',[$q]).'
                                        data-input={"_method":"delete"}
                                        data-toggle="modal" data-target="#modal-confirm">Delete</button>' ;
            })
            ->rawColumns(['action'])
            ->toJson();
    }
    public function edit($id){
        $customer=Customer::findOrFail($id);
        $data['staff']=$customer;
        return view('customer.staff.edit',$data);
    }

    public function update($id,Request $request){

        $staff=Customer::findOrFail($id);
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:staff,email,' . $staff->id,
            'status' => 'required|in:active,inactive'
        ]);

        if (!$request->password){
            unset($request['password']);
        }
        $staff->update($request->all());
        return back()->with('success','Staff successfully updated');
    }

    public function destroy($id){
        $staff=Customer::findOrFail($id);

        $staff->delete();
        return back()->with('success','Staff successfully deleted');
    }

}
