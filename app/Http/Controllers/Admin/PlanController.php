<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillingRequest;
use App\Models\Coverage;
use App\Models\Customer;
use App\Models\CustomerPlan;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{
    public function index()
    {
        return view('admin.plans.index');
    }

    public function getAll()
    {

        $customers = auth()->user()->plans()->where('added_by', 'admin')
            ->select(['id','enable_for','free_sms_credit','recurring_type', 'title', 'plan_type', 'price', 'status', 'created_at']);
        return datatables()->of($customers)
            ->addColumn('title', function ($q) {
                $subscriber=CustomerPlan::where('is_current', 'yes')->where('plan_id', $q->id)->count();
                $title='<h6 class="m-0">'.$q->title.'</h6>';
                $subs_counter='<small>'.$subscriber.' Subscriber</small>';
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
            ->addColumn('status', function ($q) {
                if ($q->status == 'Active') {
                    $status = '<strong class="text-white bg-success px-2 py-1 rounded status-font-size"> ' . ucfirst($q->status) . ' </strong>';
                } else {
                    $status = '<strong class="text-white bg-danger px-2 py-1 rounded status-font-size"> ' . ucfirst($q->status) . ' </strong>';
                }
                return $status;
            })
            ->addColumn('plan_type', function ($q) {

                $plan_type = ucwords(str_replace('_', ' ', $q->enable_for));

                return $plan_type;
            })
            ->addColumn('sell_price', function ($q) {
                $price='<h class="mb-1">'.$q->price.'</h>';
                $type='<h6><small>'.ucfirst($q->recurring_type).'</small></h6>';
                return '<div>'.$price.$type.'</div>';
            })
            ->addColumn('action', function (Plan $q) {
                $deleteBtn = '';
                if ($q->id != '1') {
                    $deleteBtn = '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this plan?"
                                        data-action=' . route('admin.plans.destroy', [$q]) . '
                                        data-input={"_method":"delete"}
                                        data-toggle="modal" data-target="#modal-confirm" title="Delete"><i class="fa fa-trash"></i></button>';
                }

                return "<a class='btn btn-sm btn-info' href='" . route('admin.plans.edit', [$q->id]) . "' title='Edit'><i class='fa fa-pencil-alt'></i></a>" . '&nbsp;&nbsp;&nbsp;' . $deleteBtn;
            })
            ->rawColumns(['action','title','free_sms_credit','sell_price','status', 'plan_type'])
            ->toJson();
    }


    public function create()
    {
        $data['coverages']=Coverage::where('added_by', 'admin')->orderByDesc('created_at')->get();
        return view('admin.plans.create', $data);
    }

    public function store(Request $request)
    {
        if (env("APP_DEMO")) {
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }
        DB::beginTransaction();
        try {
            $request->validate([
                'title' => 'required|unique:plans',
                'price' => 'required|numeric',
                'recurring_type' => 'required|in:weekly,monthly,yearly,custom',
                'sms_unit_price'=>'required|numeric|gt:0'
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

            $request['coverage_ids']=json_encode($request->coverage);
            $request['admin_id']=auth()->user()->id;
            $request['added_by']='admin';


            $data = collect($request->all())->filter(function ($value, $key) {
                return $value != null;
            })->toArray();
            auth()->user()->plans()->create($data);
            DB::commit();
            return redirect()->route('admin.plans.index')->with('success', 'Plan successfully created');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->withErrors(['failed' => $ex->getMessage()])->withInput($request->all());
        }
    }

    public function edit(Plan $plan)
    {
        $data['plan'] = $plan;
        $date = json_decode($plan->custom_date);
        if (isset($date)) {
            $data['date'] = $date->to . '-' . $date->from;
        }
        $data['coverages']=Coverage::where('added_by', 'admin')->orderByDesc('created_at')->get();
        return view('admin.plans.edit', $data);
    }

    public function update(Plan $plan, Request $request)
    {
        if (env("APP_DEMO")) {
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }
        DB::beginTransaction();
        try {
            $request->validate([
                'title' => 'required|unique:plans,title,' . $plan->id,
                'price' => 'required|numeric',
                'recurring_type' => 'required|in:weekly,monthly,yearly,custom',
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

            $request['coverage_ids']=json_encode($request->coverage);
//            $data = collect($request->all())->filter(function ($value, $key) {
//                return $value != null;
//            })->toArray();
            $plan->update($request->all());
            cache()->forget('masking');

            DB::commit();
            return redirect()->route('admin.plans.index')->with('success', 'Plan successfully updated');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect()->back()->withErrors(['failed' => $ex->getMessage()])->withInput($request->all());
        }
    }

    public function requests()
    {
        return view('admin.plans.requests');
    }

    public function get_requests()
    {
        $adminPlans = auth()->user()->plans()->pluck('id');
        $requests = auth()->user()->plan_requests()->whereIn('plan_id', $adminPlans)->orderByDesc('created_at');
        return datatables()->of($requests)
            ->addColumn('title', function (BillingRequest $q) {
                return $q->plan->title;
            })
            ->addColumn('price', function (BillingRequest $q) {
                return formatNumberWithCurrSymbol($q->plan->price);
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
                if ($q->status == 'accepted') {
                    $status = '<strong class="text-white bg-success px-2 py-1 rounded status-font-size"> ' . ucfirst($q->status) . ' </strong>';
                } else {
                    $status = '<strong class="text-white bg-danger px-2 py-1 rounded status-font-size"> ' . ucfirst($q->status) . ' </strong>';
                }
                return $status;
            })
            ->addColumn('action', function (BillingRequest $q) {
                if ($q->status == 'pending') {
                    return '<button class="mr-1 btn btn-sm btn-info" data-message="Are you sure you want to assign <b>\'' . $q->plan->title . '\'</b> to \'' . $q->customer->full_name . '\' ?"
                                        data-action=' . route('admin.customer.plan.change') . '
                                        data-input={"id":"' . $q->plan_id . '","customer_id":"' . $q->customer_id . '","from":"request","billing_id":"' . $q->id . '","status":"accepted"}
                                        data-toggle="modal" data-target="#modal-confirm" title="Approved" ><i class="fa fa-check"></i></button>' .
                        '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to reject <b>\'' . $q->plan->title . '\'</b> for \'' . $q->customer->full_name . '\' ?"
                                        data-action=' . route('admin.customer.plan.change') . '
                                        data-input={"id":"' . $q->plan_id . '","customer_id":"' . $q->customer_id . '","from":"request","billing_id":"' . $q->id . '","status":"rejected"}
                                        data-toggle="modal" data-target="#modal-confirm" title="Reject" ><i class="fa fa-times"></button>';
                } else if ($q->status == 'accepted') {
                    return '<button class="mr-1 btn btn-sm btn-success disabled" disabled title="Approved" ><i class="fa fa-check"></i></button>';
                } else {
                    return '<button class="mr-1 btn btn-sm btn-danger disabled" disabled title="Reject" ><i class="fa fa-times"></button>';
                }
            })
            ->addColumn('customer', function (BillingRequest $q) {
                return "<a href='" . route('admin.customers.edit', [$q->customer_id]) . "'>" . $q->customer->full_name . "</a>";
            })
            ->rawColumns(['action', 'customer', 'other_info', 'status'])
            ->toJson();
    }

    public function destroy(Plan $plan)
    {
        if (env("APP_DEMO")) {
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }
        $customerPlan = CustomerPlan::where('plan_id', $plan->id)->first();
        $billingRequest = BillingRequest::where('plan_id', $plan->id)->first();

        if ($customerPlan || $billingRequest) {
            return redirect()->route('admin.plans.index')->withErrors(['failed' => 'This plan already in used, You can\'n delete']);
        }

        $plan->delete();
        return redirect()->route('admin.plans.index')->with('success', 'Plan successfully delete');
    }
}
