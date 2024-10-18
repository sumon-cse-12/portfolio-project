<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Imports\ContactsImport;
use App\Jobs\ListBuilderQueue;
use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\Exception;
use App\Models\Group;
use App\Models\MessageLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class GroupController extends Controller
{
    public function index()
    {
        $data['labels']=auth('customer')->user()->labels()->where('status','active')->get();

        return view('customer.groups.index', $data);
    }


    public function getAll()
    {
        $customers = auth('customer')->user()->groups()->select(['id', 'name', 'status', 'created_at','import_status','import_fail_message']);
        return datatables()->of($customers)
            ->addColumn('created_at', function ($q) {
                return $q->created_at->format('d-m-Y');
            })
            ->addColumn('bulk_delete', function ($q) {
                return "<input class='groups' type='checkbox' name='group_ids[]' value='".$q->id."'> ";
            })
            ->addColumn('contacts', function ($q) {
                $c = [];
                foreach ($q->limited_contacts as $contact) {
                    $c[] = trim($contact->contact->number);
                }
                $count=count($c);
                $text=$count>=100?' and more '.($q->contacts()->count()-$count):'';
                return "<div class='show-more' style='max-width: 500px;white-space: pre-wrap'>" . implode(", ", $c).$text. " </div>";
            })
            ->addColumn('status',function ($q){
                if($q->import_status=='failed'){
                    $status = "<span class='btn btn-xs btn-danger'>Failed</span><br> <div class='show-more' style='max-width: 500px;white-space: pre-wrap'>$q->import_fail_message </div>";
                }else{
                    if($q->status=='active') {
                        $status= '<strong class="text-white bg-success px-2 py-1 rounded status-font-size"> '.ucfirst($q->status).' </strong>';
                    }else{
                        $status= '<strong class="text-white bg-danger px-2 py-1 rounded status-font-size"> '.ucfirst($q->status).' </strong>';
                    }
                }
                return $status;
            })
            ->addColumn('action', function ($q) {
                if($q->import_status=='running'){
                    return "<span data-id='".$q->id."' class='importing'> <i class=\"fas fa-spinner fa-pulse\"></i> importing</span>";
                }

                return "<a class='btn btn-sm btn-primary export_group_contact' data-id='$q->id' href='#' title='Export'><i class='fa fa-file-export'></i></a> &nbsp; &nbsp;"."<a class='btn btn-sm btn-info' href='" . route('customer.groups.edit', [$q->id]) . "' title='Edit'><i class='fa fa-pencil-alt'></i></a> &nbsp; &nbsp;" .
                    '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this group? <br><span class=\'text-danger text-sm\'>The contacts assigned to this group won\'t be deleted</span></br>"
                                        data-action=' . route('customer.groups.destroy', [$q]) . '
                                        data-input={"_method":"delete"}
                                        data-toggle="modal" data-target="#modal-confirm" title="Delete"><i class="fa fa-trash"></i></button>';
            })
            ->rawColumns(['bulk_delete','action', 'contacts','status'])
            ->toJson();
    }

    public function exportContact(Request $request){
        $id = $request->id;
        $group = auth('customer')->user()->groups()->where('id', $id)->first();
        if (!$group){
            return abort(404);
        }
        $labelIds=auth('customer')->user()->labels()->whereIn('id', $request->label)->pluck('id');
        $contactIds = $group->contacts->pluck('contact_id');
        $contacts = Contact::whereIn('id', $contactIds)->whereIn('label_id', $labelIds)->get();

        $fileName = strtolower(str_replace(' ','_',$group->name)).'.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        $columns = array('number','first_name','last_name','email','address','city','state','zip_code','company','note');
        $callback = function() use($contacts, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($contacts as $contact){
                $row['number']  = $contact->number;
                $row['first_name']    = $contact->first_name;
                $row['last_name']    = $contact->last_name;
                $row['email']  = $contact->email;
                $row['address']  = $contact->address;
                $row['city']  = $contact->city;
                $row['state']  = $contact->state;
                $row['zip_code']  = $contact->zip_code;
                $row['company']  = $contact->company;
                $row['note']  = $contact->note;
                fputcsv($file, array($row['number'],$row['first_name'],$row['last_name'],$row['email'],$row['address'], $row['city'],
                    $row['state'],$row['zip_code'],$row['company'],$row['note']));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
    public function create()
    {
        $current_plan = auth('customer')->user()->plan;
        $pre_contact_groups=auth('customer')->user()->groups()->count();
        if ($current_plan->unlimited_contact_group=='no' && $current_plan->contact_group_limit <= $pre_contact_groups){
            return redirect()->route('customer.billing.index')->withErrors(['failed'=>'Your contact group limit has been extend']);
        }
        return view('customer.groups.create');
    }

    public function store(Request $request)
    {
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }
        $request->validate([
            'name' => 'required',
            'status' => 'required|in:active,inactive',
            'contact_csv'=>'mimes:csv,txt'
        ]);

        $preGroup = auth('customer')->user()->groups()->where('name', $request->name)->first();
        if ($preGroup) return back()->withErrors(['msg' => "Group name already exists"]);


        $current_plan = auth('customer')->user()->plan;
        $pre_contact_groups=auth('customer')->user()->groups()->count();
        if ($current_plan->unlimited_contact_group=='no' && $current_plan->contact_group_limit <= $pre_contact_groups){
            return redirect()->route('customer.billing.index')->withErrors(['failed'=>'Your contact group limit has been extend']);
        }

        $group = new Group();
        $group->customer_id = auth('customer')->id();
        $group->name = $request->name;
        $group->status = $request->status;
        $group->save();
        $contactArray = [];
        if (isset($request->contact_ids)) {
            foreach ($request->contact_ids as $contact_id) {
                $contactArray[] = [
                    'contact_id' => $contact_id,
                    'customer_id' => $group->customer_id,
                    'group_id' => $group->id,
                ];
            }
        }

        $group->contacts()->insert($contactArray);

        if ($request->hasFile('contact_csv')) {
            $data = $request->file('contact_csv');
            $fileName=$group->id . '.' . $data->getClientOriginalExtension();
            $data->move(public_path().'/uploads/',$fileName);
            //You can choose to validate file type. e.g csv,xls,xlsx.
            $file_url = public_path() .'/uploads/'.$fileName;
            try{
                Excel::import(new ContactsImport($group->id,auth('customer')->user()), $file_url);
            }catch (\Exception $ex){
                if(isset($ex->validator)){
                    return redirect()->back()->withErrors($ex->validator->errors());
                }else{
                    return redirect()->back()->withErrors(['msg'=>$ex->getMessage()]);
                }

            }
        }

        return redirect()->route('customer.groups.index')->with('success', 'Group successfully created');
    }

    public function edit(Group $group)
    {
        $data['group'] = $group;
        $groupContacts=$group->contacts()->with('contact')->limit(200)->get();
        $groupContactIds = [];
        foreach ($groupContacts as $c) {
            $groupContactIds[] = [
                'id'=>trim($c->contact_id),
                'text'=>$c->contact->number.' '.($c->contact->first_name?'('.$c->contact->first_name.' '.$c->contact->last_name.')':'')
            ];
        }
        $data['groupContactIds'] = $groupContactIds;
        return view('customer.groups.edit', $data);
    }

    public function update(Group $group, Request $request)
    {
//        dd($request->all());

        $request->validate([
            'name' => 'required|unique:groups,name,' . $group->id,
            'status' => 'required|in:active,inactive'
        ]);

        $valid_data = $request->only('name', 'status');

        //update the model
        $group->update($valid_data);

//        if(!isset($request->is_contact_not_editable)){
//            $group->contacts()->delete();
//        }


        $pre_group_contacts=$group->contacts()->with('contact')->pluck('contact_id');
        $this_group_contacts=Contact::whereIn('id', $pre_group_contacts)->pluck('number');


        if ($request->hasFile('contact_csv')) {
            $data = $request->file('contact_csv');
            $fileName=$group->id . '.' . $data->getClientOriginalExtension();
            $data->move(public_path().'/uploads/',$fileName);
            //You can choose to validate file type. e.g csv,xls,xlsx.
            $file_url = public_path() .'/uploads/'.$fileName;
            Excel::import(new ContactsImport($group->id,auth('customer')->user(),$this_group_contacts), $file_url);
        }


//        if (isset($request->contact_ids)) {
//            $contactArray = [];
//            foreach ($request->contact_ids as $contact_id) {
//                $contactArray[] = [
//                    'contact_id' => $contact_id,
//                    'customer_id' => $group->customer_id,
//                    'group_id' => $group->id,
//                ];
//            }
//            $group->contacts()->insert($contactArray);
//        }


        return back()->with('success', 'Group successfully updated');
    }

    public function destroy(Group $group)
    {
        $contacts = $group->contacts()->pluck('contact_id');
        foreach ($contacts->chunk(10000) as $items){
            ContactGroup::whereIn('contact_id', $items)->where('group_id', $group->id)->delete();
        }
        $group->delete();

        return back()->with('success', 'Group successfully deleted');
    }

    public function groupRecords()
    {
        $data['groups'] = auth('customer')->user()->groups;
        $data['labels'] = auth('customer')->user()->labels;
        return view('customer.group_records.index', $data);
    }

    public function filteredRecord(Request $request)
    {
        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }
        $group_ids=json_decode($request->group_ids);
        $data['previous_url'] ='#';
        $data['next_url'] = '#';
        $page = $request->page??1;
        $resultCount = 2000;

        $offset = ($page - 1) * $resultCount;

        $customer = auth('customer')->user();
        if (!$group_ids){
            return  redirect()->back()->withErrors(['failed'=>'Please select at last one group']);
        }

        $contacts=ContactGroup::where('contact_groups.customer_id',$customer->id)
            ->select('contact_groups.contact_id','contact_groups.customer_id','contacts.first_name','contacts.label_id','contacts.last_name','contacts.city','contacts.address','contacts.number','contacts.state','contacts.zip_code','contacts.email')
            ->whereIn('contact_groups.group_id',$group_ids)
            ->skip($offset)
            ->take($resultCount)
            ->groupBy('contacts.number')
            ->join('contacts', 'contacts.id', '=', 'contact_groups.contact_id');

        $data['total_result_count']=$count=ContactGroup::where('customer_id',$customer->id)
            ->whereIn('group_id',$group_ids)
            ->count();
        $endCount = $offset + $resultCount;
        $data['morePages']=$morePages = $count > $endCount;



        if ($request->first_name && $request->first_name_type == '=') {
            $contacts->where('first_name', $request->first_name);
        }elseif($request->first_name && $request->first_name_type == '!='){
            $contacts->where('first_name','!=', $request->first_name);
        }

        if ($request->last_name && $request->last_name_type == '=') {
            $contacts->where('last_name', $request->last_name);
        }elseif($request->last_name && $request->last_name_type == '!='){
            $contacts->where('last_name','!=', $request->last_name);
        }

        if ($request->phone_number && $request->phone_number_type == '=') {
            $contacts->where('number', $request->phone_number);
        }elseif($request->phone_number && $request->phone_number_type == '!='){
            $contacts->where('number','!=', $request->phone_number);
        }
        if ($request->address && $request->address_type == '=') {
            $contacts->where('address', $request->address);
        }elseif($request->address && $request->address_type == '!='){
            $contacts->where('address','!=', $request->address);
        }
        if ($request->city && $request->city_type == '=') {
            $contacts->where('city', $request->city);
        }elseif($request->city && $request->city_type == '!='){
            $contacts->where('city','!=', $request->city);
        }
        if ($request->state && $request->state_type == '=') {
            $contacts->where('state', $request->state);
        }elseif($request->state && $request->state_type == '!='){
            $contacts->where('state','!=', $request->state);
        }
        if ($request->zip_code && $request->zip_code_type == '=') {
            $contacts->where('zip_code', $request->state);
        }elseif($request->zip_code && $request->zip_code_type == '!='){
            $contacts->where('zip_code','!=', $request->state);
        }
        if ($request->email && $request->email_type == '=') {
            $contacts->where('email', $request->email);
        }elseif($request->email && $request->email_type == '!='){
            $contacts->where('email','!=', $request->email);
        }
        if ($request->label){
            $contacts->where('label_id', $request->label);
        }
        $contactTo = $contacts->pluck('number');

        $sent_messages = MessageLog::where('type', 'sent')->where('status','!=','pending')->whereIn('to', $contactTo);
        if ($request->sent_type == 'older_than') {
            $sent_messages->whereDate('created_at', '<=', Carbon::now()->subDays($request->sms_sent_days));
        }
        if ($request->sent_type == 'within') {
            $sent_messages->whereBetween('created_at', [Carbon::now()->subDays($request->sms_sent_days)->toDateString(), Carbon::now()->addDay()->toDateString()]);
        }
        if ($request->sent_type == 'between') {
            $sent_messages->whereBetween('created_at', [Carbon::parse($request->between_from)->toDateString(), Carbon::parse($request->between_to)->toDateString()]);
        }
        if ($request->sent_type == 'empty'){
            $sent_messages=$sent_messages->get();
            $already_sent_to=$sent_messages->pluck('to');
            $toContactResult=$contactTo->filter(function ($value, $key) use ($already_sent_to) {
                return ! ($already_sent_to->contains($value));
            });
        }else{
            $sent_messages=$sent_messages->get();
            $toContactResult=$sent_messages->pluck('to');
        }
        $inbox_messages = MessageLog::where('type', 'inbox')->whereIn('from', $toContactResult);

        if ($request->sms_received_type == 'older_than') {
            $inbox_messages->whereDate('created_at', '<=', Carbon::now()->subDays($request->sms_received_days));
        }

        if ($request->sms_received_type == 'within') {
            $inbox_messages->whereBetween('created_at', [Carbon::now()->subDays($request->sms_received_days)->toDateString(), Carbon::now()->addDay()->toDateString()]);
        }

        if ($request->sms_received_type == 'between') {
            $inbox_messages->whereBetween('created_at', [Carbon::parse($request->sms_received_between_from)->toDateString(), Carbon::parse($request->sms_received_between_to)->toDateString()]);
        }

        if ($request->sms_received_type == 'empty') {
            $inbox_messages=$inbox_messages->get();
            $already_received_from=$inbox_messages->pluck('from');
            $toContactResult=$toContactResult->filter(function ($value, $key) use ($already_received_from) {
                return ! ($already_received_from->contains($value));
            });
        }else{
            $inbox_messages=$inbox_messages->get();
            $toContactResult=$inbox_messages->pluck('from');
        }
        $all_contacts = $contacts->whereIn('number',$toContactResult)->get();

        if(count($all_contacts)>0 && $page>1){
            $data['previous_url'] = url()->current().'?'.(http_build_query($request->except('page')) . '&page=') . ($page-1);
        }

        if($morePages){
            $data['next_url'] = url()->current().'?'.(http_build_query($request->except('page')) . '&page=') . ($page+1);
        }

        $contactsList = new Collection();
        foreach ($all_contacts as $contact) {
            $sentMessage=null;
            $inMessage=null;

            if ($request->sent_type != 'empty') {
                $sentMessage=$sent_messages->filter(function ($item) use ($contact) {
                    return strpos(" ".$item->to,$contact->number) !== false;
                })->first();
            }
            if($request->sms_received_type != 'empty'){
                $inMessage=$inbox_messages->filter(function ($item) use ($contact) {
                    return strpos(" ".$item->from,$contact->number) !== false;
                })->first();
            }
            if($sentMessage && $inMessage){
                $contact['received_sms_date'] = $inMessage->created_at;
                $contact['delivered_at'] = $sentMessage->created_at;
            }elseif ($sentMessage){
                $contact['delivered_at'] = $sentMessage->created_at;
            }elseif ($inMessage){
                $contact['received_sms_date'] = $inMessage->created_at;
            }
            if(!$contactsList->contains($contact)){
                $contactsList->push($contact);
            }
        }

        $data['contacts'] = $contactsList;
        $data['contact_ids'] = $contactsList->pluck('id');
        $data['filter_data']=$request->all();

        return view('customer.group_records.filtered_data', $data);
    }

    public function newGroup(Request $request){
        $preGroup = auth('customer')->user()->groups()->where('name', $request->name)->first();
        if ($preGroup)
            return  response()->json(['status'=>'failed','message'=>'Group name already exists']);

        $group = new Group();
        $group->customer_id = auth('customer')->id();
        $group->name = $request->name;
        $group->import_status = 'running';
        $group->status = 'active';
        $group->save();

        unset($request['page']);
        ListBuilderQueue::dispatch(json_decode($request->filter),$group,auth('customer')->user());
        return  response()->json(['status'=>'success','message'=>'Congratulations ! Group created successfully']);
    }

    public function getAllNumbers(Request $request){
        $request->validate([
            'id'=>'required',
        ]);

        $totalCount=1;

        $group=Group::where('id',$request->id)->with('contacts.contact')->first();
        if(!$group) return response()->json(['status'=>'failed','message'=>'Group not found']);

        $groupNumbers=[];

        foreach ($group->contacts as $contact){
            if (isset($contact->contact) && isset($contact->contact->number) && $contact->contact->number){
                $groupNumbers[]=$contact->contact->number;
            }
        }
        $contact = Contact::where('customer_id',auth('customer')->id())->whereIn('number',$groupNumbers)->pluck('number');
        $number = $contact->count();
        $totalSms = $totalCount*$number;
        return response()->json(['status'=>'success','data'=>['groupNumbers'=>$groupNumbers,'totalSms'=>$totalSms,'totalNumber'=> $number]]);



    }

    public function bulkDelete(Request $request){
        $user= auth('customer')->user();
        $ids= $request->ids;
        if (!$ids){
            return response()->json(['status'=>'failed', 'message'=>'Please select at last one group']);
        }
        $groups= $user->groups()->whereIn('id', $ids)->get();
        foreach ($groups as $group){
            $group->delete();
            ContactGroup::where('group_id', $group->id)->delete();
        }
        return response()->json(['status'=>'success', 'message'=>'Successfully deleted selected group']);
    }
    public function checkImportStatus(Request $request){
        $request_ids=json_decode($request->ids);
        if (!$request_ids || count($request_ids) <= 0){
            return response()->json(['status'=>'failed']);
        }

        $hasChanged=auth('customer')->user()->groups()->whereIn('id', $request_ids)->where('import_status','completed')->count();

        return response()->json(['status'=>'success', 'data'=>$hasChanged]);
    }
}
