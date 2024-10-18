<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Imports\ContactsImport;
use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\Group;
use App\Models\KeywordContact;
use App\Models\Label;
use App\Models\OptOutNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Facades\Excel;

class ContactController extends Controller
{
    public function create()
    {
        $current_plan = auth('customer')->user()->plan;
        $pre_contacts=auth('customer')->user()->contacts()->count();
        if ($current_plan->unlimited_contact=='no' && $current_plan->max_contact <= $pre_contacts){
            return redirect()->route('customer.billing.index')->withErrors(['failed'=>'Your contact limit has been extend']);
        }
        return view('customer.contacts.create');
    }

    public function index()
    {
        return view('customer.contacts.index');
    }

    public function import_contacts()
    {
        $current_plan = auth('customer')->user()->plan;
        $pre_contacts=auth('customer')->user()->contacts()->count();
        if ($current_plan->unlimited_contact=='no' && $current_plan->max_contact <= $pre_contacts){
            return redirect()->route('customer.billing.index')->withErrors(['failed'=>'Your contact limit has been extend']);
        }
        return view('customer.contacts.import_create');
    }

    public function getAll()
    {
        $contacts = auth('customer')->user()->contacts()->select(['id', 'number', 'first_name', 'last_name', 'email', 'company', 'address', 'zip_code', 'city', 'state', 'note','contact_dial_code']);
        return datatables()->of($contacts)
            ->addColumn('action', function ($q) {
                return "<a class='btn btn-sm btn-info' href='" . route('customer.contacts.edit', [$q->id]) . "' title='Edit'><i class='fa fa-pencil-alt'></i></a> &nbsp; &nbsp;" .
                    '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this number?"
                                        data-action=' . route('customer.contacts.destroy', [$q]) . '
                                        data-input={"_method":"delete"}
                                        data-toggle="modal" data-target="#modal-confirm" title="Delete"><i class="fa fa-trash"></i></button>';
            })
            ->addColumn('number', function ($q) {
                return $q->full_number;
            })
            ->addColumn('name', function ($q) {
                return $q->first_name . ' ' . $q->last_name;
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|regex:/^[0-9\-\+]{9,15}$/',
            'contact_dial_code' => 'required'
        ]);

        $current_plan = auth('customer')->user()->plan;
        $pre_contacts=auth('customer')->user()->contacts()->count();
        if ($current_plan->unlimited_contact=='no' && $current_plan->max_contact <= $pre_contacts){
            return redirect()->route('customer.billing.index')->withErrors(['failed'=>'Your contact limit has been extend']);
        }

        $notification = auth('customer')->user()->settings()->where('name', 'email_notification')->first();
        $request['email_notification'] = isset($notification->value)?$notification->value:'';
        $request['number'] = getPhoneNumberWithoutDialCode($request->contact_dial_code.$request->number);
        $label = auth('customer')->user()->labels()->where('title', 'new')->first();
        if (!$label) {
            $label = new Label();
            $label->title = 'new';
            $label->status = 'active';
            $label->customer_id = auth('customer')->user()->id;
            $label->color = 'red';
            $label->save();
        }
        $request['label_id'] = $label->id;
        $request['number']=$request->number;
        auth('customer')->user()->contacts()->create($request->all());

        return back()->with('success', 'Contact successfully added');
    }

    public function edit(Contact $contact)
    {
        $data['contact'] = $contact;
        return view('customer.contacts.edit', $data);
    }

    public function update(Contact $contact, Request $request)
    {
        $request->validate([
            'first_name' => 'required',
        ]);

        $notification = auth('customer')->user()->settings()->where('name', 'email_notification')->first();

        $valid_data = $request->only('first_name', 'last_name', 'email', 'company', 'forward_to', 'forward_to_dial_code', 'address', 'zip_code', 'city', 'state', 'note');
        $valid_data['email_notification'] = isset($notification->value)?$notification->value:'';

        //update the model
        $contact->update($valid_data);

        return back()->with('success', 'Contact successfully updated');
    }

    public function destroy(Contact $contact)
    {
        ContactGroup::where('contact_id', $contact->id)->delete();
        KeywordContact::where('contact_id', $contact->id)->delete();

        $contact->delete();
        return back()->with('success', 'Contact successfully deleted');
    }

    public function import_contacts_show(Request $request)
    {

        if($request->import_contact_csv){
            $request->validate([
                'import_contact_csv' => 'required|mimes:csv,txt'
            ]);
            $import_contact_data = Excel::toArray(new class implements ToCollection,WithCustomCsvSettings {
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
            }, $request->file('import_contact_csv')
            );
            $all_headers = $import_contact_data[0][0];
            $import_contact_contact_array = [];
            unset($import_contact_data[0][0]);
            foreach (array_slice($import_contact_data[0],0,11) as $data) {
                $only_number=str_replace('+','',$data[1]);
                $only_number=str_replace('_','',$only_number);
                $only_number=str_replace('-','',$only_number);

                $fullNumber="+".str_replace('+','',$data[0]).$only_number;
                $import_contact_contact_array[] = [
                    'country_code' => getCountryDialCode($fullNumber),
                    'number' => getPhoneNumberWithoutDialCode($fullNumber),
                    'first_name' => $data[2],
                    'last_name' => $data[3],
                    'email' => $data[4],
                    'address' => $data[5],
                    'city' => $data[6],
                    'state' => $data[7],
                    'zip_code' => $data[8],
                    'company' => $data[9],
                    'note' => $data[10],
                    'full_name' => $data[2] . '&nbsp;' . $data[3],
                ];
            }
            return response()->json(['status' => 'success', 'data' => $import_contact_contact_array,'headers'=>$all_headers]);

        }else{
            $all_contact_number = [];
            $contact_numbers = explode(",",$request->form_contact_numbers);

            foreach($contact_numbers as $contact_number){

                $contact_number_with_plus_one = substr($contact_number, 0, 1);
                if($contact_number_with_plus_one=='+'){
                    $all_contact_number [] = $contact_number;
                }else{
                    $all_contact_number [] = '+1'.$contact_number;
                }

            }
            $all_contact = array_unique($all_contact_number);

            if (count($all_contact) <= 0) {
                return response()->json(['status'=>'failed', 'message'=>'Select at last one number']);
            }
            $import_all_contact = [];
            foreach ($all_contact as $data) {
                $import_all_contact[] = [
                    'country_code' => getCountryDialCode($data),
                    'number' => getPhoneNumberWithoutDialCode($data),
                ];
            }
            return response()->json(['status' => 'success', 'data' => $import_all_contact,'type'=>'contact_paste']);
        }

    }

    public function import_contacts_store(Request $request)
    {

        if (env("APP_DEMO")){
            return redirect()->back()->withErrors(['msg' => trans('admin.app_demo_message')]);
        }
        $request->validate([
            'import_name' => 'required',
//            'import_contact_csv' => 'required|mimes:csv,txt'
        ]);

        $preGroup = auth('customer')->user()->groups()->where('name', $request->import_name)->first();
        if ($preGroup) return back()->withErrors(['msg' => "Import name already exists"]);

        $customRequestData=$request->only('country_code','number','first_name','last_name','email','address',
            'city','zip_code','company','note','add_default_country_code');

        $importContact = new Group();
        $importContact->customer_id = auth('customer')->id();
        $importContact->name =  $request->import_name;
        $importContact->save();
        $imploded_header = implode(",",$request->input_headers);
        $all_headers = explode(",",$imploded_header);

        if($request->import_contact_csv) {
            $import_contact_data = Excel::toArray(new class implements ToCollection, WithCustomCsvSettings {
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
            }, $request->file('import_contact_csv')
            );
        }

        if($request->paste_contacts) {
            $request_all_numbers = '';
            $seperate_type = 'comma';
            if ($request->seperate_type == 'semiclone') {
                $request_all_numbers = explode(';', $request->paste_contacts);
            } else if ($request->seperate_type == 'bar') {
                $request_all_numbers = explode('|', $request->paste_contacts);
            } else if ($request->seperate_type == 'tab') {
                $request_all_numbers = explode('  ', $request->paste_contacts);
            } else if ($request->seperate_type == 'new_line') {
                $request_all_numbers = explode('  ', $request->paste_contacts);
            } else if ($request->seperate_type == 'comma') {
                $request_all_numbers = explode(',', $request->paste_contacts);
            }
            $request_all_contacts = array_unique($request_all_numbers);
            if (!is_array($request_all_contacts)) {
                return back()->withErrors(['msg' => "Invalid Number Format"]);
            }
            $label= auth('customer')->user()->labels()->where('title', 'new')->first();
            if (!$label) {
                $label = new Label();
                $label->title = 'new';
                $label->status = 'active';
                $label->customer_id = auth('customer')->user()->id;
                $label->color = 'red';
                $label->save();
            }

            $genContactGroupData=[];
            foreach ($request_all_contacts as $request_all_contact) {
                $contactData=[
                    'contact_dial_code'=>getCountryDialCode($request_all_contact),
                    'number'=>getPhoneNumberWithoutDialCode($request_all_contact),
                    'label_id'=>$label->id
                ];

                $contact = auth('customer')->user()->contacts()->create($contactData);

                $genContactGroupData[] = [
                    'customer_id' => auth('customer')->user()->id,
                    'group_id' => $importContact->id,
                    'contact_id' => $contact->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ];

            }

            ContactGroup::insert($genContactGroupData);

        }




        if ($request->hasFile('import_contact_csv')) {
            $data = $request->file('import_contact_csv');
            $fileName=$importContact->id . '.' . $data->getClientOriginalExtension();
            $data->move(public_path().'/uploads',$fileName);
            $file_url = public_path().'/uploads/' .$fileName;
            try{
//                Excel::import(new ContactsImport($importContact->id,auth('customer')->user(),$grp_id=null,$customRequestData), $file_url);
                (new ContactsImport($importContact->id,auth('customer')->user(),$grp_id=null,$customRequestData))->import($file_url);
            }catch (\Exception $ex){
                if(isset($ex->validator)){
                    return redirect()->back()->withErrors($ex->validator->errors());
                } else {
                    return redirect()->back()->withErrors(['msg' => $ex->getMessage()]);
                }

            }

        }

        return back()->with('success', 'Import Contact Successfully Created');
    }

    public function search(Request $request)
    {
        $contacts = auth('customer')->user()->contacts();
        $contactsForCount=auth('customer')->user()->contacts();
        if ($request->ajax())
        {
            $page = $request->page;
            $resultCount = 25;

            $offset = ($page - 1) * $resultCount;

            if ($request->search) {
                $contacts->where('number', 'like', "%" . $request->search . "%")
                    ->orWhere('first_name', 'like', "%" . $request->search . "%")
                    ->orWhere('zip_code', 'like', "%" . $request->search . "%")
                    ->orWhere('address', 'like', "%" . $request->search . "%")
                    ->orWhere('last_name', 'like', "%" . $request->search . "%");

                $contactsForCount->where('number', 'like', "%" . $request->search . "%")
                    ->orWhere('first_name', 'like', "%" . $request->search . "%")
                    ->orWhere('zip_code', 'like', "%" . $request->search . "%")
                    ->orWhere('address', 'like', "%" . $request->search . "%")
                    ->orWhere('last_name', 'like', "%" . $request->search . "%");
            }

            $results=$contacts->skip($offset)->take($resultCount)->get();
            $count = $contactsForCount->count();
            $endCount = $offset + $resultCount;
            $morePages = $count > $endCount;
            $finalResults = [];
            foreach ($results as $contact) {
                $finalResults[] = [
                    'id' => $contact->id,
                    'text' => $contact->number.' '.($contact->first_name?'('.$contact->first_name.' '.$contact->last_name.')':'')
                ];
            }
            $results = array(
                "results" => $finalResults,
                "pagination" => array(
                    "more" => $morePages
                )
            );

            return response()->json($results);
        }
    }


    public function otpNumbers(){

        return view('customer.contacts.opt_out_number');
    }
    public function getAllOtpNumbers()
    {
        $numbers=OptOutNumber::where('customer_id', auth()->user('customer')->id)->pluck('contact_id');
        $contacts=auth()->user('customer')->contacts()->whereIn('id', $numbers);

        return datatables()->of($contacts)
            ->addColumn('number', function ($q) {
                return $q->full_number;
            })
            ->addColumn('name', function ($q) {
                return $q->first_name . ' ' . $q->last_name;
            })
            ->toJson();
    }

    public function exportOtpNumbers(){
        $numbers=OptOutNumber::where('customer_id', auth()->user('customer')->id)->pluck('contact_id');
        $contacts=auth()->user('customer')->contacts()->whereIn('id', $numbers)->get();
        if(count($contacts) <= 0){
            return redirect()->back()->withErrors(['failed'=>'No Data Available']);
        }

        $random=Str::random(4);
        $fileName = 'Opt-out-number'.$random.'.csv';
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        $columns = array('contact_dial_code','number','first_name','last_name','email','address','city','state','zip_code','company','note');
        $callback = function() use($contacts, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($contacts as $contact){
                $row['country_code']  =$contact->contact_dial_code;
                $row['number'] = $contact->number;
                $row['first_name']    = $contact->first_name;
                $row['last_name']    = $contact->last_name;
                $row['email']  = $contact->email;
                $row['address']  = $contact->address;
                $row['city']  = $contact->city;
                $row['state']  = $contact->state;
                $row['zip_code']  = $contact->zip_code;
                $row['company']  = $contact->company;
                $row['note']  = $contact->note;
                fputcsv($file, array($row['country_code'],$row['number'],$row['first_name'],$row['last_name'],$row['email'],$row['address'], $row['city'],
                    $row['state'],$row['zip_code'],$row['company'],$row['note']));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

}
