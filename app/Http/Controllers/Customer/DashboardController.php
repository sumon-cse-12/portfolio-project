<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\BecameReseller;
use App\Models\BillingRequest;
use App\Models\Domain;
use App\Models\Notice;
use App\Models\NumberRequest;
use App\Models\Plan;
use App\Models\SenderId;
use App\Models\Ticket;
use App\Models\TopUpRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use function PHPUnit\Framework\isNull;

class DashboardController extends Controller
{


    public function index()
    {

        return view('customer.dashboard');
    }

    public function downloadAttach(Request $request){

        $user=auth('customer')->user();

        $notice=Notice::where('status', 'active')->whereIn('for', [$user->type, 'all'])->where('id', $request->id)->firstOrFail();

        $file=isset($notice->attached_data)?public_path('uploads/'.$notice->attached_data):'';

        if(!file_exists($file) || !$notice->attached_data){
            return redirect()->back()->withErrors(['failed'=>'File does not exist']);
        }

        return Response::download($file);
    }

    public function viewAllNotices(){

        return view('customer.notices');
    }

    public function countNotification(){
        $customer=auth('customer')->user();
        if($customer->type=='master_reseller'){
            $addedBy='master_reseller';
        }else if($customer->type=='reseller'){
            $addedBy='reseller';
        }else{
            $data=[
                'plan_request'=>0,
                'topUpReq'=>0,
            ];
            return response()->json([ $data, 'status'=>'success'], 200);
        }

        $sellerCustomers=$customer->customers()->pluck('id');
        $plans = $customer->plans()->where('added_by', $addedBy)->pluck('id');
        $planReq=BillingRequest::whereIn('plan_id', $plans)->where('status', 'pending')->count();
        if ($customer->type == 'master_reseller') {
            $topup_requests = TopUpRequest::where('admin_id', $customer->id)->where('status', 'pending')
                ->whereIn('customer_type', ['reseller', 'master_reseller_customer'])->count();
        } else if ($customer->type == 'reseller') {
            $topup_requests = TopUpRequest::where('admin_id', $customer->id)->where('status', 'pending')->where('customer_type', 'reseller_customer')->count();
        }



        $data=[
            'plan_request'=>$planReq,
            'topUpReq'=>$topup_requests,
            'inboxCount'=>$inboxCount,
        ];
        return response()->json([ $data, 'status'=>'success'], 200);
    }

    public function clearCache(){
        $customer=auth('customer')->user();
        cache()->forget('newMessageCount_'.$customer->id);
        cache()->forget('inboxCount_'.$customer->id);
        cache()->forget('sentCount_'.$customer->id);
        cache()->forget('inboxes_'.$customer->id);
        cache()->forget('todayExpense_'.$customer->id);
        cache()->forget('weeklyExpense_'.$customer->id);
        cache()->forget('totalExpense_'.$customer->id);
        cache()->forget('outboundResponse_'.$customer->id);
        cache()->forget('weeklySent_'.$customer->id);
        cache()->forget('weeklyReceived_'.$customer->id);
        cache()->forget('allInboundResponse_'.$customer->id);
        cache()->forget('allOutboundResponse_'.$customer->id);
        cache()->forget('todayTotalMessages_'.$customer->id);
        cache()->forget('totalFailed_'.$customer->id);
        cache()->forget('dailyFailed_'.$customer->id);
        cache()->forget('weeklyFailed_'.$customer->id);
        cache()->forget('weeklyFailed_'.$customer->id);
        cache()->forget('totalDelivered_'.$customer->id);
        cache()->forget('dailyDelivered_'.$customer->id);
        cache()->forget('weeklyDelivered_'.$customer->id);
        cache()->forget('sellerRequest_'.$customer->id);
        cache()->forget('domain_'.$customer->id);
        cache()->forget('plans_'.$customer->id);
        cache()->forget('wallet_'.$customer->id);

        return redirect()->back()->with('success', trans('customer.messages.cache_cleared'));
    }

}
