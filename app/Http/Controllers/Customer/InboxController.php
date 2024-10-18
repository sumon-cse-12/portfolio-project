<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    public function index()
    {
        $data['messages'] =$messages = auth('customer')->user()->receive_messages()->paginate(10);
        $contactNumbers = [];
        $contactDetails = [];
        foreach ($messages as $message){
            $contactNumbers[]=$message->formatted_number_from;
        }

        $contacts = Contact::whereIn('number', $contactNumbers)->get();

        foreach ($contacts as $contact){
            $contactDetails[$contact->number]=$contact->address;
        }
        $data['contact_address']= $contactDetails;
        return view('customer.smsbox.inbox', $data);
    }

    public function changeStatus(Request $request)
    {
        $message = auth('customer')->user()->receive_messages()->where('id', $request->id)->first();
        if (!$message) {
            return response()->json(['status' => 'fail', 'message' => 'Message not found']);
        }
        if ($request->status == 'read')
            $message->read = 'yes';
        elseif ($request->status == 'unread')
            $message->read = 'no';

        $message->save();
        return response()->json(['status' => 'success', 'message' => 'Message status changed successfully']);

    }

    public function move_trash(Request $request){
        $request->validate([
            'ids'=>'required'
        ]);
        $ids=explode(',', $request->ids);

        auth('customer')->user()->receive_messages()->whereIn('id',$ids)->delete();

        return back()->with('success', 'Message successfully moved to trash');

    }


}
