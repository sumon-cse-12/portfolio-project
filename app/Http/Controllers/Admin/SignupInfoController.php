<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SignupInfo;

class SignupInfoController extends Controller
{
    public function index(){
        return view('admin.signupinfo.index');
    }
    public function getAll(){
        $sign_up_info = SignupInfo::orderBy('created_at', 'desc');
        return datatables()->of($sign_up_info)
            ->addColumn('status', function ($q) {
                $sign_up_info = $q->status;
                if ($sign_up_info == 'active') {
                    return '<span class="badge badge-success">Active</span>';
                } else {
                    return '<span class="badge badge-danger">Inactive</span>';
                }
            })
            ->addColumn('action', function ($q) {
                return "<a class='btn btn-sm btn-info' href='" . route('admin.signupinfo.edit', [$q]) . "'>Edit</a> &nbsp; &nbsp;" .
                '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this Sign Up Information ?"
                                    data-action=' . route('admin.signupinfo.destroy', [$q]) . '
                                    data-input={"_method":"delete"}
                                    data-toggle="modal" data-target="#modal-confirm">Delete</button>';
            })
            ->rawColumns(['action', 'status'])
            ->toJson();
    }
    public function create(){
        $data['sign_up_info']=SignupInfo::first();
        return view('admin.signupinfo.create',$data);
    }
    public function store(Request $request){
        $request->validate([
            'title' => 'required',
            'status' => 'required|in:active,inactive',
        ]);
        $sign_up_info =  SignupInfo::firstOrNew();
        $sign_up_info->title = $request->title;
        $sign_up_info->short_description = $request->short_description;
        if ($request->has('features')) {
            $features = array_filter($request->features);
            
            $sign_up_info->features = json_encode($features);
        }
        $sign_up_info->status = $request->status;
        $sign_up_info->save();

        return back()->with('success', 'Sign Up Information Successfully Created');
    }
    public function edit(SignupInfo $signupinfo){
        $data['sign_up_info']=$signupinfo;
        return view('admin.signupinfo.edit',$data);
    }
    public function update(SignupInfo $signupinfo , Request $request){
        $request->validate([
            'title' => 'required',
            'status' => 'required|in:active,inactive',
        ]);
        $signupinfo->title = $request->title;
        if ($request->has('features')) {
            $features = array_filter($request->features);
            
            $signupinfo->features = json_encode($features);
        }
        $signupinfo->status = $request->status;
        $signupinfo->save();
        return back()->with('success', 'Sign Up Information Successfully Updated');
    }
    public function destroy(SignupInfo $signupinfo){
        $signupinfo->delete();
        return back()->with('success', 'Sign Up Information Successfully Delete');
    }
}
