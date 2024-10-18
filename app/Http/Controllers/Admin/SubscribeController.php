<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscribe;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{
    public function subscribe_store(Request $request){

        $request->validate([
            'subscribe_email' => 'required|unique:subscribes,subscribe_email',
        ]);
        Subscribe::create($request->all());

        return redirect()->back()->with('success','Subscribe successfully done.');
    }
    public function index(Request $request){

        $data['subscribes'] = Subscribe::get();
        return  view('admin.subscribe.index',$data);
    }
}
