<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Jobs\CampaignCreateJob;
use App\Models\AssignStaffContact;
use App\Models\Campaign;
use App\Models\CampaignStaff;
use App\Models\Coverage;
use App\Models\Customer;
use App\Models\Exception;
use App\Models\FromGroup;
use App\Models\Group;
use App\Models\Message;
use App\Models\Number;
use App\Models\SenderId;
use App\Models\Settings;
use App\Models\SmsQueue;
use App\Models\SmsTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CampaignController extends Controller
{
    public function index()
    {

        return view('customer.campaign.index');
    }
public function smsTemplate(){
    $customer = auth('customer')->user();
    $data['sms_templates']= SmsTemplate::where('customer_id',$customer->id)->get();
        return view('customer.template.sms_template', $data);
}
    public function report(Request $request)
    {
        $customer = auth('customer')->user();
        if ($request->campaign_id)
        $campaign = $customer->campaings()->where('id', $request->campaign_id)->firstOrFail();
        // $messageLogs = $customer->message_logs()->select(['from', 'to', 'body','response_code', 'updated_at']);

        // if ($request->campaign_id) {
        //     $messageLogs = $messageLogs->where('campaign_id', $campaign->id);
        // }
        // if ($request->response_code){
        //     $messageLogs = $messageLogs->where('response_code', $request->response_code);
        // }
        // if ($request->campaign_id) {
        //     $data['reports'] = $messageLogs->simplePaginate(20);
        // }else{
        //     $data['reports']='';
        // }
        $data['campaigns']=$customer->campaings;
        $data['requestData']=$request->only('campaign_id','response_code');
        return view('customer.campaign.report',$data);
    }

    public function getAll()
    {
        $campaings = auth('customer')->user()->campaings()->where('is_dynamic', 'no')->withCount(['sms_queue','total_processed'])->orderByDesc('id');        return datatables()->of($campaings)
            ->addColumn('title', function ($q) {
                $title = $q->title;
                if ($q->sms_queue_count) {
                    $title .= '(' . $q->total_processed_count . '/' . $q->sms_queue_count . ')';
                }
                return $title;
            })
            ->addColumn('start_date', function ($q) {
                return $q->start_date->format('Y-m-d');
            })
            ->addColumn('end_date', function ($q) {
                return $q->end_date->format('Y-m-d');
            })
            ->addColumn('status', function ($q) {


                if ($q->import_fail_message) {
                    return ' <button type="button" class="btn light btn-sm btn-danger">Import Failed</button> <br>' . $q->import_fail_message;
                }

                if ($q->status == 'running') {
                    if ($q->total_processed_count >= $q->sms_queue_count) {
                        return ' <button type="button" style="cursor:default" class="btn btn-sm btn-success">Completed</button>';
                    }

                    return '  <button type="button" class="btn light btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                Running
                               </button>
                                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">
                                     <button data-message="Are you sure, you want to change this campaign status?" data-action=' . route('customer.campaign.status', ['id' => $q->id, 'status' => 'paused']) . '
                                        data-input={"_method":"post"} data-toggle="modal" data-target="#modal-confirm" class="dropdown-item">
                                                    Pause
                                     </button>
                                </div>';
                } elseif ($q->status == 'failed') {
                    return '  <button type="button" class="btn light btn-sm btn-danger">Failed</button> <br>' . $q->import_fail_message;
                } elseif ($q->status == 'importing') {
                    return "<span data-id='".$q->id."' class='importing'> <i class=\"fas fa-spinner fa-pulse\"></i> importing</span>";
                }else if($q->status == 'completed'){
                    return ' <button type="button" class="btn light btn-sm btn-primary">Completed</button>';
                } else {
                    return '<button type="button" class="btn light btn-sm btn-warning dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                Pause
                               </button>
                                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">
                                     <button data-message="Are you sure, you want to change this campaign status?" data-action=' . route('customer.campaign.status', ['id' => $q->id, 'status' => 'running']) . '
                                        data-input={"_method":"post"} data-toggle="modal" data-target="#modal-confirm" class="dropdown-item">
                                                    Running
                                     </button>
                                </div>';
                }
            })->addColumn('action', function ($q) {
                return '<a href="'.route('customer.campaign.statistic',[$q->id]).'" target="_blank" class="btn light btn-sm btn-info mr-2" title="Statistic"><i class="fa fa-file"></i></a>'.'<button class="btn btn-sm btn-danger" data-message="Are you sure, you want to delete this campaign? <br> <br> <small>N.B: This will delete all messages including sent and queue related to this campaign.</small>"
                                        data-action=' . route('customer.campaign.destroy', [$q]) . '
                                        data-input={"_method":"delete"}
                                        data-toggle="modal" data-target="#modal-confirm" title="Delete"><i class="fa fa-trash"></i></button>';
            })->rawColumns(['title', 'action', 'status'])->toJson();
    }


    public function allSenders(Request $request){
        $customer=auth('customer')->user();

        $fromGroups=$customer->from_groups()->where('type', $request->type)->get();
        $numbers=$customer->numbers()->where('expire_date','>', now());
        if($request->from_selected_type=='sms'){
            $numbers->where('sms_capability', 'yes');
        }else if($request->from_selected_type=='mms'){
            $numbers->where('mms_capability', 'yes');
        }else if($request->from_selected_type=='whatsapp'){
            $numbers->where('whatsapp_capability', 'yes');
        }else if($request->from_selected_type=='voicecall'){
            $numbers->where('voice_capability', 'yes');
        }

        $numbers=$numbers->get();

        $usersFormGroups = [];
        $usersFormNumbers = [];
        if($request->from_selected_type !='mms') {
            foreach ($fromGroups as $group) {
                $usersFormGroups[] = ['value' => $group->name, 'id' => $group->id, 'type' => 'group'];
            }
        }
        if ($request->type != 'sender_id') {
            foreach ($numbers as $key => $number) {
                $usersFormNumbers[] = ['number' => $number->number, 'type' => 'from'];
            }
        }

        if($request->type=='sender_id'){
            $senderIds=$customer->sender_ids;
            foreach ($senderIds as $senderId){
                $usersFormNumbers[]=['number'=>$senderId->sender_id,'id' => $senderId->id,'type'=>'from'];
            }
        }


        return response()->json(['number'=>$usersFormNumbers,'groups'=>$usersFormGroups, 'status'=>'success']);
    }

    public function create()
    {
        $customer = auth('customer')->user();
        $current_plan = $customer->plan;
        if (!$current_plan)
            return back()->withErrors(['failed'=>'Customer doesn\'t have any plan right now']);

        $data['templates'] = SmsTemplate::where('customer_id', $customer->id)->where('status','active')->get();
        $data['groups'] = $customer->groups()->withCount('contacts')->get();
        $data['from_groups']=$customer->from_groups;

        $usersFormGroups = [];
        $usersFormNumbers = [];
        $from_groups=$customer->from_groups()->where('type', 'number')->get();
        foreach ($from_groups as $group) {
            $usersFormGroups[] = ['number' => $group->name, 'id' => $group->id, 'type' => 'group'];
        }
        $all_numbers=$customer->numbers()->where('expire_date','>', now())->where('sms_capability', 'yes')->get();
        foreach ($all_numbers as $key=>$number) {
            $usersFormNumbers[] = ['number' => $number->number, 'type' => 'from'];
        }

        $usersFormSenderIdGroups = [];
        $usersFormSenders = [];
        $from_groups=$customer->from_groups()->where('type', 'sender_id')->get();
        foreach ($from_groups as $group) {
            $usersFormSenderIdGroups[] = ['number' => $group->name, 'id' => $group->id, 'type' => 'group'];
        }
        $all_senders=$customer->numbers()->where('expire_date','>', now())->where('sms_capability', 'yes')->get();
        foreach ($all_senders as $key=>$number) {
            $usersFormSenders[] = ['number' => $number->sender_id,'id'=>$number->id, 'type' => 'from'];
        }

        $data['users_from_groups']=$usersFormGroups;
        $data['users_from_number']=$usersFormNumbers;

        $data['senderid_from_groups']=$usersFormSenderIdGroups;
        $data['from_sender_ids']=$usersFormSenders;
        $data['plain_sms']=$current_plan->plain_sms?intval($current_plan->plain_sms, '0'):1;
        $data['staffs'] = Customer::where('admin_id', auth('customer')->user()->id)->where('type', 'staff')->get();
        return view('customer.campaign.create', $data);
    }

    public function getTemplate(Request $request)
    {

        $customer = auth('customer')->user();
        $template = SmsTemplate::where('id', $request->template_id)->where('customer_id', $customer->id)->first();

        return response()->json(['status' => 'success', 'data' => $template->body]);
    }

    public function get_from_numbers(Request $request){
        $customer=auth('customer')->user();
        if($request->type=='sms'){
            $type='number';
        }else if($request->type=='mms'){
            $type='mms';
        }else if($request->type=='whatsapp'){
            $type='whatsapp';
        }else if($request->type=='voicecall'){
            $type='voicecall';
        }

        if(!$type){
            return response()->json(['status'=>'failed','message'=>'Invalid type']);
        }
        $groups=$customer->from_groups()->where('type', $type)->get();
        $numbers= $customer->numbers()->where('expire_date','>', now());

        if($request->type=='sms'){
            $numbers=$numbers->where('sms_capability', 'yes');
        }
        if($request->type=='mms'){
            $numbers=$numbers->where('mms_capability', 'yes');
        }
        if($request->type=='whatsapp'){
            $numbers=$numbers->where('whatsapp_capability', 'yes');
        }
        if($request->type=='voicecall'){
           $numbers= $numbers->where('voice_capability', 'yes');
        }

        $numbers=$numbers->get();

        $usersFormGroups = [];
        $usersFormNumbers = [];
        foreach ($groups as $group) {
            $usersFormGroups[] = ['value' => $group->name, 'id' => $group->id, 'type' => 'group'];
        }
        foreach ($numbers as $key=>$number) {
            $usersFormNumbers[] = ['value' => $number->number, 'number' => $number->number, 'type' => 'from'];
        }

        return response()->json(['numbers'=>$usersFormNumbers, 'groups'=>$usersFormGroups,'status'=>'success']);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try{

            if (env("APP_DEMO")){
                return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
            }

            if(!$request->start_date){
                $start_date=now()->format('Y-m-d');
                $request['start_date']=$start_date;
            }

            if(!$request->end_date){
                $end_date=now()->format('Y-m-d');
                $request['end_date']=$end_date;
            }

            if(!$request->start_time){
                $start_time=now()->format('H:i');
                $request['start_time']=$start_time;
            }

            if(!$request->end_time){
                $end_time=(now()->addSeconds(10))->format('H:i');
                $request['end_time']=$end_time;
            }

            $request->validate([
                'title' => 'required',
                'start_date' => 'required',
                'template_body' => 'required',
                'end_date' => 'required',
                'start_time' => 'required',
                'end_time' => 'required',
            ]);
            $current_plan = auth('customer')->user()->plan;
            if (!$current_plan)
                return back()->withErrors(['failed'=>'Customer doesn\'t have any plan right now']);

            if($current_plan->unlimited_sms_send=='no'){
                $sendlimit_expire=$current_plan->created_at->addMonths(1);
                $total_sms_queue=auth('customer')->user()->sms_queues()->whereBetween('created_at', [$current_plan->created_at, $sendlimit_expire])->count();

                if($sendlimit_expire < now()){
                    return redirect()->route('customer.billing.index')->withErrors(['failed'=> 'Your SMS sending limit has expired']);
                }
                if($current_plan->sms_sending_limit <= $total_sms_queue){
                    return redirect()->route('customer.billing.index')->withErrors(['failed'=> 'Your SMS sending limit has ended!']);
                }
            }

            $coverages=Coverage::whereIn('id', json_decode($current_plan->coverage_ids))->get();


            $coverage_rate = [];
            if ($coverages) {
                foreach ($coverages as $coverage) {
                    if ($request->from_selected_type == 'sms') {
                        $coverage_rate[$coverage->country_code] = $coverage->plain_sms;
                    } else if ($request->from_selected_type == 'mms') {
                        $coverage_rate[$coverage->country_code] = $coverage->send_mms;
                    } else if ($request->from_selected_type == 'whatsapp') {
                        $coverage_rate[$coverage->country_code] = $coverage->send_voice_sms;
                    } else if ($request->from_selected_type == 'voicecall') {
                        $coverage_rate[$coverage->country_code] = $coverage->send_whatsapp_sms;
                    }
                }
            }

            $totalGroups = Group::whereIn('id', $request->groups)->get();
            $totalTo = [];
            $totalSmsAmount=0;
            $findStaffContactNumber=[];
            foreach ($totalGroups as $gContacts) {
                foreach ($gContacts->contacts as $contact) {
                    if(isset($coverage_rate[str_replace('+','',$contact->contact->contact_dial_code)])){
                        $totalTo[] = $contact->contact->contact_dial_code.$contact->contact->number;
                        $totalSmsAmount=$totalSmsAmount + $coverage_rate[str_replace('+','',$contact->contact->contact_dial_code)];
                        $findStaffContactNumber[$contact->contact->number]=$contact->contact->id;
                    }
                }
            }

            $totalTo = array_unique($totalTo);
            if (count($totalTo) <= 0) {
                return redirect()->back()->withErrors(['failed' => 'Select at last one group with more than 1 contacts']);
            }

            $onException = Exception::where('customer_id', auth('customer')->id())->whereIn('number', $totalTo)->pluck('number')->toArray();
            $to = array_values(array_diff($totalTo, $onException));
            if (count($to) <= 0) {
                return redirect()->back()->withErrors(['failed' => 'There is no available to number']);
            }
            //subtracting one sms TODO:: will need to count text and sub that
            $wallet = auth('customer')->user()->wallet()->first();


            // Count Template Body Characters
            $totalCount=1;
//            $requestCharacters=implode('',$request->template_body);
            $requestCharacters=$request->template_body;
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

            $totalToNumbers= ($totalSmsAmount * $totalCount);

            if ($current_plan) {
                if ($wallet->credit < $totalToNumbers) {
                    return redirect()->back()->with('fail', 'Doesn\'t have enough sms')->withInput();
                }
            }



            $allGroupIds=[];
            $allFromNumber=[];


            if ($request->type=='number') {
                foreach ($request->from_number as $item) {
                    $number = (array)json_decode($item);
                    if (isset($number['type'])) {
                        if ($number['type'] == 'from') {
                            $allFromNumber[] = $number['number'];
                        } elseif ($number['type'] == 'group') {
                            $allGroupIds[] = $number['id'];
                        }
                    }
                }

                $fromGroups = FromGroup::where('status', 'active')->whereIn('id', $allGroupIds)->get();
                foreach ($fromGroups as $from_group) {
                    foreach ($from_group->from_group_numbers as $from_number) {
                        $allFromNumber[] = $from_number->number;
                    }
                }
                $allNumbers = Number::whereIn('number', $allFromNumber)->get();

                $find_gateway_id=[];
                foreach ($allNumbers as $from_gateway) {
                    $find_gateway_id[$from_gateway->number]=$from_gateway->dynamic_gateway_id;
                }

            }else if($request->type=='sender_id') {
                $allFromSenderIds=[];
                foreach ($request->from_number as $item) {
                    $number = (array)json_decode($item);
                    if (isset($number['type'])) {
                        if ($number['type'] == 'from') {
                            $allFromSenderIds[] = $number['id'];
                        } elseif ($number['type'] == 'group') {
                            $allGroupIds[] = $number['id'];
                        }
                    }
                }

                $senderIds=SenderId::whereIn('id', $allFromSenderIds)->get();
                foreach($senderIds as $senderid){
                    $allFromNumber[]=$senderid->sender_id;
                }
                $allNumbers = $allFromNumber;

                $find_gateway_id=[];
                foreach ($senderIds as $from_gateway) {
                    $find_gateway_id[$from_gateway->sender_id]=$from_gateway->dynamic_gateway_id;
                }
            }else {
                $allFromNumber=  [$request->whatsapp_from_number];
            }

            $voice_obj=[];
            if($request->language && $request->voice_type){
                $voice_obj=[
                    'language'=>$request->language,
                    'voice_type'=>$request->voice_type
                ];
            }



            $allFromNumber = array_values(array_unique($allFromNumber));

            $customer = auth('customer')->user();
            $campaign = new Campaign();
            $campaign->title = $request->title;
            $campaign->customer_id = $customer->id;
            $campaign->from_number = json_encode($allFromNumber);
            $campaign->to_number = json_encode($to);
            $campaign->start_date = $request->start_date;
            $campaign->end_date = $request->end_date;
            $campaign->start_time = $request->start_time;
            $campaign->end_time = $request->end_time;
            $campaign->template_id = $request->template_id;
            $campaign->message_body = json_encode($request->template_body);
            $campaign->message_send_rate = 999999;
            $campaign->from_group_id = json_encode($allGroupIds);
            $campaign->status = 'importing';
            $campaign->save();
            $from = $allFromNumber;

            if($request->staff_ids) {
                $campaign_staffs = [];
                foreach ($request->staff_ids as $staff_id) {
                    $campaign_staffs[] = [
                        'campaign_id' => $campaign->id,
                        'customer_id' => $customer->id,
                        'staff_id' => $staff_id,
                        'created_at'=>now(),
                        'updated_at'=>now()
                    ];
                }
                CampaignStaff::insert($campaign_staffs);
            }

            $genStaffContacts=[];
            for ($i = 0; $i < count($to); $i += count($request->staff_ids)) {
                for ($j = 0; $j < count($request->staff_ids); $j++) {
                    if (isset($to[$i + $j])) {
                        $genStaffContacts[$request->staff_ids[$j]][] = trim($to[$i + $j]);
                    }
                }
            }

            foreach ($genStaffContacts as $key=>$genStaff){
                foreach($genStaff as $contact){
                    if(isset($findStaffContactNumber[getPhoneNumberWithoutDialCode($contact)])) {
                        $new_staff_contact = new AssignStaffContact();
                        $new_staff_contact->customer_id = auth('customer')->user()->id;
                        $new_staff_contact->staff_id = $key;
                        $new_staff_contact->contact_id = $findStaffContactNumber[getPhoneNumberWithoutDialCode($contact)];
                        $new_staff_contact->campaign_id = $campaign->id;
                        $new_staff_contact->save();
                    }
                }
            }


            $totalSmsCredit=$totalToNumbers;
            if ($current_plan) {
                $wallet->credit = $wallet->credit - $totalSmsCredit;
                $wallet->save();
            }

            $totalToNumbersCount = 0;
            $totalFromNumbersCount = count($allFromNumber);
            $generatedToNumbers = [];
            $lastKey=end($from);
            for ($i = 0; $i < count($to); $i += count($from)) {
                for ($j = 0; $j < count($from); $j++) {
                    if (isset($to[$i + $j])) {
                        $generatedToNumbers[$from[$j]][] = trim($to[$i + $j]);
                        $totalToNumbersCount++;
                    }
                }
            }

            $messageFiles=[];
            if ($request->message_files) {
                foreach ($request->message_files as $key => $file) {
                    $messageFiles[] = $fileName = time() . $key . '.' . $file->extension();
                    $file->move(public_path('uploads/'), $fileName);
                }
                $messageFiles=$messageFiles;
            }

            foreach ($generatedToNumbers as $key => $toNumbers) {
                $dynamic_gateway_id = isset($find_gateway_id[$key]) ? $find_gateway_id[$key] : '';
                if ($dynamic_gateway_id) {
                    /*Start*/
                    $startDate = (new Carbon($request->start_date))->subDay();
                    $endDate = new Carbon($request->end_date);
                    $startTime = new Carbon($request->start_time);
                    $endTime = new Carbon($request->end_time);
                    $difference_time = $startTime->diffInSeconds($endTime);
                    $difference_date = $startDate->diffInDays($endDate);
                    $total_minute = $difference_time * $difference_date;
                    $send_speed = floor($total_minute / $totalToNumbersCount);
                    /*End*/

                    //create new message
                    $newMessage = new Message();
                    $newMessage->customer_id = $customer->id;
                    $newMessage->body = json_encode($request->template_body);
                    $newMessage->numbers = json_encode(['from' => $key, 'to' => $toNumbers]);
                    $newMessage->campaign_id = $campaign->id;
                    $newMessage->message_files = json_encode($messageFiles);
                    $newMessage->sender_type = $request->from_selected_type;
                    $newMessage->type = 'sent';
                    $newMessage->read = 'no';
                    $newMessage->voice_obj = json_encode($voice_obj);
                    $newMessage->save();

                    CampaignCreateJob::dispatch($key, $toNumbers, $campaign, $newMessage, $totalToNumbersCount, $totalFromNumbersCount, $difference_date, $startDate, $startTime, $send_speed, auth('customer')->user(), $lastKey,$dynamic_gateway_id);
                }
            }

            DB::commit();
            return redirect()->route('customer.campaign.index')->with('success', trans('Campaign created successfully'));
        }catch(\Exception $ex){
           DB::rollBack();
           return  redirect()->back()->withErrors(['failed'=>$ex->getMessage()]);
        }

    }


    public function destroy(Campaign $campaign)
    {
        if ($campaign->sms_queue) {
            $campaign->sms_queue()->delete();
        }
        if ($campaign->messages) {
            $campaign->messages()->delete();
        }
        $campaign->delete();

        return redirect()->route('customer.campaign.index')->with('success', 'Congratulations ! Campaign successfully deleted');
    }

    public function status(Request $request)
    {
        $request->validate([
            'status' => 'required|in:running,paused',
        ]);

        $customer = auth('customer')->user();
        $campaign = Campaign::where('customer_id', $customer->id)->where('id', $request->id)->firstOrFail();
        $campaign->status = $request->status;
        $campaign->save();

        SmsQueue::where('campaign_id', $campaign->id)->where('schedule_completed','no')->where('status', $request->status == 'paused' ? 'running' : 'paused')->update(['status' => $request->status]);

        return redirect()->route('customer.campaign.index')->with('success', 'Congratulations ! Campaign status updated');

    }
    public function statistic($id){
        $campaign = auth('customer')->user()->campaings()->where('id', $id)->firstOrFail();
        $data['messageRunningLogs'] = auth('customer')->user()->sms_queues()->where('campaign_id', $campaign->id)->orderBy('schedule_datetime')->where('schedule_completed','no')->where('status','running')->paginate(20, ['*'], 'running');
        $data['messagePausedLogs'] = auth('customer')->user()->sms_queues()->where('campaign_id', $campaign->id)->orderBy('schedule_datetime')->where('status','paused')->paginate(20, ['*'], 'paused');
        $data['messageFailedLogs'] = auth('customer')->user()->sms_queues()->where('campaign_id', $campaign->id)->orderBy('schedule_datetime')->where('schedule_completed','yes')->where('status','failed')->paginate(20, ['*'], 'failed');
        $data['messageDeliveredLogs'] = auth('customer')->user()->sms_queues()->where('campaign_id', $campaign->id)->orderBy('schedule_datetime')->where('status','!=','failed')->where('schedule_completed','yes')->whereColumn('created_at', '<', 'updated_at')->whereNull('response_code')->paginate(20, ['*'], 'delivered');

        return view('customer.campaign.statistic', $data);
    }

    public function checkImportStatus(Request $request){
        $request_ids=json_decode($request->ids);
        if (!$request_ids || count($request_ids) <= 0){
            return response()->json(['status'=>'failed']);
        }
        $hasChanged=auth('customer')->user()->campaings()->whereIn('id', $request_ids)->where('status','running')->count();

        return response()->json(['status'=>'success', 'data'=>$hasChanged]);
    }
}
