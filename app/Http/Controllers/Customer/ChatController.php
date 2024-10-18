<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\AssignStaffContact;
use App\Models\Campaign;
use App\Models\CampaignStaff;
use App\Models\Contact;
use App\Models\Coverage;
use App\Models\Customer;
use App\Models\Exception;
use App\Models\FlagContact;
use App\Models\Label;
use App\Models\Message;
use App\Models\MessageLog;
use App\Models\Number;
use App\Models\SenderId;
use App\Models\TrashContact;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function index()
    {
        $customer = auth('customer')->user();
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

        $data['numbers'] = auth('customer')->user()->numbers()->get();
        $data['chat_responses'] = auth('customer')->user()->chat_responses()->where('status', 'active')->get();
        $data['labels'] = auth('customer')->user()->labels()->where('status', 'active')->get();

        $data['staffs'] = Customer::where('admin_id', auth('customer')->user()->id)->where('type', 'staff')->get();
        $data['campaigns'] = auth('customer')->user()->campaings()->where('is_dynamic', 'no')->with('campaign_staffs')->orderByDesc('created_at')->get();
        $access_staff_view= auth('customer')->user()->settings()->where('name','view_staff_message')->first();
        if($access_staff_view && isset($access_staff_view->value)){
            $data['access_staff_view']=$access_staff_view->value;
        }
        return view('customer.chat.index', $data);
    }



    public function getCampaignStaffs(Request $request){
        $campaign=Campaign::where('id', $request->campaign_id)->first();
        if(!$campaign){
            return response()->json(['status'=>'failed', 'message'=>'Invalid Campaign']);
        }
        $campaignStaffs=CampaignStaff::where('campaign_id', $campaign->id)->where('customer_id', auth('customer')->user()->id)->pluck('staff_id');

        $assignedStaff=AssignStaffContact::where('campaign_id', $campaign->id)->whereIn('staff_id', $campaignStaffs)->pluck('staff_id')->toArray();
        $staffs=Customer::where('type', 'staff')->whereIn('id', $campaignStaffs)->get();

        $all_staffs=[];

        foreach($staffs as $staff){
            if(!in_array($staff->id, $assignedStaff)) {
                $all_staffs[] = $staff;
            }
        }
        $pre_assigned=AssignStaffContact::where('customer_id', auth('customer')->user()->id)->where('contact_id', $request->contact_id)
            ->where('campaign_id', $request->campaign_id)->with('staff')->get();
        $pre_staffs=[];
        foreach ($pre_assigned as $pre_assign){
            $pre_staffs[]=[
                'name'=>$pre_assign->staff->fullname,
                'id'=>$pre_assign->id
            ];
        }
        return response()->json(['status'=>'success','staffs'=>$all_staffs,'pre_staffs'=>$pre_staffs]);
    }

    public function deleteContactStaff(Request $request){
        $a_staff= AssignStaffContact::where('id', $request->id)->first();

        if(!$a_staff){
            return response()->json(['status'=>'failed', 'message'=>'Invalid staff']);
        }
        $a_staff->delete();
        return response()->json(['status'=>'success', 'message'=>'Staff Successfully Removed From Contact']);
    }

    public function assignStaffInContact(Request $request)
    {
        $ex_staff_contact = AssignStaffContact::where('contact_id', $request->contact_id)->first();

        if ($ex_staff_contact && !$request->type) {
            return response()->json(['status' => 'pre_assigned']);
        }

        $new_staff_contact = new AssignStaffContact();
        $new_staff_contact->customer_id = auth('customer')->user()->id;
        $new_staff_contact->staff_id = $request->staff_id;
        $new_staff_contact->contact_id = $request->contact_id;
        $new_staff_contact->campaign_id = $request->campaign_id;
        $new_staff_contact->message_log_id = $request->message_log_id;
        $new_staff_contact->save();

        return response()->json(['status' => 'success', 'message' => 'Staff successfully assigned']);
    }

    public function trashContact(Request $request){
        $pre_trash_contact=TrashContact::where('contact_id', $request->contact_id)->where('customer_id', auth('customer')->user()->id)->first();

        if(!$pre_trash_contact){
            $new_trash= new TrashContact();
            $new_trash->contact_id=$request->contact_id;
            $new_trash->customer_id=auth('customer')->user()->id;
            $new_trash->save();
        }

        return response()->json(['status'=>'success', 'message'=>'Conversation moved to trash']);
    }
    public function removeTrashContact(Request $request){
        $pre_trash_contact=TrashContact::where('contact_id', $request->contact_id)->where('customer_id', auth('customer')->user()->id)->first();

        if(!$pre_trash_contact){
            return response()->json(['status'=>'success', 'message'=>'Something went wrong try again after sometimes']);
        }

        $pre_trash_contact->delete();

        return response()->json(['status'=>'success', 'message'=>'Conversation moved to inbox']);
    }

    public function messageCount(Request $request){
        $customer=auth('customer')->user();
        $numbersWithPlusArray = [];
        $trashContacts=TrashContact::where('customer_id', auth('customer')->user()->id)->pluck('contact_id');
        $contacts=auth('customer')->user()->contacts()->whereIn('id', $trashContacts)->get();
        foreach($contacts as $contact){
            $numbersWithPlusArray[]=$contact->contact_dial_code.$contact->number;
        }


        if($request->message_type && $request->message_type=='trash') {
            if(!$numbersWithPlusArray) {
                return response()->json(['status' => 'success', 'total_message' => 0, 'total_unread_message' => 0]);
            }

            $total_message = $customer->message_logs()->whereIn('from', $numbersWithPlusArray)->select('from','to',DB::raw('MAX(updated_at) as created_at'))
                ->where('type', 'inbox')->groupBy('from')->count();
            $total_unread_message = $customer->message_logs()->where('type', 'inbox')->where('is_read', 'no')->where('to', $numbersWithPlusArray)->count();
        }else{
            $total_message = $customer->message_logs()->select('id','type','from','to',DB::raw('MAX(updated_at) as created_at'))
                ->where('type', 'inbox')->whereNotIn('from', $numbersWithPlusArray)->groupBy('from')->get();

            $total_message=count($total_message);

            $total_unread_message = $customer->message_logs()->where('type', 'inbox')->where('is_read', 'no')->count();
        }

        return response()->json(['status'=>'success', 'total_message'=>$total_message, 'total_unread_message'=>$total_unread_message]);
    }

    public function flaggedContact(Request $request){
        $preContact=FlagContact::where('customer_id', auth('customer')->user()->id)->where('contact_id', $request->contact_id)->first();

        if($preContact){
            $preContact->delete();
            return response()->json(['status'=>'failed', 'message'=>'Contact Successfully Removed From Flag']);
        }

        $flagged= new FlagContact();
        $flagged->customer_id=auth('customer')->user()->id;
        $flagged->contact_id=$request->contact_id;
        $flagged->save();

        return response()->json(['status'=>'success', 'message'=>'Successfully added in flag list']);

    }



//    public function get_numbers(Request $request)
//    {
//        $page_no = $request->page;
//        if (!$page_no) abort(404);
//
//        $no_of_data = 10;
//        $offset = ($page_no * $no_of_data) - $no_of_data;
//
//        $search = $request->search;
//        $allNumbers = auth()->user()->message_logs()->select('from AS to', 'body', DB::raw('MAX(updated_at) as created_at'))->where('type', 'inbox')->groupBy('from');
//        if ($request->type == 'old') {
//            $allNumbers = $allNumbers->orderBy('created_at', 'asc');
//        } else {
//            $allNumbers = $allNumbers->orderByDesc('created_at');
//        }
//        if ($search) {
//            $contacts = Contact::where('number', 'like', '%' . $search . '%')->orWhere('first_name', 'like', '%' . $search . '%')->orWhere('last_name', 'like', '%' . $search . '%')->pluck('number');
//            $allNumbers = $allNumbers->whereIn('from', $contacts)->where('type', 'inbox');
//        }
//
//        if ($request->date) {
//            $dates = explode('-', $request->date);
//            $fromDate = isset($dates) && isset($dates["0"]) ? str_replace(' ', '', $dates["0"]) : now();
//            $toDate = isset($dates) && isset($dates["1"]) ? str_replace(' ', '', $dates["1"]) : now();
//            $fromDate = new \DateTime($fromDate);
//            $toDate = new \DateTime($toDate);
//            if ($fromDate != $toDate) {
//                $allNumbers = $allNumbers->whereBetween('updated_at', [$fromDate, $toDate]);
//            }
//        }
//        if ($request->label_id) {
//
//            $label = Label::where('id', $request->label_id)->first();
//            if (!$label) {
//                return response()->json(['status' => 'failed', 'message' => 'Invalid Label']);
//            }
//            $contacts = auth('customer')->user()->contacts()->where('label_id', $label->id)->pluck('number')->unique();
//            $allNumbers = $allNumbers->whereIn('from', $contacts);
//        }
//        $allNumbers = $allNumbers->limit($no_of_data)->offset($offset)->get();
//
//        if ($request->type == 'old') {
//            $from_numbers = $allNumbers->sortBy('created_at')->pluck('to')->unique();
//        } else {
//            $from_numbers = $allNumbers->sortByDesc('created_at')->pluck('to')->unique();
//        }
//
//
//        $createdAt = [];
//        foreach ($allNumbers as $number) {
//            $diffInMinutes = now()->diffInMinutes($number->created_at);
//            $createdAt[$number->to] = $diffInMinutes > 60 ? $number->created_at->format('M,d,y h:i A') : $diffInMinutes . " min";
//        }
//        $allChats = auth('customer')->user()->message_logs()->whereIn('to', $from_numbers)
//            ->orWhereIn('from', $from_numbers)
//            ->orderBy('updated_at')
//            ->get(['body', 'to', 'from', 'created_at', 'updated_at']);
//
//        $find_chat = [];
//        foreach ($allChats as $key => $chat) {
//            $find_chat[getPhoneNumberWithoutDialCode($chat->to)] = Str::limit($chat->body,50);
//        }
//        foreach ($allChats as $key => $chat) {
//            $find_chat[getPhoneNumberWithoutDialCode($chat->from)] = Str::limit($chat->body,50);
//        }
////        Created At For New Message
//        $find_created_at=[];
//        foreach ($allChats as $key => $chat) {
//            $find_created_at[$chat->from] = $chat->created_at->format('y-m-d h:i:s');
//        }
//
//        $numbersWithPlusArray = [];
//        $numbersWithoutPlusArray = [];
//        foreach ($from_numbers as $number) {
//            $numbersWithPlusArray[] = '+' . str_replace('+', '', $number);
//            $numbersWithoutPlusArray[] = getPhoneNumberWithoutDialCode($number);
//        }
//        $findContacts = auth('customer')->user()->contacts()->whereIn('number', $numbersWithPlusArray)->orWhereIn('number', $numbersWithoutPlusArray)->orderBy('created_at')->get(['id','number','first_name','last_name','contact_dial_code']);
//        $findContact = [];
//        foreach ($findContacts as $contact) {
//            $findContact["".getPhoneNumberWithoutDialCode($contact->number).""] = [
//                'label' => '',
//                'number' => getPhoneNumberWithDialCode($contact->number,$contact->contact_dial_code),
//                'color' => '',
//                'full_name' => $contact->full_name ?? '',
//                'id' => $contact->id ?? ''
//            ];
//        }
//        $unreadsCount=MessageLog::select('from',DB::raw('count(*) as total'))->whereIn('from', $from_numbers)->where('is_read', 'no')->groupBy('from')->pluck('total','from')->toArray();
//        $data = [];
//
//        foreach ($numbersWithoutPlusArray as $key => $from_number) {
//            $data[$key]['full_name'] = isset($findContact[$from_number]) && isset($findContact[$from_number]['full_name']) ? $findContact[$from_number]['full_name'] : '';
//            $data[$key]['id'] = isset($findContact[$from_number]) && isset($findContact[$from_number]['id']) ? $findContact[$from_number]['id'] : '';
//            $data[$key]['number'] =  isset($findContact[$from_number]) && isset($findContact[$from_number]['number']) ? $findContact[$from_number]['number'] : '';
//            $data[$key]['created_at'] = isset($createdAt) && isset($createdAt[$from_number]) ? $createdAt[$from_number] : '';
//            $data[$key]['label'] = isset($findContact[$from_number]) && isset($findContact[$from_number]['label']) ? $findContact[$from_number]['label'] : '';
//            $data[$key]['color'] = isset($findContact[$from_number]) && isset($findContact[$from_number]['color']) ? $findContact[$from_number]['color'] : '';
//            $data[$key]['body'] = isset($find_chat[$from_number]) ? $find_chat[$from_number] : '';
//            $data[$key]['unread']= $unreadsCount[$from_number] ?? 0;
//            $data[$key]['createdAt'] = isset($find_created_at[$from_number])?$find_created_at[$from_number] : '';
//        }
//
//       // $data=collect($data)->sortByDesc('createdAt')->values();
//        $labels = collect([]);
//
//        if ($from_numbers->isNotEmpty()) {
//            return response()->json(['status' => 'success', 'data' => ['numbers' => $data, 'labels' => $labels, 'page' => $page_no + 1]]);
//        } else {
//            return response()->json(['status' => 'success', 'data' => ['numbers' => [], 'page' => 'end']]);
//        }
//    }
//


    public function get_numbers(Request $request)
    {
        $page_no = $request->page;
        if (!$page_no) abort(404);

        $no_of_data = 10;
        $offset = ($page_no * $no_of_data) - $no_of_data;

        $customer=auth('customer')->user();

        if($customer->type=='staff'){
            $customer=Customer::where('id', $customer->admin_id)->first();
        }


        $search = $request->search;
        $allNumbers = $customer->message_logs()->select('from AS to', 'body', DB::raw('MAX(updated_at) as created_at'))->where('type', 'inbox')->groupBy('from');
        if ($request->order_by_oldest && $request->order_by_oldest== 'true') {
            $allNumbers = $allNumbers->orderBy('created_at', 'asc');
        } else {
            $allNumbers = $allNumbers->orderByDesc('created_at');
        }

        //TODO::Filter Data By Specific Campaign
        if($request->campaign_id && $request->campaign_id !='all') {
            $allNumbers = $allNumbers->where('campaign_id', $request->campaign_id);
        }

        //TODO::Filter Unread Only
        if($request->unread_only && $request->unread_only=='true'){
            $allNumbers = $allNumbers->where('is_read', 'no');
        }

        if ($search) {
            $contacts = Contact::where('number', 'like', '%' . $search . '%')->orWhere('first_name', 'like', '%' . $search . '%')->orWhere('last_name', 'like', '%' . $search . '%')->pluck('number');
            $allNumbers = $allNumbers->whereIn('from', $contacts)->where('type', 'inbox');
        }

        if ($request->date) {
            $dates = explode('-', $request->date);
            $fromDate = isset($dates) && isset($dates["0"]) ? str_replace(' ', '', $dates["0"]) : now();
            $toDate = isset($dates) && isset($dates["1"]) ? str_replace(' ', '', $dates["1"]) : now();
            $fromDate = new \DateTime($fromDate);
            $toDate = new \DateTime($toDate);
            if ($fromDate != $toDate) {
                $allNumbers = $allNumbers->whereBetween('updated_at', [$fromDate, $toDate]);
            }
        }
        if ($request->label_id) {

            $label = Label::where('id', $request->label_id)->first();
            if (!$label) {
                return response()->json(['status' => 'failed', 'message' => 'Invalid Label']);
            }
            $contacts = auth('customer')->user()->contacts()->where('label_id', $label->id)->pluck('number')->unique();
            $allNumbers = $allNumbers->whereIn('from', $contacts);
        }
        $allNumbers = $allNumbers->limit($no_of_data)->offset($offset)->get();


        //TODO::Filter OrderBy Old
        if ($request->order_by_oldest && $request->order_by_oldest == 'true') {
            $from_numbers = $allNumbers->sortBy('created_at')->pluck('to')->unique();
        } else {
            $from_numbers = $allNumbers->sortByDesc('created_at')->pluck('to')->unique();
        }

        //TODO::Manage Staff Section Chat Data
        if(auth('customer')->user()->type=='staff') {
            $assign_staff_contacts=AssignStaffContact::where('staff_id', auth('customer')->user()->id)->pluck('contact_id');
            $from_numbers=$customer->contacts()->whereIn('id', $assign_staff_contacts)->get();
            $staff_contacts=[];
            foreach ($from_numbers as $from_number){
                $staff_contacts[]=$from_number->contact_dial_code.$from_number->number;
            }
            $from_numbers=collect($staff_contacts);
        }

        $createdAt = [];
        foreach ($allNumbers as $number) {
            $diffInMinutes = now()->diffInMinutes($number->created_at);
            $createdAt[$number->to] = $diffInMinutes > 60 ? $number->created_at->format('M,d,y h:i A') : $diffInMinutes . " min";
        }

        //TODO::Query For Filter ChatBox
        if($request->staff_id && is_numeric($request->staff_id)) {
            $assign_staff_contacts=AssignStaffContact::where('staff_id', $request->staff_id)->pluck('contact_id');
            $from_numbers=$customer->contacts()->whereIn('id', $assign_staff_contacts)->get();
            $staff_contacts=[];
            foreach ($from_numbers as $from_number){
                $staff_contacts[]=$from_number->contact_dial_code.$from_number->number;
            }
            $from_numbers=collect($staff_contacts);
        }

        //TODO::Filter Data By Campaign
//        if($request->campaign_id && $request->campaign_id !='all') {
//            $allChats = $customer->message_logs()->where('campaign_id', $request->campaign_id)->where(function($q) use ($from_numbers){
//                return $q->whereIn('to', $from_numbers)->orWhereIn('from', $from_numbers);
//            })->orderBy('updated_at')->get(['body', 'to', 'from', 'created_at', 'updated_at', 'campaign_id', 'id']);
//
//            if(!$allChats->isNotEmpty()){
//                return response()->json(['status' => 'success', 'data' => ['numbers' => [], 'page' => 'end']]);
//            }
//        }else{
        $allChats = $customer->message_logs()->whereIn('to', $from_numbers)
            ->orWhereIn('from', $from_numbers)
            ->orderBy('updated_at')
            ->get(['body', 'to', 'from', 'created_at', 'updated_at', 'campaign_id', 'id']);
//        }


        $find_chat = [];
        $find_campaign = [];
        $find_message_id = [];
        foreach ($allChats as $key => $chat) {
            $find_chat[getPhoneNumberWithoutDialCode($chat->to)] = Str::limit($chat->body,50);
            $find_campaign[getPhoneNumberWithoutDialCode($chat->to)] = $chat->campaign_id;
            $find_message_id[getPhoneNumberWithoutDialCode($chat->to)] = $chat->id;
        }
        foreach ($allChats as $key => $chat) {
            $find_chat[getPhoneNumberWithoutDialCode($chat->from)] = Str::limit($chat->body,50);
            $find_campaign[getPhoneNumberWithoutDialCode($chat->from)] = $chat->campaign_id;
            $find_message_id[getPhoneNumberWithoutDialCode($chat->from)] = $chat->id;
        }
//        Created At For New Message
        $find_created_at=[];
        foreach ($allChats as $key => $chat) {
            $find_created_at[$chat->from] = $chat->created_at->format('y-m-d h:i:s');
        }

        //Generate Trash Contact
        $trashContacts=TrashContact::where('customer_id', auth('customer')->user()->id)->with('contact')->get();
        $generateTrashContacts=[];
        foreach ($trashContacts as $trashContact){
            $generateTrashContacts[]=$trashContact->contact->contact_dial_code.$trashContact->contact->number;
        }

        $generateUnreadMessage=[];


        $numbersWithPlusArray = [];
        $numbersWithoutPlusArray = [];
        if($request->trash_contacts && $request->trash_contacts=='trash') {
            foreach ($from_numbers as $number) {
                $totUnread=$customer->message_logs()->where('to', $number)->where('is_read', 'no')->count();
                if (in_array($number, $generateTrashContacts)) {
                    $numbersWithPlusArray[] = '+' . str_replace('+', '', $number);
                    $numbersWithoutPlusArray[] = getPhoneNumberWithoutDialCode($number);
                    $generateUnreadMessage[$number]=$totUnread;
                }
            }
        }else{
            foreach ($from_numbers as $number) {
                $totUnread=$customer->message_logs()->where('to', $number)->where('is_read', 'no')->count();
                if(!in_array($number, $generateTrashContacts)) {
                    $numbersWithPlusArray[] = '+' . str_replace('+', '', $number);
                    $numbersWithoutPlusArray[] = getPhoneNumberWithoutDialCode($number);
                    $generateUnreadMessage[$number]=$totUnread;
                }
            }
        }
        $findContacts = auth('customer')->user()->contacts()->whereIn('number', $numbersWithPlusArray)->orWhereIn('number', $numbersWithoutPlusArray)->orderBy('created_at')->get(['id','number','first_name','last_name','contact_dial_code']);
        $findContact = [];
        $contactIds=[];
        foreach ($findContacts as $contact) {
            $findContact["".getPhoneNumberWithoutDialCode($contact->number).""] = [
                'label' => '',
                'number' => getPhoneNumberWithDialCode($contact->number,$contact->contact_dial_code),
                'color' => '',
                'full_name' => $contact->full_name ?? '',
                'id' => $contact->id ?? ''
            ];
            $contactIds[]=$contact->id;
        }
        $unreadsCount=MessageLog::select('from',DB::raw('count(*) as total'))->whereIn('from', $from_numbers)->where('is_read', 'no')->groupBy('from')->pluck('total','from')->toArray();
        $data = [];

        //TODO::Serialize Flagged Contact
        $flagContacts=FlagContact::whereIn('contact_id', $contactIds)->get();
        $generatedFlagArray=[];
        foreach ($flagContacts as $flagContact){
            $generatedFlagArray[$flagContact->contact_id]=$flagContact;
        }

        //Get All Staffs
        $getStaffName=[];
        $assignedStaffs=AssignStaffContact::where('customer_id', $customer->id)->get();
        foreach ($assignedStaffs as $assignedStaff){
            $gen_number=$assignedStaff->contact->number;
            $getStaffName[$gen_number]=$assignedStaff->staff->fullname;
        }



        foreach ($numbersWithoutPlusArray as $key => $from_number) {
            $data[$key]['full_name'] = isset($findContact[$from_number]) && isset($findContact[$from_number]['full_name']) ? $findContact[$from_number]['full_name'] : '';
            $data[$key]['id'] = isset($findContact[$from_number]) && isset($findContact[$from_number]['id']) ? $findContact[$from_number]['id'] : '';
            $data[$key]['number'] = isset($findContact[$from_number]) && isset($findContact[$from_number]['number']) ? $findContact[$from_number]['number'] : '';
            $data[$key]['created_at'] = isset($createdAt) && isset($createdAt[$from_number]) ? $createdAt[$from_number] : '';
            $data[$key]['label'] = isset($findContact[$from_number]) && isset($findContact[$from_number]['label']) ? $findContact[$from_number]['label'] : '';
            $data[$key]['color'] = isset($findContact[$from_number]) && isset($findContact[$from_number]['color']) ? $findContact[$from_number]['color'] : '';
            $data[$key]['body'] = isset($find_chat[$from_number]) ? $find_chat[$from_number] : '';
            $data[$key]['campaign_id'] = isset($find_campaign[$from_number]) ? $find_campaign[$from_number] : '';
            $data[$key]['message_id'] = isset($find_message_id[$from_number]) ? $find_message_id[$from_number] : '';
            $data[$key]['unread'] = $unreadsCount[$from_number] ?? 0;
            $data[$key]['createdAt'] = isset($find_created_at[$from_number]) ? $find_created_at[$from_number] : '';
            $data[$key]['flagged'] = $generatedFlagArray && isset($findContact[$from_number]) && isset($generatedFlagArray[$findContact[$from_number]]) && isset($generatedFlagArray[$findContact[$from_number]['id']]) ? true : false;
            $data[$key]['total_unread'] = isset($generateUnreadMessage[$from_number])?$generateUnreadMessage[$from_number]:0;
            $data[$key]['assigned_staff_name'] = isset($getStaffName[$from_number])?substr($getStaffName[$from_number], 0, 2):'';
        }

        // $data=collect($data)->sortByDesc('createdAt')->values();
        $labels = collect([]);
        $access_staff_view= auth('customer')->user()->settings()->where('name','view_staff_message')->first();
        if($access_staff_view && isset($access_staff_view->value)){
            $access_staff_view=$access_staff_view->value;
        }

        if ($from_numbers->isNotEmpty()) {
            return response()->json(['status' => 'success', 'data' => ['numbers' => $data,'access_staff_view'=>$access_staff_view, 'labels' => $labels, 'page' => $page_no + 1]]);
        } else {
            return response()->json(['status' => 'success', 'data' => ['numbers' => [], 'page' => 'end']]);
        }
    }

    public function getNewChat(Request $request){
        try {
            $timeD = new Carbon($request->time);
            $time = $timeD->format('y-m-d h:i:s');
            $totalNew = MessageLog::where('customer_id', auth('customer')->user()->id)->where('type', 'inbox')->where('is_read', 'no')->get();
            $counter=0;

            foreach ($totalNew as $count){
                $msgTime=$count->created_at->format('y-m-d h:i:s');
                $msgTime=strtotime($msgTime);
                $time=strtotime($time);

                if ($msgTime > $time){
                    $counter++;
                }
            }


            return response()->json(['status' => 'success', 'data' => count($totalNew)]);
        } catch (\Exception $ex) {
            return response()->json(['status' => 'failed', 'message'=>$ex->getMessage()]);
        }
    }
    public function label_update(Request $request)
    {
        $request->validate([
            'number' => 'required',
            'label' => 'required'
        ]);

        $contact = Contact::where_number($request->number)->first();

        if (!$contact) {
            return response()->json(['status' => 'failed']);
        }
        $label = auth('customer')->user()->labels()->where('id', $request->label)->where('status', 'active')->first();
        if (!$label) {
            return response()->json(['status' => 'failed', 'message' => 'This is not a valid label']);
        }
        $contact->label_id = $label->id;
        $contact->update();
        return response()->json(['status' => 'success', 'message' => 'Label successfully updated']);
    }


    public function get_data(Request $request)
    {
        $customer=auth('customer')->user();

        $no_of_data = 20;
        if($customer->type=='staff') {
            $staffCampaign=AssignStaffContact::where('staff_id', $customer->id)->pluck('campaign_id');
            $chats = MessageLog::whereIn('campaign_id', $staffCampaign)->where(function ($q) use ($request) {
                $q->where('to', $request->number)->orWhere('to', str_replace('+', '', $request->number))->orWhere('to', "+" . str_replace('+', '', $request->number));
            })->orWhere(function ($q) use ($request) {
                $q->where('from', $request->number)->orWhere('from', str_replace('+', '', $request->number))->orWhere('from', "+" . str_replace('+', '', $request->number));
            })->orderByDesc('updated_at')->limit($no_of_data)->get(['id', 'is_read', 'body', 'to', 'from', 'type', 'created_at', 'updated_at', 'status'])->toArray();
        }else{
            $chats = auth('customer')->user()->message_logs()->where(function ($q) use ($request) {
                $q->where('to', $request->number)->orWhere('to', str_replace('+', '', $request->number))->orWhere('to', "+" . str_replace('+', '', $request->number));
            })->orWhere(function ($q) use ($request) {
                $q->where('from', $request->number)->orWhere('from', str_replace('+', '', $request->number))->orWhere('from', "+" . str_replace('+', '', $request->number));
            })->orderByDesc('updated_at')->limit($no_of_data)->get(['id', 'is_read', 'body', 'to', 'from', 'type', 'created_at', 'updated_at', 'status'])->toArray();

        }

        $messageLogIds=[];
        foreach ($chats as $chat){
            if($chat['is_read']=='no'){
                $messageLogIds[]=$chat['id'];
            }
        }
        if($messageLogIds){
            auth('customer')->user()->message_logs()->whereIn('id',$messageLogIds)->update(['is_read'=>'yes']);
        }

        $contact_id = auth('customer')->user()->contacts()->select('id','first_name','last_name','label_id','address')->where('number',  getPhoneNumberWithoutDialCode($request->number))->orWhere('number', str_replace('+', '', $request->number))->orWhere('number', "+" . str_replace('+', '', $request->number))->first();

        if ($contact_id) {
            $address = isset($contact_id->address) ? $contact_id->address : '';
            $exception_cache=cache('exception_'.auth('customer')->id());
            $isException='';
            if($exception_cache){
                $exceptions=json_decode($exception_cache);
                if($exceptions && in_array($contact_id->id,$exceptions)){
                    $isException='true';
                }
            }else{
                $exception_contacts=Exception::where('customer_id',auth('customer')->id())->pluck('contact_id')->toArray();

                if($exception_contacts){
                    cache()->remember('exception_'.auth('customer')->id(), 10800, function () use ($exception_contacts) {
                        return json_encode($exception_contacts);
                    });
                }

            }
            $label = auth('customer')->user()->labels()->select('id','color')->where('id', $contact_id->label_id)->where('status', 'active')->first();
            return response()->json(['status' => 'success', 'data' => ['address' => $address, 'number' => $isException, 'id' => $contact_id->id, 'color' => $label->color ?? '', 'label' => $label->id ?? '', 'name' => $contact_id->full_name, 'messages' => $chats, 'page' => count($chats) < $no_of_data ? 'end' : 2]]);
        }
        return response()->json(['status' => 'success', 'data' => ['id' => null, 'label' => null, 'messages' => $chats, 'page' => count($chats) < $no_of_data ? 'end' : 2]]);
    }

    public function get_chats(Request $request)
    {
        $chats_no = $request->chats;
        if (!$chats_no) abort(404);

        $no_of_data = 20;
        $offset = ($chats_no * $no_of_data) - $no_of_data;

        $chats = auth('customer')->user()->message_logs()->where(function ($q) use ($request) {
            $q->where('to', $request->number)->orWhere('to', str_replace('+', '', $request->number))->orWhere('to', "+" . str_replace('+', '', $request->number));
        })->orWhere(function ($q) use ($request) {
            $q->where('from', $request->number)->orWhere('from', str_replace('+', '', $request->number))->orWhere('from', "+" . str_replace('+', '', $request->number));
        })->orderByDesc('updated_at')->offset($offset)->limit($no_of_data)->get(['body', 'to', 'from', 'type', 'created_at', 'updated_at'])->toArray();

        if ($chats) {
            return response()->json(['status' => 'success', 'data' => ['messages' => $chats, 'page' => count($chats) < $no_of_data ? 'end' : $chats_no + 1]]);
        } else {
            return response()->json(['status' => 'success', 'data' => ['messages' => [], 'page' => 'end']]);
        }
    }

    public function exception(Request $request)
    {
        $label = auth('customer')->user()->labels()->where('title', 'new')->first();
        if (!$label) {
            $label = new Label();
            $label->title = 'new';
            $label->status = 'active';
            $label->customer_id = auth('customer')->user()->id;
            $label->color = 'red';
            $label->save();
        }
        $contact = auth('customer')->user()->contacts()->where('number', $request->number)->orWhere('number', str_replace('+', '', $request->number))->orWhere('number', "+" . str_replace('+', '', $request->number))->first();

        if (!$contact) {
            $contact = new Contact();
            $contact->customer_id = auth('customer')->user()->id;
            $contact->number = $request->number;
            $contact->label_id = $label->id;
            $contact->save();
        }
        if ($request->type == 'add') {
            $exception = new Exception();
            $exception->contact_id = $contact->id;
            $exception->number = $request->number;
            $exception->customer_id = auth('customer')->user()->id;
            $exception->save();
            $exception_contacts=Exception::where('customer_id',auth('customer')->id())->pluck('contact_id')->toArray();
            if($exception_contacts){
                cache()->remember('exception_'.auth('customer')->id(), 10800, function () use ($exception_contacts) {
                    return json_encode($exception_contacts);
                });
            }
            return response()->json(['status' => 'success', 'type' => $request->type]);
        } elseif ($request->type == 'delete') {
            $exception = Exception::where('number', $request->number)->orWhere('number', str_replace('+', '', $request->number))->where('customer_id', auth('customer')->user()->id)->first();
            if ($exception) {
                $exception->delete();
                $exception_contacts=Exception::where('customer_id',auth('customer')->id())->pluck('contact_id')->toArray();
                if($exception_contacts){
                    cache()->remember('exception_'.auth('customer')->id(), 10800, function () use ($exception_contacts) {
                        return json_encode($exception_contacts);
                    });
                }
            }
            return response()->json(['status' => 'success', 'type' => $request->type]);
        } elseif ($request->type == 'block') {
            $exception = Exception::where('number', $request->number)->orWhere('number', str_replace('+', '', $request->number))->where('customer_id', auth('customer')->user()->id)->where('type', 'block')->first();
            if (!$exception) {
                $exception = new Exception();
            }
            $exception->contact_id = $contact->id;
            $exception->number = $request->number;
            $exception->type = 'block';
            $exception->customer_id = auth('customer')->user()->id;
            $exception->save();
            if ($request->ajax()) {
                return response()->json(['status' => 'success', 'type' => $request->type]);
            }
            return redirect()->back()->with(['msg' => 'Contact has been blocked']);
        }

    }

    public function addNewContact(Request $request)
    {
        $label = auth('customer')->user()->labels()->where('id', $request->label)->where('status', 'active')->first();
        if (!$label) {
            return response()->json(['status' => 'failed', 'message' => 'This is not a valid label']);
        }
        $preContact = Contact::where_number($request->number)->first();
        if ($preContact) {
            $preContact->label_id = $label->id;
            $preContact->save();
            return response()->json(['status' => 'success', 'message' => 'Contact Successfully added']);
        }
        $contact = new Contact();
        $contact->customer_id = auth('customer')->user()->id;
        $contact->number = "+" . str_replace('+', '', $request->number);
        $contact->label_id = $label->id;
        $contact->save();
        return response()->json(['status' => 'success', 'message' => 'Contact Successfully added']);
    }

    public function sendContactInfo(Request $request)
    {
        if (!$request->number || !$request->url) {
            return response()->json(['status' => 'failed', 'message' => 'Invalid Request']);
        }
        $contact = Contact::select('first_name', 'last_name', 'number', 'email', 'label_id', 'city', 'state', 'zip_code', 'note', 'address', 'company')
            ->where_number($request->number)->first();
        if (!$contact) {
            return response()->json(['status' => 'failed', 'message' => 'Invalid number']);
        }
        $message_logs = MessageLog::select('to', 'from', 'body', 'type', 'status', 'created_at', 'updated_at')->where('to', $contact->number)->orWhere('from', $contact->number)->orderByDesc('updated_at')->get();

        $contactData = [
            'first_name' => $contact->first_name,
            'last_name' => $contact->last_name,
            'number' => $contact->number,
            'email' => $contact->email,
            'city' => $contact->city,
            'state' => $contact->state,
            'zip_code' => $contact->zip_code,
            'address' => $contact->address,
            'note' => $contact->note,
            'company' => $contact->company,
            'label' => null
        ];
        if (isset($contact->label)) {
            $contactData['label'] = [
                'title' => $contact->label->title,
                'color' => $contact->label->color,
            ];
        }
        $messageData = [];
        foreach ($message_logs as $key => $message_log) {
            $messageData[$key]['from'] = $message_log->from;
            $messageData[$key]['to'] = $message_log->to;
            $messageData[$key]['body'] = $message_log->body;
            $messageData[$key]['type'] = $message_log->type;
            $messageData[$key]['status'] = $message_log->status;
            $messageData[$key]['created_at'] = $message_log->created_at->toDateTimeString();
            $messageData[$key]['updated_at'] = $message_log->updated_at->toDateTimeString();
        }

        $data = [
            'contact' => $contactData,
            'messages' => $messageData,
        ];

        $client = new \GuzzleHttp\Client(['verify' => false]);
        if ($request->url_method == 'post') {
            $client->post($request->url, [
                'form_params' => $data
            ]);
        } else {
            $client->get($request->url, [
                'query' => $data
            ]);
        }
        return response()->json(['status' => 'success']);
    }

    public function findUser(Request $request)
    {
        $page_no = $request->page;
        if (!$page_no) abort(404);

        $no_of_data = 20;
        $offset = ($page_no * $no_of_data) - $no_of_data;

        $contacts = auth('customer')->user()->contacts()->limit($no_of_data)->offset($offset)->select(['first_name', 'last_name', 'id', 'number']);
        if ($request->name) {
            $nameArray = explode(' ', $request->name);
            foreach ($nameArray as $item) {
                $contacts->where(function ($q) use ($item) {
                    $q->orWhere(DB::raw('CONCAT(`first_name`," ",`last_name`)'), 'like', '%' . $item . '%')
                        ->orWhere('number', 'like', '%' . $item . '%');
                });
            }
        }

        $allContacts = [];
        foreach ($contacts->get() as $contact) {
            $allContacts[] = [
                'name' => $contact->fullname ? ucwords($contact->fullname) : $contact->number,
                'number' => $contact->number,
                'id' => $contact->id,
            ];
        }

        return response()->json(['data' => $allContacts, 'status' => 'success']);

    }

    public function deleteFullConversation(Request $request)
    {
        $contact = auth('customer')->user()->contacts()->where('number', $request->number)->orWhere('number', str_replace('+', '', $request->number))->orWhere('number', "+" . str_replace('+', '', $request->number))->firstOrFail();
        auth('customer')->user()->message_logs()->where('from', $contact->number)->orWhere('to', $contact->number)->delete();

        return redirect()->back()->with('success', 'Conversation deleted successfully');
    }
    public function pop_up_message_send(Request $request){

        $request->validate([
            'send_type' => 'required',
            'from_number' => 'required|array',
            'to_numbers' => 'required',
            'message_body' => 'required',
            'from_selected_type' => 'required',
        ]);
        DB::beginTransaction();
        try{
            $current_plan = auth('customer')->user()->plan;
            if (!$current_plan)
                return response()->json(['status'=>'failed', 'message'=>'Customer doesn\'t have any plan right now']);
                $coverages=Coverage::whereIn('id', json_decode($current_plan->coverage_ids))->get();
             $coverage_rate = [];
             if ($coverages) {
                 foreach ($coverages as $coverage) {
                     if ($request->from_selected_type == 'sms') {
                         $coverage_rate[$coverage->country_code] = $coverage->plain_sms;
                     }
                 }
             }
             $to_numbers = [];
             $totalTo = [];
             $totalSmsAmount = 0;
             $to_numbers = explode(",",$request->to_numbers);
             foreach ($to_numbers as $item) {

                $country_code = getCountryDialCode($item);
                if(isset($coverage_rate[str_replace('+','',$country_code)])){
                    $totalTo[] = $item;
                    $totalSmsAmount=$totalSmsAmount + $coverage_rate[str_replace('+','',$country_code)];
                }
            }
            $totalTo = array_unique($totalTo);

            if (count($totalTo) <= 0) {
                return response()->json(['status'=>'failed', 'message'=>'Select at last one number']);
            }

            $allFromNumber=[];
            if ($request->send_type=='number') {
                foreach ($request->from_number as $item) {
                    $number = (array)json_decode($item);
                    if (isset($number['type'])) {
                        if ($number['type'] == 'from') {
                            $allFromNumber[] = $number['number'];
                        }
                    }
                }

                $allNumbers = Number::whereIn('number', $allFromNumber)->get();

                $find_gateway_id=[];
                foreach ($allNumbers as $from_gateway) {
                    $find_gateway_id[$from_gateway->number]=$from_gateway->dynamic_gateway_id;
                }

            }else if($request->send_type=='sender_id') {
                $allFromSenderIds=[];
                foreach ($request->from_number as $item) {
                    $number = (array)json_decode($item);
                    if (isset($number['type'])) {
                        if ($number['type'] == 'from') {
                            $allFromSenderIds[] = $number['id'];
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
            }
            $allFromNumber = array_values(array_unique($allFromNumber));
            $wallet = auth('customer')->user()->wallet()->first();
            $totalCount=1;
            $requestCharacters=$request->message_body;
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
            $totalSmsCredit=$totalToNumbers;
            if($wallet->credit < $totalSmsCredit){
                return response()->json(['status'=>'failed','message'=>'Insufficient Balance']);
            }

            if ($current_plan) {
                $wallet->credit = $wallet->credit - $totalSmsCredit;
                $wallet->save();
            }
            $from = $allFromNumber;
            $to = $totalTo;
            $totalToNumbersCount = 0;
            $generatedToNumbers = [];
            for ($i = 0; $i < count($to); $i += count($from)) {
                for ($j = 0; $j < count($from); $j++) {
                    if (isset($to[$i + $j])) {
                        $generatedToNumbers[$from[$j]][] = trim($to[$i + $j]);
                        $totalToNumbersCount++;
                    }
                }
            }
            $customer = auth('customer')->user();
            $sms_queue = [];
            foreach ($generatedToNumbers as $key => $toNumbers) {

                $dynamic_gateway_id = isset($find_gateway_id[$key]) ? $find_gateway_id[$key] : '';
                if ($dynamic_gateway_id) {
                    $newMessage = new Message();
                    $newMessage->customer_id = $customer->id;
                    $newMessage->body = json_encode($request->message_body);
                    $newMessage->numbers = json_encode(['from' => $key, 'to' => $toNumbers]);
                    $newMessage->sender_type = $request->from_selected_type;
                    $newMessage->type = 'sent';
                    $newMessage->read = 'no';
                    $newMessage->save();

                    foreach($toNumbers as $to_number){

                        $sms_queue[] = [
                            'message_id' => $newMessage->id,
                            'from' => $key,
                            'to' => $to_number,
                            'from_type' => $request->from_selected_type,
                            'body' => $request->message_body,
                            'dynamic_gateway_id' => $dynamic_gateway_id,
                            'created_at' => now(),
                            'updated_at' => now(),
                            'type' => 'sent',
                        ];

                      $contact = Contact::firstOrCreate([
                            'number' => getPhoneNumberWithoutDialCode($to_number),
                            'contact_dial_code' => getCountryDialCode($to_number)
                        ]);

                        $contact->save();

                    }
                }
            }

            auth('customer')->user()->sms_queues()->createMany($sms_queue);
            auth('customer')->user()->message_logs()->createMany($sms_queue);

            DB::commit();
            return response()->json(['status'=>'success','message'=> 'Message sent successfully']);
        }catch(\Exception $ex){
            DB::rollBack();
            return response()->json(['status'=>'failed', 'message'=>$ex->getMessage()]);
         }

    }
}
