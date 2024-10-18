<?php

namespace App\Http\Controllers\Customer;

use App\Events\SendMail;
use App\Http\Controllers\Controller;
use App\Models\BillingRequest;
use App\Models\Domain;
use App\Models\EmailTemplate;
use App\Models\Report;
use App\Models\TopUpRequest;
use App\Models\Transactions;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Plan;
use App\Models\Number;
use App\Models\senderId;
use App\Models\CustomerSettings;
use App\Models\Label;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ResellerCustomerController extends Controller
{

    public function index()
    {
        return view('customer.reseller_customers.index');
    }

    public function reseller()
    {
        return view('customer.reseller_customers.reseller');
    }

    public function getAll(Request $request)
    {

        $user=auth('customer')->user();
        $customers=[];
       if($user->type=='reseller'){
            $customers = Customer::select(['id', 'first_name', 'last_name','type', 'email', 'status', 'created_at'])->where('type', 'reseller_customer')->where('admin_id', $user->id)->get();
        }

        return datatables()->of($customers)
            ->addColumn('profile', function ($q) {

                $name='<h6>'.$q->full_name.'</h6>';
                $email='<h6>'.$q->email.'</h6>';
                return '<div>'.$name.$email.'</div>';
            })
            ->addColumn('plan_details', function ($q) {
                if($q->plan && $q->plan->plan) {
                    $plan=$q->plan->plan;
                    $endDate = '';
                    if ($plan->recurring_type == 'yearly') {
                        $endDate = now()->addYear();
                    } else if ($plan->recurring_type == 'weekly') {
                        $endDate = now()->addWeek();
                    } else if ($plan->recurring_type == 'monthly') {
                        $endDate = now()->addMonth();
                    }
                    $title = '<p class="m-0 p-0">' . trans('customer.plan_name') . ': ' . $plan->title . '</p>';
                    if ($plan->recurring_type == 'custom') {
                        $customDate=json_decode($plan->custom_date);
                        $to=isset($customDate->to)?new Carbon($customDate->to):now();
                        $from=isset($customDate->from)?new Carbon($customDate->from):now()->addMonth(4);
                        $created_at = '<p class="m-0 p-0">' . trans('customer.subscription_on') . ': ' . formatDate($to) . '</p>';
                        $ended_at = '<p class="m-0 p-0">' . trans('customer.ended_at') . ': ' . formatDate($to) . '</p>';
                    }else{
                        $created_at = '<p class="m-0 p-0">' . trans('customer.subscription_on') . ': ' . formatDate($q->created_at) . '</p>';
                        $ended_at = '<p class="m-0 p-0">' . trans('customer.ended_at') . ': ' . formatDate($endDate) . '</p>';
                    }

                    $plan_details='<div>'.$title.$created_at.$ended_at.'</div>';
                }else{
                    $plan_details='';
                }
                return $plan_details;
            })
            ->addColumn('status', function (Customer $q) {
                if($q->status=='Active'){
                    $status= '<strong class="text-success"> '.ucfirst($q->status).' </strong>';
                }else{
                    $status= '<strong class="text-danger"> '.ucfirst($q->status).' </strong>';
                }
                return $status;
            })
            ->addColumn('unit', function (Customer $q) {
                $wallet= $q->wallet()->first();
                $unit="<h6>".trans('Credit').': '.$wallet->credit.'</h6>';
                return $unit;
            })
            ->addColumn('action', function (Customer $q) {
                $btn='<div class="btn-group">
                            <button type="button" class="btn btn-success" data-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu" role="menu" style="">
                                <a class="dropdown-item" href="'.route('customer.reseller-customers.edit', [$q->id]).'">Edit</a>
                                 <a class="dropdown-item addSubstract" data-id="'.$q->id.'" type="button">Add / Substract</a>
                                <button class="dropdown-item" data-message="You will be logged in as Agant?"
                                        data-action='.route('customer.reseller.customer.loginas').'
                                        data-input={"id":'.$q->id.'}
                                        data-toggle="modal" data-target="#modal-confirm">Login As</button>
                            </div>
                        </div>';
                return $btn;
            })
            ->rawColumns(['action', 'unit','status','profile','plan_details'])
            ->toJson();
    }

    public function editCustomerPLan(Customer $customer){
        $currPLan=$customer->plan()->first();
        $data['current_plan']=$currPLan;
        return view('customer.reseller_customers.edit_plan', $data);
    }

    public function updateCustomerPLan($customer, Request $request){
        $customer=Customer::findOrFail($customer);
        $currPLan=$customer->plan()->first();

        return redirect()->route('customer.reseller-customers.index')->with('success', trans('customer.message.updated_customer_plan'));
    }

    public function create()
    {
        $settings = auth('customer')->user()->settings()->where('name', 'payment_gateway')->first();
        $data['gateways']=isset($settings->value)?json_decode($settings->value):[];
        $data['plans']=auth('customer')->user()->plans()->where('added_by', 'reseller')->get();

        return view('customer.reseller_customers.create', $data);
    }

    public function getPlan(Request  $request){
        if ($request->type=='reseller') {
            $plans = auth('customer')->user()->plans()->select('title', 'id')->where('plan_type', 'reseller')->where('added_by', auth('customer')->user()->type)->get();
        }else{
            $plans = auth('customer')->user()->plans()->select('title', 'id')->where('added_by', auth('customer')->user()->type)->get();
        }

        return response()->json(['data', $plans]);
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
            $plan = Plan::where('id', $request->plan_id)->first();
            if(!$plan){
            return redirect()->back()->withErrors(['failed'=> 'This plan isn\'n available']);
            }

            if(auth('customer')->user()->type=='master_reseller'){
                $type=$request->type;
            }else if(auth('customer')->user()->type=='reseller'){
                $type='reseller_customer';
            }
            $customer=new Customer();
            $customer->first_name=$request->first_name;
            $customer->last_name=$request->last_name;
            $customer->email=$request->email;
            $customer->password=$request->password;
            $customer->status=$request->status;
            $customer->email_verified_at=now();
            $customer->admin_id=auth('customer')->user()->id;
            $customer->type= $type;
            $customer->added_by= auth('customer')->user()->type;
            $customer->profile_picture='default_profile.png	';
            $customer->save();

            $setting= new CustomerSettings();
            $setting->customer_id = $customer->id;
            $setting->name = 'email_notification';
            $setting->value = 'true';
            $setting->save();
            //        For Payment Gateway
            $setting= new CustomerSettings();
            $setting->customer_id = $customer->id;
            $setting->name = 'payment_gateway';
            $setting->value = json_encode($request->payment_gateway);
            $setting->save();

            $label = new Label();
            $label->title='new';
            $label->customer_id=$customer->id;
            $label->color='red';
            $label->status='active';
            $label->save();

//            For Customer Wallet
            $wallet= new Wallet();
            $wallet->customer_id=$customer->id;
            $wallet->credit=0;
            $wallet->status='approved';
            $wallet->save();

            //Assigning plan to customer
            if ($plan->recurring_type == 'weekly') {
                $time = \Illuminate\Support\Carbon::now()->addWeek();
            } else if ($plan->recurring_type == 'monthly') {
                $time = \Carbon\Carbon::now()->addMonth();
            } else if ($plan->recurring_type == 'yearly') {
                $time = Carbon::now()->addYear();
            } else if ($plan->recurring_type == 'custom') {
                $date = json_decode($plan->custom_date);
                $time = isset($date->from) ? new \DateTime($date->from) : '';
            }

            $customer->plan()->create([
                'is_current' => 'yes', 'price' => $plan->price, 'expire_date' => $time, 'plan_id' => $plan->id,
                'sms_sending_limit' => $plan->sms_sending_limit, 'max_contact' => $plan->max_contact, 'contact_group_limit' => $plan->contact_group_limit,
                'sms_unit_price' => $plan->sms_unit_price, 'free_sms_credit' => $plan->free_sms_credit,'coverage_ids'=>$plan->coverage_ids,
                'api_availability' => $plan->api_availability, 'sender_id_verification' => $plan->sender_id_verification,
                'unlimited_sms_send' => $plan->unlimited_sms_send, 'unlimited_contact' => $plan->unlimited_contact, 'unlimited_contact_group' => $plan->unlimited_contact_group
            ]);

            //Transaction Report
            $transaction= new Transactions();
            $transaction->customer_id=$customer->id;
            $transaction->added_by=$customer->type;
            $transaction->type='plan';
            $transaction->amount=$plan->price;
            $transaction->status='paid';
            $transaction->ref_id=$plan->id;
            $transaction->save();

//            Default Number
            $number = Number::where('is_default', 'yes')->first();
            if ($number) {
                $time = \Carbon\Carbon::now()->addMonths(1);
                if (!$customer->numbers()->where('is_default', 'yes')->first()) {
                    $customer->numbers()->create(['number_id' => $number->id, 'number' => $number->number, 'expire_date' => $time, 'cost' => $number->sell_price, 'is_default' => 'yes']);
                }
            }

            $sellerWallet = auth('customer')->user()->wallet()->first();

            if ($plan->free_sms_credit > 0) {
                if ($sellerWallet->credit > $plan->free_sms_credit) {
                    $wallet->credit = $wallet->credit + $plan->free_sms_credit;
                    $wallet->save();

                    $sellerWallet->credit = $sellerWallet->credit - $plan->free_sms_credit;
                    $sellerWallet->save();

                } else {
                    $topUpReq = new TopUpRequest();
                    $topUpReq->credit = $plan->free_sms_credit;
                    $topUpReq->customer_id = $customer->id;
                    $topUpReq->admin_id = $customer->admin_id;
                    $topUpReq->payment_status = 'unpaid';
                    $topUpReq->customer_type = $customer->type;
                    $topUpReq->transaction_id = $request->transaction_id;
                    $topUpReq->save();
                }
            }
            cache()->forget('wallet_' . $customer->id);


            DB::commit();
            return redirect()->route('customer.reseller-customers.index')->with('success', 'Customer successfully created');
        } catch (\Throwable $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['fail' => $e->getMessage()]);
        }
    }

    public function edit(Customer $reseller_customer)
    {
        $data['reseller_customer'] = $reseller_customer;
        $customer= auth('customer')->user();
        $data['availableNumbers'] = $customer->numbers;

        if($reseller_customer->type=='reseller') {
            $data['activePlans'] = $customer->plans()->where('added_by', $customer->type)->where('plan_type', 'reseller')->where('status', 'active')->get();
        }else {
            $data['activePlans'] = $customer->plans()->where('added_by', $customer->type)->where('status', 'active')->get();
        }

        $data['sender_Ids'] = $customer->sender_ids()->where('is_paid', 'yes')->get();

        $settings = auth('customer')->user()->settings()->where('name', 'payment_gateway')->first();
        $data['gateways']=isset($settings->value)?json_decode($settings->value):[];

        $reseller_setting= $reseller_customer->settings->where('name', 'payment_gateway')->first();
        $data['payment_gateway']=$reseller_setting && isset($reseller_setting->value)?json_decode($reseller_setting->value):[];

        return view('customer.reseller_customers.edit', $data);
    }

    public function update(Customer $reseller_customer, Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:customers,email,'.$reseller_customer->id,
            'status' => 'required'
        ]);

        //Check for password availability
        if (!$request->password) unset($request['password']);

        //update the model
        $reseller_customer->update($request->all());

        $setting= $reseller_customer->settings->where('name', 'payment_gateway')->first();
        if($setting) {
            $setting=$setting;
        }else{
            $setting= new CustomerSettings();
            $setting->name= 'payment_gateway';
            $setting->customer_id= $reseller_customer->id;
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

        $customer = auth('customer')->user()->customers()->where('id', $request->customer_id)->first();
        if (!$customer) return back()->with('fail', 'Customer not found');

        $reseller_number = auth('customer')->user()->numbers->where('id', $request->id)->first();
        $number = Number::where('id', $reseller_number->number_id)->first();

        if (!$number) return back()->with('fail', 'Number not found');

        $isAssigned = $customer->numbers()->where('number_id', $number->id)->first();
        if ($isAssigned) return back()->with('fail', 'Number already assigned to this customer');

        $time = Carbon::now()->addMonths(1);

        $customer->numbers()->create(['number_id' => $number->id, 'number' => $number->number, 'expire_date' => $time, 'cost' => $number->sell_price]);

        $reseller_number->delete();

        return back()->with('success', 'Number successfully added to the customer');
    }


    public function senderId(Request $request){

        $reseller_customer = auth('customer')->user()->customers->where('id', $request->customer_id)->first();
        if(!$reseller_customer){
            return redirect()->back()->withErrors(['failed'=> 'Invalid customer']);
        }

        if($request->type=='remove'){
            $sender_id=$reseller_customer->sender_ids->where('id', $request->id)->first();
            if(!$sender_id){
                return redirect()->back()->withErrors(['failed'=> 'Invalid sender ID']);
            }
            $senderIdData=$sender_id;
            $sender_id->delete();
            $senderId= new senderId();
            $senderId->customer_id=auth('customer')->user()->id;
            $senderId->sender_id=$senderIdData->sender_id;
            $senderId->from=$senderIdData->from;
            $senderId->status='approved';
            $senderId->expire_date=now();
            $senderId->save();
            return back()->with('success','Sender ID successfully deleted');
        }


        if($request->type=='assign'){
            $sender_id = auth('customer')->user()->sender_ids->where('id', $request->id)->first();
            if(!$sender_id){
                return redirect()->back()->withErrors(['failed'=> 'Invalid sender ID']);
            }
            $senderIdData=$sender_id;
            $sender_id->delete();
            $senderId= new senderId();
            $senderId->customer_id=$reseller_customer->id;
            $senderId->sender_id=$senderIdData->sender_id;
            $senderId->from=$senderIdData->from;
            $senderId->status='approved';
            $senderId->expire_date=now();
            $senderId->save();
            return back()->with('success','Sender ID successfully assigned');
        }


    }



    public function removeNumber(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'customer_id' => 'required|exists:customers,id',
        ]);

        $customer = auth('customer')->user()->customers()->where('id', $request->customer_id)->first();
        if (!$customer) return back()->with('fail', 'Customer not found');

        $number = Number::find($request->id);
        if (!$number) return back()->with('fail', 'Number not found');

        $isAssigned = $customer->numbers()->where('number_id', $number->id)->first();
        if (!$isAssigned) return back()->with('fail', 'Number haven\'t assigned to this customer');

        auth('customer')->user()->numbers()->create(['number_id' => $number->id, 'number' => $number->number, 'expire_date' => now(), 'cost' => $number->sell_price]);

        $isAssigned->delete();

        return back()->with('success', 'Number successfully removed from the customer');
    }

    public function changePlan(Request $request)
    {
       DB::beginTransaction();
       try{
           $request->validate([
               'id' => 'required',
               'customer_id' => 'required',
           ]);

           $plans = auth('customer')->user();
           $customer = auth('customer')->user()->customers()->where('id', $request->customer_id)->first();
           if (!$customer) return back()->with('fail', 'Customer not found');

           $plan = Plan::where('added_by', $plans->type)->where('admin_id', auth('customer')->user()->id)->where('id', $request->id)->first();

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

                   $billingRequest->status = $request->status;
                   $billingRequest->save();

                   if ($request->status == 'rejected') return back()->with('success', 'Status successfully cancelled for the customer');

               } else
                   return back()->with('fail', 'Invalid data for billing request');
           }

           //        Customer Brand

           $reseller = auth('customer')->user();
           if ($reseller->type=='reseller' || $reseller->type=='master_reseller') {
               $mailSett = $reseller->settings()->where('name', 'smtp_setting')->first();
               if($mailSett) {
                   $mailSett = isset($mailSett->value) ? json_decode($mailSett->value) : '';
                   $config = array(
                       'driver' => 'smtp',
                       'host' => $mailSett->host,
                       'port' => $mailSett->port,
                       'from' => array('address' => $mailSett->from, 'name' => $mailSett->name),
                       'encryption' => $mailSett->encryption,
                       'username' => $mailSett->username,
                       'password' => $mailSett->password,
                   );
                   $emailTemplate = EmailTemplate::where('added_by', $reseller->type)->where('type', 'plan_accepted')->where('user_id', $reseller->id)->first();
                   if ($request->status == 'accepted' && $emailTemplate) {
                       $regTemp = str_replace('{customer_name}', $customer->first_name . ' ' . $customer->last_name, $emailTemplate->body);
                       SendMail::dispatch($customer->email, $emailTemplate->subject, $regTemp, $config);
                   }
               }
           }else {
               $emailTemplate = get_email_template('plan_accepted');
               if ($request->status == 'accepted' && $emailTemplate) {
                   $regTemp = str_replace('{customer_name}', $customer->first_name . ' ' . $customer->last_name, $emailTemplate->body);
                   SendMail::dispatch($customer->email, $emailTemplate->subject, $regTemp);
               }
           }

           //delete previous plan
           //TODO: suggestion: might need to change plan status in future without deleting plan
           if ($pre_plan) {
               $customer->plan()->update(['is_current'=>'no']);
           }

           if ($plan->recurring_type == 'weekly') {
               $time = \Illuminate\Support\Carbon::now()->addWeek();
           } else if ($plan->recurring_type == 'monthly') {
               $time = \Carbon\Carbon::now()->addMonth();
           } else if ($plan->recurring_type == 'yearly') {
               $time = Carbon::now()->addYear();
           } else if ($plan->recurring_type == 'custom') {
               $date = json_decode($plan->custom_date);
               $time = isset($date->from) ? new \DateTime($date->from) : '';
           }

           $customer->plan()->create([
               'is_current' => 'yes', 'price' => $plan->price, 'expire_date' => $time, 'plan_id' => $plan->id,
               'sms_sending_limit' => $plan->sms_sending_limit, 'max_contact' => $plan->max_contact, 'contact_group_limit' => $plan->contact_group_limit,
               'sms_unit_price' => $plan->sms_unit_price, 'free_sms_credit' => $plan->free_sms_credit,'coverage_ids' => $plan->coverage_ids,
               'api_availability' => $plan->api_availability, 'sender_id_verification' => $plan->sender_id_verification,
               'unlimited_sms_send' => $plan->unlimited_sms_send, 'unlimited_contact' => $plan->unlimited_contact, 'unlimited_contact_group' => $plan->unlimited_contact_group
           ]);

           cache()->forget('current_plan_'.$customer->id);

           //Transaction Report
           $transaction= new Transactions();
           $transaction->customer_id=$customer->id;
           $transaction->added_by=$customer->type;
           $transaction->type='plan';
           $transaction->amount=$plan->price;
           $transaction->status='paid';
           $transaction->ref_id=$plan->id;
           $transaction->save();

           $wallet=$customer->wallet()->first();

           $sellerWallet = auth('customer')->user()->wallet()->first();

           if ($plan->free_sms_credit > 0) {
               if ($sellerWallet->credit > $plan->free_sms_credit) {
                   $wallet->credit = $wallet->credit + $plan->free_sms_credit;
                   $wallet->save();

                   $sellerWallet->credit = $sellerWallet->credit - $plan->free_sms_credit;
                   $sellerWallet->save();
               } else {
                   $topUpReq = new TopUpRequest();
                   $topUpReq->credit = $plan->free_sms_credit;
                   $topUpReq->customer_id = $customer->id;
                   $topUpReq->admin_id = $customer->admin_id;
                   $topUpReq->payment_status = 'unpaid';
                   $topUpReq->customer_type = $customer->type;
                   $topUpReq->transaction_id = $request->transaction_id;
                   $topUpReq->save();
               }
           }

           cache()->forget('wallet_'.$customer->id);

           DB::commit();
           return back()->with('success', 'Plan successfully updated for the customer');
       }catch(\Exception $ex){
           DB::rollBack();
           return redirect()->back()->withErrors(['failed'=>$ex->getMessage()]);
       }
    }

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
    public function subtract(Request $request){

        $user=auth('customer')->user();

        $customer = Customer::where('id', $request->customer_id)->where('type', 'reseller_customer')->where('admin_id', $user->id)->firstOrFail();


        $wallet = $customer->wallet;
        $sellerWallet = $user->wallet;



        if(isset($request->select_type) && $request->select_type=='add'){
            if(isset($request->credit) && $request->credit > 0 && $sellerWallet->credit > $request->credit){
//                Customer
                $wallet->credit = $wallet->credit + $request->credit;
                $wallet->save();

//              Seller
                $sellerWallet->credit = $sellerWallet->credit - $request->credit;
                $sellerWallet->save();
            }
        }


        if(isset($request->select_type) && $request->select_type=='subtract'){

            if(isset($request->pre_credit) && $request->pre_credit > 0){
//                Customer
                $wallet->credit = $wallet->credit - $request->pre_credit;
                $wallet->save();

//                Seller
                $sellerWallet->credit = $sellerWallet->credit + $request->pre_credit;
                $sellerWallet->save();
            }

        }

        cache()->forget('wallet_'.$wallet->customer_id);
        cache()->forget('wallet_'.$sellerWallet->customer_id);


        return redirect()->back()->with('success', ucfirst($request->select_type).' Successfully updated');
    }


    public function loginAs(Request $request){
        if(!$request->id) abort(404);
        auth('customer')->loginUsingId($request->id);
        return redirect()->route('customer.dashboard')->with('success',trans('You are now logged as customer'));
    }

}
