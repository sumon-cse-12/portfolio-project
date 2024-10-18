<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Coverage;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\CustomerPlan;
use App\Models\BillingRequest;
use Illuminate\Support\Facades\DB;

class ResellerPlanController extends Controller
{

    public function index()
    {
        return view('customer.plans.index');
    }

    public function getAll()
    {
        $user=auth('customer')->user();
        $addedBy='reseller';

        $plans = Plan::where('admin_id', $user->id)->where('added_by', $addedBy)->get();
        return datatables()->of($plans)
            ->addColumn('created_at', function ($q) {
                return $q->created_at->format('d-m-Y');
            })

            ->addColumn('title', function ($q) {
                $title='<h6 class="m-0">'.$q->title.'</h6>';
                $customers=Customer::where('added_by', 'reseller')->where('admin_id', auth('customer')->user()->id)->pluck('id');
                if($customers) {
                    $subscriber = CustomerPlan::where('is_current', 'yes')->whereIn('id', $customers)->where('plan_id', $q->id)->count();
                    $subs_counter = '<small>' . $subscriber . ' Subscriber</small>';
                }else{
                    $subs_counter = '<small>0 Subscriber</small>';
                }
                return '<div>'.$title.$subs_counter.'</div>';
            })
            ->addColumn('created_at', function ($q) {
                return $q->created_at->format('d-m-Y');
            })
            ->addColumn('free_sms_credit', function ($q) {
                $credit='<h6 class="m-0">'.$q->free_sms_credit.'</h6>';
                $text='<h6>Sending Credit</h6>';

                return '<div>'.$credit.$text.'</div>';
            })
            ->addColumn('sell_price', function ($q) {
                $price='<h class="mb-1">'.$q->price.'</h>';
                $type='<h6><small>'.ucfirst($q->recurring_type).'</small></h6>';
                return '<div>'.$price.$type.'</div>';
            })

            ->addColumn('status', function (Plan $q) {
                if($q->status=='Active'){
                    $status= '<strong class="text-success"> '.ucfirst($q->status).' </strong>';
                }else{
                    $status= '<strong class="text-danger"> '.ucfirst($q->status).' </strong>';
                }
                return $status;
            })
            ->addColumn('type', function ($q) {
                $type='';
                if($q->plan_type=='master_reseller_customer' || $q->plan_type=='reseller_customer'){
                    $type='Customer';
                }else{
                    $type=ucfirst(str_replace('_','-', $q->plan_type));
                }
                return $type;
            })
            ->addColumn('action', function (Plan $q) {
                return "<a class='btn btn-sm btn-info' href='" . route('customer.plans.edit', [$q->id]) . "'>Edit</a>".
                '&nbsp;&nbsp;<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this plan?"
                                        data-action=' . route('customer.plans.destroy', [$q]) . '
                                        data-input={"_method":"delete"}
                                        data-toggle="modal" data-target="#modal-confirm">Delete</button>';
            })
            ->rawColumns(['action','type','status','title','sell_price','free_sms_credit'])
            ->toJson();
    }


    public function create()
    {
        $current_plan=auth('customer')->user()->plan;
        $coverage_ids=json_decode($current_plan->coverage_ids);
        if(!$coverage_ids){
            return redirect()->back()->withErrors(['failed'=>'Please upgrade your plan and try to create']);
        }

        $data['coverages']=Coverage::whereIn('id', $coverage_ids)->get();
        return view('customer.plans.create', $data);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try{
            $request->validate([
                'title' => 'required|unique:plans',
                'price' => 'required|numeric',
                'recurring_type' => 'required|in:weekly,monthly,yearly,custom',
                'sms_unit_price'=>'required|numeric|gt:0',
            ]);
            $user=auth('customer')->user();
            $request['admin_id']=$user->id;
            $request['added_by']='reseller';


            unset($request['_token']);

            if ($request->recurring_type == 'custom') {
                $date = explode('-', $request->custom_date);
                $dateField = [
                    'to' => isset($date[0]) ? $date[0] : '',
                    'from' => isset($date[1]) ? $date[1] : ''
                ];
                $request['custom_date'] = json_encode($dateField);
            }

            if ($request->status == 'on') {
                $request['status'] = 'active';
            } else {
                $request['status'] = 'active';
            }
            if ($request->set_as_popular == 'on') {
                $request['set_as_popular'] = 'yes';
            } else {
                $request['set_as_popular'] = 'no';
            }
            if (!$request->coverage || count($request->coverage) <=0){
                return redirect()->back()->withErrors(['failed'=>'You should select at last one coverage']);
            }

            $request['coverage_ids']=json_encode($request->coverage);

           $user->plans()->create($request->all());

            DB::commit();
            return redirect()->route('customer.plans.index')->with('success', 'Plan successfully created');
        }catch(\Exception $ex){
            DB::rollBack();
            return  redirect()->back()->withErrors(['failed'=>$ex->getMessage()])->withInput($request->all());
        }
    }

    public function edit(Plan $plan)
    {
        $data['plan'] = $plan;
        $data['coverages']=Coverage::where('added_by', 'reseller')->where('admin_id', auth('customer')->user()->id)->get();
        return view('customer.plans.edit', $data);
    }

    public function update(Plan $plan, Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'title' => 'required|unique:plans,title,' . $plan->id,
                'price' => 'required|numeric',
                'sms_unit_price'=>'required|numeric|gt:0',
            ]);

            unset($request['_token']);

            if ($request->recurring_type == 'custom') {
                $date = explode('-', $request->custom_date);
                $dateField = [
                    'to' => isset($date[0]) ? $date[0] : '',
                    'from' => isset($date[1]) ? $date[1] : ''
                ];
                $request['custom_date'] = json_encode($dateField);
            }

            if ($request->status == 'on') {
                $request['status'] = 'active';
            } else {
                $request['status'] = 'active';
            }
            if ($request->set_as_popular == 'on') {
                $request['set_as_popular'] = 'yes';
            } else {
                $request['set_as_popular'] = 'no';
            }

            $valid_data = $request->only('title', 'price', 'status', 'masking', 'masking_rate', 'non_masking', 'non_masking_rate', 'custom_date',
                'recurring_type', 'plan_type', 'module', 'non_masking_credit', 'masking_credit', 'whatsapp_status', 'whatsapp_rate');

            if ($plan->admin_id != auth('customer')->user()->id) {
                return abort(404);
            }
            //update the model
            $plan->update($request->all());

            DB::commit();
            return redirect()->route('customer.plans.index')->with('success', 'Plan successfully updated');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->withErrors(['failed' => $ex->getMessage()])->withErrors($request->all());
        }
    }


    public function destroy(Plan $plan){

        $customerPlan=CustomerPlan::where('plan_id', $plan->id)->first();
        $billingRequest=BillingRequest::where('plan_id', $plan->id)->first();

        if($customerPlan || $billingRequest){
            return redirect()->route('customer.plans.index')->withErrors(['failed'=> 'This plan already used, can\'n delete this plan']);
        }

        $plan->delete();
        return redirect()->route('customer.plans.index')->with('success', 'Plan successfully delete');
    }

    public function requests()
    {
        return view('customer.plans.requests');
    }

    public function get_requests()
    {
        $customer=auth('customer')->user();
        if($customer->type=='master_reseller'){
            $addedBy='master_reseller';
        }else{
            $addedBy='reseller';
        }
        $plans = $customer->plans()->where('added_by', $addedBy)->pluck('id');
        $requests = BillingRequest::whereIn('plan_id', $plans)->get();

        return datatables()->of($requests)
            ->addColumn('title', function (BillingRequest $q) {
                return $q->plan->title;
            })
            ->addColumn('price', function (BillingRequest $q) {
                return $q->plan->price;
            })
            ->addColumn('transaction_id', function (BillingRequest $q) {
                return $q->transaction_id;
            })
            ->addColumn('other_info', function (BillingRequest $q) {
                if ($q->other_info) {
                    $array = (array)json_decode($q->other_info);
                    $obj = json_encode(array_combine(array_map("ucfirst", array_keys($array)), array_values($array)));
                } else
                    $obj = "";
                return "<div class='show-more' style='max-width: 500px;white-space: pre-wrap'>" . str_replace(['_', '"', "{", "}"], [' ', ' ', '', ''], $obj) . "</div>";
            })
            ->addColumn('status', function (BillingRequest $q) {
                return $q->status;
            })
            ->addColumn('action', function (BillingRequest $q) {
                if ($q->status == 'pending') {
                    return '<button class="mr-1 btn btn-sm btn-info" data-message="Are you sure you want to assign <b>\'' . $q->plan->title . '\'</b> to \'' . $q->customer->full_name . '\' ?"
                                        data-action=' . route('customer.plan.change') . '
                                        data-input={"id":"' . $q->plan_id . '","customer_id":"' . $q->customer_id . '","from":"request","billing_id":"' . $q->id . '","status":"accepted"}
                                        data-toggle="modal" data-target="#modal-confirm"  >Approve</button>' .
                        '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to reject <b>\'' . $q->plan->title . '\'</b> for \'' . $q->customer->full_name . '\' ?"
                                        data-action=' . route('customer.plan.change') . '
                                        data-input={"id":"' . $q->plan_id . '","customer_id":"' . $q->customer_id . '","from":"request","billing_id":"' . $q->id . '","status":"rejected"}
                                        data-toggle="modal" data-target="#modal-confirm"  >Reject</button>';
                } else if ($q->status == 'accepted') {
                    return '<button class="mr-1 btn btn-sm btn-success disabled" disabled  >Accepted</button>';
                } else {
                    return '<button class="mr-1 btn btn-sm btn-danger disabled" disabled  >Rejected</button>';
                }
            })
            ->addColumn('customer', function (BillingRequest $q) {
                return $q->customer->full_name;
            })
            ->rawColumns(['action', 'customer', 'other_info'])
            ->toJson();
    }

}
