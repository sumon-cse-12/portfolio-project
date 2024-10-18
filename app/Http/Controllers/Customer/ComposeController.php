<?php

namespace App\Http\Controllers\Customer;

use App\Events\SendMail;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\Number;
use App\Models\Report;
use App\Models\SenderId;
use App\Models\SentFail;
use App\Models\Ibft;
use App\Models\WhatsAppNumber;
use App\SmsProvider\SendSMS;
use App\VoiceCallProvider\SendVoiceCallProcess;
use App\WhatsAppProvider\SendMessageProcess;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ComposeController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->guard('customer')->user();
        $data['draft'] = $user->drafts()->where('id', $request->draft)->first();

        $usersToGroups = [];
        $usersToContacts = [];
        foreach ($user->active_groups as $group) {
            $usersToGroups[] = ['value' => $group->name, 'id' => $group->id, 'type' => 'group'];
        }
        foreach ($user->contacts()->limit(10000)->get() as $contact) {
            $usersToContacts[] = ['value' => isset($contact->first_name) ? $contact->number . ' (' . $contact->first_name . ' ' . $contact->last_name . ')' : $contact->number, 'id' => $contact->id, 'type' => 'contact'];
        }


        $data['users_to_contacts'] = $usersToContacts;
        $data['users_to_groups'] = $usersToGroups;
        $data['from_type'] = $request->type;

        return view('customer.smsbox.compose', $data);
    }

    public function sentCompose(Request $request)
    {
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }
        $current_plan=auth('customer')->user()->plan;
        if(!$current_plan){
            return redirect()->route('customer.billings.index')->withErrors(['failed'=>'Don\'t have any plan right now']);
        }
        if ($request->from_type == 'phone_number' || $request->from_type == 'whatsapp_number' ) {
            $request->validate([
                'from_number' => 'required',
                'to_numbers' => 'required|array',
                'body' => 'required',
            ]);
        }else if($request->from_type=='sender_id'){
            $request->validate([
                'sender_id' => 'required',
                'to_numbers' => 'required|array',
                'body' => 'required',
            ]);
        }else if($request->from_type=='voicecall'){
            $request->validate([
                'file_mp3' => 'required|mimes:mp3',
                'to_numbers' => 'required|array',
            ]);
        }

        $messageFiles = [];
        $sendFailed = [];
        if ($request->mms_files) {
            foreach ($request->mms_files as $key => $file) {
                $messageFiles[] = $fileName = time() . $key . '.' . $file->extension();
                $file->move(public_path('uploads/'), $fileName);
            }
            $request['message_files'] = json_encode($messageFiles);
        }
        if ($request->file_mp3) {
            $fileName = time() . '.' . $request->file_mp3->extension();
            $request->file_mp3->move(public_path('uploads/'), $fileName);
            $request['message_files'] = $fileName;
        }

        if (isset($request->isSchedule)) {
            $sd = Carbon::createFromTimeString($request->schedule);
            $request['schedule_datetime'] = $sd;
        }
        $allToNumbers = [];
        $allGroupIds = [];
        $allContactIds = [];

        foreach ($request->to_numbers as $item) {
            $number = (array)json_decode($item);
            if (isset($number['type']) && isset($number['id'])) {
                if ($number['type'] == 'contact') {
                    $allContactIds[] = $number['id'];
                } elseif ($number['type'] == 'group') {
                    $allGroupIds[] = $number['id'];
                }
            } else {
                $allToNumbers[] = $item;
            }
        }

        $contactNumbers = Contact::select('id', 'number','contact_dial_code')->whereIn('id', $allContactIds)->get();
        $groupNumbers = ContactGroup::with('contact')->whereIn('group_id', $allGroupIds)->get();

        foreach ($contactNumbers as $cn) {
            $allToNumbers[] = getPhoneNumberWithDialCode(trim($cn->number),$cn->contact_dial_code);
        }
        foreach ($groupNumbers as $gn) {
            $allToNumbers[] = getPhoneNumberWithDialCode(trim($gn->contact->number),$gn->contact->contact_dial_code);
        }

        if ($request->from_type == 'phone_number') {
            $number_form = $request->from_number;
        }else if($request->from_type=='sender_id'){
            $sender_id = SenderId::where('id',$request->sender_id)->firstOrFail();
            $number_form = $sender_id->sender_id;
        }else{
            $whatsAppNumber = auth('customer')->user()->whatsapp_numbers()->where('expire_date','>', now())->where('number', $request->whatsapp_from_number)->first();
            if(!$whatsAppNumber){
                return redirect()->back()->withErrors(['failed'=>'Invalid Number']);
            }
            $number_form = $whatsAppNumber->number;
        }

        $allToNumbers = array_unique($allToNumbers);

        $request['to_numbers'] = $allToNumbers;
        $request['numbers'] = json_encode(['from' => $number_form, 'to' => $allToNumbers]);
        $request['type'] = 'sent';


        $totalCount=1;
        $requestCharacters=$request->body;
        $characters=mb_strlen($requestCharacters, "UTF-8");
        if (strlen($requestCharacters) != strlen(utf8_decode($requestCharacters))) {
            if($characters && $characters > 70){
                $grandTotal=ceil($characters / 70);
                if($grandTotal > 1)
                    $totalCount= $grandTotal;
            }
        }else {
            if($characters && $characters > 160){
                $grandTotal=ceil($characters / 160);
                if($grandTotal > 1)
                    $totalCount= $grandTotal;
            }
        }

        $wallet = auth('customer')->user()->wallet()->first();

        $totalToNumbers= count($allToNumbers) * $totalCount;
        if ($current_plan && $current_plan->unlimited_sms_send == 'no') {
            if ($wallet->credit < $totalToNumbers) {
                return redirect()->back()->with('fail', 'Doesn\'t have enough sms');
            }
        }
        $plain_sms=$current_plan->plain_sms;

        //send sms here using API
        if ($request->from_type == 'phone_number') {
            $number = Number::where('number', $number_form)->first();
        }else if($request->from_type=='sender_id'){
            $number = SenderId::where('sender_id', $number_form)->first();
        }else if($request->from_type=='whatsapp_number') {
            $number = WhatsAppNumber::where('number', $number_form)->first();
        }

        if (!$number)
            return back()->with('fail', 'Number not found please contact with administrator');

        if ($request->from_type == 'phone_number') {
            $numb = $number->number;
            $fromType='number';
        }else if($request->from_type=='sender_id'){
            $numb = $number->sender_id;
            $fromType='sender_id';
        }else if($request->from_type=='whatsapp_number'){
            $numb = $number->number;
            $fromType='whatsapp';
        }


        $gateway=$number->gateway;

        if(!$gateway){
            return redirect()->back()->withErrors(['failed'=>'Invalid number / gateway']);
        }

        DB::beginTransaction();
        try {

            $newMessage = auth('customer')->user()->messages()->create($request->all());

            $totalCredit=$totalToNumbers * $plain_sms;

            if ($request->from_type == 'phone_number') {
                $subType='non_masking';
            } else if ($request->from_type == 'sender_id') {
                $subType='masking';
            }
            if($current_plan && $current_plan->unlimited_sms_send=='no') {
                $wallet->credit = $wallet->credit - $totalCredit;
                $wallet->save();
            }


            //Report
            $report= new Report();
            $report->customer_id=$newMessage->customer_id;
            $report->ref_id=$newMessage->id;
            $report->type='message';
            $report->sub_type=$subType;
            $report->amount='-'.$totalCredit;
            $report->save();

            $sms_queue = [];
            foreach ($request->to_numbers as $to) {
                $newMessageFiles = null;
                if ($messageFiles) {
                    $newMessageFiles = $messageFiles;

                    array_walk($newMessageFiles, function (&$value, $index) {
                        $value = asset('uploads/' . $value);
                    });
                }
                if($request->from_type=='whatsapp_number'){
                    $to = 'whatsapp:'.$to;
                }
                $sms_queue[] = [
                    'message_id' => $newMessage->id,
                    'from' => $numb,
                    'to' => $to,
                    'from_type' => $fromType,
                    'schedule_datetime' => $request->schedule_datetime?$request->schedule_datetime:now(),
                    'body' => $request->body,
                    'dynamic_gateway_id' => $gateway->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'type' => 'sent',
                ];
            }
            auth('customer')->user()->sms_queues()->createMany($sms_queue);
            // auth('customer')->user()->message_logs()->createMany($sms_queue);



            if (!isset($request->isSchedule)) {
                $failedNumber = collect($sendFailed)->pluck('to_number');
                //        Send Mail
                try {
                    $contacts = Contact::whereIn('number', $allToNumbers)->whereNotIn('number', $failedNumber)->get();
                    foreach ($contacts as $contact) {
                        if ($contact->email && $contact->email_notification == 'true') {
                            SendMail::dispatch($contact->email, 'New Message', $request->body);
                        }
                    }

                } catch (\Exception $ex) {
                    Log::error($ex->getMessage());
                }
            }

            if ($sendFailed) {
                SentFail::insert($sendFailed);
                $totalFailedMessage=count($sendFailed) * $totalCount;
                $subType='';
                if ($request->from_type == 'phone_number') {
                    $subType='non_masking';
                } else if ($request->from_type == 'sender_id') {
                    $subType='masking';
                }
                $totalFailedReverse=$totalFailedMessage * $plain_sms;
                if($current_plan && $current_plan->unlimited_sms_send=='no') {
                    $wallet->credit = $wallet->credit + $totalFailedReverse;
                    $wallet->save();
                }

                //Report
                $report= new Report();
                $report->customer_id=$newMessage->customer_id;
                $report->ref_id=$newMessage->id;
                $report->type='message';
                $report->sub_type=$subType;
                $report->amount='+'.$totalToNumbers;
                $report->save();
            }

            DB::commit();
            if(!$request->ajax()) {
                if ($sendFailed)
                    return back()->withErrors(['failed'=>'Message sent failed']);
                else
                    return back()->with('success', 'Message sent successfully');
            }else{
                if ($sendFailed)
                    return response()->json(['status'=>'success','message'=>'Message sent partially']);
                else
                    return response()->json(['status'=>'success','message'=> 'Message sent successfully']);
            }
        } catch (\Exception $ex) {
            Log::error($ex);
            DB::rollBack();
            cache()->forget('wallet_'.auth('customer')->user()->id);
            if($request->ajax()){
                return response()->json(['status'=>'failed', 'message'=>$ex->getMessage()]);
            }else {
                return back()->with('fail', $ex->getMessage());
            }
        }
    }

    public function queueList(Request $request)
    {

        $data['queuesList'] = auth('customer')->user()->sms_queues()->whereNotNull('schedule_datetime')->whereNull('delivered_at')->orderBy('created_at', 'desc')->paginate(10);
        return view('customer.smsbox.queue', $data);
    }

    public function overview()
    {
        return view('customer.smsbox.overview');
    }
    public function overview_get_data(Request $request)
    {
        $overview = auth('customer')->user()->orderByDesc('created_at');
        if ($request->type && $request->type == 'trash') {
            $overview = $overview->onlyTrashed();
        } else if ($request->type) {
            $overview = $overview->where('type', $request->type);
        }

        if ($request->status && $request->status == 'queue') {
            $overview = $overview->where('status', 'running')->where('schedule_completed', 'no')->whereNotNull('schedule_datetime')->whereNull('delivered_at');
        } else
            if ($request->status) {
            $overview = $overview->where('status', $request->status);
        }

        if ($request->from_date && $request->to_date) {
            $overview = $overview->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }


        if ($request->type == 'draft'){
            return datatables()->of($overview)
                ->addColumn('updated_at', function ($q) {
                    return "<a href='" . route('customer.smsbox.compose', ['draft'=>$q->id]) . "'>".formatDate($q->updated_at)."</a>";
                })
                ->addColumn('to', function ($q) {
                   $draftNumbers = json_decode($q->numbers)->to;
                    $draftTONumbers = [];
                   foreach ($draftNumbers as $draftNumber){
                       $draftTONumbers[] = json_decode($draftNumber)->value;
                   }
                    $count=count($draftTONumbers);
                    $text=$count>=100?' and more '.($q->contacts()->count()-$count):'';
                    return "<div class='show-more' style='max-width: 500px;white-space: pre-wrap'>" . implode(", ", $draftTONumbers).$text. " </div>";
                    return $draftTONumbers;
                })
                ->addColumn('from', function ($q) {
                    $draftFromNumbers = json_decode($q->numbers)->from;
                    return $draftFromNumbers;
                })
                ->addColumn('type', function ($q) {
                    $draftType = null;
                    return $draftType;
                })
                ->addColumn('status', function ($q) {
                    $draftStatus = null;
                    return $draftStatus;
                })
                ->addColumn('action', function ($q) {
                    return '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this draft?"
                                        data-action=' . route('customer.smsbox.draft.delete', ['id'=>$q]) . '
                                        data-input={"_method":"post"}
                                        data-toggle="modal" data-target="#modal-confirm" title="Delete"><i class="fa fa-trash"></i></button>';
                })
                ->rawColumns(['action','updated_at','to'])
                ->toJson();
        }else{
            return datatables()->of($overview)
                ->addColumn('updated_at', function ($q) {
                    return formatDate($q->updated_at);
                })
                ->addColumn('status', function ($q) {
                    $status='';
                    if($q->status=='pending' || $q->status=='failed'){
                        $status='<span class="badge badge-danger">'.ucfirst($q->status).'</span>';
                    }else{
                        $status='<span class="badge badge-success">'.ucfirst($q->status).'</span>';
                    }

                    return $status;
                })
                ->addColumn('type', function ($q) {
                    $type='';
                    if($q->status=='inbox'){
                        $type='<span class="badge badge-primary">'.ucfirst($q->type).'</span>';
                    }else{
                        $type='<span class="badge badge-success">'.ucfirst($q->type).'</span>';
                    }

                    return $type;
                })


                ->addColumn('action', function ($q) {
                    return '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this message?"
                                        data-action=' . route('customer.smsbox.overview.data.delete', ['id'=>$q]) . '
                                        data-input={"_method":"delete"}
                                        data-toggle="modal" data-target="#modal-confirm" title="Delete"><i class="fa fa-trash"></i></button>';
                })
                ->rawColumns(['action','status','type'])
                ->toJson();
        }

    }

    public function overview_data_delete(Request $request)
    {
        $request->validate([
            'id'=>'required'
        ]);
        $ids=explode(',', $request->id);
        auth('customer')->user()->message_logs()->whereIn('id',$ids)->delete();
        return back()->with('success', 'Message successfully moved to trash');
    }
    public function smsCalculate(Request $request){
        $request->validate([
            'from_type'=>'required',
            'to_numbers.*'=>'required',
            'message'=>'required'
        ]);

        $totalCount=1;
        $requestCharacters=$request->message;
        $characters=mb_strlen($requestCharacters, "UTF-8");
        if (strlen($requestCharacters) != strlen(utf8_decode($requestCharacters))) {
            if($characters && $characters > 70){
                $grandTotal=ceil($characters / 70);
                if($grandTotal > 1)
                    $totalCount= $grandTotal;
            }
        }else {
            if($characters && $characters > 160){
                $grandTotal=ceil($characters / 160);
                if($grandTotal > 1)
                    $totalCount= $grandTotal;
            }
        }
        $currentPlan = auth('customer')->user()->plan;
        if ($request->from_type == 'phone_number'){
            $rate = $currentPlan->non_masking_rate;
        }elseif ($request->from_type == 'sender_id'){
            $rate = $currentPlan->masking_rate;
        }elseif($request->from_type == 'whatsapp_number'){
            $rate = $currentPlan->whatsapp_rate;
        }


        $number = count($request->to_numbers);
        $totalSms = $totalCount*$number;
        $totalRate = $rate*$totalSms;
        return response()->json(['status'=>'success','data'=>['totalSms'=>$totalSms,'totalNumber'=> $number,'totalRate'=>$totalRate]]);

    }
    public function ibft_transfer($id)
    {
        $ibft_list = Ibft::where('id', $id)->first();
        $data['ibft_list']=$ibft_list;
        return view('customer.ibft.index',$data);
    }
    public function ibft_transfer_list()
    {
        $user = auth()->guard('customer')->user();
        $ibft_lists = Ibft::where('agent', $user->id)->get();
        $data['ibft_lists']=$ibft_lists;
        return view('customer.ibft.ibft-list',$data);
    }
}
