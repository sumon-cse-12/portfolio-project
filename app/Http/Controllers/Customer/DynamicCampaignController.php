<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Jobs\CampaignCreateJob;
use App\Jobs\ProcessCampaign;
use App\Models\Campaign;
use App\Models\CreditHistory;
use App\Models\Exception;
use App\Models\FromGroup;
use App\Models\Group;
use App\Models\Message;
use App\Models\Number;
use App\Models\SenderId;
use App\Models\SmsTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Facades\Excel;

class DynamicCampaignController extends Controller
{
    public function index()
    {

        return view('customer.dynamic_campaign.index');
    }
    public function getAll()
    {
        $campaings = auth('customer')->user()->campaings()->where('is_dynamic', 'yes')->orderByDesc('created_at');
        return datatables()->of($campaings)
            ->addColumn('title', function ($q) {
                $sent_sms = $q->sms_queue()->where('schedule_completed', 'yes')->count();
                return $q->title . '(' . $sent_sms . '/' . count($q->sms_queue) . ')';
            })
            ->addColumn('start_date', function ($q) {
                return $q->start_date->format('Y-m-d');
            })
            ->addColumn('end_date', function ($q) {
                return $q->end_date->format('Y-m-d');
            })
            ->addColumn('status', function ($q) {
                $endDate = Carbon::parse($q->end_date->toDateString() . ' ' . $q->end_time);
                $timeDiff = $endDate->diffInMinutes(now(), false);
                if ($timeDiff > 0) {
                    $complete_smsqueue = $q->sms_queue()->where('schedule_completed', 'yes')->count();
                    $smsqueue = $q->sms_queue()->count();
                    if ($complete_smsqueue==$smsqueue) {
                        return ' <button type="button" class="btn light btn-sm btn-primary">Completed</button>';
                    }else{
                        return ' <button type="button" class="btn light btn-sm btn-warning">Incompleted</button>';
                    }
                }

                if ($q->import_fail_message) {
                    return ' <button type="button" class="btn light btn-sm btn-danger">Import Failed</button> <br>' . $q->import_fail_message;
                }

                if ($q->status == 'running') {
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
                    return "<span> <i class=\"fas fa-spinner fa-pulse\"></i> importing</span>";
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

        $data['users_from_groups']=$usersFormGroups;
        $data['users_from_number']=$usersFormNumbers;
        $data['plain_sms']=$current_plan->plain_sms?intval($current_plan->plain_sms, '0'):1;
        return view('customer.dynamic_campaign.create', $data);
    }

    public function import_template(Request $request){
        $request->validate([
            'import_file' => 'required|mimes:csv,txt'
        ]);
        $handle = fopen($request->import_file, "r");
        $fileExtension='';
        if ($request->hasFile('import_file')) {
            $file = $request->file('import_file');
           $fileExtension=$file->getClientOriginalExtension();
        }

        $replace_headers=[];
        if($fileExtension && $fileExtension=='xls'){
            $headers = fgetcsv($handle, 0, "\t");
            // Output each header separately
            foreach ($headers as $header) {
                $replace_headers[]=$header;
            }
        }else{
            $replace_headers = fgetcsv($handle);
        }


        return response()->json(['data'=>$replace_headers,'status'=>'success']);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try{

            if (env("APP_DEMO")){
                return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
            }

            if(!$request->start_date){
                $start_date=now()->format('m/d/Y');
                $request['start_date']=$start_date;
            }

            if(!$request->end_date){
                $end_date=now()->format('m/d/Y');
                $request['end_date']=$end_date;
            }

            if(!$request->start_time){
                $start_time=now()->format('H:s');
                $request['start_time']=$start_time;
            }

            if(!$request->end_time){
                $end_time=now()->addSeconds(10)->format('H:s');
                $request['end_time']=$end_time;
            }

            $request->validate([
                'title' => 'required',
                'start_date' => 'required',
                'template_body' => 'required',
                'end_date' => 'required',
                'start_time' => 'required',
                'from_number' => 'required',
                'end_time' => 'required',
            ]);

            //Manage Message File
            $messageFiles=[];
            if ($request->message_files) {
                foreach ($request->message_files as $key => $file) {
                    $messageFiles[] = $fileName = time() . $key . '.' . $file->extension();
                    $file->move(public_path('uploads/'), $fileName);
                }
            }


            //Manage From
            $allFromNumber=[];
            $allGroupIds=[];
            //TODO::Manage All From Numbers
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


            $allFromNumber = array_values(array_unique($allFromNumber));
            $from=$allFromNumber;

            //Manage Voice obj
            $voice_obj=[];
            if($request->language && $request->voice_type){
                $voice_obj=[
                    'language'=>$request->language,
                    'voice_type'=>$request->voice_type
                ];
            }


            //Dynamic Template File
            $dynamic_template_data = Excel::toArray(new class implements ToCollection,WithCustomCsvSettings {
                public function collection(\Illuminate\Support\Collection $rows)
                {
                    return $rows;
                }
                public function getCsvSettings(): array
                {
                    return [
                        'input_encoding' => 'ISO-8859-1'
                    ];
                }
            }, $request->file('import_file')
            );


            $request_data=$request->except(['import_file','message_files']);


            ProcessCampaign::dispatch(auth('customer')->user(),$dynamic_template_data,$request_data,$messageFiles,$allFromNumber,$from,$voice_obj,$find_gateway_id);

            DB::commit();

            return redirect()->route('customer.dynamic.campaign')->with('success', trans('Dynamic Campaign created successfully'));
        }catch(\Exception $ex){
            DB::rollBack();
            return  redirect()->back()->withErrors(['failed'=>$ex->getMessage()]);
        }

    }


    public function destroy(Campaign $campaign)
    {
        if($campaign->is_dynamic=='yes') {
            if ($campaign->sms_queue) {
                $campaign->sms_queue()->delete();
            }
            if ($campaign->messages) {
                $campaign->messages()->delete();
            }
            $campaign->delete();
            return redirect()->route('customer.dynamic.campaign')->with('success', 'Congratulations ! Campaign successfully deleted');

        }else{
            return abort('404');
        }

    }
}
