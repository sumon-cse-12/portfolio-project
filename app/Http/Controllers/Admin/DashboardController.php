<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BecameReseller;
use App\Models\BillingRequest;
use App\Models\BlogList;
use App\Models\Customer;
use App\Models\Domain;
use App\Models\EmailTemplate;
use App\Models\Instrument;
use App\Models\Message;
use App\Models\MessageLog;
use App\Models\NumberRequest;
use App\Models\Publication;
use App\Models\SenderId;
use App\Models\SmsQueue;
use App\Models\Ticket;
use App\Models\TopUpRequest;
use App\Models\WhatsAppNumberRequest;
use App\Models\FreeAds;
use App\Models\Blog;
use App\Models\Poll;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(){
        $cache_in_seconds = config('cache_time');

        $user =auth()->user();
        $cacheCustomers = cache('customers_'.$user->id);
        if (is_null($cacheCustomers)) {
            $customers=$user->customers;
            $customers = cache()->remember('customers_'.$user->id, $cache_in_seconds, function () use ($customers) {
                return $customers;
            });
        } else {
            $customers = $cacheCustomers;
        }

        // $customer_ids=[];
        // foreach ($customers as $key=>$customer){
        //     $customer_ids[]=$customer->id;
        // }

        $cacheNewMessageCount = cache('newMessageCount_'.$user->id);
        if (is_null($cacheNewMessageCount)) {
            $newMessageCount = '';
            // $newMessageCount = MessageLog::whereIn('customer_id',$customer_ids)->where('type','inbox')->whereDate('created_at', '>=', Carbon::now())->count();
            $newMessageCount = cache()->remember('newMessageCount_'.$user->id, $cache_in_seconds, function () use ($newMessageCount) {
                return $newMessageCount;
            });
        } else {
            $newMessageCount = $cacheNewMessageCount;
        }
        $data['newMessageCount']=$newMessageCount;


        $cacheNewSentCount = cache('newSentCount_'.$user->id);
        if (is_null($cacheNewSentCount)) {
            $newSentCount = '';
            // $newSentCount = MessageLog::whereIn('customer_id',$customer_ids)->where('type','sent')->whereDate('created_at', '>=', Carbon::now())->count();
            $newSentCount = cache()->remember('newSentCount_'.$user->id, $cache_in_seconds, function () use ($newSentCount) {
                return $newSentCount;
            });
        } else {
            $newSentCount = $cacheNewSentCount;
        }
        $data['newSentCount']=$newSentCount;

        $cacheTotalInbox = cache('totalInbox_'.$user->id);
        if (is_null($cacheTotalInbox)) {
            $totalInbox = '';
            // $totalInbox = MessageLog::whereIn('customer_id',$customer_ids)->where('type','inbox')->count();
            $totalInbox = cache()->remember('totalInbox_'.$user->id, $cache_in_seconds, function () use ($totalInbox) {
                return $totalInbox;
            });
        } else {
            $totalInbox = $cacheTotalInbox;
        }
        $data['totalInbox']=$totalInbox;

        $cacheTotalSent = cache('totalSent_'.$user->id);
        if (is_null($cacheTotalSent)) {
            $totalSent = ' ';
            // $totalSent = MessageLog::whereIn('customer_id',$customer_ids)->where('type','sent')->count();
            $totalSent = cache()->remember('totalSent_'.$user->id, $cache_in_seconds, function () use ($totalSent) {
                return $totalSent;
            });
        } else {
            $totalSent = $cacheTotalSent;
        }
        $data['totalSent']=$totalSent;



        $cacheInboxes = cache('inboxes_'.$user->id);
        if (is_null($cacheInboxes)) {
            $inboxes ='';
            // $inboxes = MessageLog::whereIn('customer_id',$customer_ids)->where('type','inbox')
            //     ->select(DB::Raw('count(*) as count'),DB::Raw('DATE(created_at) day'))
            //     ->whereDate('created_at', '>', Carbon::now()->subDays(30))
            //     ->groupBy('day')->get()
            //     ->pluck('count','day');
            $inboxes = cache()->remember('inboxes_'.$user->id, $cache_in_seconds, function () use ($inboxes) {
                return $inboxes;
            });
        } else {
            $inboxes = $cacheInboxes;
        }
        $data['inboxes']=$inboxes;



        $data['weekDates']=getLastNDays(30);
        $chatInboxes=[];
        foreach (getLastNDays(30) as $day){
            $chatInboxes[]=isset($inboxes[trim($day, '"')])?$inboxes[trim($day, '"')]:0;
        }
        $data['chart_inbox']=$chatInboxes;

        $cacheSents = cache('sents_'.$user->id);
        if (is_null($cacheSents)) {
            $sents='';
            // $sents = MessageLog::whereIn('customer_id',$customer_ids)->where('type','sent')
            //     ->select(DB::Raw('count(*) as count'),DB::Raw('DATE(created_at) day'))
            //     ->whereDate('created_at', '>', Carbon::now()->subDays(30))
            //     ->groupBy('day')->get()
            //     ->pluck('count','day');
            $sents = cache()->remember('sents_'.$user->id, $cache_in_seconds, function () use ($sents) {
                return $sents;
            });
        } else {
            $sents = $cacheSents;
        }
        $data['sents']=$sents;

        // $ads = FreeAds::where('created_at', '>=', Carbon::now()->subDays(7))->get();
        // dd($ads);

        $chat_sents=[];
        foreach (getLastNDays(30) as $day){
            $chat_sents[]=isset($sents[trim($day, '"')])?$sents[trim($day, '"')]:0;
        }
        $data['chart_sent']=$chat_sents;



//        Check Settings

        $availableSettings=[];
        $pgs=json_decode(get_settings('payment_gateway'));
        $app_name=get_settings('app_name');
        $recaptcha_site_key=get_settings('recaptcha_site_key');
        $app_favicon=get_settings('app_favicon');
        $app_logo=get_settings('app_logo');
        $contact_info=json_decode(get_settings('contact_info'));

//        Mail
        $mail_name=get_settings('mail_name');
        $mail_from=get_settings('mail_from');
        $mail_host=get_settings('mail_host');
        $mail_port=get_settings('mail_port');
        $mail_username=get_settings('mail_username');
        $mail_password=get_settings('mail_password');
        $mail_encryption=get_settings('mail_encryption');

//        Local
        $local_setting=json_decode(get_settings('local_setting'));




        if(!$app_name || !$recaptcha_site_key || !$app_favicon || !$app_logo ||
            !$contact_info || !isset($contact_info->phone_number) || !isset($contact_info->email_address) || !isset($contact_info->address)){
            $availableSettings[]=trans("Configure Application Settings");
        };

        if(!$mail_name || !$mail_from || !$mail_host || !$mail_port || !$mail_username || !$mail_password || !$mail_encryption){
            $availableSettings[]=trans("Configure SMTP Settings");
        };
        $cacheEt = cache('e_template_'.$user->id);
        if (is_null($cacheEt)) {
            $et ='';
            // $et=EmailTemplate::whereIn('type',['registration','forget_password'])->get();
            $et = cache()->remember('e_template_'.$user->id, $cache_in_seconds, function () use ($et) {
                return $et;
            });
        } else {
            $et = $cacheEt;
        }

        // if($et->count() <=0){
        //     $availableSettings[]=trans("Configure Email Template");
        // }
        if(!$local_setting){
            $availableSettings[]=trans("Configure Local Settings");
        }


        $ss=json_decode(get_settings('site_setting'));
        if(isset($ss) && !$ss->favicon){ $availableSettings[]=trans("Need to upload favicon");};
        if(isset($ss) && !$ss->logo){ $availableSettings[]=trans("Need to upload logo");};

        $es=json_decode(get_settings('email_setting'));
        if(isset($es) && !$es->host){ $availableSettings[]=trans("Need to configure email settings");};


        $data['available_setting']=$availableSettings;
        $customer = Customer::count();
        $data['total_blogs'] = BlogList::where('status','active')->count();
        $data['total_insturments'] = Instrument::count();
        $data['total_publications'] = Publication::where('status','active')->count();

        $data['totalcustomer'] = $customer;

        return view('admin.dashboard',$data);
    }
    public function setLocale($type)
    {
        $availableLang = get_available_languages();

        if (!in_array($type, $availableLang)) abort(400);

        session()->put('locale', $type);

        return redirect()->back();
    }

    public function countNotification(){
        $adminUsers=Customer::where('added_by', 'admin')->pluck('id');
        $planReq=BillingRequest::where('admin_id', auth()->user()->id)->whereIn('customer_id', $adminUsers)->where('status', 'pending')->count();
        $tickets=Ticket::where('status', 'pending')->count();
        $verifications=BecameReseller::where('status', 'pending')->count();
        $topUpReq=TopUpRequest::where('admin_id', auth()->user()->id)->whereIn('customer_id', $adminUsers)->where('status', 'pending')->count();
        $domain=Domain::where('status', 'pending')->count();
        $numberReq=NumberRequest::where('status', 'pending')->count();
        $whatsAppReq=WhatsAppNumberRequest::where('status', 'pending')->count();
        $senderId=SenderId::whereIn('status', ['pending','review','review_pending'])->count();


        $data=[
            'plan_request'=>$planReq,
            'tickets'=>$tickets,
            'verifications'=>$verifications,
            'topUpReq'=>$topUpReq,
            'domain'=>$domain,
            'numberReq'=>$numberReq,
            'senderId'=>$senderId,
            'whatsappReq'=>$whatsAppReq,
        ];
        return response()->json([ $data, 'status'=>'success'], 200);
    }

    public function clearCache(){
        $user=auth()->user();
        cache()->forget('customers_'.$user);
        cache()->forget('newMessageCount_'.$user);
        cache()->forget('newSentCount_'.$user);
        cache()->forget('totalInbox_'.$user);
        cache()->forget('totalSent_'.$user);
        cache()->forget('inboxes_'.$user);
        cache()->forget('sents_'.$user);

        return redirect()->back()->with('success', trans('customer.messages.cache_cleared'));
    }
}
