<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserMessage;
use Illuminate\Http\Request;

class UserMessageController extends Controller
{
    public function index(Request $request){

        $data['user_messages'] = UserMessage::get();
        return  view('admin.user_message.index',$data);
    }

    public function store(Request $request){

        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required',
            'message' => 'required'
        ]);

          $user_message = new UserMessage();
          $user_message->name = $request->name;
          $user_message->email = $request->email;
          $user_message->message = $request->message;
          $user_message->subject = $request->subject;
          $user_message->save();

          return back()->with('success', 'Successfully Send Message');

    }
}
