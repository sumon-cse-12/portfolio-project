<?php

namespace App\Http\Controllers\Admin;

use App\Events\SendMail;
use App\Http\Controllers\Controller;
use App\Models\BecameReseller;
use App\Models\BillingRequest;
use App\Models\Customer;
use App\Models\CustomerSettings;
use App\Models\DynamicGateway;
use App\Models\Invoice;
use App\Models\Keyword;
use App\Models\KeywordContact;
use App\Models\Label;
use App\Models\Number;
use App\Models\NumberRequest;
use App\Models\Plan;
use App\Models\Report;
use App\Models\SenderId;
use App\Models\TopUpRequest;
use App\Models\Transactions;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Session;

class CustomerController extends Controller
{
    public function index()
    {
        return view('admin.customers.index');
    }

    public function reseller(){

        return view('admin.customers.reseller');
    }
    public function masterReseller(){

        return view('admin.customers.master_reseller');
    }
    public function getAll(Request $request)
    {
        $customers =Customer::orderBy('created_at', 'desc');
        // if(isset($request->type) && $request->type=='reseller'){
        //     $customers = Customer::select(['id','first_name', 'last_name', 'email', 'status', 'created_at'])->where( 'reseller');
        // }elseif(isset($request->type) && $request->type=='master_reseller'){
        //     $customers = Customer::select(['id','first_name', 'last_name', 'email', 'status', 'created_at'])->where( 'master_reseller');
        // }else{
        //     $customers = Customer::select(['id','first_name', 'last_name', 'email', 'status', 'created_at'])->where( 'normal');
        // }

        return datatables()->of($customers)
            ->addColumn('profile', function ($q) {

                $name='<h6>'.$q->full_name.'</h6>';
                $email='<h6>'.$q->email.'</h6>';
                $phone_number='<h6>'.$q->phone_number.'</h6>';

                return '<div>'.$name.$email.$phone_number.'</div>';
            })
            ->addColumn('image', function ($q) {
                $image=asset('images/'.$q->image);

                return '<img src="'.$image.'" width="30" height="30">';
            })
            ->addColumn('created_at', function ($q) {
                return $q->created_at->format('Y-m-d');
            })
            ->addColumn('status', function (Customer $q) {
                if($q->status=='Active'){
                    $status= '<strong class="text-white bg-success px-2 py-1 rounded status-font-size"> '.ucfirst($q->status).' </strong>';
                }else{
                    $status= '<strong class="text-white bg-danger px-2 py-1 rounded status-font-size"> '.ucfirst($q->status).' </strong>';
                }
                return $status;
            })
            // ->addColumn('is_verified', function (Customer $q) {
            //     $status='';
            //     $verification=BecameReseller::where('customer_id', $q->id)->where('status', 'approved')->first();
            //     if($verification){
            //         $status='<span title="Verified" class="badge badge-success mt-3"><i class="fa fa-check mr-1"></i> Verified</span>';
            //     }else{
            //         $status='<span title="Unverified" class="badge badge-danger mt-3"><i class="fa fa-times mr-1"></i>Unverified</span>';
            //     }

            //     return $status;
            // })
            ->addColumn('action', function (Customer $q) {
                $btn='<div class="btn-group">
                            <button type="button" class="btn btn-success rounded" data-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu" role="menu" style="">

                                <a class="dropdown-item" href="'.route('admin.customers.edit', [$q->id]).'">Edit</a>
                                <button class="dropdown-item" data-message="You will be logged in as Agant?"
                                        data-action='.route('admin.customer.login.ass').'
                                        data-input={"id":'.$q->id.'}
                                        data-toggle="modal" data-target="#modal-confirm">Login As</button>
                            </div>
                        </div>';
                return $btn;
            })
            ->rawColumns(['action','status', 'is_verified','profile','image'])
            ->toJson();
    }
    // <a class="dropdown-item" href="'.route('admin.customer.current.plan.edit', [$q->id]).'">Edit SMS Rate</a>
    // <a class="dropdown-item" href="'.route('admin.customer.assign.senderid').'">Assign SenderID</a>

    public function getCustomerInfo(Request  $request){

        $customer=Customer::where('id', $request->id)->first();
        if(!$customer){
            return response()->json(['message'=>'Invalid customer']);
        }
        $wallet=$customer->wallet;

        $data=[
            'credit'=>$wallet->credit,
        ];

        return response()->json(['data'=>$data]);
    }

    public function editCustomerPLan(Customer $customer){
        $currPLan=$customer->plan()->first();
        $data['current_plan']=$currPLan;
        return view('admin.customers.edit_plan', $data);
    }

    public function updateCustomerPLan($customer, Request $request){
        $customer=Customer::findOrFail($customer);
        $currPLan=$customer->plan()->first();

        if($request->sms_unit_price){
            $price=$request->sms_unit_price;
        }else{
            $price=0;
        }

        $currPLan->sms_unit_price=$price;
        $currPLan->save();
        cache()->forget('current_plan_'.$customer->id);

       if($customer->type=='reseller'){
            return redirect()->route('admin.reseller')->with('success', trans('customer.message.updated_customer_plan'));
        }else {
            return redirect()->route('admin.customers.index')->with('success', trans('customer.message.updated_customer_plan'));
        }
    }
    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }
        DB::beginTransaction();

        try {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:customers',
            'password' => 'required',
            'status' => 'required',
            'payment_gateway*' => 'required|in:'.implode(',', getAllPaymentGateway()),
        ]);
        $customer = new Customer();
        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->email = $request->email;
        $customer->phone_number = $request->phone_number;
        $imageName='';
        if($request->hasFile('image')){
            $file=$request->file('image');
            $imageName=time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('/images'), $imageName);
        }
        $customer->image = $imageName;
        $customer->password = $request->password;
        $customer->status = $request->status;
        $customer->agent_code = $request->agent_code;
        $customer->save();

//         $request['email_verified_at']=now();
//             if(!get_settings('reseller_status') || get_settings('reseller_status')=='disabled'){
//                 $request['type']='normal';
//             }

//         $customer=auth()->user()->customers()->create($request->all());

//         $setting= new CustomerSettings();
//         $setting->customer_id = $customer->id;
//         $setting->name = 'email_notification';
//         $setting->value = 'true';
//         $setting->save();
// //        For Payment Gateway
//         $setting= new CustomerSettings();
//         $setting->customer_id = $customer->id;
//         $setting->name = 'payment_gateway';
//         $setting->value = json_encode($request->payment_gateway);
//         $setting->save();

//         $label = new Label();
//         $label->title='new';
//         $label->customer_id=$customer->id;
//         $label->color='red';
//         $label->status='active';
//         $label->save();

//         $wallet= new Wallet();
//         $wallet->customer_id=$customer->id;
//         $wallet->credit=0;
//         $wallet->status='approved';
//         $wallet->save();

//         //Assigning plan to customer
//         $plan = Plan::first();
//             if ($plan->recurring_type == 'weekly') {
//                 $time = \Illuminate\Support\Carbon::now()->addWeek();
//             } else if ($plan->recurring_type == 'monthly') {
//                 $time = Carbon::now()->addMonth();
//             } else if ($plan->recurring_type == 'yearly') {
//                 $time = Carbon::now()->addYear();
//             } else if ($plan->recurring_type == 'custom') {
//                 $date = json_decode($plan->custom_date);
//                 $time = isset($date->from) ? new \DateTime($date->from) : '';
//             }

//             $customer->plan()->create([
//                 'is_current' => 'yes', 'price' => $plan->price, 'expire_date' => $time, 'plan_id' => $plan->id,
//                 'sms_sending_limit' => $plan->sms_sending_limit, 'max_contact' => $plan->max_contact, 'contact_group_limit' => $plan->contact_group_limit,
//                 'sms_unit_price' => $plan->sms_unit_price, 'free_sms_credit' => $plan->free_sms_credit,
//                 'coverage_ids' => $plan->coverage_ids,
//                 'api_availability' => $plan->api_availability, 'sender_id_verification' => $plan->sender_id_verification,
//                 'unlimited_sms_send' => $plan->unlimited_sms_send, 'unlimited_contact' => $plan->unlimited_contact, 'unlimited_contact_group' => $plan->unlimited_contact_group
//             ]);


            // $wallet->credit=$plan->free_sms_credit;
            // $wallet->save();

            $number = Number::where('is_default', 'yes')->first();
            if ($number) {
                $time = \Carbon\Carbon::now()->addMonths(1);
                if (!$customer->numbers()->where('is_default', 'yes')->first()) {
                    $customer->numbers()->create(['number_id' => $number->id, 'number' => $number->number, 'expire_date' => $time, 'cost' => $number->sell_price, 'is_default' => 'yes']);
                }
            }

        DB::commit();
        return back()->with('success', 'Customer successfully created');
        } catch (\Throwable $ex) {
            DB::rollback();
            return redirect()->back()->withErrors(['fail' => $ex->getMessage()]);
        }
    }

    public function edit(Customer $customer)
    {
        $data['customer'] = $customer;
        $data['availableNumbers'] = auth()->user()->available_numbers;
        if($customer->type=='normal'){
            $data['activePlans'] = auth()->user()->active_plans;
        }else if($customer->type=='reseller' && $customer->added_by=='admin') {
            $data['activePlans'] = Plan::where('enable_for', 'reseller')->where('status', 'active')->where('added_by', 'admin')->get();
        }else if($customer->type=='reseller_customer'){
            $data['activePlans'] = Plan::where('admin_id', $customer->admin_id)->where('status', 'active')->where('enable_for', 'customer')->where('added_by', 'reseller')->get();
        }
        $data['sender_Ids'] = SenderId::where('customer_id',$customer->id)->where('status','approved')->get();
        $setting= $customer->settings->where('name', 'payment_gateway')->first();
        $data['payment_gateway']=$setting && isset($setting->value)?json_decode($setting->value):[];

        return view('admin.customers.edit', $data);
    }

    public function update(Customer $customer, Request $request)
    {
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:customers,email,' . $customer->id,
            'status' => 'required',
            'payment_gateway*' => 'required|in:'.implode(',', getAllPaymentGateway()),
        ]);

        //Check for password availability
        if (!$request->password) unset($request['password']);

        //update the model
        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->email = $request->email;
        $customer->phone_number = $request->phone_number;
        if($request->hasFile('image')){
            $file=$request->file('image');
            $imageName=time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('/images'), $imageName);
        }else{
            $imageName = $customer->image;
        }
        $customer->image = $imageName;
        $customer->password = $request->password;
        $customer->status = $request->status;
        $customer->agent_code = $request->agent_code;
        $customer->save();

        //        For Payment Gateway
        $setting= $customer->settings->where('name', 'payment_gateway')->first();
        if($setting) {
            $setting=$setting;
        }else{
            $setting= new CustomerSettings();
            $setting->name= 'payment_gateway';
            $setting->customer_id= $customer->id;
        }

            $setting->value = json_encode($request->payment_gateway);
            $setting->save();
        return back()->with('success', 'Customer successfully updated');
    }

    public function assignNumber(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'customer_id' => 'required|exists:customers,id',
        ]);

        $customer = auth()->user()->customers()->where('id', $request->customer_id)->first();
        if (!$customer) return back()->with('fail', 'Customer not found');

        $number = Number::find($request->id);
        if (!$number) return back()->with('fail', 'Number not found');

        $isAssigned = $customer->numbers()->where('number_id', $number->id)->first();
        if ($isAssigned) return back()->with('fail', 'Number already assigned to this customer');

        $time = Carbon::now()->addMonths(1);

        $numb=$customer->numbers()->create(['number_id' => $number->id,'dynamic_gateway_id'=>$number->dynamic_gateway_id,'number' => $number->number,
            'expire_date' => $time, 'cost' => $number->sell_price,'sms_capability'=>$number->sms_capability,'mms_capability'=>$number->mms_capability,
            'voice_capability'=>$number->voice_capability,'whatsapp_capability'=>$number->whatsapp_capability]);


        return back()->with('success', 'Number successfully added to the customer');
    }

    public function removeNumber(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'customer_id' => 'required|exists:customers,id',
        ]);

        $customer = auth()->user()->customers()->where('id', $request->customer_id)->first();
        if (!$customer) return back()->with('fail', 'Customer not found');

        $number = Number::find($request->id);
        if (!$number) return back()->with('fail', 'Number not found');

        $isAssigned = $customer->numbers()->where('number_id', $number->id)->first();
        if (!$isAssigned) return back()->with('fail', 'Number haven\'t assigned to this customer');

        $numberRequest=NumberRequest::where('customer_id', $customer->id)->where('number_id', $isAssigned->id)->first();

        if($numberRequest){
            $numberRequest->delete();
        }
        $keyword=Keyword::where('customer_number_id',$isAssigned->id)->first();
        KeywordContact::where('keyword_id',$keyword->id)->delete();
        Keyword::where('customer_number_id',$isAssigned->id)->delete();
        $isAssigned->delete();

        return back()->with('success', 'Number successfully removed from the customer');
    }

    public function changePlan(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'id' => 'required',
                'customer_id' => 'required',
            ]);

            $customer = auth()->user()->customers()->where('id', $request->customer_id)->first();
            if (!$customer) return back()->with('fail', 'Customer not found');

            $plan = Plan::find($request->id);
            if (!$plan) return back()->with('fail', 'Plan not found');

            $pre_plan = $customer->plan;
            if ($pre_plan) {
                $isAssigned = $pre_plan->plan_id == $plan->id;
                if ($isAssigned) return back()->with('fail', 'This Plan is already assigned to this customer');
            }

            if (isset($request->from)) {

                if ($request->from == 'request' && $request->billing_id && in_array($request->status, ['accepted', 'rejected'])) {
                    $billingRequest = BillingRequest::find($request->billing_id);
                    if (!$billingRequest)
                        return back()->with('fail', 'Billing request not found');

                    if($request->status=='accepted') {
                        $transactions = Transactions::where('ref_id', $billingRequest->id)->where('type', 'plan')->first();
                        if ($transactions) {
                            $transactions->status = 'paid';
                            $transactions->save();
                        }

                        if($plan && $plan->enable_for=='reseller' && $customer->type=='normal'){
                            $customer->type='reseller';
                            $customer->save();
                        }
                    }

                    $billingRequest->status = $request->status;
                    $billingRequest->save();


                    if ($billingRequest->invoice_id) {
                        $invoice = Invoice::find($billingRequest->invoice_id);
                        if ($invoice) {
                            if ($request->status == 'accepted') {
                                $invoice->payment_status = 'approved';
                            } else {
                                $invoice->payment_status = 'rejected';
                            }
                            $invoice->save();
                        }
                    }
                    DB::commit();

                    if ($request->status == 'rejected')
                        return back()->with('success', 'Status successfully cancelled for the customer');

                } else
                    return back()->with('fail', 'Invalid data for billing request');
            }

            $emailTemplate = get_email_template('plan_accepted');
            if ($request->status == 'accepted' && $emailTemplate) {
                $regTemp = str_replace('{customer_name}', $customer->first_name . ' ' . $customer->last_name, $emailTemplate->body);
                SendMail::dispatch($customer->email, $emailTemplate->subject, $regTemp);
            }

            //delete previous plan
            //TODO: suggestion: might need to change plan status in future without deleting plan
            if ($pre_plan) {
                $customer->plan()->update(['is_current' => 'no']);
            }
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
                'sms_unit_price' => $plan->sms_unit_price, 'free_sms_credit' => $plan->free_sms_credit, 'country' => $plan->country, 'coverage_ids' => $plan->coverage_ids,
                'api_availability' => $plan->api_availability, 'sender_id_verification' => $plan->sender_id_verification,
                'unlimited_sms_send' => $plan->unlimited_sms_send, 'unlimited_contact' => $plan->unlimited_contact, 'unlimited_contact_group' => $plan->unlimited_contact_group
            ]);


            cache()->forget('current_plan_' . $customer->id);


            $wallet = $customer->wallet()->first();
            if ($customer->added_by == 'admin') {
                if ($plan->free_sms_credit > 0) {
                    $wallet->credit = $wallet->credit + $plan->free_sms_credit;
                    $wallet->save();
                    //Report
                    $report = new Report();
                    $report->customer_id = $customer->id;
                    $report->ref_id = $plan->id;
                    $report->type = 'topup';
                    $report->sub_type = 'topup';
                    $report->amount = '+' . $plan->free_sms_credit;
                    $report->save();
                }


            } else {
                $seller = Customer::where('id', $customer->admin_id)->where('type', $customer->added_by)->first();
                if (!$seller) {
                    throw new \Exception('Seller not available');
                }
                $sellerWallet = $seller->wallet()->first();

                if ($plan->free_sms_credit > 0) {
                    if ($sellerWallet->credit > $plan->free_sms_credit) {
                        $wallet->credit = $wallet->credit + $plan->free_sms_credit;
                        $wallet->save();

                        $sellerWallet->credit = $sellerWallet->credit - $plan->free_sms_credit;
                        $sellerWallet->save();
                    } else {
                        $topUpReq = new TopUpRequest();
                        $topUpReq->credit = $plan->free_sms_credit;
                        $topUpReq->credit_type = 'non_masking';
                        $topUpReq->customer_id = $customer->id;
                        $topUpReq->admin_id = $customer->admin_id;
                        $topUpReq->payment_status = 'unpaid';
                        $topUpReq->customer_type = $customer->type;
                        $topUpReq->transaction_id = $request->transaction_id;
                        $topUpReq->save();
                    }
                }
            }

            cache()->forget('wallet_' . $customer->id);

            //Transaction Report
            $transaction= new Transactions();
            $transaction->added_by=$customer->type;
            $transaction->customer_id=$customer->id;
            $transaction->type='plan';
            $transaction->amount=$plan->price;
            $transaction->status='paid';
            $transaction->ref_id=$plan->id;
            $transaction->save();

            DB::commit();
            return back()->with('success', 'Plan successfully updated for the customer');
        } catch (\Exception $ex) {
            DB::rollBack();
            return  redirect()->back()->withErrors(['failed'=>$ex->getMessage()]);
        }
    }

    public function subtract(Request $request){

        DB::beginTransaction();

        try{
            $customer = Customer::where('id', $request->customer_id)->firstOrFail();

            $current_plan=$customer->plan;

            $unit_price=$current_plan->sms_unit_price?$current_plan->sms_unit_price:0;

            $wallet = $customer->wallet;

            if(isset($request->select_type) && $request->select_type=='add'){
                if(isset($request->credit) && $request->credit > 0){
                    $wallet->credit = $wallet->credit + $request->credit;
                    $wallet->save();

                }
                cache()->forget('wallet_'.$customer->id);

                //Transaction Report
                $transaction= new Transactions();
                $transaction->added_by=$customer->type;
                $transaction->customer_id=$customer->id;
                $transaction->type='top_up';
                $transaction->amount=$request->credit * $unit_price;
                $transaction->status='paid';
                $transaction->save();


                DB::commit();
                return redirect()->back()->with('success', 'Credit Successfully Added');
            }


            if(isset($request->select_type) && $request->select_type=='subtract'){
                if(isset($request->pre_credit) && $request->pre_credit > 0 && $wallet->credit >= $request->pre_credit){
                    $wallet->credit = $wallet->credit - $request->pre_credit;
                    $wallet->save();

                    $transaction= new Transactions();
                    $transaction->added_by=$customer->type;
                    $transaction->customer_id=$customer->id;
                    $transaction->type='subtract';
                    $transaction->amount=$request->pre_credit * $unit_price;
                    $transaction->status='paid';
                    $transaction->ref_id=$wallet->id;
                    $transaction->save();

                }
                cache()->forget('wallet_'.$customer->id);
                DB::commit();
                return redirect()->back()->with('success', 'Credit Successfully Subtracted');
            }

        }catch(\Exception $ex){
            DB::rollBack();
            return redirect()->back()->withErrors(['failed'=>$ex->getMessage()]);
        }
    }

    public function assignSenderId()
    {

        $data['availableSMSGateway']=DynamicGateway::where('status', 'active')->orderByDesc('created_at')->get();
        $data['customers'] = Customer::where('status', 'active')->get();

        return view('admin.customers.assign_senderid', $data);
    }

    public function saveAssignSenderId(Request  $request){
        $request->validate([
            'sender_id' => 'required|unique:sender_ids,sender_id',
        ]);
        $customer= Customer::where('id', $request->customer_id)->firstOrFail();

        $request['status']='approved';
        $request['is_paid']='yes';
        $request['dynamic_gateway_id']=$request->dynamic_gateway_id;

        $customer->sender_ids()->create($request->only('sender_id', 'dynamic_gateway_id','expire_date', 'from', 'status','is_paid'));

        return redirect()->back()->with('success', 'SenderID Successfully Assigned');
    }

    public function loginAs(Request $request){
        if(!$request->id) abort(404);
        Customer::where('id', $request->id)->update(['updated_at' => now()]);
        session(['customer_session_'.$request->id => $request->id]);
        auth('customer')->loginUsingId($request->id);
        return redirect()->route('admin.customer.ibft.transfer.list')->with('success',trans('You are now logged as Agent'));
    }


}
