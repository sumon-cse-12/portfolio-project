<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\SenderId;
use App\Models\Customer;
use App\Models\SenderIdDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function React\Promise\all;

class SenderIdController extends Controller
{
    public function index()
    {

        return view('customer.senderID.index');
    }
    public function create(){
        return view('customer.senderID.create');
    }
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
          $request->validate([
              'sender_id' => 'required|unique:sender_ids,sender_id',
          ]);

       $senderId=  auth('customer')->user()->sender_ids()->create($request->only('sender_id'));
            DB::commit();
            return redirect()->route('customer.sender-id.index')->with('success', 'Sender ID successfully created');
        } catch (\Throwable $ex) {
            DB::rollback();
            return redirect()->back()->withErrors(['failed' => $ex->getMessage()])->withInput();
        }
    }
    public function getAll()
    {
        $authUser=auth('customer')->user();
        if(!$authUser){
            $senders = SenderId::select(['id','customer_id', 'is_paid','sender_id', 'status','dynamic_gateway_id','expire_date'])->orderByDesc('created_at');
        }else{

            $senders =$authUser->sender_ids()->orderByDesc('created_at')->where('customer_id', $authUser->id)->select(['id','customer_id','is_paid', 'sender_id', 'status','dynamic_gateway_id','expire_date']);
        }

        return datatables()->of($senders)
            ->addColumn('from',function(SenderId $q){
                if($q->status=='approved'){
                    $gateway=$q->gateway->name;
                    return $gateway;
                }
            })
            ->addColumn('status',function(SenderId $q){
                if($q->status == 'review'){
                    return 'Not paid Yet';
                }else if($q->status=='review_pending'){
                    return 'Review Payment';
                }else{
                    return ucfirst($q->status);
                }
            })
            ->addColumn('expire_date',function(SenderId $q){
                if ($q->expire_date) {
                    return formatDate($q->expire_date);
                }else{
                    return;
                }
            })
            ->addColumn('status',function(SenderId $q){
                $status='';
                if ($q->status=='approved') {
                    $status='<span class="badge badge-success">Approved</span>';
                }else if ($q->status=='pending') {
                    $status='<span class="badge badge-danger">Pending</span>';
                }else if ($q->status=='rejected') {
                    $status='<span class="badge badge-danger">Rejected</span>';
                }else if ($q->status=='review') {
                    $status='<span class="badge badge-info">Review</span>';
                }else if ($q->status=='review_pending') {
                    $status='<span class="badge badge-success">Waiting For Review</span>';
                }

                return $status;
            })
            ->addColumn('action',function(SenderId $q){
                $buyBtn='';
                $senderIdPrice = isset(json_decode(get_settings('senderid_price'))->sender_id_price) ? json_decode(get_settings('senderid_price'))->sender_id_price : 0;
                if($q->status == "review" && $q->is_paid == "no") {
                    if (\Module::has('PaymentGateway') && \Module::find('PaymentGateway')->isEnabled() && $senderIdPrice > 0) {
                        $buyBtn = '<button class="btn btn-sm btn-info mr-2" data-message="Are you sure you want to buy <b>\'' . $q->sender_id . '\'</b> ?"
                                        data-action=' . route('paymentgateway::sender.id.process') . '
                                        data-input={"id":"' . $q->id . '"}
                                        data-toggle="modal" data-target="#modal-confirm"  >Buy</button>';
                    } else {
                        $buyBtn = '<button class="btn btn-sm btn-info mr-2" data-message="Are you sure you want to buy <b>\'' . $q->sender_id . '\'</b> for <b>'.formatNumberWithCurrSymbol($senderIdPrice).'</b> ?"
                                        data-action=' . route('customer.buy.sender.id') . '
                                        data-input={"id":"' . $q->id . '","_method":"post"}
                                        data-toggle="modal" data-target="#modal-confirm"  >Buy ('.formatNumberWithCurrSymbol($senderIdPrice).')</button>';
                    }
                }

                if($q->status == "approved" && $q->is_paid == "yes" && ($q->expire_date <= now())){
                    if (\Module::has('PaymentGateway') && \Module::find('PaymentGateway')->isEnabled()) {
                        $buyBtn = '<button class="btn btn-sm btn-info mr-2" data-message="Are you sure you want to renew <b>\'' . $q->sender_id . '\'</b> ?"
                                        data-action=' . route('paymentgateway::sender.id.process') . '
                                        data-input={"id":"' . $q->id . '"}
                                        data-toggle="modal" data-target="#modal-confirm"  >Renew</button>';
                    }
                }


                if($q->status == "pending"){
                    if($q->customer_id == auth('customer')->user()->id){
                        $edit='<a href="#" data-senderid="'.$q->sender_id.'" data-url="'.route('customer.sender-id.update', [$q]).'" class="btn btn-sm btn-info edit" >Edit</a>';
                    }else{
                        $edit='&nbsp;&nbsp;  &nbsp;&nbsp;';
                    }
                return'<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this sender Id?"
                                         data-action='.route('customer.sender-id.destroy',[$q]).'
                                        data-input={"_method":"delete"}
                                         data-toggle="modal" data-target="#modal-confirm">Delete</button>'.'&nbsp;&nbsp;'. $edit;
                } return $buyBtn.'<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this sender Id?"
                                         data-action='.route('customer.sender-id.destroy',[$q]).'
                                        data-input={"_method":"delete"}
                                         data-toggle="modal" data-target="#modal-confirm">Delete</button>';
            })
            ->rawColumns(['action','status','from','expire_date'])
            ->toJson();
    }
    public function show(){

    }
    public function edit(SenderId $sender_id){
        $data['sender_id']=$sender_id;
        $data['detail']=$sender_id->detail();
        return view('customer.senderID.edit', $data);
    }

    public function update(SenderId $sender_id, Request $request)
    {
        if($sender_id->status !='pending'){
            return redirect()->back()->withErrors(['failed' => 'Sender-ID status is not pending'])->withInput();
        }

        DB::beginTransaction();

        try {
            $request->validate([
                'sender_id' => 'required|unique:sender_ids,sender_id,' . $sender_id->id,
            ]);

            $sender_id->update($request->only('sender_id'));
            DB::commit();
            return redirect()->route('customer.sender-id.index')->with('success', 'Sender ID successfully updated');
        } catch (\Throwable $ex) {
            DB::rollback();
            return redirect()->back()->withErrors(['failed' => $ex->getMessage()])->withInput();
        }

    }
    public function destroy($senderId){
        $user=auth('customer')->user();
        $sender_id = $user->sender_ids()->where('id',$senderId)->first();

        $senderIdData=$sender_id;

        if(!$sender_id){
            return redirect()->back()->with('fail','Invalid sender ID');
        }
        $sender_id->delete();

        return back()->with('success','Sender ID successfully deleted');
     }

     public function requests(){

        return view('customer.senderID.requests');
     }
    public function getAllRequests()
    {
        $customers=auth('customer')->user()->customers->pluck('id');
        $senders = SenderId::whereIn('customer_id', $customers)->where('status','!=','approved')->select(['id','customer_id', 'sender_id', 'status','from']);


//        $senders = SenderId::where('status','!=','approved')->select(['id','customer_id','sender_id','status']);
        return datatables()->of($senders)
            ->addColumn('customer',function($q){
                return "<a href='" . route('admin.customers.edit', [$q->customer_id]) . "'>".$q->customer->full_name."</a>";
            })
            ->addColumn('action',function(SenderId $q){
                if($q->status == 'pending'){
                    return '<button class="mr-1 btn btn-sm btn-info" data-message="Are you sure you want to approve this Sender ID ?"
                                        data-action='.route('customer.sender-id.request.status').'
                                        data-input={"id":"'.$q->id.'","status":"approved","customer_id":"'.$q->customer_id.'"}
                                        data-toggle="modal" data-target="#modal-confirm"  >Approve</button>'.
                        '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to reject the request ?"
                                        data-action='.route('customer.sender-id.request.status').'
                                        data-input={"id":"'.$q->id.'","status":"rejected","customer_id":"'.$q->customer_id.'"}
                                        data-toggle="modal" data-target="#modal-confirm"  >Reject</button>';
                }
            })
            ->rawColumns(['action','customer'])
            ->toJson();
    }

    public function requestStatus(Request $request){
       $reseller_customer=  auth('customer')->user()->customers->where('id', $request->customer_id)->first();
       if(!$reseller_customer){
           return redirect()->back()->withErrors(['failed'=>'Invalid customer senderID']);
       }
       $senderId=$reseller_customer->sender_ids->where('id', $request->id)->first();
        if(!$senderId){
            return redirect()->back()->withErrors(['failed'=>'Invalid SenderID']);
        }

        return redirect()->back()->with('success', 'SenderID Successfully approved');
    }


    public function buySenderId(Request $request){
        $customer = auth('customer')->user();
        $sender_id = $customer->sender_ids->where('id', $request->id)->firstOrFail();

        $sender_id->status = 'review_pending';
        if ($request->invoice_id){
            $sender_id->invoice_id=$request->invoice_id;
        }
        $sender_id->save();

        $senderIdPrice = isset(json_decode(get_settings('senderid_price'))->sender_id_price) ? json_decode(get_settings('senderid_price'))->sender_id_price : 0;
        if ($senderIdPrice <= 0) {
            $sender_id->is_paid = 'no';
            $sender_id->save();
        }
        return redirect()->route('customer.sender-id.index')->with('success', trans('Congratulations! SenderId successfully purchase'));
    }
}
