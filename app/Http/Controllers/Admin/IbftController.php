<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ibft;
use App\Models\Customer;

class IbftController extends Controller
{
    public function index(){
        return view('admin.ibft.index');
    }
    public function getAll(){
        $ibft = Ibft::orderBy('created_at', 'desc');
        return datatables()->of($ibft)
            ->addColumn('status', function ($q) {
                $ibft = $q->status;
                if ($ibft == 'approved') {
                    return '<span class="badge badge-success">Approved</span>';
                } else {
                    return '<span class="badge badge-danger">Declined</span>';
                }
            })
            ->addColumn('action', function ($q) {
                return "<a class='btn btn-sm btn-info' href='" . route('admin.ibft.edit', [$q]) . "'>Edit</a> &nbsp; &nbsp;" .
                '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this IBFT?"
                                    data-action=' . route('admin.ibft.destroy', [$q]) . '
                                    data-input={"_method":"delete"}
                                    data-toggle="modal" data-target="#modal-confirm">Delete</button>';
            })
            ->rawColumns(['action', 'status'])
            ->toJson();
    }
    public function create(){
        $data['agents'] =Customer::all();
        return view('admin.ibft.create',$data);
    }
    public function store(Request $request){
        $request->validate([
            'agent' => 'required',
            'name' => 'required',
            'passport' => 'required',
            'instruction' => 'required',
            'bank_name' => 'required',
            'account_number' => 'required',
            'amount' => 'required',
            'status' => 'required|in:declined,approved',
            'initalizing' => 'required|in:processing,finished',
        ]);
        if(!$request->agent){
            return redirect()->back()->withErrors(['msg' => trans('Select agent')]);
        }
        $ibft = new Ibft();
        $ibft->agent = $request->agent;
        $ibft->name = $request->name;
        $ibft->passport = $request->passport;
        $ibft->instruction = $request->instruction;
        $ibft->bank_name = $request->bank_name;
        $ibft->account_number = $request->account_number;
        $ibft->amount = $request->amount;
        $ibft->percentage = $request->percentage;
        $ibft->status = $request->status;
        $ibft->initalizing = $request->initalizing;
        $ibft->sms_code = $request->sms_code;
        $ibft->vjut_code = $request->vjut_code;
        $ibft->conditional_addproval = $request->conditional_addproval;
        $ibft->save();
        return back()->with('success', trans('admin.success_message',['action'=>trans('admin.created'),'title'=>trans('admin.ibft')]));

    }
    public function edit(Ibft $ibft)
    {
        $data['agents'] =Customer::all();
        $data['ibft'] = $ibft;
        return view('admin.ibft.edit',$data);
    }
    public function update(Ibft $ibft, Request $request){

        $request->validate([
            'agent' => 'required',
            'name' => 'required',
            'passport' => 'required',
            'instruction' => 'required',
            'bank_name' => 'required',
            'account_number' => 'required',
            'amount' => 'required',
            'status' => 'required|in:declined,approved',
            'initalizing' => 'required|in:processing,finished',
        ]);
        if(!$request->agent){
            return redirect()->back()->withErrors(['msg' => trans('Select agent')]);
        }
        $ibft->agent = $request->agent;
        $ibft->name = $request->name;
        $ibft->passport = $request->passport;
        $ibft->instruction = $request->instruction;
        $ibft->bank_name = $request->bank_name;
        $ibft->account_number = $request->account_number;
        $ibft->amount = $request->amount;
        $ibft->percentage = $request->percentage;
        $ibft->status = $request->status;
        $ibft->initalizing = $request->initalizing;
        $ibft->sms_code = $request->sms_code;
        $ibft->vjut_code = $request->vjut_code;
        $ibft->conditional_addproval = $request->conditional_addproval;
        $ibft->save();
        return redirect()->back()->with('success', trans('admin.success_message',['action'=>trans('admin.update'),'title'=>trans('admin.ibft')]));
    }
    public function destroy(Ibft $ibft){

        $ibft->delete();
        return redirect()->back()->with('success', trans('admin.success_message',['action'=>trans('admin.deleted'),'title'=>trans('admin.ibft')]));

    }
}
