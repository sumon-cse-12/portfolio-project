<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fee;
use App\Models\Instrument;

class FeesController extends Controller
{
    public function index(){
        return view('admin.fees.index');
    }
    public function getAll(){
        $fees = Fee::orderBy('created_at', 'desc');
        return datatables()->of($fees)
            ->addColumn('status', function ($q) {
                $fees = $q->status;
                if ($fees == 'active') {
                    return '<span class="badge badge-success">Active</span>';
                } else {
                    return '<span class="badge badge-danger">Inactive</span>';
                }
            })
            ->addColumn('action', function ($q) {
                return "<a class='btn btn-sm btn-info' href='" . route('admin.fees.edit', [$q]) . "'>Edit</a> &nbsp; &nbsp;" .
                '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this fee ?"
                                    data-action=' . route('admin.fees.destroy', [$q]) . '
                                    data-input={"_method":"delete"}
                                    data-toggle="modal" data-target="#modal-confirm">Delete</button>';
            })
            ->rawColumns(['action', 'status'])
            ->toJson();
    }
    public function create(){
        $data['instruments']=Instrument::all();
        return view('admin.fees.create',$data);
    }
    public function store(Request $request){
        $request->validate([
            'service_name' => 'required',
            'uhn_rate' => 'required',
            'ea_rate' => 'required',
            'type_of_instrument' => 'required',
            'status' => 'required|in:active,inactive',
        ]);
        $fees = new Fee();
        $fees->service_name = $request->service_name;
        $fees->uhn_rate = $request->uhn_rate;
        $fees->ea_rate = $request->ea_rate;
        $fees->status = $request->status;
        $fees->bottom_text = $request->bottom_text;
        $fees->type_of_instrument = $request->type_of_instrument;
        $fees->save();

        return back()->with('success', 'Fees Successfully Created');
    }
    public function edit(Fee $fee){
        $data['fees']=$fee;
        $data['instruments']=Instrument::all();
        return view('admin.fees.edit',$data);
    }
    public function update(Fee $fee , Request $request){
        $request->validate([
            'service_name' => 'required',
            'uhn_rate' => 'required',
            'ea_rate' => 'required',
            'type_of_instrument' => 'required',
            'status' => 'required|in:active,inactive',
        ]);
        $fee->service_name = $request->service_name;
        $fee->uhn_rate = $request->uhn_rate;
        $fee->ea_rate = $request->ea_rate;
        $fee->status = $request->status;
        $fee->bottom_text = $request->bottom_text;
        $fee->type_of_instrument = $request->type_of_instrument;
        $fee->save();

        return back()->with('success', 'Fees Successfully Updated');
    }
    public function destroy(Fee $fee){
        $fee->delete();
        return back()->with('success', 'Fees Successfully Delete');
    }
    public function header(){
        return view('admin.fees.header');
    }
}
