<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\FromGroup;
use App\Models\FromGroupNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class FromGroupController extends Controller
{
    public function index(){
//        Artisan::call('queue:work');

        return view('customer.from_group.index');
    }
    public function show(){
        $customers = auth('customer')->user()->from_groups()->select(['id', 'name', 'status']);
        return datatables()->of($customers)
            ->addColumn('numbers', function ($q) {
                $c = [];
                $from_numbers = FromGroupNumber::where('group_id', $q->id)->get();
                foreach ($from_numbers as $contact) {
                    $c[] = trim($contact->number);
                }
                $count=count($c);
                $text=$count>=100?' and more '.($q->from_group_numbers()->count()-$count):'';
                return "<div class='show-more' style='max-width: 500px;white-space: pre-wrap'>" . implode(", ", $c).$text. " </div>";
            })
            ->addColumn('status',function($q){
                if($q->status=='active') {
                    $status= '<strong class="text-white bg-success px-2 py-1 rounded status-font-size"> '.ucfirst($q->status).' </strong>';
                }else{
                    $status= '<strong class="text-white bg-danger px-2 py-1 rounded status-font-size"> '.ucfirst($q->status).' </strong>';
                }
                return $status;
            })
            ->addColumn('action', function ($q) {

                return "<a class='btn btn-sm btn-info' href='" . route('customer.from-group.edit', [$q]) . "' title='Edit'><i class='fa fa-pencil-alt'></i></a> &nbsp; &nbsp;" .
                    '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this from-group? <br><span class=\'text-danger text-sm\'>This will delete all the from numbers assigned to this group</span></br>"
                                        data-action=' . route('customer.from-group.destroy', [$q]) . '
                                        data-input={"_method":"delete"}
                                        data-toggle="modal" data-target="#modal-confirm" title="Delete"><i class="fa fa-trash"></i></button>';
            })
            ->rawColumns(['action', 'numbers','status'])
            ->toJson();
    }
    public function create(){
        $all_numbers=[];
       $numbers= auth('customer')->user()->numbers()->where('expire_date','>', now())->get();
       foreach ($numbers as $number){
           if($number->sms_capability && $number->sms_capabili=='yes') {
               $all_numbers['sms_numbers'] = [
                   $number
               ];
           }else if($number->mms_capability && $number->mms_capability=='yes') {
               $all_numbers['mms_numbers'] = [
                   $number
               ];
           }else if($number->voice_capability && $number->voice_capability=='yes') {
               $all_numbers['voice_numbers'] = [
                   $number
               ];
           }else if($number->whatsapp_capability && $number->whatsapp_capability=='yes') {
               $all_numbers['whatsapp_numbers'] = [
                   $number
               ];
           }
       }

       $data['numbers']=$all_numbers;
        return view('customer.from_group.create', $data);
    }

    public function store(Request $request){

        $request->validate([
            'name' => 'required|unique:from_groups,name',
            'status' => 'required|in:active,inactive',
            'type' => 'required|in:sender_id,mms,voice,number',
        ]);

        $from_number=[];
        if ($request->type=='mms'){
            $from_number=$request->mms_numbers;
        }else if ($request->type=='sender_id'){
            $from_number=$request->mms_numbers;
        }else if ($request->type=='whatsapp'){
            $from_number=$request->whatsapp_numbers;
        }else if ($request->type=='voice'){
            $from_number=$request->voice_numbers;
        }else{
            $from_number=$request->from_numbers;
        }
        if (!$from_number || count($from_number) < 1){
            return  redirect()->back()->withErrors(['failed'=> trans('customer.messages.al_last_one_number_need')]);
        }
        $from_group= auth('customer')->user()->from_groups()->create($request->only('name','status','type'));
        if ($from_number){
            foreach ($from_number as $number){
                if($number) {
                    $group_number = new FromGroupNumber();
                    $group_number->group_id = $from_group->id;
                    $group_number->number = $number;
                    $group_number->save();
                }
            }
        }

        return redirect()->route('customer.from-group.index')->with('success', trans('customer.messages.from_group_created'));
    }

    public function edit(FromGroup $from_group){
        $data['group'] = $from_group;
        $from_group_number = $from_group->from_group_numbers()->pluck('number');
        $data['from_group_numbers'] = json_decode($from_group_number);
        return view('customer.from_group.edit', $data);
    }

    public function update(FromGroup $from_group, Request $request){
        $request->validate([
            'name' => 'required|unique:from_groups,name,'.$from_group->id,
            'status' => 'required|in:active,inactive',
            'type' => 'required|in:sender_id,mms,voice,number',
        ]);
        $from_group->update($request->all());

        if (!$request->from_numbers || count($request->from_numbers) < 1){
            return  redirect()->back()->withErrors(['failed'=> trans('customer.messages.al_last_one_number_need')]);
        }

        if ($request->from_numbers){
            foreach ($request->from_numbers as $number){
                $group_number = $from_group->id?FromGroupNumber::where('group_id',$from_group->id)->first():new FromGroupNumber();
                $group_number->group_id=$from_group->id;
                $group_number->number= $number;
                $group_number->save();
            }
        }
        return redirect()->route('customer.from-group.index')->with('success', trans('customer.messages.from_group_updated'));
    }

    public function destroy(FromGroup $from_group){
        $campaign = Campaign::where('from_group_id', $from_group->id)->first();
        if ($campaign) {
            return redirect()->back()->withErrors(['failed' => trans('customer.messages.from_group_used')]);
        }
        if ($from_group->from_group_numbers()){
            $from_group->from_group_numbers()->delete();
        }
        $from_group->delete();
        return redirect()->route('customer.from-group.index')->with('success', trans('customer.messages.from_group_deleted'));
    }
}
