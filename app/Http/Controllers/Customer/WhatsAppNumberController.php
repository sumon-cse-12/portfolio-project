<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerNumber;
use App\Models\CustomerWhatsAppNumber;
use App\Models\Number;
use App\Models\NumberRequest;
use App\Models\WhatsAppNumber;
use App\Models\WhatsAppNumberRequest;
use Illuminate\Http\Request;

class WhatsAppNumberController extends Controller
{
    public function phone_numbers()
    {
        $data['numbers'] = auth('customer')->user()->whatsapp_numbers;
        return view('customer.whatsapp_number.index', $data);
    }

    public function get_whatsapp_numbers()
    {
        if (!request()->ajax()) return response()->json(['status' => 'Not found'], 404);
        $numbers = auth('customer')->user()->whatsapp_numbers()->select(['id', 'number', 'cost', 'created_at','forward_to','forward_to_dial_code','expire_date']);
        return datatables()->of($numbers)
            ->addColumn('forward_to', function ($q) {
                if ($q->forward_to)
                    return "(" . $q->forward_to_dial_code . ")" . $q->forward_to;
                else
                    return "";
            })
            ->addColumn('purchased_at', function (CustomerWhatsAppNumber $q) {
                return $q->created_at->format('d-m-Y');
            })
            ->addColumn('expire_date', function (CustomerWhatsAppNumber $q) {
                return $q->expire_date->format('d-m-Y');
            })
            ->addColumn('action', function (CustomerWhatsAppNumber $q) {
                return ' <button data-id="'.$q->id.'" data-forward-to="'.$q->forward_to.'" data-forward-to-code="'.$q->forward_to_dial_code.'" type="button" class="btn-sm btn btn-info change-forward-to">Change Forward To</button>'.
                    ' <button class="btn btn-sm btn-danger" data-message="Are you sure you want to remove <b>\'' . $q->number . '\'</b> ?"
                                        data-action=' . route('customer.numbers.purchase.remove') . '
                                        data-input={"id":"' . $q->id . '"}
                                        data-toggle="modal" data-target="#modal-confirm"  >Remove</button> '
                    ;
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function purchaseList()
    {
        return view('customer.whatsapp_number.purchase_list');
    }

    public function wpNumberPurchaseListGet()
    {
        if (!request()->ajax()) return response()->json(['status' => 'Not found'], 404);
        $numbers = auth('customer')->user()->admin->available_whatsapp_numbers()->select(['id', 'number', 'sell_price', 'created_at']);
        return datatables()->of($numbers)
            ->addColumn('cost', function (WhatsAppNumber $q) {
                return '$' . $q->sell_price . '/month';
            })
            ->addColumn('action', function (WhatsAppNumber  $q) {
                if (\Module::has('PaymentGateway') && \Module::find('PaymentGateway')->isEnabled()) {
                    return '<button class="btn btn-sm btn-info mr-2" data-message="Are you sure you want to buy <b>\'' . $q->number . '\'</b> ?"
                                        data-action=' . route('paymentgateway::whatsapp.number.process') . '
                                        data-input={"id":"' . $q->id . '"}
                                        data-toggle="modal" data-target="#modal-confirm"  >Buy</button>';
                }else{
                    return '<button class="btn btn-sm btn-info mr-2" data-message="Are you sure you want to buy <b>\'' . $q->number . '\'</b> ?"
                                        data-action=' . route('customer.buy.whatsapp.numbers') . '
                                        data-input={"id":"' . $q->id . '"}
                                        data-toggle="modal" data-target="#modal-confirm"  >Buy</button>';
                }

            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function buyWpNumber(Request $request){
        $customer = auth('customer')->user();
        $number = WhatsAppNumber::find($request->id);
        if (!$number)
            return redirect()->route('customer.whatsapp.number.purchase')->withErrors(['msg' => trans('Whatsapp Number not found')]);


        $pre_number = auth('customer')->user()->numbers()->where('number_id', $number->id)->first();
        if (isset($pre_number) && $pre_number->expire_date > now()) {
            return redirect()->route('customer.whatsapp.number.purchase')->with('fail', 'You have already this whatsapp number');
        }

        $preReq = WhatsAppNumberRequest::where(['customer_id' => $customer->id, 'number_id' => $number->id, 'status' => 'pending'])->first();
        if ($preReq) {
            return redirect()->route('customer.whatsapp.number.purchase')->with('fail', 'You already have a pending request. Please wait for the admin reply.');
        }
        $numberReq = new WhatsAppNumberRequest();
        if($customer->type=='reseller_customer'){
            $numberReq->admin_id = $customer->admin_id;
        }else{
            $numberReq->admin_id = $number->admin_id;
        }
        $numberReq->customer_id = $customer->id;
        $numberReq->number_id = $number->id;
        $numberReq->save();
        return redirect()->back()->with('success', 'We have received your request. We will contact with you shortly');
    }
    public function purchaseStore(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $number = WhatsAppNumber::find($request->id);
        if (!$number) {
            return redirect()->back()->with('fail', 'WhatsApp Number not found');
        }
        $pre_number = auth('customer')->user()->whatsapp_numbers()->where('id', $number->id)->first();
        if (isset($pre_number) && $pre_number->id == $request->id) {
            return redirect()->back()->with('fail', 'You have already this number');
        }
        $customer = auth('customer')->user();
        $preReq = WhatsAppNumberRequest::where(['customer_id' => $customer->id, 'number_id' => $number->id, 'status' => 'pending'])->first();
        if ($preReq) {
            return redirect()->back()->with('fail', 'You already have a pending request. Please wait for the admin reply.');
        }
        $numberReq = new WhatsAppNumberRequest();
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
        $number = auth('customer')->user()->whatsapp_numbers()->where('id', $request->id)->first();

        if (!$number) {
            return redirect()->back()->with('fail', 'Number not found');
        }
        $admin_number = $number->admin_number;
        $admin_number->status = 'active';
        $admin_number->save();

        $number->delete();

        //TODO:: Send a mail here to the customer and the admin as well
        return back()->with('success', 'WhatsApp number has been removed from your account');

    }

    public function updateForwardTo(Request $request){

        $numbers = auth('customer')->user()->whatsapp_numbers()->where('id',$request->id)->first();
        if(!$numbers) return redirect()->back()->withErrors(['msg'=>'Number not found']);

        $numbers->forward_to_dial_code=$request->forward_to_dial_code;
        $numbers->forward_to=$request->forward_to;
        $numbers->save();
        return redirect()->back()->with('success','Forward number updated successfully');
    }
}

