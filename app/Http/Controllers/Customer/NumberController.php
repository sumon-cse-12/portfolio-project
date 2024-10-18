<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\BillingRequest;
use App\Models\CustomerNumber;
use App\Models\Keyword;
use App\Models\KeywordContact;
use App\Models\Number;
use App\Models\NumberRequest;
use App\Models\Plan;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class NumberController extends Controller
{
    public function phone_numbers()
    {
        $data['numbers'] = auth('customer')->user()->numbers;
        return view('customer.numbers.index', $data);
    }


    public function get_numbers()
    {
//        if (!request()->ajax()) return response()->json(['status' => 'Not found'], 404);
        $numbers = auth('customer')->user()->numbers()->select(['id', 'number', 'cost', 'created_at','forward_to','forward_to_dial_code','expire_date','number_id']);
        return datatables()->of($numbers)
            ->addColumn('forward_to', function ($q) {
                if ($q->forward_to)
                    return "(" . $q->forward_to_dial_code . ")" . $q->forward_to;
                else
                    return "";
            })
            ->addColumn('purchased_at', function (CustomerNumber $q) {
                return $q->created_at->format('d-m-Y');
            })
            ->addColumn('capability', function (CustomerNumber $q) {
                $number=$q->admin_number;
                $capability='';
                if($number->sms_capability=='yes') {
                    $capability = '<span class="badge badge-success capability-badge">SMS</span>';
                }
                if($number->mms_capability=='yes') {
                    $capability =$capability. '<span class="badge badge-success capability-badge ml-2">MMS</span>';
                }
                if($number->voice_capability=='yes') {
                    $capability =$capability. '<span class="badge badge-success capability-badge ml-2">Voice SMS</span>';
                }
                if($number->whatsapp_capability=='yes') {
                    $capability =$capability. '<span class="badge badge-success capability-badge ml-2">Whatsapp</span>';
                }
                return $capability;
            })
            ->addColumn('expire_date', function (CustomerNumber $q) {
                $ex_date= new \DateTime($q->expire_date);
                return $ex_date->format('d-m-Y');
            })
            ->addColumn('action', function (CustomerNumber $q) {
                if ($q->expire_date > now()){
                    return ' <button data-id="' . $q->id . '" data-forward-to="' . $q->forward_to . '" data-forward-to-code="' . $q->forward_to_dial_code . '" type="button" class="btn-sm btn btn-info change-forward-to" title="Change Forward To"><i class="fa fa-reply-all"></i></button>' .
                        ' <button class="btn btn-sm btn-danger" data-message="Are you sure you want to remove <b>\'' . $q->number . '\'</b> ?"
                                        data-action=' . route('customer.numbers.purchase.remove') . '
                                        data-input={"id":"' . $q->id . '"}
                                        data-toggle="modal" data-target="#modal-confirm" title="Remove" ><i class="fa fa-trash"></i></button> ';
                }else{
                    if (\Module::has('PaymentGateway') && \Module::find('PaymentGateway')->isEnabled()){
                        $renewBtn='<button class="btn btn-sm btn-info" data-message="Are you sure you want to renew <b>\'' . $q->number . '\'</b> ?"
                                        data-action=' . route('paymentgateway::number.process') . '
                                        data-input={"id":"' . $q->number_id . '"}
                                        data-toggle="modal" data-target="#modal-confirm"  >Renew</button>';
                    }else{
                        $renewBtn= '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to remove <b>\'' . $q->number . '\'</b> ?"
                                        data-action=' . route('customer.numbers.purchase.remove') . '
                                        data-input={"id":"' . $q->id . '"}
                                        data-toggle="modal" data-target="#modal-confirm"  ><i class="fa fa-trash"></button> ';
                    }
                return $renewBtn;
            }
            })
            ->rawColumns(['action','capability'])
            ->toJson();
    }

    public function purchaseList()
    {
        $user=auth('customer')->user();
        $admin=User::first();
        $numbers = $admin->available_numbers()->select(['id', 'number', 'sell_price', 'created_at'])->get();

        return view('customer.numbers.purchase_list', compact('numbers'));
    }

    public function purchaseListGet()
    {
        if (!request()->ajax()) return response()->json(['status' => 'Not found'], 404);
        $numbers = auth('customer')->user()->admin->available_numbers()->select(['id', 'number', 'sell_price', 'created_at']);
        return datatables()->of($numbers)
            ->addColumn('cost', function (Number $q) {
                return '$' . $q->sell_price . '/month';
            })
            ->addColumn('action', function (Number $q) {
                if (\Module::has('PaymentGateway') || \Module::find('PaymentGateway')->isEnabled()) {
                    return '<button class="btn btn-sm btn-info" data-message="Are you sure you want to buy <b>\'' . $q->number . '\'</b> ?"
                                        data-action=' . route('customer.numbers.purchase') . '
                                        data-input={"id":"' . $q->id . '"}
                                        data-toggle="modal" data-target="#modal-confirm"  >Buy</button>';
                }
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function purchaseStore(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $number = Number::find($request->id);
        if (!$number) {
            return redirect()->back()->with('fail', 'Number not found');
        }
        $pre_number = auth('customer')->user()->numbers()->where('id', $number->id)->first();
        if (isset($pre_number) && $pre_number->id == $request->id) {
            return redirect()->back()->with('fail', 'You have already this number');
        }
        $customer = auth('customer')->user();
        $preReq = NumberRequest::where(['customer_id' => $customer->id, 'number_id' => $number->id, 'status' => 'pending'])->first();
        if ($preReq) {
            return redirect()->back()->with('fail', 'You already have a pending request. Please wait for the admin reply.');
        }
        $numberReq = new NumberRequest();
        $numberReq->admin_id = $number->admin_id;
        $numberReq->customer_id = $customer->id;
        $numberReq->number_id = $number->id;
        $numberReq->save();

        // TODO:: send email to customer here

        return redirect()->back()->with('success', 'We have received your request. We will contact with you shortly');
    }

    public function purchase_remove(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $number = auth('customer')->user()->numbers()->where('id', $request->id)->first();

        if (!$number) {
            return redirect()->back()->with('fail', 'Number not found');
        }
        $admin_number = $number->admin_number;
        $admin_number->status = 'active';
        $admin_number->save();

        $numberRequest=NumberRequest::where('customer_id', auth('customer')->user()->id)->where('number_id', $number->id)->first();

        if($numberRequest){
            $numberRequest->delete();
        }
        $keyword=Keyword::where('customer_number_id',$number->id)->first();
        KeywordContact::where('keyword_id',$keyword->id)->delete();
        Keyword::where('customer_number_id',$number->id)->delete();
        $number->delete();

        //TODO:: Send a mail here to the customer and the admin as well
        return back()->with('success', 'Number has been removed from your account');

    }

    public function updateForwardTo(Request $request){

        $numbers = auth('customer')->user()->numbers()->where('id',$request->id)->first();
        if(!$numbers) return redirect()->back()->withErrors(['msg'=>'Number not found']);

        $numbers->forward_to_dial_code=$request->forward_to_dial_code;
        $numbers->forward_to=$request->forward_to;
        $numbers->save();
        return redirect()->back()->with('success','Forward number updated successfully');
    }

    public function buyNumber(Request $request){
        $customer = auth('customer')->user();
        $number = Number::find($request->id);
        if (!$number)
            return redirect()->route('customer.numbers.purchase')->withErrors(['msg' => trans('Number not found')]);

        $pre_number = auth('customer')->user()->numbers()->where('number_id', $number->id)->first();
        if (isset($pre_number) && $pre_number->expire_date > now()) {
            return redirect()->route('customer.numbers.purchase')->with('fail', 'You have already this number');
        }

        $preReq = NumberRequest::where(['customer_id' => $customer->id, 'number_id' => $number->id, 'status' => 'pending'])->first();
        if ($preReq) {
            return redirect()->route('customer.numbers.purchase')->with('fail', 'You already have a pending request. Please wait for the admin reply.');
        }
        $numberReq = new NumberRequest();
        if($customer->type=='reseller_customer'){
            $numberReq->admin_id = $customer->admin_id;
        }else{
            $numberReq->admin_id = $number->admin_id;
        }
        $numberReq->customer_id = $customer->id;
        $numberReq->number_id = $number->id;
        $numberReq->save();

        return redirect()->route('customer.numbers.purchase')->with('success', trans('Congratulations! number successfully purchase'));
    }
}
